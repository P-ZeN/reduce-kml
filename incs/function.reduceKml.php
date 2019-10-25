<?php
/**
 * Fonction de réduction de cordonnées KML
 *
 * @package   ReduceKmlOnline
 * @author    Philippe Zénone <contact@philippezenone.net>
 * @copyright 2018 PhilippeZenone.net, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; 
 * @version   <GIT:></GIT:> 
 * @link      http://philippezenone.net
 */


defined ('ZEN') or die('Forbiden access fr');

function reduceKML($file, $accuracy) {
    // $pathIn = BASE_PATH . 'tmp_upload';
    // $pathOut = BASE_PATH . 'tmp_download';
    
    //##########Stats########
    // Initialisation d'un tableau pour récupperer des informations sur le traitement
    $resultat = new stdClass;
    $resultat->nom_de_fichier = $file['name'] . '_reduced';
    $resultat->orig_size = GetSizeName(filesize($file['tmp_name']));

    // Récupération du fichier à traiter.
    $kmlDoc = simplexml_load_file($file['tmp_name']);

    // Je parcours l'ensemble des namespace qu'il y a dans le fichier chargé
    foreach ($kmlDoc->getDocNamespaces() as $strPrefix => $strNamespace) {

        // Si il existe le namespace
        if (strlen($strPrefix) == 0) {

            // Je créé un préfixe. La valeur de ce préfixe n'a pas d'importance (ici : première lettre de l'alphabet)
            $strPrefix = "a";
        }

        // Je renseigne le préfixe avec le namespace courant
        $kmlDoc->registerXPathNamespace($strPrefix, $strNamespace);
    }

    // Initialisation de variables
    // Cette variable sera un indice si il y a plusieurs fois "coordinates" dans le fichier kml
    $indiceEnsembleCoordonnees = 0;

    // Boucle qui parcours l'ensemble des noeuds "coordinates" présent dans le fichier
    foreach (($kmlDoc->xpath("//a:coordinates")) as $kml_coordinates) {

        // Mise sous forme de tableau les coordonées.
        // ATTENTION : Une coordonées c'est une lattitude, une longitude et un 0 (altitude (?)). 
        //Ces données sont séparées par une virgule et chaque coordonnées est séparée par un espace
        $coords[0] = explode(' ', (string) $kml_coordinates);

        // création d'un tableau latitude / longitude pour chaque coordonnée
        $points = array();
        for ($i = 0; $i < count($coords[0]) - 1 ; $i++) {
            $tmp = explode(',', $coords[0][$i]);
            $point = new stdClass;
            $point->lat = preg_replace("#\n|\t|\r| +#", "", $tmp[0]);
            $point->long = preg_replace("#\n|\t|\r| +#", "", $tmp[1]);
            $points[$i] = $point;
        }

        // Détermination du seuil de précision en fonction de la valeur du paramètre $accuracy
        //$accuracy = strip_tags(trim($_POST['hiddenAccuracy']));
        $precision = 1 / bcpow(10, $accuracy);

        // comparaison de la différence des latitudes et longitudes avec chaque précédent point
        $precedent = new stdClass;
        $precedent->lat = 0;
        $precedent->long = 0;
        $pointsReduits = array();
        foreach ($points as $point) {
            $diff_lat1 = abs($precedent->lat) - abs($point->lat);
            $diff_lat2 = abs($point->lat) - abs($precedent->lat);
            $diff_long1 = abs($precedent->long) - abs($point->long);
            $diff_long2 = abs($point->long) - abs($precedent->long);

            //Si une des différence est supérieure au seuil de précision 
            if ($diff_lat1 > $precision or $diff_lat2 > $precision or $diff_long1 > $precision or $diff_long2 > $precision) {
                //on garde le point
                $pointsReduits[] = $point;
                // et on stocke ses coordonnées pour les comparer au point suivant
                $precedent->lat =  $point->lat;
                $precedent->long =  $point->long;
            }
        }

        //reconstruction de la liste de coordonnées
        $i = 0;
        foreach ($pointsReduits as $pointReduit) {
            $newCoords[$indiceEnsembleCoordonnees][$i] = $pointReduit->lat.','.$pointReduit->long.',0';
            $i++;
        }

        /*DEBUG
        echo '<pre>';
        print_r($newCoords);
        echo '</pre>';
        die;
        */

        //##########Stats########
        $resultat->coordonnees[$indiceEnsembleCoordonnees] = new stdClass;
        $resultat->coordonnees[$indiceEnsembleCoordonnees]->longueur_initiale = count($coords[0]);
        $resultat->coordonnees[$indiceEnsembleCoordonnees]->longueur_reduite = count($pointsReduits);
        $taux_de_compression 
            = $resultat->coordonnees[$indiceEnsembleCoordonnees]->longueur_reduite
                * 100
                / $resultat->coordonnees[$indiceEnsembleCoordonnees]->longueur_initiale;
        $resultat->coordonnees[$indiceEnsembleCoordonnees]->taux_de_compression = $taux_de_compression.'%';

        // Incrémentation de l'indice de l'ensemble des noeud coordinates
        $indiceEnsembleCoordonnees++;
    }

    // Création d'un objet de type DOMElement avec l'objet SimpleXML (ce dernier est un objet créé avec le fichier KML traité)
    $dom_sxe = dom_import_simplexml($kmlDoc);

    // Création d'une instance de type DOMDocument
    $dom = new DOMDocument('1.0');

    // Importantion d'un noeaud dans un document
    $dom_sxe = $dom->importNode($dom_sxe, true);

    // Ajout d'un noeud fils à l'un des fils
    $dom_sxe = $dom->appendChild($dom_sxe);

    // Récupération de toutes les balises "coordinates" du fichier, sous forme d'item
    $items = $dom->getElementsByTagName("coordinates");

    // Remise à zéro de la variable si il y a plusieurs balises coordinates dans le fichier.
    // On va réaffecter les nouvelles coordonnées dans l'ordre dans lequelle elles ont étaitent traité.
    $indiceEnsembleCoordonnees=0;

    // Parcours de l'ensemble des items récupérés précédemment
    foreach ($items as $node) {
        // Clonage du noeud précédamment récupéré
        $newNode=$node->cloneNode();
        // Modification des valeurs du noeud cloné
        $newNode->nodeValue=implode(" ", $newCoords[$indiceEnsembleCoordonnees]);
        // Remplacement des noeuds
        $node->parentNode->replaceChild($newNode, $node);
        $indiceEnsembleCoordonnees++;
    }
    // Ajout du mot "Reduce" dans le nom du fichier, juste avant l'extension ".kml"
    //$file=substr_replace($file, "Reduce.kml", strpos($file, ".kml"));
    // Sauvegarde dans un fichier
    $dom->formatOutput = true;
    $xml_string = $dom->saveXML();

    //$dom->save($pathOut."/".$file);
    // export_kml($xml_string, $file, $resultat);
    //return $resultat;
    $resultat->xml_string = $xml_string;
    $resultat->final_size = GetSizeName(mb_strlen($xml_string, '8bit'));
    return $resultat;
}

function export_kml($xml_string, $file) {


        $output_filename = $file .'_reduced.kml';

        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-type: application/vnd.google-earth.kml+xml kml');
        header('Content-Disposition: attachment; filename=' . $output_filename);
        header('Expires: 0');
        header('Pragma: public');

        echo $xml_string;
        exit;
}
?>