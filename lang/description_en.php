<?php
/**
 * Description HTML
 *
 * @package   ReduceKmlOnline
 * @author    Philippe Zénone <contact@philippezenone.net>
 * @copyright 2018 PhilippeZenone.net, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; 
 * @version   <GIT:></GIT:> 
 * @link      http://philippezenone.net
 */
?>
<div class="col-md-12">
    <p class="lead">The reduceKml script reduces the size of files exported from GIS software by removing all points whose distance to the previous one is less than the chosen precision parameter.</p>
</div>
<div class="col-md-8">
    <div class="row">
        <div class="col-md-6">
            <div class="thumbnail">
                <img src="images/reduceKml_avant.jpg" class="img-fluid alt="Fichier original">
                <div class="caption">
                    <h3>Original File </h3>
                    <p>The polygon generated from GIS software has a lot of unnecessary points.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="thumbnail">
                <img src="images/reduceKml_apres.jpg" class="img-fluid alt="Fichier réduit">
                <div class="caption">
                    <h3>Reduced file</h3>
                    <p>Unnecessary points have been removed but the polygon retains its general shape.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
<fieldset class="fieldset">
    <ol>
        <li>Select a * .kml file that contains one or more polygons</li>
        <li>Adjust the degree of accuracy to obtain a good size reduction without the accuracy of the plot being affected too much</li>
        <li>When satisfied with the result, save the reduced file</li>
    </ol>
</fieldset>

</div>

