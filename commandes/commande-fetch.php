<?php

//category_fetch.php

include('../database_connection.php');
include('../AddLogInclude.php');

// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');


$colonne = array("id_commande", "reference_commande", "date_commande", "client_commande", "total_commande", "acompte_commande");

$query = '';

$output = array();

$query .= "
    SELECT * 
    FROM commande WHERE deleted = 0
";

if(isset($_POST["search"]["value"]))
{
	$query .= 'AND(statut_commande LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR date_commande LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR client_commande LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR total_commande LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR reference_commande LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR acompte_commande LIKE "%' .$_POST["search"]["value"]. '%") ';
	
	
}

// Filtrage dans le tableau
if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$colonne[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id_commande DESC ';
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
	
	$status = '';
	if($row['statut_commande'] == 'Oui')
	{
		$status = '<center><span class="badge badge-pill badge-success"> Oui </span></center>';
	}
	elseif($row['statut_commande'] == 'Non')
	{
		$status = '<center><span class="badge badge-pill badge-danger"> Non </span></center>';
	}else{
		$status = '<center><span class="badge badge-pill badge-warning"> Annulé </span></center>';
	}

	$sub_array = array();
	$sub_array[] = $row['reference_commande'];
	$sub_array[] = $row['date_commande'];
  $sub_array[] = $row['client_commande'];
  $sub_array[] = $row['total_commande'];
  $sub_array[] = $row['acompte_commande'];
	$sub_array[] = $status;

	// Super Administrateur ==========================================================================================
	if($_SESSION['type_user'] == 'Super Administrateur')
	{
		if ($row['statut_commande'] == 'Non') {
		
	$sub_array[] = '
	<center>
	
	<div class="btn-group">
      <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item view" id="'.$row["id_commande"].'" href="#"> Consulter </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item update" id="'.$row["id_commande"].'" href="commande-edit.php?id='.$row["id_commande"].'"> Modifier </a>
        <div class="dropdown-divider"></div>
		<a class="dropdown-item remove" id="'.$row["id_commande"].'" href="#" data-status="'.$row["statut_commande"].'"> Supprimer </a>

        <div class="dropdown-divider"></div>


        <a class="dropdown-item new_facture" id="'.$row["id_commande"].'" href="#">'. "Editer facture" . '</a>
      </div>
    </div>
	
	</center>
	';
}elseif($row['statut_commande'] == 'Oui'){
	$sub_array[] = '
	<center>
	
	<div class="btn-group">
      <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item view" id="'.$row["id_commande"].'" href="#"> Consulter </a>

        <div class="dropdown-divider"></div>


        <a class="dropdown-item view_facture" id="'.$row["id_commande"].'" href="#">'. "Afficher la facture" . '</a>
      </div>
    </div>
	
	</center>
	';
}else{
	$sub_array[] = '
	<center>
	
	<div class="btn-group">
      <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item view" id="'.$row["id_commande"].'" href="#"> Consulter </a>

        <div class="dropdown-divider"></div>


        <a class="dropdown-item view_facture" id="'.$row["id_commande"].'" href="#">'. "Afficher la facture" . '</a>
      </div>
    </div>
	
	</center>
	';
}
	}

	// Administrateur ==========================================================================================
	if($_SESSION['type_user'] == 'Administrateur')
	{
	$sub_array[] = '
	<center>
	
	<div class="btn-group">
      <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item view" id="'.$row["id_commande"].'" href="#"> Consulter </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item update" id="'.$row["id_commande"].'" href="commande-edit.php?id='.$row["id_commande"].'"> Modifier </a>
        <div class="dropdown-divider"></div>
		<a class="dropdown-item remove" id="'.$row["id_commande"].'" href="#" data-status="'.$row["statut_commande"].'"> Supprimer </a>


        <!--div class="dropdown-divider"></div-->
        <!--a class="dropdown-item delete" id="'.$row["id_commande"].'" href="#" data-status="'.$row["statut_commande"].'">'. "Désactiver" . '</a-->
      </div>
    </div>
	
	
	</center>
	';
	}


	// Editeur ==========================================================================================
	if($_SESSION['type_user'] == 'Editeur')
	{
	$sub_array[] = '
	<center>
	
	<div class="btn-group">
      <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
      </button>
      <div class="dropdown-menu">
	  <a class="dropdown-item view" id="'.$row["id_commande"].'" href="#"> Consulter </a>
	  <div class="dropdown-divider"></div>
	  <a class="dropdown-item update" id="'.$row["id_commande"].'" href="commande-edit.php?id='.$row["id_commande"].'"> Modifier </a>
      </div>
    </div>
	
	
	</center>
	';
	}


	// Auteur ==========================================================================================
	if($_SESSION['type_user'] == 'Auteur')
	{
	$sub_array[] = '
	<center>
	
	<div class="btn-group">
      <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item view" id="'.$row["id_commande"].'" href="#">'. $consulter . '</a>
      </div>
    </div>
	
	
	</center>
	';
	}


	// Lecteur ==========================================================================================
	if($_SESSION['type_user'] == 'Lecteur')
	{
	$sub_array[] = '
	<center>
	
	<div class="btn-group">
      <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item view" id="'.$row["id_commande"].'" href="#">'. $consulter . '</a>
      </div>
    </div>
	
	
	</center>
	';
	}



	
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
	//$statement = $connect->prepare("SELECT * FROM commande WHERE deleted=false");//requete d'en haut à remettre
	$statement = $connect->prepare("
        SELECT * 
        FROM commande WHERE deleted = 0	
    ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>