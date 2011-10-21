<?php

if (!isset($lg))
{
  // Direct setting
if ((isset($_GET)) && (isset($_GET["lg"])))
  $lg=$_GET["lg"];
else
  // Cookies
  if (isset($HTTP_COOKIE_VARS["preferedLanguage"]))
    $lg=$HTTP_COOKIE_VARS["preferedLanguage"];
  else
    // Browser preferences
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    {
      $a=split(";",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
      if (strstr($a[0], "fr")) 
        $lg="fr";
      else
        $lg="en";
    }
    else
      // Default language
      $lg="fr";
}
?>
