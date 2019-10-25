<?php
/**
 * Réponse HTML
 *
 * @package   ReduceKmlOnline
 * @author    Philippe Zénone <contact@philippezenone.net>
 * @copyright 2018 PhilippeZenone.net, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; 
 * @version   <GIT:></GIT:> 
 * @link      http://philippezenone.net
 */

if ($resultat == true) {

    $name = $fichier['name'];
    $output_name = str_replace('-', '', str_replace(' ', '_', substr($name, 0, strlen($name) - 4))). '_reduced';

?>
<div class="row">
    <div class="col-6">
        <p style="text-align: center !important">
            <span id="legende_in"></span>&nbsp;<?php echo POLY_ORIG; ?>&nbsp;(<?php echo $resultat->orig_size; ?>), 
            <span id="legende_out"></span>&nbsp;<?php echo POLY_REDUIT; ?>&nbsp;(<?php echo $resultat->final_size; ?>)
        </p>
    </div>
    <div class="col-6">
        <p class="text-center">
            <button onclick="createAndOpenFile()" class="btn btn-primary" >
                <img src="images/download-solid.svg" width="22" /> <?php echo SAUVEGARDER_FICHIER; ?>
            </button>
        </p>
    </div>
</div>

<input type="hidden" id="xmlString" value="<?php echo base64_encode($resultat->xml_string); ?>"/>
<input type="hidden" id="reducedFileName" value="<?php echo $output_name; ?>"/>

<?php // div pour la carte ?>
<div id="gmap" style="width: 100%; height: 500px"></div>

<div class="row text-center mt-3">
<?php
        $i = 1; 
foreach ($resultat->coordonnees as $coord) { ?>
    <div class="col-4">
        <ul class="list-group success">
            <li class="list-group-item list-group-item-success">
                <strong><?php echo POLYGON; ?> <?php echo $i; ?> :</strong>
            </li>
            <li class="list-group-item">
                <?php echo NBR_INIT_COORDS; ?>
                <span class="badge badge-info"><?php echo $coord->longueur_initiale; ?></span>
            </li>
            <li class="list-group-item">
                <?php echo NBR_FINAL_COORDS; ?>
                <span class="badge badge-info"><?php echo $coord->longueur_reduite; ?></span>
            </li>
            <li class="list-group-item">
                <?php echo REDUIT_DE; ?>
                <span class="badge badge-info"><?php echo (100 - round($coord->taux_de_compression, 3)); ?>%</span>
            </li>
        </ul>
    </div>
<?php
    $i++;
}
?>
</div>
<div class="row text-center mt-3">
    <div class="col-12 text-center">
        <button onclick="createAndOpenFile()" class="btn btn-primary" >
            <img src="images/download-solid.svg" width="22" /> <?php echo SAUVEGARDER_FICHIER; ?>
        </button>
    </div>
    <hr />
</div>
<?php
}
?>

<?php // javascript pour la carte ?>
<script>
<?php echo prepareGmap($fichier, $resultat); ?>
<?php // Initialisation de la carte.  ?>

    initGmap();
</script>


