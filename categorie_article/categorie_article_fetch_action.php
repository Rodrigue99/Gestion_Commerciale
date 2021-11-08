<?php


include('../database_connection.php');
include('../AddLogInclude.php');
include('../scripts_php/fonctions_sql.php');

// Langues
// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');



if(isset($_POST['btn_action']))
{
    // AJOUTER
if($_POST['btn_action'] == 'AJOUTER')
{

    // Vérifier si la categorie d'article existe déjà dans la base
    $query0 = "
    SELECT * FROM categorie_article
    WHERE nom_categorie_article = :nom_categorie_article";
   
    $statement0 = $connect->prepare($query0);
    $statement0->execute(
        array(
            'nom_categorie_article'	=>	$_POST["nom_categorie_article"]
        )
    );
    $count = $statement0->rowCount();
    if($count > 0)
    {
        echo json_encode('categorie existante');
    }else
    {
        
        if ($_POST["nombre_article_categorie_article"] < 0) {
            echo json_encode('bad');
        }
        else {

        $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
            $maxsize = 8*1048576;
            $nameImage = '/images/categories_article_images/' .  $_POST['nom_categorie_article'] . md5(uniqid(rand(), true)) . '.';

            if(isset($_FILES['photo_categorie_article'])) {
                if($_FILES['photo_categorie_article']['error'] != UPLOAD_ERR_NO_FILE) {
                    
                    $info_file = pathinfo($_FILES['photo_categorie_article']['name']);

                    if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo_categorie_article']['size']) {

                        $nameImage = $nameImage . $info_file['extension'];
                        $answer = move_uploaded_file($_FILES['photo_categorie_article']['tmp_name'], ".." . $nameImage);

                        if($answer) {

                            $query = "INSERT INTO categorie_article (photo_categorie_article, nom_categorie_article, description_categorie_article, nombre_article_categorie_article, date_create_categorie_article, date_last_modif_categorie_article, user_create_categorie_article, user_last_modif_categorie_article) 
                                      VALUES (:photo_categorie_article, :nom_categorie_article, :description_categorie_article, :nombre_article_categorie_article, :date_create_categorie_article, :date_last_modif_categorie_article, :user_create_categorie_article, :user_last_modif_categorie_article)";


                                $statement = $connect->prepare($query);
                                $correct = $statement->execute(
                                array(
                                    'photo_categorie_article' => $nameImage,
                                    'nom_categorie_article' => $_POST["nom_categorie_article"],
                                    'description_categorie_article' => $_POST["description_categorie_article"],
                                    'nombre_article_categorie_article' => $_POST["nombre_article_categorie_article"],
                                    'date_create_categorie_article' => date("Y-m-d H:i:s"),
                                    'date_last_modif_categorie_article' => date("Y-m-d H:i:s"),
                                    //'date_del_categorie_article' => date("Y-m-d H:i:s"),
                                    'user_create_categorie_article' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                    'user_last_modif_categorie_article' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                    //'user_del_categorie_article' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                                )
                            );

                            if($correct){
                                echo json_encode('Success');
                            }
                            else
                            {
                                echo json_encode('error');
                            }
    
    
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
        }
    }  
}  



//delete
if($_POST['btn_action'] == 'remove')
{
    
    $status = 'Actif';
    if($_POST['status'] == 'Actif')
    {
        $status = 'Inactif';
    }
    $query = "UPDATE categorie_article SET statut_categorie_article = :statut_categorie_article, deleted = :deleted, date_del_categorie_article = :date_del_categorie_article, user_del_categorie_article = :user_del_categorie_article WHERE categorie_article.id_categorie_article = :id_categorie_article";
    $statement = $connect->prepare($query);
    $correct = $statement->execute(array(
        'id_categorie_article' => $_POST["id_categorie_article"],
        'statut_categorie_article' => $status,
        'deleted' => 1,
        'date_del_categorie_article' => date("Y-m-d H:i:s"),
        'user_del_categorie_article' => $_SESSION['prenom_user'] . ' ' . $_SESSION['nom_user']
    ));

    echo json_encode("Supprime");

    // Log
    // On a besoin du nom de la boisson
    // $query00 = "
    // SELECT nom_categorie_article
    // FROM categorie_article 
    // WHERE id_categorie_article = '".$_POST["id_categorie_article"]."'
    // ";
    // $statement00 = $connect->prepare($query00);
    // $statement00->execute();
    // $result00 = $statement00->fetchAll();

    // $nom = "";

    // foreach($result00 as $row00)
    // {
    //     $nom = $row00["nom_categorie_article"];
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


      /* changer statut */
      if($_POST['btn_action'] == 'delete')
      {
       
          $status = 'Actif';
          if($_POST['status'] == 'Actif')
          {
              $status = 'Inactif';
              
          }else{$status = 'Actif';}
         
          
          $req = 'UPDATE categorie_article SET statut_categorie_article = :statut_categorie_article , 
          date_last_modif_categorie_article = :date_last_modif_categorie_article, 
          user_last_modif_categorie_article = :user_last_modif_categorie_article
                    WHERE categorie_article.id_categorie_article = :id_categorie_article';
          $result = $connect->prepare($req);
          $result->execute(array(
            'id_categorie_article' =>$_POST["id_categorie_article"],
            'statut_categorie_article' => $status,
            'date_last_modif_categorie_article' => date("Y-m-d H:i:s"),
            'user_last_modif_categorie_article' => $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
  ));
  
          echo json_encode($status);
  
          // Log
          // On a besoin du nom de la chambre
        //   $query00 = "
        //   SELECT nom_categorie_article
        //   FROM categorie_article
        //   WHERE id_categorie_article = '".$_POST["id_categorie_article"]."'
        //   ";
        //   $statement00 = $connect->prepare($query00);
        //   $statement00->execute();
        //   $result00 = $statement00->fetchAll();
  
        //   $nom_categorie_article = "";
  
        //   foreach($result00 as $row00)
        //   {
        //       $nom_categorie_article = $row00["nom_categorie_article"];
        //   }
  
        //   switch ($_SESSION['type_user']) {
  
        //       case 1:
        //           addlog("Chg-01-chambre", $nom_categorie_article. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //           break;
        //       case 2:
        //           addlog("Chg-02-chambre", $nom_categorie_article. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //           break;
        //   }
  
  
  
      //}
}
}


 
 /* Consulter */
/* la selection de tous les colums de la table lorque le bouton est cliqué*/

if(isset($_POST['btn_action_view'])) {

    if ($_POST['btn_action_view'] == 'consulter') {

        $query = "SELECT * FROM categorie_article WHERE id_categorie_article = :id_categorie_article";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_categorie_article' => $_POST["id_categorie_article_view"]
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
            if ($row['statut_categorie_article'] == 'Actif') {
                $status = '<span class=" badge badge-pill badge-success">Actif</span>';
            } else {
                $status = '<span class="badge badge-pill badge-danger">Inactif</span>';
            }
          
            $output .= '
            <tr>
				<td>Photo: </td>
                <td><img style="height: 100px; width: 100px;" src="..' . $row['photo_categorie_article'] .  '" alt="Image" /></td>
			</tr>
			<tr>
				<td>Nom de la catégoorie: </td>
				<td>' . $row["nom_categorie_article"] . '</td>
			</tr>
			<tr>
				<td>Description de la catégorie: </td>
				<td>' . $row["description_categorie_article"] . '</td>
			</tr>
            <tr>
				<td>Nombre d\'article: </td>
				<td>' . $row["nombre_article_categorie_article"] . '</td>
			</tr>
			<tr>
				<td>Date de création: </td>
				<td>' . date("d-m-Y", strtotime($row["date_create_categorie_article"])) . ' à ' . date("H:i", strtotime($row["date_create_categorie_article"])) . '</td>
			</tr>
			<tr>
				<td>Date de dernière modification: </td>
				<td>' . date("d-m-Y", strtotime($row["date_last_modif_categorie_article"])) . ' à ' . date("H:i", strtotime($row["date_last_modif_categorie_article"])) . '</td>
			</tr>
			<tr>
				<td>Créé par: </td>
                <td>' . $row['user_create_categorie_article'] . '</td>
                </tr>
			<tr>
				<td>Statut: </td>
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
        //         addlog("Info-01-categorie_article", $row["nom_categorie_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 2:
        //         addlog("Info-02-categorie_article", $row["nom_categorie_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 3:
        //         addlog("Info-03-categorie_article", $row["nom_categorie_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 4:
        //         addlog("Info-04-categorie_article", $row["nom_categorie_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 5:
        //         addlog("Info-05-categorie_article", $row["nom_categorie_article"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        // }
    }
}



if(isset($_POST['btn_action_modif']))
{

    // fetch single
    if($_POST['btn_action_modif'] == 'fetch_single')
    {

        $query = "SELECT * FROM categorie_article WHERE id_categorie_article = :id_categorie_article";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_categorie_article'	=>	$_POST["id_categorie_article_modif"]
            )
        );
        $result = $statement->fetchAll();

        $nomcategorie = '';
        $description = '';
        $nombrearticle = '';
        // $superficie = '';
        //$photo= '';
        // $notes = '';

        foreach($result as $row)
        {
            $nomcategorie = $row['nom_categorie_article'];
            $description = $row['description_categorie_article'];
            $nombrearticle = $row['nombre_article_categorie_article'];
            // $superficie = $row['superficie'];
            //$photo = $row['photo_categorie_article'];
            // $notes = $row['notes'];
        }

        $output = array(
            'nom_categorie_article' => $nomcategorie,
            'description_categorie_article' => $description,
            'nombre_article_categorie_article' => $nombrearticle,
            // 'superficie' => $superficie,
             //'photo_categorie_article' => $photo,
            // 'notes' => $notes
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
        	FROM categorie_article 
        	WHERE id_categorie_article <> :id_categorie_article  
        ) AS JP 
        WHERE nom_categorie_article = :nom_categorie_article
    	";
        $statement0 = $connect->prepare($query0);
        $statement0->execute(
            array(
                ':id_categorie_article'	    =>	$_POST["id_categorie_article_modif"],
                ':nom_categorie_article'	=>	$_POST["nom_categorie_article_modif"]
            )
        );
        $count = $statement0->rowCount();


        if($count > 0)
        {
            echo json_encode('Catégorie existante');
            //echo json_encode('echec');
        }else
        {
            if ($_POST["nombre_article_categorie_article_modif"] < 0) {
                echo json_encode('bad');
            }
            else {

            $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
            $maxsize = 8*1048576;
            $nameImage = '/images/categories_article_images/' .  $_POST['nom_categorie_article_modif'] . md5(uniqid(rand(), true)) . '.';

            if(isset($_FILES['photo'])) {
                if($_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) {
                    
                    $info_file = pathinfo($_FILES['photo']['name']);

                    if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo']['size']) {

                        $nameImage = $nameImage . $info_file['extension'];
                        $answer = move_uploaded_file($_FILES['photo']['tmp_name'], ".." . $nameImage);

                        if($answer) {
                            
                            $query = "UPDATE categorie_article SET photo_categorie_article = :photo_categorie_article, id_categorie_article = :id_categorie_article, nom_categorie_article = :nom_categorie_article, description_categorie_article = :description_categorie_article, nombre_article_categorie_article = :nombre_article_categorie_article, date_last_modif_categorie_article = :date_last_modif_categorie_article, user_last_modif_categorie_article = :user_last_modif_categorie_article
                                      WHERE categorie_article.id_categorie_article = :id_categorie_article";

                            $statement = $connect->prepare($query);
                            $correct = $statement->execute(array(
                                'id_categorie_article'=>$_POST["id_categorie_article_modif"],
                                'nom_categorie_article'=>$_POST["nom_categorie_article_modif"],
                                'photo_categorie_article' => $nameImage, 
                                'description_categorie_article'=>$_POST["description_categorie_article_modif"],
                                'nombre_article_categorie_article'=>$_POST["nombre_article_categorie_article_modif"],
                                'date_last_modif_categorie_article'=> date("Y-m-d H:i:s"),
                                'user_last_modif_categorie_article'=> $_SESSION['prenom_user'] . ' ' . $_SESSION['nom_user']
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

        }}
   } 
} 


?>