<?php

include('../database_connection.php');
include('../AddLogInclude.php');
include('../scripts_php/fonctions_sql.php');

// Langues
// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');



if (isset($_POST['btn_action'])) {

        // AJOUTER
if($_POST['btn_action'] == 'AJOUTER')
{
     // Vérifier si l'article existe déjà dans la base de données
     $query0 = "
     SELECT * FROM article
     WHERE code_barre_article = :code_barre_article";
    
     $statement0 = $connect->prepare($query0);
     $statement0->execute(
         array(
             'code_barre_article'	=>	$_POST["code_barre_article"]
         )
     );
     $count = $statement0->rowCount();
     if($count > 0)
     {
        echo json_encode("article existant"); 
     }
     else
     {
             $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
             $maxsize = 8*1048576;
             $nameImage = '/images/articles_images/' .  $_POST['nom_article'] . md5(uniqid(rand(), true)) . '.';
 
             if(isset($_FILES['photo_article'])) {
                 if($_FILES['photo_article']['error'] != UPLOAD_ERR_NO_FILE) {
                     
                     $info_file = pathinfo($_FILES['photo_article']['name']);
 
                     if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo_article']['size']) {
 
                         $nameImage = $nameImage . $info_file['extension'];
                         $answer = move_uploaded_file($_FILES['photo_article']['tmp_name'], ".." . $nameImage);
 
                         if($answer) {
 
                             $query = "INSERT INTO article (nom_article, photo_article, code_barre_article, ref_article, 
                                                             fournisseur_article, categorie_article, type_article, note_article,
                                                              date_create_article, date_last_modif_article, user_create_article, 
                                                              user_last_modif_article) 
                                       VALUES (:nom_article, :photo_article, :code_barre_article, :ref_article, :fournisseur_article,
                                                 :categorie_article, :type_article, :note_article, :date_create_article, :date_last_modif_article, 
                                                 :user_create_article, :user_last_modif_article)";
 
 
                                 $statement = $connect->prepare($query);
                                 $correct = $statement->execute(
                                 array(
                                     'photo_article' => $nameImage,
                                     'code_barre_article' => $_POST["code_barre_article"],
                                     'ref_article' => $_POST["reference_article"],
                                     'nom_article' => $_POST["nom_article"],
                                     'fournisseur_article' => $_POST["fournisseur_article"],
                                     'categorie_article' => $_POST["categorie_article"],
                                     'type_article' => $_POST["type_article"],
                                     'note_article' => $_POST["note_article"],
                                     'date_create_article' => date("Y-m-d H:i:s"),
                                     'date_last_modif_article' => date("Y-m-d H:i:s"),
                                     'user_create_article' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                     'user_last_modif_article' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                                 )
                             );
 
                             if ($correct) {
                             echo json_encode('Success');
 
                         } else {
                             echo json_encode("Erreur enregistrement image");
                         }
                     } else {
 
                         echo json_encode("Extension non valide ou image trop volumineuse");
                     }
                 } else {
                     echo json_encode("Erreur Telechargement");
                 }
             } else {
                 echo json_encode("Image non soumise");
             }
            }}}
 
//delete
if($_POST['btn_action'] == 'remove')
{
    
    $status = 'Actif';
    if($_POST['status'] == 'Actif')
    {
        $status = 'Inactif';
    }
    $query = "UPDATE article SET statut_article = :statut_article, deleted = :deleted, date_del_article = :date_del_article, user_del_article = :user_del_article WHERE article.id_article = :id_article";
    $statement = $connect->prepare($query);
    $correct = $statement->execute(array(
        'id_article' => $_POST["id_article"],
        'statut_article' => $status,
        'deleted' => 1,
        'date_del_article' => date("Y-m-d H:i:s"),
        'user_del_article' => $_SESSION['prenom_user'] . ' ' . $_SESSION['nom_user']
    ));

    echo json_encode("Supprime");
    // Log
    // // On a besoin du nom de la boisson
    // $query00 = "
    // SELECT nom_article
    // FROM article 
    // WHERE id_article = '".$_POST["id_article"]."'
    // ";
    // $statement00 = $connect->prepare($query00);
    // $statement00->execute();
    // $result00 = $statement00->fetchAll();

    // $nom = "";

    // foreach($result00 as $row00)
    // {
    //     $nom = $row00["nom_article"];
    // }

/*
    switch ($_SESSION['type_user']) {

        case 1:
            addlog("Chg-01-boisson", $lib_boisson. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            break;
        case 2:
            addlog("Chg-02-boisson", $lib_boisson. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            break;
    }*/



}




    /* Changer statut */

    if ($_POST['btn_action'] == 'delete') {

        $status = 'Actif';
        if ($_POST['status'] == 'Actif') {
            $status = 'Inactif';
        } else {
            $status = 'Actif';
        }


        $req = 'UPDATE article SET statut_article = :statut_article,
           date_last_modif_article = :date_last_modif_article, user_last_modif_article = :user_last_modif_article 
           WHERE article.id_article = :id_article';
        $result = $connect->prepare($req);
        $result->execute(array(
            'id_article' => $_POST["id_article"],
            'statut_article' => $status,
            'date_last_modif_article' => date("Y-m-d H:i:s"),
            'user_last_modif_article' => $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]
        ));

        echo json_encode($status);

        // // Log
        // // On a besoin du nom de la chambre
        // $query00 = "
        //   SELECT nom_article
        //   FROM article
        //   WHERE id_article = '" . $_POST["id_article"] . "'
        //   ";
        // $statement00 = $connect->prepare($query00);
        // $statement00->execute();
        // $result00 = $statement00->fetchAll();

        // $nom_article = "";

        // foreach ($result00 as $row00) {
        //     $nom_article = $row00["nom_article"];
        // }

        //   switch ($_SESSION['type_user']) {

        //       case 1:
        //           addlog("Chg-01-chambre", $nom_article. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //           break;
        //       case 2:
        //           addlog("Chg-02-chambre", $nom_article. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //           break;
        //   }



        //}

    }
}




/* Consulter */
/* la selection de tous les columns de la table lorque le bouton est cliqué*/

if (isset($_POST['btn_action_view'])) {

    if ($_POST['btn_action_view'] == 'consulter') {

        $query = "SELECT * FROM article WHERE id_article = :id_article";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                'id_article' => $_POST["id_article_view"]
            )
        );



        /* debut du tableau dans le formulaire */
        $result = $statement->fetchAll();
        $output = '
		<div class="table-responsive">
			<table class="table table-boredered">
		';

        foreach ($result as $row) {
            $status = '';
            if ($row['statut_article'] == 'Actif') {
                $status = '<span class=" badge badge-pill badge-success">Actif</span>';
            } else {
                $status = '<span class="badge badge-pill badge-danger">Inactif</span>';
            }



            $output .= '
            <tr>
				<td>Photo de l\'article: </td>
                <td><img style="height: 100px; width: 100px;" src="..' . $row['photo_article'] .  '" alt="Image de l\'article" /></td>
			</tr>
			<tr>
				<td>Nom del\'article: </td>
				<td>' . $row["nom_article"] . '</td>
			</tr>
			<tr>
				<td>Code barre: </td>
				<td>' . $row["code_barre_article"] . '</td>
			</tr>
            <tr>
				<td>Référence: </td>
				<td>' . $row["ref_article"] . '</td>
			</tr>
            <tr>
				<td>Catégorie: </td>
				<td>' . $row["categorie_article"] . '</td>
			</tr>
            <tr>
                <td>Type article: </td>
                <td>' . $row["type_article"] . '</td>
            </tr>
			<tr>
				<td>Date de création: </td>
				<td>' . date("d-m-Y", strtotime($row["date_create_article"])) . ' à ' . date("H:i", strtotime($row["date_create_article"])) . '</td>
			</tr>
			<tr>
				<td>Modifié le: </td>
				<td>' . date("d-m-Y", strtotime($row["date_last_modif_article"])) . ' à ' . date("H:i", strtotime($row["date_last_modif_article"])) . '</td>
			</tr>
			<tr>
				<td>Créé par: </td>
                <td>' . $row['user_create_article'] . '</td>
                </tr>
			<tr>
				<td>Satut</td>
				<td>' . $status . '</td>
			</tr>
			';
        }


        $output .= '
			</table> 
		</div>
		';
        /* fermeture du tableau */
        echo json_encode($output);

        // switch ($_SESSION['type_user']) {

        //     case 1:
        //         addlog("Info-01-article", $row["nom_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 2:
        //         addlog("Info-02-article", $row["nom_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 3:
        //         addlog("Info-03-article", $row["nom_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 4:
        //         addlog("Info-04-article", $row["nom_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 5:
        //         addlog("Info-05-article", $row["nom_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        // }


    }
}


if (isset($_POST['btn_action_modif'])) {

     // fetch single
     if($_POST['btn_action_modif'] == 'fetch_single')
     {
 
         $query = "SELECT * FROM article WHERE id_article = :id_article";
         $statement = $connect->prepare($query);
         $statement->execute(
             array(
                 ':id_article'	=>	$_POST["id_article_modif"]
             )
         );
         $result = $statement->fetchAll();
 
         $nom = '';
         $code = '';
         $reference = '';
         $categorie = '';
         $fournissseur = '';
         $type = '';
         //photo = '';
         $note = '';
        
         foreach($result as $row)
         {
             $nom = $row['nom_article'];
             $code = $row['code_barre_article'];
            $reference = $row['ref_article'];
            $categorie = $row['categorie_article'];
            $fournissseur = $row['fournisseur_article'];
            $type = $row['type_article'];
            //$photo = $row["photo_article"];
            $note = $row["note_article"];
        
         }
 
         $output = array(
             'nom_article' => $nom,
             'code_barre_article' => $code,
             'reference_article' => $reference,
             'categorie_article' => $categorie,
             'fournisseur_article' => $fournissseur,
             'type_article' => $type,
            // //'photo_client' => $photo,
             'note_article' => $note
         );
 
         echo json_encode($output);
 
     }
 
 
     // Modifier
     if($_POST['btn_action_modif'] == 'Modifier')
     {
         // Vérifier si la categorie d'article existe déjà dans la base
         $query0 = "
         SELECT * 
         FROM ( 
             SELECT * 
             FROM article 
             WHERE id_article <> :id_article  
         ) AS JP 
         WHERE nom_article = :nom_article
         ";
         $statement0 = $connect->prepare($query0);
         $statement0->execute(
             array(
                 ':id_article'	    =>	$_POST["id_article_modif"],
                 ':nom_article'	=>	$_POST["nom_article_modif"]
             )
         );
         $count = $statement0->rowCount();
 
 
         if($count > 0)
         {
             echo json_encode('Article existant');
             //echo json_encode('echec');
         }else
         {
             $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
             $maxsize = 8*1048576;
             $nameImage = '/images/articles_images/' .  $_POST['nom_article_modif'] . md5(uniqid(rand(), true)) . '.';
 
             if(isset($_FILES['photo'])) {
                 if($_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) {
                     
                     $info_file = pathinfo($_FILES['photo']['name']);
 
                     if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo']['size']) {
 
                         $nameImage = $nameImage . $info_file['extension'];
                         $answer = move_uploaded_file($_FILES['photo']['tmp_name'], ".." . $nameImage);
 
                         if($answer) {
                             
                             $query = "UPDATE article SET photo_article = :photo_article, id_article = :id_article, nom_article = :nom_article, code_barre_article = :code_barre_article, ref_article = :ref_article, categorie_article = :categorie_article, type_article = :type_article, fournisseur_article = :fournisseur_article, note_article = :note_article, date_last_modif_article = :date_last_modif_article, user_last_modif_article = :user_last_modif_article
                                       WHERE article.id_article = :id_article";
 
                             $statement = $connect->prepare($query);
                             $correct = $statement->execute(array(
                                 'id_article'=>$_POST["id_article_modif"],
                                 'nom_article'=>$_POST["nom_article_modif"],
                                 'code_barre_article' => $_POST["code_barre_article_modif"],
                                 'ref_article' => $_POST["reference_article_modif"],
                                 'categorie_article' => $_POST["categorie_article_modif"],
                                 'fournisseur_article' => $_POST["fournisseur_article_modif"],
                                 'type_article' => $_POST["type_article_modif"],
                                 'note_article' => $_POST["note_article_modif"],
                                 'photo_article' => $nameImage,
                                 'date_last_modif_article' => date("Y-m-d H:i:s"),
                                 'user_last_modif_article' => $_SESSION['prenom_user'] . ' ' . $_SESSION['nom_user']
                             ));
 
 
                             if($correct){
                                 echo json_encode('Modifié');}else{echo json_encode('error');}
                            
                     
                                
                         } else {
                             echo json_encode("Erreur enregistrement image");
                         }
                     } else {
 
                         echo json_encode("Extension non valide ou image trop volumineuse");
                     }
                 } else {
                     echo json_encode("Erreur Telechargement");
                 }
             } else {
                 echo json_encode("Image non soumise");
             }
 
 
             //echo json_encode('Modifié');
 /*
             // Log
             switch ($_SESSION['type_user']) {
 
                 case 1:
                     addlog("Modif-01-boisson", $_POST['lib_boisson'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                     break;
                 case 2:
                     addlog("Modif-02-boisson", $_POST['lib_boisson'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                     break;
                 case 3:
                     addlog("Modif-03-boisson", $_POST['lib_boisson'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                     break;
             }
 */
 
         }
    } 
 } 
 
 