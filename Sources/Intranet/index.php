<?php
session_start();
require_once("utils.php");
if (!basilic_rights("access")) header("Location: $public_path/noway.php");
else header("Location: $intra_path/Publications/");
?>
