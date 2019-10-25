<?php
/**
 * Création de la carte Google Map
 * 
 * @package ReduceKmlOnline
 *
 * @author    Philippe Zénone <contact@philippezenone.net>
 * @copyright 2018 PhilippeZenone.net, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; 
 * @version   <GIT:></GIT:> 
 * @link      http://philippezenone.net
 * 
 */

defined('ZEN') or die('Forbiden access gm');

function prepareGmap($fichier, $resultats = null) {


    $listeFichiers = array();
    $listeFichiers[] = $fichier['tmp_name'];
    $name = $fichier['name'];
    
    // liste des fichiers reduits
    if (isset($resultats)) {
        $listeFichiersReduits = array();
        $listeFichiersReduits[] = $resultats->xml_string;

    }
    
    ////////////////////////////////////////////////////////////////////////
    /////////////////////// Initialisation carte ///////////////////////////
    ////////////////////////////////////////////////////////////////////////
    $features_Gmap = file_get_contents(BASE_PATH . '/assets/features_Gmap.js');
    $gscript = '
    
    function initGmap() {
        
        // fonction de calcul des frontières du polygone (ie. du rectangle dans lequel il s\'inscrit)
        google.maps.Polygon.prototype.my_getBounds = function(){
            var bounds = new google.maps.LatLngBounds()
            this.getPath().forEach(function(element,index){bounds.extend(element)})
            return bounds
        }
    
        // chargement du JSON contenant les options des layers de la carte
        var featureOpts = '.$features_Gmap.';
    
        // Options de la carte
        var myOptions = {
          styles: featureOpts,
          mapTypeId: google.maps.MapTypeId.TERRAIN,
            mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.LEFT_BOTTOM
                },
                panControl: false,
                zoomControl: true,
                zoomControlOptions: {
                    /*style: google.maps.ZoomControlStyle.SMALL,*/
                    position: google.maps.ControlPosition.LEFT_TOP
                },
                scaleControl: true,
                scaleControlOptions: {
                    position: google.maps.ControlPosition.LEFT_TOP
                }
        };
        
        // Initialisation de la carte
        var map = new google.maps.Map(document.getElementById("gmap"), myOptions);
        
        // initialisation du popup
        var infowindow = new google.maps.InfoWindow();
        // Le popup se ferme si on clique ailleurs sur la carte
        google.maps.event.addListener(map, \'click\', function() {
            infowindow.close();
        });
    ';

    ///////////////////////////////////////////////////////////////////////
    ////////////////////////////   Polygones    ///////////////////////////
    ///////////////////////////////////////////////////////////////////////
    
    $index_polys_centers = 0;
    $gscript .= '
    // Initialisation du tableau des polygones
    var polygons = [];

    ';

    ///////////////////////////////////////////////////////////////////////
    ////////////////////// Affichage polygones sources ////////////////////
    ///////////////////////////////////////////////////////////////////////

    $total_points = 0;
    $total_pointsR = 0;

    foreach ($listeFichiers as $fichier) { 
        $color = '#0000ff';

        $dom = new DOMDocument();
        $dom->load($fichier);
        $coordinates = $dom->getElementsByTagName('coordinates');

        $alias = str_replace('-', '', str_replace(' ', '_', substr($name, 0, strlen($name) - 4)));

        $result = draw_polygon($alias, $coordinates, $color);
        $gscript .= $result->gscript;
        $total_points = $total_points + $result->total_points;
    }
    

    ///////////////////////////////////////////////////////////////////////
    ////////////////////// Affichage polygones réduits ////////////////////
    ///////////////////////////////////////////////////////////////////////
    
    
    if (!empty($resultats)) {

        foreach ($listeFichiersReduits as $fichier) { 

            $color = '#00ff00';

            $dom = new DOMDocument();
            $dom->loadXML($fichier);
            $coordinates = $dom->getElementsByTagName('coordinates');

            $alias = str_replace('-', '', str_replace(' ', '_', substr($name, 0, strlen($name) - 4)));
            $alias .= '_reduit';

            $result = draw_polygon($alias, $coordinates, $color, true);
            $gscript .= $result->gscript;
            $total_pointsR = $total_pointsR + $result->total_points;
    
        }   
    }   
    ////////////////////////////////////////////////////////////////////////
    /////////////////////   Fermeture & rendu    ///////////////////////////
    ////////////////////////////////////////////////////////////////////////

    $gscript .= '
    // centrage de la carte
    
    var bounds = new google.maps.LatLngBounds();
    
    for (var i = 0; i < polygons.length; i++) {
        for (var j = 0; j < polygons[i].length; j++) {
            bounds.extend(new google.maps.LatLng(polygons[i][j].lat, polygons[i][j].lng));
        }
    }
    
    map.fitBounds(bounds);
    map.setCenter(bounds.getCenter());
        
} // fin initGmap
    ';
    return $gscript;
}



function draw_polygon($name, $coordinates, $color, $reduit = false) {

    $result = new stdclass;
    $result->gscript = '';

    $index_polys_centers = 0;
    for ($j = 0; $j < $coordinates->length; $j++) {
        $trace = $coordinates->item($j)->nodeValue;
        $polygone ='';
        $coords = explode(' ', $trace);
        $total_points = 0;
        for ($i = 0; $i < count($coords) - 1; $i++) {
            $coord = $coords[$i];
            $latlong = explode(',', $coord);
            $polygone .= ' {lat:'.preg_replace("#\n|\t|\r| +#", "", $latlong[1]) . ', lng:' . preg_replace("#\n|\t|\r| +#", "", $latlong[0]) . '}';
            $fin = $i + 2 < count($coords) ?  ',' : '';
            $polygone .= $fin;
            $total_points++;
        }
        
        
        // output
        $alias = $name . '_poly' . ($index_polys_centers + 1);
        $result->gscript .= '
                
    var Coords' . $alias . ' = [' . $polygone . '];
    
    var Poly' . $alias . ' = new google.maps.Polygon({
        paths: Coords' . $alias . ',
        strokeColor: \'' . $color . '\',
        strokeOpacity: 1,
        strokeWeight: ' . ($reduit == false ? "5" : "2") .',
        fillColor: \'' . $color . '\',
        fillOpacity: \'0.2\'
    });
    Poly' . $alias . '.setMap(map);

    var contentString' . $alias . ' = 
        "<h3 >' . $alias . '</h3>" +
        "<p>La description du polygone reste à implémenter... ;-)</p>";
    
    google.maps.event.addListener(Poly' . $alias . ', \'click\', function(event) {
        infowindow.close();
        infowindow.setPosition(event.latLng);
        infowindow.setContent(contentString' . $alias . ');
        infowindow.open(map);
    });


';
        if (count($coords) > 2 ) {
            $result->gscript .= '
    var Poly' . $alias . 'LatLng = Poly' . $alias . '.my_getBounds().getCenter();
    polygons.push(Coords' . $alias . ');

';
        }
        if ($reduit == false) {
            $result->gscript .= '
    var marker' . $alias . ' = new MarkerWithLabel({
        position: Poly' . $alias . 'LatLng,
        draggable: false,
        map: map,
        labelContent: "' . POLYGON . ' ' . ($index_polys_centers + 1) . '",
        labelAnchor: new google.maps.Point(30, 35),
        labelClass: "labelsGmap",
        icon: " "
    });

';
        }
        $index_polys_centers++;

    }
    $result->total_points = $total_points;
    return $result;
}
?>