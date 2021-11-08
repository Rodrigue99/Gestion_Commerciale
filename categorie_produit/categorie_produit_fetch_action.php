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
    // Vérifier si le categorie de produit existe déjà dans la base
    $query0 = "
    SELECT * FROM categorie_produit
    WHERE nom_categorie_produit = :nom_categorie_produit";
   
    $statement0 = $connect->prepare($query0);
    $statement0->execute(
        array(
            'nom_categorie_produit'	=>	$_POST["nom_categorie_produit"]
        )
    );
    $count = $statement0->rowCount();
    if($count > 0)
    {
        echo json_encode('categorie existante'); 
        
    }
    else
    {
        if ($_POST["nombre_produit_categorie_produit"] < 0) {
            echo json_encode('bad');
        }
        else {
        
            $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
            $maxsize = 8*1048576;
            $nameImage = '/images/categories_produit_images/' .  $_POST['nom_categorie_produit'] . md5(uniqid(rand(), true)) . '.';

            if(isset($_FILES['photo_categorie_produit'])) {
                if($_FILES['photo_categorie_produit']['error'] != UPLOAD_ERR_NO_FILE) {
                    
                    $info_file = pathinfo($_FILES['photo_categorie_produit']['name']);

                    if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo_categorie_produit']['size']) {

                        $nameImage = $nameImage . $info_file['extension'];
                        $answer = move_uploaded_file($_FILES['photo_categorie_produit']['tmp_name'], ".." . $nameImage);

                        if($answer) {

                            $query = "INSERT INTO categorie_produit (photo_categorie_produit, nom_categorie_produit, description_categorie_produit, nombre_produit_categorie_produit, date_create_categorie_produit, date_last_modif_categorie_produit, user_create_categorie_produit, user_last_modif_categorie_produit) 
                                      VALUES (:photo_categorie_produit, :nom_categorie_produit, :description_categorie_produit, :nombre_produit_categorie_produit, :date_create_categorie_produit, :date_last_modif_categorie_produit, :user_create_categorie_produit, :user_last_modif_categorie_produit)";


                                $statement = $connect->prepare($query);
                                $statement->execute(
                                array(
                                    'photo_categorie_produit' => $nameImage,
                                    'nom_categorie_produit' => $_POST["nom_categorie_produit"],
                                    'description_categorie_produit' => $_POST["description_categorie_produit"],
                                    'nombre_produit_categorie_produit' => $_POST["nombre_produit_categorie_produit"],
                                    'date_create_categorie_produit' => date("Y-m-d H:i:s"),
                                    'date_last_modif_categorie_produit' => date("Y-m-d H:i:s"),
                                    //'date_del_categorie_produit' => date("Y-m-d H:i:s"),
                                    'user_create_categorie_produit' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                    'user_last_modif_categorie_produit' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                    //'user_del_categorie_produit' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                                )
                            );

                            if ($statement) {
                                  echo json_encode('Success');
                            }else{
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

         // Log
         switch ($_SESSION['type_user']) {
            case "Super Administrateur":
                addlog("Enr-01-categ_produit", $_POST["nom_categorie_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Enr-02-categ_produit", $_POST["nom_categorie_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Editeur":
                addlog("Enr-03-categ_produit", $_POST["nom_categorie_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Auteur":
                addlog("Enr-04-categ_produit", $_POST["nom_categorie_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
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
        $query = "UPDATE categorie_produit SET deleted = :deleted, statut_categorie_produit = :statut_categorie_produit, date_del_categorie_produit = :date_del_categorie_produit, user_del_categorie_produit = :user_del_categorie_produit WHERE categorie_produit.id_categorie_produit = :id_categorie_produit";
        $statement = $connect->prepare($query);
        $correct = $statement->execute(array(
            'id_categorie_produit' => $_POST["id_categorie_produit"],
            'deleted'=> 1,
            'statut_categorie_produit' => $status,
            'date_del_categorie_produit' => date("Y-m-d H:i:s"),
            'user_del_categorie_produit' => $_SESSION['prenom_user'] . ' ' . $_SESSION['nom_user']
            ));
    
    
        if($correct){ 
            echo json_encode("Supprime"); 

            
        }else{ echo json_encode("error");}
    
         // Log
          //On a besoin du nom de la chambre
          $query00 = "
          SELECT nom_categorie_produit
          FROM categorie_produit
          WHERE id_categorie_produit = '".$_POST["id_categorie_produit"]."'
          ";
          $statement00 = $connect->prepare($query00);
          $statement00->execute();
          $result00 = $statement00->fetchAll();
  
          $nom_categorie_produit = "";
  
          foreach($result00 as $row00)
          {
              $nom_categorie_produit = $row00["nom_categorie_produit"];
          }

        switch ($_SESSION['type_user']) {
    
            case "Super Administrateur":
                addlog("Del-01-produit", $nom_categorie_produit. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Del-02-produit", $nom_categorie_produit. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }
    
    
    
    }
    

      /* changer status */
      if($_POST['btn_action'] == 'delete')
      {
       
          $status = 'Actif';
          if($_POST['status'] == 'Actif')
          {
              $status = 'Inactif';
              
          }else{$status = 'Actif';}
         
          
          $req = 'UPDATE categorie_produit SET statut_categorie_produit = :statut_categorie_produit, date_last_modif_categorie_produit = :date_last_modif_categorie_produit, user_last_modif_categorie_produit = :user_last_modif_categorie_produit
                   WHERE categorie_produit.id_categorie_produit = :id_categorie_produit';
          $result = $connect->prepare($req);
          $result->execute(array(
            'id_categorie_produit' =>$_POST["id_categorie_produit"],
            'statut_categorie_produit' => $status,
            'date_last_modif_categorie_produit' => date("Y-m-d H:i:s"),
            'user_last_modif_categorie_produit' => $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
  ));
  
          echo json_encode($status);
  
          // Log
          //On a besoin du nom de la chambre
          $query00 = "
          SELECT nom_categorie_produit
          FROM categorie_produit
          WHERE id_categorie_produit = '".$_POST["id_categorie_produit"]."'
          ";
          $statement00 = $connect->prepare($query00);
          $statement00->execute();
          $result00 = $statement00->fetchAll();
  
          $nom_categorie_produit = "";
  
          foreach($result00 as $row00)
          {
              $nom_categorie_produit = $row00["nom_categorie_produit"];
          }
  
          switch ($_SESSION['type_user']) {
  
              case "Super Administrateur":
                  addlog("Chg-01-produit", $nom_categorie_produit. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                  break;
              case "Administrateur":
                  addlog("Chg-02-produit", $nom_categorie_produit. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                  break;
          }
  
  
  
      //}

}
}

 
 /* Consulter */
/* la selection de tous les colums de la table lorque le bouton est cliqué*/

if(isset($_POST['btn_action_view'])) {

    if ($_POST['btn_action_view'] == 'consulter') {

        $query = "SELECT * FROM categorie_produit WHERE id_categorie_produit = :id_categorie_produit";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_categorie_produit' => $_POST["id_categorie_produit_view"]
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
            if ($row['statut_categorie_produit'] == 'Actif') {
                $status = '<span class=" badge badge-pill badge-success">Actif</span>';
            } else {
                $status = '<span class="badge badge-pill badge-danger">Actif</span>';
            }


            $output .= '
            <tr>
                <td> Photo catégorie: </td>
                <td><img style="height: 100px; width: 100px;" src="..' . $row['photo_categorie_produit'] .  '" alt="Image de la categorie" /></td>
            </tr>
			<tr>
				<td>Nom de la catégorie</td>
				<td>' . $row["nom_categorie_produit"] . '</td>
			</tr>
			<tr>
				<td>Description</td>
				<td>' . $row["description_categorie_produit"] . '</td>
			</tr>
            <tr>
				<td>Nombre de d\'articles</td>
				<td>' . $row["nombre_produit_categorie_produit"] . '</td>
			</tr>
			<tr>
				<td>Date de création</td>
				<td>' . date("d-m-Y", strtotime($row["date_create_categorie_produit"])) . ' à ' . date("H:i", strtotime($row["date_create_categorie_produit"])) . '</td>
			</tr>
			<tr>
				<td>Date de modification</td>
				<td>' . date("d-m-Y", strtotime($row["date_last_modif_categorie_produit"])) . ' à ' . date("H:i", strtotime($row["date_last_modif_categorie_produit"])) . '</td>
			</tr>
			<tr>
				<td>Créé par</td>
                <td>' . $row['user_create_categorie_produit'] . '</td>
                </tr>
			<tr>
				<td>Statut</td>
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

        switch ($_SESSION['type_user']) {

            case "Super Administrateur":
                addlog("Info-01-produit", $row["nom_categorie_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Info-02-produit", $row["nom_categorie_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Editeur":
                addlog("Info-03-produit", $row["nom_categorie_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Auteur":
                addlog("Info-04-produit", $row["nom_categorie_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Auteur":
                addlog("Info-05-produit", $row["nom_categorie_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }


    }

}



if(isset($_POST['btn_action_modif']))
{

    // fetch single
    if($_POST['btn_action_modif'] == 'fetch_single')
    {

        $query = "SELECT * FROM categorie_produit WHERE id_categorie_produit = :id_categorie_produit";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_categorie_produit'	=>	$_POST["id_categorie_produit_modif"]
            )
        );
        $result = $statement->fetchAll();

        $nomcategorie = '';
        $description = '';
        $nombreproduit = '';
        $photo= '';  

        foreach($result as $row)
        {
            $nomcategorie = $row['nom_categorie_produit'];
            $description = $row['description_categorie_produit'];
            $nombreproduit = $row['nombre_produit_categorie_produit'];
            //$photo = $row['photo_categorie_produit'];
        }

        $output = array(
            'nom_categorie_produit' => $nomcategorie,
            'description_categorie_produit' => $description,
            'nombre_produit_categorie_produit' => $nombreproduit,
            //'photo_categorie_produit' => $photo
    
        );

        echo json_encode($output);

    }


    // Modifier
    if($_POST['btn_action_modif'] == 'Modifier')
    {
        // Vérifier si l'categorie_produit existe déjà dans la base
        $query0 = "
    	SELECT * 
        FROM ( 
            SELECT * 
        	FROM categorie_produit 
        	WHERE id_categorie_produit <> :id_categorie_produit  
        ) AS JP 
        WHERE nom_categorie_produit = :nom_categorie_produit
    	";
        $statement0 = $connect->prepare($query0);
        $statement0->execute(
            array(
                ':id_categorie_produit'	    =>	$_POST["id_categorie_produit_modif"],
                ':nom_categorie_produit'	=>	$_POST["nom_categorie_produit_modif"]
            )
        );
        $count = $statement0->rowCount();


        if($count > 0)
        {
            echo json_encode('Catégorie existante');
            //echo json_encode('echec');
        }else
        {
            if ($_POST["nombre_produit_categorie_produit_modif"] < 0) {
                echo json_encode('bad');
            }
            else {
            
            $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
            $maxsize = 8*1048576;
            $nameImage = '/images/categories_produit_images/' .  $_POST['nom_categorie_produit_modif'] . md5(uniqid(rand(), true)) . '.';

            if(isset($_FILES['photo'])) {
                if($_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) {
                    
                    $info_file = pathinfo($_FILES['photo']['name']);

                    if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo']['size']) {

                        $nameImage = $nameImage . $info_file['extension'];
                        $answer = move_uploaded_file($_FILES['photo']['tmp_name'], ".." . $nameImage);

                        if($answer) {
                            
                            $query = "UPDATE categorie_produit SET id_categorie_produit = :id_categorie_produit, photo_categorie_produit = :photo_categorie_produit, nom_categorie_produit = :nom_categorie_produit, description_categorie_produit = :description_categorie_produit, nombre_produit_categorie_produit = :nombre_produit_categorie_produit, date_last_modif_categorie_produit = :date_last_modif_categorie_produit, user_last_modif_categorie_produit = :user_last_modif_categorie_produit
                                      WHERE categorie_produit.id_categorie_produit = :id_categorie_produit";

                            $statement = $connect->prepare($query);
                            $correct = $statement->execute(array(
                                'id_categorie_produit'=>$_POST["id_categorie_produit_modif"],
                                'nom_categorie_produit'=>$_POST["nom_categorie_produit_modif"],
                                'photo_categorie_produit' => $nameImage, 
                                'description_categorie_produit'=>$_POST["description_categorie_produit_modif"],
                                'nombre_produit_categorie_produit'=>$_POST["nombre_produit_categorie_produit_modif"],
                                'date_last_modif_categorie_produit'=> date("Y-m-d H:i:s"),
                                'user_last_modif_categorie_produit'=> $_SESSION['prenom_user'] . ' ' . $_SESSION['nom_user']
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
            // Log
            switch ($_SESSION['type_user']) {

                case "Super Administrateur":
                    addlog("Modif-01-produit", $_POST["nom_categorie_produit_modif"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                    break;
                case "Administrateur":
                    addlog("Modif-02-produit", $_POST["nom_categorie_produit_modif"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                    break;
                case "Editeur":
                    addlog("Modif-03-produit", $_POST["nom_categorie_produit_modif"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                    break;
            }


        }}
   } 
} 

?>