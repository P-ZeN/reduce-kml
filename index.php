<?php
/**
 * Index
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

 
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <base href="<?php echo BASE_URL; ?>">
    
    <title><?php echo TITRE_PAGE; ?></title>
    <link rel="stylesheet" href="assets/reducekmlonline.css">
    <script src="assets/reducekmlonline.js"></script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyBO-GJJDBGwU3cUdNbXXL7Rt_UtyGMwUUA"></script>
    <script src="assets/markerwithlabel.js"></script>
    
</head>
<body>
<div class="container mt-2">
<div class="row">
        <div class="col-12">
            <div class="d-inline-block float-right">
                <form method="post" class="form-inline text-right" id="langForm">
                    <div class="input-group">
                        <select name="lang" id="langSelect" class="form-control">
                            <option value="fr" <?php if ($lang == 'fr') : echo 'selected'; endif; ?>>Français</option>
                            <option value="en" <?php if ($lang == 'en') : echo 'selected'; endif; ?>>English</option>
                        </select>
                        <!--<div class="input-group-append">
                            <button type="submit" class="btn btn-outline-default">Go</button>
                        </div>-->
                    </div>
                </form>
            </div>
            <h1>
                <img src="images/cyber-bonom.jpg" alt="www.cyber-diego.com" style="vertical-align: middle">
                <?php echo TITRE_PAGE; ?>
            </h1>
            <fieldset class="fieldset">
                <form id="upForm" action="ajax_controller.php">
                    <div class="form-row">
                        <div class="col-md-8 my-1">
                            <label for="fileInput"><strong><?php echo FICHIER_A_REDUIRE; ?></strong>
                                <small class="form-text text-muted">
                                <?php echo CHOISIR_FICHIER_A_REDUIRE; ?>
                                </small>
                            </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="fileInput" id="fileInput" aria-describedby="inputGroupFile01"  lang="<?php echo $lang; ?>" required>
                                    <label class="custom-file-label" for="inputGroupFile01" id='fileInputLabel' ><?php echo CHOISIR_FICHIER; ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <label for="accuracy"><strong><?php echo CHOISIR_PRECISION; ?></strong>
                        <small class="form-text text-muted">
                        <?php echo CHOISIR_PRECISION_DESC; ?>
                        </small>
                    </label>
                        <div class="form-group col-12 mb-4 px-3" style="position: relative">
                            <ul class="range-legend">
                                <li>10 km<br>|</li>
                                <li>5 km<br>|</li>
                                <li>1 km<br>|</li>
                                <li>500 m<br>|</li>
                                <li>100 m<br>|</li>
                                <li>50 m<br>|</li>
                                <li>10 m<br>|</li>
                                <li>5 m<br>|</li>
                                <li>1 m<br>|</li>
                                <li>0.5 m<br>|</li>
                                <li>0.1 m<br>|</li>
                                <li>0.05 m<br>|</li>
                                <li>0.01 m<br>|</li>
                            </ul>                        
                            <input id="accuracy" name="accuracy" type="range" class="accuracy" min="0" max="6" step="0.5" required/>
                        </div>
                        <div class="col-md-12 text-center">
                            <div class="loader12" style="display: none"></div>
                        </div>
                    </div>
                </form>
            </fieldset>
        </div>
    </div>
    <div class="card p-2 my-2 d-none" id="container_resultat">
        <div class="col-12 my-3" id="resultat">
        </div>
    </div>
    <div class="row">
            <?php require_once 'lang/description_' . $lang . '.php'; ?> 
    </div>
</div> <!-- /container -->
<div id="err"></div>
</body>
</html>