<?php
// Langues
include_once('../lang/fr-lang.php');
include_once('../lang/en-lang.php');

if ($_SESSION['lang'] == 'EN') {
    $dashboard = DASHBOARD_EN;
} else {
    $dashboard = DASHBOARD_FR;
}

switch ($_SESSION['type_compte']) {

    case 1:
        echo '<div class="breadcrumb-item active"><a href="tableau-de-bord-admin.php">'. $dashboard .'</a></div>';
        break;
    case 2:
        echo '<div class="breadcrumb-item active"><a href="tableau-de-bord-sec.php">'. $dashboard .'</a></div>';
        break;
    case 3:
        echo '<div class="breadcrumb-item active"><a href="tableau-de-bord-sec.php">'. $dashboard .'</a></div>';
        break;
    case 4:
        echo '<div class="breadcrumb-item active"><a href="tableau-de-bord-sec.php">'. $dashboard .'</a></div>';
        break;
    case 5:
        echo '<div class="breadcrumb-item active"><a href="tableau-de-bord-sec.php">'. $dashboard .'</a></div>';
        break;
}

?>

