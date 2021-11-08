<?php

//category_fetch.php

include('../database_connection.php');
include('../AddLogInclude.php');

include('../lang/fr-lang.php');
include('../lang/en-lang.php');

if ($_SESSION['lang'] == 'EN') {
    $actif = STATUT_ACTIF_EN;
	$annule = ANNULE_EN;
    $MONTHS_SHORT = MONTHS_SHORT_EN;
} else {
    $actif = STATUT_ACTIF_FR;
	$annule = ANNULE_FR;
    $MONTHS_SHORT = MONTHS_SHORT_FR;
}

// formatter les dates
function formatDatetime($datetime, $lang) {
    $datetime = strtotime($datetime);
    $day = date('d', $datetime);
    $month = date('m', $datetime);
    $year = date('Y', $datetime);
    $hour_12 = date('h', $datetime);
    $hour_24 = date('H', $datetime);
    $min = date('i', $datetime);
    // $sec = date('s', $datetime);
    global $MONTHS_SHORT;

    $format_fr = '%s %s %s à %sh%s';
    $format_en = '%s %s %s at %s:%s %s';
    if ($lang == 'FR') {
        return sprintf($format_fr, $day, $MONTHS_SHORT[(integer) $month], $year, $hour_24, $min);
    } else if ($lang == 'EN') {
        $suffix = 'AM';
        if ((integer) $hour_24 >= 12) {
            $suffix = 'PM';
        }
        return sprintf($format_en, $MONTHS_SHORT[(integer) $month], $day, $year, $hour_12, $min, $suffix);
    }
    return '';
}


// noms des colonnes dans l'ordre
$colonne = array("id_facture", "num_facture", "date_facture", "nom_client", "montant_ttc_facture", "statut_facture");

$query = '';

$output = array();

$query .= "SELECT * FROM facture, commande
WHERE facture.id_commande_fk_facture = commande.id_commande
"; // changer



if(isset($_POST["search"]["value"]))
{	// changer les colonnes à rechercher
	$query .= 'AND ( num_facture LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR date_facture LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR client_commande LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR statut_facture LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR montant_ttc_facture LIKE "%'.$_POST["search"]["value"].'%") ';
}

// Filtrage dans le tableau
if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$colonne[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY date_facture DESC ';
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
	$sub_array = array(); // tenir compte de l'ordre dans le tableau
	$sub_array[] = $row['num_facture'];
	$sub_array[] = $row['date_facture'];
	$sub_array[] = $row['client_commande'];
	$sub_array[] = $row['montant_ttc_facture'];

	// statut
    if($row['statut_facture'] == 'Actif') {
        $statut = '<center><span class="badge badge-pill badge-primary">Actif</span></center>';
    } else {
        $statut = '<td><td><center><span class="badge badge-pill badge-warning">Annulé</span></center></td></td>';
    }
    $sub_array[] = $statut;
	

	if ($_SESSION['lang'] == 'EN') {
		$view_facture = AFFICHER_FACTURE_EN;
		$annule_facture = ANNULER_FACTURE_EN;
	} else {
		$view_facture = "Afficher la facture";
		$annule_facture = "Annuler la facture";
	}

	$bouton_view_facture    =   '<a class="dropdown-item view_facture" id="'.$row["id_facture"].'" href="#" title="Accès: Tous les rôles">'. $view_facture . '</a>';
    $bouton_annule_facture    =   '<a class="dropdown-item delete_facture" id="'.$row["id_facture"].'" href="#" title="Accès: Super Administrateur uniquement">'. $annule_facture . '</a>';

    $actions = '
        <center>
            <div class="btn-group">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu">
    ';

    // Super Administrateur ==========================================================================================
	if($_SESSION['type_user'] == 'Super Administrateur')
	{
		$actions .= $bouton_view_facture;
		if ($row['statut_facture'] == 'Actif') {
			$actions .= $bouton_annule_facture;
		}
	}

    // Administrateur ==========================================================================================
	if($_SESSION['type_user'] == 'Administrateur')
	{
		$actions .= $bouton_view_facture;
	}

	// Editeur ==========================================================================================
	if($_SESSION['type_user'] == 'Editeur')
	{
		$actions .= $bouton_view_facture;
	}

	// Auteur ==========================================================================================
	if($_SESSION['type_user'] == 'Auteur')
	{
		$actions .= $bouton_view_facture;
	}

	// Lecteur ==========================================================================================
	if($_SESSION['type_user'] == 'Lecteur')
	{
		$actions .= $bouton_view_facture;
	}


    $actions .= '
                </div>
            </div>
        </center>
    ';

	$sub_array[] = $actions;
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
	$statement = $connect->prepare("SELECT * FROM facture, commande
	WHERE facture.id_commande_fk_facture = commande.id_commande
	"); // same query as above
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>