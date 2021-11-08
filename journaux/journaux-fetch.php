<?php

include('../database_connection.php');
include('../AddLogInclude.php');

include('../lang/fr-lang.php');
include('../lang/en-lang.php');

// if ($_SESSION['lang'] == 'EN') {
//     define('LOG', LOG_EN);
// 	define('LABEL_TYPE_COMPTE', LABEL_TYPE_COMPTE_EN);
// } else {
    define('LOG', LOG_FR);
	define('LABEL_TYPE_COMPTE', LABEL_TYPE_COMPTE_FR);
// }

// noms des colonnes dans l'ordre
$colonne = array("id_addlog_table", "CodeEvenement", "MessageEvenement", "DateEvenement", "HeureEvenement", "PseudoUtilisateur", "AdresseIP");

$query = '';

$output = array();

$query .= "SELECT * FROM addlog_table ";

if(isset($_POST["search"]["value"]))
{	// changer les colonnes à rechercher
	$query .= 'WHERE CodeEvenement LIKE "%'.$_POST["search"]["value"].'%" ';
	//$query .= 'OR MessageEvenement LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR DateEvenement LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR HeureEvenement LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR PseudoUtilisateur LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR AdresseIP LIKE "%'.$_POST["search"]["value"].'%" ';
}

// Filtrage dans le tableau
if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$colonne[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id_addlog_table DESC ';
}

if($_POST['length'] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

$filtered_rows = $statement->rowCount();

foreach($result as $row)
{
	$sub_array = array(); // tenir compte de l'ordre dansle tableau
	$sub_array[] = $row['id_addlog_table'];
	$sub_array[] = $row['CodeEvenement'];
	
	$code_evenement = $row['CodeEvenement'];
	$code = explode("-", $code_evenement)[0];
	$type_compte = (integer) explode("-", $code_evenement)[1];
	$parametres_evenement =  explode(",", $row['ParametresEvenement']);

	// Message Evenement
	// gérer les langues du statut
	if ($code == 'Chg') {
		$statut = $parametres_evenement[1];
		if($_SESSION['lang'] == 'EN') {
			if($statut == 'Actif') {$statut = STATUT_ACTIF_EN;}  
			if($statut == 'Inactif') {$statut = STATUT_INACTIF_EN;}  
			$parametres_evenement[1] = $statut;
		}
	}

	$message_evenement = sprintf(LOG[$code_evenement], ...$parametres_evenement);
	$sub_array[] = $message_evenement;


	$sub_array[] = date("d-m-Y", strtotime($row['DateEvenement']));
	$sub_array[] = $row['HeureEvenement'];
	
	
	// Pseudo Utilisateur
	if ($code !== 'ErrConnex') {
		$sub_array[] = $row['PseudoUtilisateur'] . " - " . LABEL_TYPE_COMPTE[$type_compte];
	} else {
		$sub_array[] = $row['PseudoUtilisateur'];
	}


	$sub_array[] = $row['AdresseIP'];
	
	
	$data[] = $sub_array;
}

$output = array(
	"draw"			=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"				=>	$data
);

function get_total_all_records($connect)
{
	$statement = $connect->prepare("SELECT * FROM addlog_table"); // same query as above
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>