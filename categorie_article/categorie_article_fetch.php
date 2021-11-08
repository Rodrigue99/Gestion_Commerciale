<?php

include('../database_connection.php');
include('../AddLogInclude.php');

// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');


$colonne = array("id_categorie_article", "photo_categorie_article", "nom_categorie_article", "description_categorie_article, nombre_article_categorie_article");

$query = '';

$output = array();

$query .= "SELECT * FROM categorie_article WHERE deleted = 0 ";

if(isset($_POST["search"]["value"]))
{
	$query .= 'AND( photo_categorie_article LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR statut_categorie_article LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR nom_categorie_article LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR description_categorie_article LIKE "%' .$_POST["search"]["value"]. '%" ';
    $query .= 'OR nombre_article_categorie_article LIKE "%' .$_POST["search"]["value"]. '%") ';
	
	
}

// Filtrage dans le tableau
if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$colonne[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id_categorie_article DESC ';
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
	if($row['statut_categorie_article'] == 'Actif')
	{
		$status = '<center><span class="badge badge-pill badge-success">Actif</span></center>';
	}
	else
	{
		$status = '<center><span class="badge badge-pill badge-danger">Inactif</span></center>';
	}

	$sub_array = array();
	//$sub_array[] = $row['id_categorie_article'];
	$sub_array[] = '<img class="avatar" src="..' . $row['photo_categorie_article'] . '" alt="image" />';
	$sub_array[] = $row['nom_categorie_article'];
    $sub_array[] = $row['description_categorie_article'];
    $sub_array[] = $row['nombre_article_categorie_article'];
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
        <a class="dropdown-item view" id="'.$row["id_categorie_article"].'" href="#">Consulter</a>
        <a class="dropdown-item update" id="'.$row["id_categorie_article"].'" href="categorie-article-edit.php?id='.$row["id_categorie_article"].'">Modifier</a>
		<a class="dropdown-item remove" id="'.$row["id_categorie_article"].'" href="#">Supprimer</a>


        <div class="dropdown-divider"></div>
        <a class="dropdown-item delete" id="'.$row["id_categorie_article"].'" href="#" data-status="'.$row["statut_categorie_article"].'">'. "Désactiver" . '</a>
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
        <a class="dropdown-item view" id="'.$row["id_categorie_article"].'" href="#">Consulter</a>
        <a class="dropdown-item update" id="'.$row["id_categorie_article"].'" href="categorie-article-edit.php?id=' .$row["id_categorie_article"]. '">Modifier</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item delete" id="'.$row["id_categorie_article"].'" href="#" data-status="'.$row["statut_categorie_article"].'">'. "Désactiver" . '</a>
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
        <a class="dropdown-item view" id="'.$row["id_categorie_article"].'" href="#">Consulter<a>
        <a class="dropdown-item update" id="'.$row["id_categorie_article"].'" href="categorie-article-edit.php?id=' .$row["id_categorie_article"]. '">Modifier</a>
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
        <a class="dropdown-item view" id="'.$row["id_categorie_article"].'" href="#">Consulter</a>
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
        <a class="dropdown-item view" id="'.$row["id_categorie_article"].'" href="#">Consulter</a>
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
	$statement = $connect->prepare("SELECT * FROM categorie_article WHERE deleted = 0 ");//requete d'en haut à remettre
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>