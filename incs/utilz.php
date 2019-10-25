<?php
/**
 * Utilités
 *
 * @package   ReduceKmlOnline
 * @author    Philippe Zénone <contact@philippezenone.net>
 * @copyright 2018 PhilippeZenone.net, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; 
 * @version   <GIT:></GIT:> 
 * @link      http://philippezenone.net
 */


 // convertit la taille en bytes en octets
function GetSizeName($octet)
{
    // Array contenant les differents unités 
    $unite = array(' Octet',' Ko',' Mo',' Go');
    
    if ($octet < 1000) // octet
    {
        return $octet.$unite[0];
    }
    else 
    {
        if ($octet < 1000000) // ko
        {
            $ko = ceil($octet/1024);
            return $ko.$unite[1];
        }
        else // Mo ou Go 
        {
            if ($octet < 1000000000) // Mo 
            {
                $mo = round($octet/(1024*1024),2);
                return $mo.$unite[2];
            }
            else // Go 
            {
                $go = round($octet/(1024*1024*1024),2);
                return $go.$unite[3];    
            }
        }
    }
}

// détecte sir la connexion est https ou http
function isSecure() {
    return
      (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || $_SERVER['SERVER_PORT'] == 443;
}

// détermine la langue à utiliser
function decide_language() {
  $acceptlang = getenv("HTTP_ACCEPT_LANGUAGE");
  $set_lang = explode(',', $acceptlang);
  if (isset($_POST['lang'])) {
          $lang = $_POST['lang'];
          setcookie("lang", $lang, time() + 240000, '/');
  } else {
      if (isset($_COOKIE['lang'])) {
          $lang = substr($_COOKIE['lang'], 0, 2);
          setcookie("lang", $lang, time() + 240000, '/');
      } else {
          setcookie("lang", $set_lang[0], time() + 240000, '/');
          $lang = substr($set_lang[0], 0, 2);
      }
  }  
  return $lang;   
}
