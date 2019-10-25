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
    <p class="lead">Le script reduceKml permet de réduire la taille des fichiers exportés depuis un programe SIG en supprimant tous les points dont la distance avec le précédent est inférieure au paramètre précision choisi.</p>
</div>
<div class="col-md-8">
    <div class="row">
        <div class="col-md-6">
            <div class="thumbnail">
                <img src="images/reduceKml_avant.jpg" class="img-fluid alt="Fichier original">
                <div class="caption">
                    <h3>Fichier original</h3>
                    <p>Le polygone généré à partir d'un logiciel SIG comporte un grand nombre de points inutiles.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="thumbnail">
                <img src="images/reduceKml_apres.jpg" class="img-fluid alt="Fichier réduit">
                <div class="caption">
                    <h3>Fichier réduit</h3>
                    <p>Les points inutiles ont été supprimés mais le polygone conserve sa forme générale.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
<fieldset class="fieldset">
    <ol>
        <li>Sélectionnez un fichier *.kml contenant un ou plusieurs polygone</li>
        <li>Ajustez le degré de précision afin d'obtenir une bonne réduction de taille sans que la précision du tracé ne soit trop affectée</li>
        <li>Une fois satisfaitdu résultat, sauvegardez le fichier réduit</li>
    </ol>
</fieldset>

</div>

