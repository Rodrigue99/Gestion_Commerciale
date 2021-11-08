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

    // Vérifier si le fournisseur existe déjà dans la base
    $query0 = "
    SELECT * FROM fournisseur
    WHERE mail_fournisseur = :mail_fournisseur";
   
    $statement0 = $connect->prepare($query0);
    $statement0->execute(
        array(
            'mail_fournisseur'	=>	$_POST["mail_fournisseur"]
        )
    );
    $count = $statement0->rowCount();
    if($count > 0)
    {
        echo json_encode('Fournisseur existant');
    }else
    {

        $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
        $maxsize = 8*1048576;
        $nameImage = '/images/fournisseurs_images/' .  $_POST['nom_fournisseur'] . md5(uniqid(rand(), true)) . '.';

        if(isset($_FILES['photo_fournisseur'])) {
            if($_FILES['photo_fournisseur']['error'] != UPLOAD_ERR_NO_FILE) {
                
                $info_file = pathinfo($_FILES['photo_fournisseur']['name']);

                if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo_fournisseur']['size']) {

                    $nameImage = $nameImage . $info_file['extension'];
                    $answer = move_uploaded_file($_FILES['photo_fournisseur']['tmp_name'], ".." . $nameImage);

                    if($answer) {

                        $query = "INSERT INTO fournisseur (photo_fournisseur, nom_fournisseur, dg_fournisseur, ville_fournisseur, pays_fournisseur, tel_fournisseur, mail_fournisseur, site_web_fournisseur, note_fournisseur, date_create_fournisseur, date_last_modif_fournisseur, user_create_fournisseur, user_last_modif_fournisseur) 
                                  VALUES (:photo_fournisseur, :nom_fournisseur, :dg_fournisseur, :ville_fournisseur, :pays_fournisseur, :tel_fournisseur, :mail_fournisseur, :site_web_fournisseur, :note_fournisseur, :date_create_fournisseur, :date_last_modif_fournisseur, :user_create_fournisseur, :user_last_modif_fournisseur)";


                            $statement = $connect->prepare($query);
                            $correct = $statement->execute(
                            array(
                                'photo_fournisseur' => $nameImage,
                                'nom_fournisseur' => $_POST["nom_fournisseur"],
                                'dg_fournisseur' => $_POST["dg_fournisseur"],
                                'ville_fournisseur' => $_POST["ville_fournisseur"],
                                'pays_fournisseur' => $_POST["pays_fournisseur"],
                                'tel_fournisseur' => $_POST["tel_fournisseur"],
                                'mail_fournisseur' => $_POST["mail_fournisseur"],
                                'site_web_fournisseur' => $_POST["site_web_fournisseur"],
                                'note_fournisseur' => $_POST["note_fournisseur"],
                                'date_create_fournisseur' => date("Y-m-d H:i:s"),
                                'date_last_modif_fournisseur' => date("Y-m-d H:i:s"),
                                'user_create_fournisseur' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                'user_last_modif_fournisseur' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                            )
                        );
                        if($correct){
                            echo json_encode('Success');

                             // Log
        switch ($_SESSION['type_user']) {
            case "Super Administrateur":
                addlog("Enr-01-fournisseur", $_POST["nom_fournisseur"], $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Enr-02-fournisseur", $_POST["nom_fournisseur"], $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
                break;
            case "Editeur":
                addlog("Enr-03-fournisseur", $_POST["nom_fournisseur"], $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
                break;
            case "Auteur":
                addlog("Enr-04-fournisseur", $_POST["nom_fournisseur"], $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
                break;
        }
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

    }}

        
//delete
if($_POST['btn_action'] == 'remove')
{
    
    $status = 'Actif';
    if($_POST['status'] == 'Actif')
    {
        $status = 'Inactif';
    }

    $query = "UPDATE fournisseur SET statut_fournisseur = :statut_fournisseur, deleted = :deleted, date_del_fournisseur = :date_del_fournisseur, user_del_fournisseur = :user_del_fournisseur WHERE fournisseur.id_fournisseur = :id_fournisseur ";
    $statement = $connect->prepare($query);
    $correct = $statement->execute(array(
        'id_fournisseur' => $_POST["id_fournisseur"],
        'statut_fournisseur' => $status,
        'deleted' => 1,
        'date_del_fournisseur' => date("Y-m-d H:i:s"),
        'user_del_fournisseur' => $_SESSION['prenom_user'] . ' ' . $_SESSION['nom_user']
    ));


    if($correct){ echo json_encode("Supprime");}else{echo json_encode("erreur");}


    // Log
    // On a besoin du nom du fournisseur
    $query00 = "
    SELECT nom_fournisseur
    FROM fournisseur 
    WHERE id_fournisseur = '".$_POST["id_fournisseur"]."'
    ";
    $statement00 = $connect->prepare($query00);
    $statement00->execute();
    $result00 = $statement00->fetchAll();

    $nom = "";

    foreach($result00 as $row00)
    {
        $nom = $row00["nom_fournisseur"];
    }


    switch ($_SESSION['type_user']) {

        case "Super Administrateur":
            addlog("Del-01-fournisseur", $nom,  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            break;
        case "Administrateur":
            addlog("Del-02-fournisseur", $nom,  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            break;
    }



}




      /* changer statut */
      if($_POST['btn_action'] == 'delete')
      {
       
          $status = 'Actif';
          if($_POST['status'] == 'Actif')
          {
              $status = 'Inactif';
              
          }else{$status = 'Actif';}
         
          
          $req = 'UPDATE fournisseur SET statut_fournisseur = :statut_fournisseur, date_last_modif_fournisseur = :date_last_modif_fournisseur, user_last_modif_fournisseur = :user_last_modif_fournisseur 
                    WHERE fournisseur.id_fournisseur = :id_fournisseur';
          $result = $connect->prepare($req);
          $result->execute(array(
            'id_fournisseur' =>$_POST["id_fournisseur"],
            'statut_fournisseur' => $status,
            'date_last_modif_fournisseur' =>  date("Y-m-d H:i:s"),
            'user_last_modif_fournisseur' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
  ));
  
          echo json_encode($status);
  
          // Log
          // On a besoin du nom du fournisseur
          $query00 = "
          SELECT nom_fournisseur
          FROM fournisseur
          WHERE id_fournisseur = '".$_POST["id_fournisseur"]."'
          ";
          $statement00 = $connect->prepare($query00);
          $statement00->execute();
          $result00 = $statement00->fetchAll();
  
          $nom_fournisseur = "";
  
          foreach($result00 as $row00)
          {
              $nom_fournisseur = $row00["nom_fournisseur"];
          }
  
          switch ($_SESSION['type_user']) {

            case "Super Administrateur":
                addlog("Chg-01-fournisseur", $nom_fournisseur. ",". $status,  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Chg-02-fournisseur", $nom_fournisseur. ",". $status,  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }
  
  
  
      //}



}


}

 
 /* Consulter */
/* la selection de tous les colums de la table lorque le bouton est cliqué*/

if(isset($_POST['btn_action_view'])) {

    if ($_POST['btn_action_view'] == 'consulter') {

        $query = "SELECT * FROM fournisseur WHERE id_fournisseur = :id_fournisseur";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_fournisseur' => $_POST["id_fournisseur_view"]
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
            if ($row['statut_fournisseur'] == 'Actif') {
                $status = '<span class=" badge badge-pill badge-success">Actif</span>';
            } else {
                $status = '<span class="badge badge-pill badge-danger">Inactif</span>';
            }

            // Pour le journal d'événements
            //$nom_carac_chambre = $row["nom_carac_chambre"];

            $output .= '
            <tr>
                <td> Image </td>
                <td><img style="height: 100px; width: 100px;" src="..' . $row['photo_fournisseur'] .  '" alt="Image du fournisseur" /></td>
            </tr>
			<tr>
				<td>Nom du fournisseur: </td>
				<td>' . $row["nom_fournisseur"] . '</td>
			</tr>
			<tr>
				<td>DG fournisseur: </td>
				<td>' . $row["dg_fournisseur"] . '</td>
			</tr>
            <tr>
				<td>Ville du fournisseur: </td>
				<td>' . $row["ville_fournisseur"] . '</td>
			</tr>
            <tr>
                <td>Téléphone du fournisseur: </td>
                <td>' . $row["tel_fournisseur"] . '</td>
            </tr>
            <tr>
                <td>Mail du fornisseur</td>
                <td>' . $row["mail_fournisseur"] . '</td>
            </tr>
			<tr>
				<td>Date de création: </td>
				<td>' . date("d-m-Y", strtotime($row["date_create_fournisseur"])) . ' à ' . date("H:i", strtotime($row["date_create_fournisseur"])) . '</td>
			</tr>
			<tr>
				<td>Modifié le: </td>
				<td>' . date("d-m-Y", strtotime($row["date_last_modif_fournisseur"])) . ' à ' . date("H:i", strtotime($row["date_last_modif_fournisseur"])) . '</td>
			</tr>
			<tr>
				<td>Créé par: </td>
                <td>' . $row['user_create_fournisseur'] . '</td>
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

        switch ($_SESSION['type_user']) {

            case "Super Administrateur":
                addlog("Info-01-fournisseur", $row["nom_fournisseur"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Info-02-fournisseur", $row["nom_fournisseur"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Editeur":
                addlog("Info-03-fournisseur", $row["nom_fournisseur"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Auteur":
                addlog("Info-04-fournisseur", $row["nom_fournisseur"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Lecteur":
                addlog("Info-05-fournisseur", $row["nom_fournisseur"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }


    }

}


if(isset($_POST['btn_action_modif']))
{

    /* fetch single */
    if($_POST['btn_action_modif'] == 'fetch_single')
    {

        $query = "SELECT * FROM fournisseur WHERE id_fournisseur = :id_fournisseur";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_fournisseur'	=>	$_POST["id_fournisseur_modif"]
            )
        );
        
        
        $result = $statement->fetchAll();

        $nom_fournisseur = '';
        $dg_fournisseur = '';
        $ville_fournisseur = '';
        $pays_fournisseur = '';
        $tel_fournisseur = '';
        $mail_fournisseur = '';
        $site_web_fournisseur = '';
        $note_fournisseur = '';
        // $photo_fournisseur = '';


        foreach($result as $row)
        {
            $nom_fournisseur = $row['nom_fournisseur'];
            $dg_fournisseur = $row['dg_fournisseur'];
            $ville_fournisseur = $row['ville_fournisseur'];
            $pays_fournisseur = $row['pays_fournisseur'];
            $tel_fournisseur = $row['tel_fournisseur'];
            $mail_fournisseur = $row["mail_fournisseur"];
            $site_web_fournisseur  =$row["site_web_fournisseur"];
            $note_fournisseur = $row["note_fournisseur"];
            // $photo_fournisseur = $row["photo_fournisseur"];
        }

        $output = array(
            'nom_fournisseur' => $nom_fournisseur,
            'dg_fournisseur' => $dg_fournisseur,
            'ville_fournisseur' => $ville_fournisseur,
            'pays_fournisseur' => $pays_fournisseur,
            'tel_fournisseur' => $tel_fournisseur,
            'mail_fournisseur' => $mail_fournisseur,
            'site_web_fournisseur' => $site_web_fournisseur,
            'note_fournisseur' => $note_fournisseur,
            // 'photo_fournisseur' => $photo_fournisseur
        );

        echo json_encode($output);
        // echo json_encode("hi");
        // exit();

    }
    
     // Modifier

     if($_POST['btn_action_modif'] == 'Modifier')
     {
         
 
         // Vérifier si le fournisseur existe déjà dans la base
         $query0 = "
         SELECT * 
         FROM ( 
             SELECT * 
             FROM fournisseur 
             WHERE id_fournisseur <> :id_fournisseur  
         ) AS JP 
         WHERE mail_fournisseur = :mail_fournisseur
         ";
         $statement0 = $connect->prepare($query0);
         $statement0->execute(
             array(
                 'id_fournisseur'	    =>	$_POST["id_fournisseur_modif"],
                 'mail_fournisseur'	    =>	$_POST["mail_fournisseur_modif"],
             )
         );
         $count = $statement0->rowCount();
         if($count > 0)
         {
             echo json_encode('Fournisseur existant');
         }else
         {
             $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
             $maxsize = 8*1048576;
             $nameImage = '/images/fournisseurs_images/' .  $_POST['nom_fournisseur_modif'] . md5(uniqid(rand(), true)) . '.';
 
             if(isset($_FILES['photo_fournisseur_modif'])) {
                 if($_FILES['photo_fournisseur_modif']['error'] != UPLOAD_ERR_NO_FILE) {
                     
                     $info_file = pathinfo($_FILES['photo_fournisseur_modif']['name']);
 
                     if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo_fournisseur_modif']['size']) {
 
                         $nameImage = $nameImage . $info_file['extension'];
                         $answer = move_uploaded_file($_FILES['photo_fournisseur_modif']['tmp_name'], ".." . $nameImage);
 
                         if($answer) {
 
             $query = "UPDATE fournisseur SET photo_fournisseur = :photo_fournisseur, nom_fournisseur = :nom_fournisseur, dg_fournisseur = :dg_fournisseur, ville_fournisseur = :ville_fournisseur, pays_fournisseur = :pays_fournisseur, tel_fournisseur = :tel_fournisseur, mail_fournisseur = :mail_fournisseur, site_web_fournisseur = :site_web_fournisseur, note_fournisseur = :note_fournisseur, date_last_modif_fournisseur = :date_last_modif_fournisseur, user_last_modif_fournisseur = :user_last_modif_fournisseur
             WHERE fournisseur.id_fournisseur = :id_fournisseur";
 
                 $statement = $connect->prepare($query);
                 $correct = $statement->execute(
                 array(
                     'id_fournisseur' => $_POST["id_fournisseur_modif"],
                     'nom_fournisseur' => $_POST["nom_fournisseur_modif"],
                     'photo_fournisseur' => $nameImage,
                     'dg_fournisseur' => $_POST["dg_fournisseur_modif"],
                     'ville_fournisseur' => $_POST["ville_fournisseur_modif"],
                     'pays_fournisseur' => $_POST["pays_fournisseur_modif"],
                     'tel_fournisseur' => $_POST["tel_fournisseur_modif"],
                     'mail_fournisseur' => $_POST["mail_fournisseur_modif"],
                     'site_web_fournisseur' => $_POST["site_web_fournisseur_modif"],
                     'note_fournisseur' => $_POST["note_fournisseur_modif"],
                     'date_last_modif_fournisseur' => date("Y-m-d H:i:s"),
                     'user_last_modif_fournisseur' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                 )
             );
             
             if($correct){
                 echo json_encode('Modifié'); } else { echo json_encode('error');}
            
     
                
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
 
 
 
             // Log
            //  switch ($_SESSION['type_user']) {
 
            //      case "Super Administrateur":
            //          addlog("Modif-01-fournisseur", $_POST['nom_fournisseur_modif'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            //          break;
            //      case "Administrateur":
            //          addlog("Modif-02-fournisseur", $_POST['nom_fournisseur_modif'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            //          break;
            //      case "Editeur":
            //          addlog("Modif-03-fournisseur", $_POST['nom_fournisseur_modif'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            //          break;
                 
            //  }
 
         }
 
     }
 }
 
?>