<?php
/**
 * Ajax Controller
 * 
 * @package ReduceKmlOnline
 *
 * @author    Philippe Zénone <contact@philippezenone.net>
 * @copyright 2018 PhilippeZenone.net, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; 
 * @version   <GIT:></GIT:> 
 * @link      http://philippezenone.net
 */

// inclusions du fichier de configuration
require_once 'conf.php';

if (!isset($_POST['accuracy']) or !isset($_FILES)) {
    echo 'Pas de données !';
    exit;
}
    $accuracy = $_POST['accuracy'];
    $fichier = $_FILES['fichier'];

    $resultat = reduceKML($fichier, $accuracy);
    
    require_once 'incs/response.php';

?>