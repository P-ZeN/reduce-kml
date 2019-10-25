<?php
/**
 * Fichier de configuration
 *
 * @package   ReduceKmlOnline
 * @author    Philippe Zénone <contact@philippezenone.net>
 * @copyright 2018 PhilippeZenone.net, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; 
 * @version   <GIT:></GIT:> 
 * @link      http://philippezenone.net
 */

require_once 'incs/utilz.php';

// Constantes d'url
if (!defined('ZEN')) {
    define('ZEN', true);
}

$cur_dir = basename(dirname($_SERVER['PHP_SELF']));
$suffix = empty($cur_dir) ? '': $cur_dir . '/';
// echo '<pre>$cur_dir = ' . $cur_dir . '</pre>';
if (!defined('BASE_URL')) {
    $prefix = isSecure() ? 'https://' : 'http://';
    define('BASE_URL', $prefix . $_SERVER['HTTP_HOST'] . '/' . $suffix);
}
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/' . $suffix);
}
/*
echo '<pre>';
echo 'BASE_URL : ' . BASE_URL . '<br>';
echo 'BASE_PATH : ' . BASE_PATH . '<br>';
echo '</pre>';
*/

// Language
$lang = decide_language();

require_once 'lang/' . $lang . '.php'; 

//inclusions des fonctions
require_once 'incs/function.Gmap.php';
require_once 'incs/function.reduceKml.php';
 




