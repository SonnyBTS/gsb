<?php 
include 'models/pdogsb.php';
include('vues/v_header.php');

$uc = !isset($_REQUEST['uc']) ? 'controleur' : $_REQUEST['uc'];

switch ($uc) {
    case 'controleur':
        include('controleurs/c_form.php');
        break;
}

include('vues/v_footer.php');
?>