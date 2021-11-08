<?php

//category_fetch.php

include('../database_connection.php');
include('../AddLogInclude.php');

// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');


$colonne = array("id_fournisseur", "photo_fournisseur", "nom_fournisseur", "dg_fournisseur", "ville_fournisseur", "tel_fournisseur", "mail_fournisseur");

$query = '';

$output = array();

$query .= "SELECT * FROM fournisseur WHERE deleted = 0 ";

if(isset($_POST["search"]["value"]))
{
	$query .= 'AND (photo_fournisseur LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR statut_fournisseur LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR nom_fournisseur LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR dg_fournisseur LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR ville_fournisseur LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR tel_fournisseur LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR mail_fournisseur LIKE "%' .$_POST["search"]["value"]. '%" )';
	
	
}

// Filtrage dans le tableau
if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$colonne[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id_fournisseur DESC ';
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
	if($row['statut_fournisseur'] == 'Actif')
	{
		$status = '<center><span class="badge badge-pill badge-success">Actif</span></center>';
	}
	else
	{
		$status = '<center><span class="badge badge-pill badge-danger">Inactif</span></center>';
	}

	$sub_array = array();
	//$sub_array[] = $row['id_fournisseur'];
	$sub_array[] = '<img src="..' . $row['photo_fournisseur'] .  '" alt="Image du client" />';
	$sub_array[] = $row['nom_fournisseur'];
    $sub_array[] = $row['dg_fournisseur'];
    $sub_array[] = $row['ville_fournisseur'];
    $sub_array[] = $row['tel_fournisseur'];
    $sub_array[] = $row['mail_fournisseur'];
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
        <a class="dropdown-item view" id="'.$row["id_fournisseur"].'" href="#">Consulter</a>
        <a class="dropdown-item update" id="'.$row["id_fournisseur"].'" href="fournisseur-edit.php?id='.$row["id_fournisseur"].'">Modifier</a>
		<a class="dropdown-item remove" id="'.$row["id_fournisseur"].'" href="#">Supprimer</a>


        <div class="dropdown-divider"></div>
        <a class="dropdown-item delete" id="'.$row["id_fournisseur"].'" href="#" data-status="'.$row["statut_fournisseur"].'">'. "Désactiver" . '</a>
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
        <a class="dropdown-item view" id="'.$row["id_fournisseur"].'" href="#">Consulter</a>
        <a class="dropdown-item update" id="'.$row["id_fournisseur"].'" href="#">fournisseur-edit.php?id='.$row["id_fournisseur"].'</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item delete" id="'.$row["id_fournisseur"].'" href="#" data-status="'.$row["statut_fournisseur"].'">'. "Désactiver" . '</a>
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
        <a class="dropdown-item view" id="'.$row["id_fournisseur"].'" href="#">Consulter</a>
        <a class="dropdown-item update" id="'.$row["id_fournisseur"].'" href="fournisseur-edit.php?id='.$row["id_fournisseur"].'">Modifier</a>
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
        <a class="dropdown-item view" id="'.$row["id_fournisseur"].'" href="#">Consulter</a>
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
        <a class="dropdown-item view" id="'.$row["id_fournisseur"].'" href="#">Consulter</a>
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
	$statement = $connect->prepare("SELECT * FROM fournisseur WHERE deleted  = 0 ");//requete d'en haut à remettre
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>