<?php

//category_fetch.php

include('../database_connection.php');
include('../AddLogInclude.php');

// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');


$colonne = array("id_client", "photo_client", "nom_client", "dg_client", "ville_client", "tel_client", "nombre_comm_client");

$query = '';

$output = array();

$query .= "
    SELECT * 
    FROM client WHERE deleted_client = 0
";

if(isset($_POST["search"]["value"]))
{
	$query .= 'AND( photo_client LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR statut_client LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR nom_client LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR dg_client LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR ville_client LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR tel_client LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR nombre_comm_client LIKE "%' .$_POST["search"]["value"]. '%" )';
	
	
}

// Filtrage dans le tableau
if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$colonne[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id_client DESC ';
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
	if($row['statut_client'] == 'Actif')
	{
		$status = '<center><span class="badge badge-pill badge-success"> Actif </span></center>';
	}
	else
	{
		$status = '<center><span class="badge badge-pill badge-danger"> Inactif </span></center>';
	}

	$sub_array = array();
	//$sub_array[] = $row['id_client'];
	$sub_array[] = '<img class="avatar" src="..' . $row['photo_client'] . '" alt="image" />';
	$sub_array[] = $row['nom_client'];
    $sub_array[] = $row['dg_client'];
    $sub_array[] = $row['ville_client'];
    $sub_array[] = $row['tel_client'];
    $sub_array[] = $row['nombre_comm_client'];
	$sub_array[] = $status;

	// Super Administrateur ==========================================================================================
	if($_SESSION['type_user'] == 'Super Administrateur')
	{
	$sub_array[] = '
	<center>
	
	<div class="btn-group">
      <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item view" id="'.$row["id_client"].'" href="#"> Consulter </a>
        <a class="dropdown-item update" id="'.$row["id_client"].'" href="client-edit.php?id='.$row["id_client"].'"> Modifier </a>
		<a class="dropdown-item remove" id="'.$row["id_client"].'" href="#" data-status="'.$row["statut_client"].'"> Supprimer </a>


        <div class="dropdown-divider"></div>
        <a class="dropdown-item delete" id="'.$row["id_client"].'" href="#" data-status="'.$row["statut_client"].'">'. "Désactiver" . '</a>
      </div>
    </div>
	
	</center>
	';
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
        <a class="dropdown-item view" id="'.$row["id_client"].'" href="#"> Consulter </a>
        <a class="dropdown-item update" id="'.$row["id_client"].'" href="client-edit.php?id='.$row["id_client"].'"> Modifier </a>
		<a class="dropdown-item remove" id="'.$row["id_client"].'" href="#" data-status="'.$row["statut_client"].'"> Supprimer </a>


        <div class="dropdown-divider"></div>
        <a class="dropdown-item delete" id="'.$row["id_client"].'" href="#" data-status="'.$row["statut_client"].'">'. "Désactiver" . '</a>
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
	  <a class="dropdown-item view" id="'.$row["id_client"].'" href="#"> Consulter </a>
	  <a class="dropdown-item update" id="'.$row["id_client"].'" href="client-edit.php?id='.$row["id_client"].'"> Modifier </a>
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
        <a class="dropdown-item view" id="'.$row["id_client"].'" href="#">'. $consulter . '</a>
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
        <a class="dropdown-item view" id="'.$row["id_client"].'" href="#">'. $consulter . '</a>
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
	//$statement = $connect->prepare("SELECT * FROM client WHERE deleted=false");//requete d'en haut à remettre
	$statement = $connect->prepare("
        SELECT * 
        FROM client WHERE deleted_client = 0	
    ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>