<?php


include('../database_connection.php');
include('../AddLogInclude.php');
include('../scripts_php/fonctions_sql.php');

// Langues
include('../lang/fr-lang.php');
include('../lang/en-lang.php');



if(isset($_POST['btn_action']))
{  
// AJOUTER
if($_POST['btn_action'] == 'AJOUTER')
{

    // Vérifier si le client existe déjà dans la base
    $query0 = "
    SELECT * FROM client 
    WHERE mail_client = :mail_client 
    ";
    $statement0 = $connect->prepare($query0);
    $statement0->execute(
        array(
            ':mail_client'	=>	$_POST["mail_client"]
        )
    );
    $count = $statement0->rowCount();
    if($count > 0)
    {
        echo json_encode('Client existant');
    }else
    {

        $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
        $maxsize = 8*1048576;
        $nameImage = '/images/clients_images/' .  $_POST['nom_client'] . md5(uniqid(rand(), true)) . '.';

        if(isset($_FILES['photo_client'])) {
            if($_FILES['photo_client']['error'] != UPLOAD_ERR_NO_FILE) {
                
                $info_file = pathinfo($_FILES['photo_client']['name']);

                if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo_client']['size']) {

                    $nameImage = $nameImage . $info_file['extension'];
                    $answer = move_uploaded_file($_FILES['photo_client']['tmp_name'], ".." . $nameImage);

                    if($answer) {

                        $query = "INSERT INTO client (photo_client, nom_client, dg_client, ville_client, pays_client, tel_client, adresse_client, mail_client, site_web_client, nombre_comm_client, note_client, date_create_client, date_last_modif_client, user_create_client, user_last_modif_client) 
                                  VALUES (:photo_client, :nom_client,:dg_client, :ville_client, :pays_client, :tel_client, :adresse_client, :mail_client, :site_web_client, :nombre_comm_client, :note_client, :date_create_client, :date_last_modif_client, :user_create_client, :user_last_modif_client)";


                            $statement = $connect->prepare($query);
                            $correct = $statement->execute(
                            array(
                                'photo_client' => $nameImage,
                                'nom_client' => $_POST["nom_client"],
                                'dg_client' => $_POST["dg_client"],
                                'ville_client' => $_POST["ville_client"],
                                'pays_client' => $_POST["pays_client"],
                                'tel_client' => $_POST["tel_client"],
                                'adresse_client' => $_POST["adresse_client"],
                                'mail_client' => $_POST["mail_client"],
                                'site_web_client' => $_POST["site_web_client"],
                                'nombre_comm_client' => 0,
                                'note_client' => $_POST["note_client"],
                                'date_create_client' => date("Y-m-d H:i:s"),
                                'date_last_modif_client' => date("Y-m-d H:i:s"),
                                //'date_del_client' => date("Y-m-d H:i:s"),
                                'user_create_client' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                'user_last_modif_client' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                //'user_del_client' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                            )
                        );

                        
                        if($correct){
                            echo json_encode('Success');

                             // Log
        switch ($_SESSION['type_user']) {
            case "Super Administrateur":
                addlog("Enr-01-client", $_POST["nom_client"], $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Enr-02-client", $_POST["nom_client"], $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
                break;
            case "Editeur":
                addlog("Enr-03-client", $_POST["nom_client"], $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
                break;
            case "Auteur":
                addlog("Enr-04-client", $_POST["nom_client"], $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
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
    $query = "UPDATE client SET statut_client = :statut_client, deleted_client = :deleted_client, date_del_client = :date_del_client, user_del_client = :user_del_client WHERE client.id_client = :id_client";
    $statement = $connect->prepare($query);
    $correct = $statement->execute(array(
        'id_client' => $_POST["id_client"],
        'statut_client' => $status,
        'deleted_client' => 1,
        'date_del_client' => date("Y-m-d H:i:s"),
        'user_del_client' => $_SESSION['prenom_user'] . ' ' . $_SESSION['nom_user']
    ));


    if($correct){ echo json_encode("Supprime");}else{echo json_encode("erreur");}

    // Log
    // On a besoin du nom du client
    $query00 = "
    SELECT nom_client
    FROM client 
    WHERE id_client = '".$_POST["id_client"]."'
    ";
    $statement00 = $connect->prepare($query00);
    $statement00->execute();
    $result00 = $statement00->fetchAll();

    $nom = "";

    foreach($result00 as $row00)
    {
        $nom = $row00["nom_client"];
    }


    switch ($_SESSION['type_user']) {

        case "Super Administrateur":
            addlog("Del-01-client", $nom, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            break;
        case "Administrateur":
            addlog("Del-02-client", $nom, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            break;
    }



}



      //changer statut
      if($_POST['btn_action'] == 'delete')
      {
       
          $status = 'Actif';
          if($_POST['status'] == 'Actif')
          {
              $status = 'Inactif';
              
          }else{$status = 'Actif';
        }
         
          
          $req = 'UPDATE client SET statut_client = :statut_client, date_last_modif_client = :date_last_modif_client, user_last_modif_client = :user_last_modif_client  WHERE client.id_client = :id_client';
          $result = $connect->prepare($req);
          $result->execute(array(
            'id_client' =>$_POST["id_client"],
            'statut_client' => $status,
            'date_last_modif_client' => date("Y-m-d H:i:s"),
            'user_last_modif_client'=> $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
  ));
  
          echo json_encode($status);
  
          // Log
          // On a besoin du nom du client
          $query00 = "
          SELECT nom_client
          FROM client
          WHERE id_client = '".$_POST["id_client"]."'
          ";
          $statement00 = $connect->prepare($query00);
          $statement00->execute();
          $result00 = $statement00->fetchAll();
  
          $nom_client = "";
  
          foreach($result00 as $row00)
          {
              $nom_client = $row00["nom_client"];
          }
  
          switch ($_SESSION['type_user']) {
  
              case "Super Administrateur":
                  addlog("Chg-01-client", $nom_client. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                  break;
              case "Administrateur":
                  addlog("Chg-02-client", $nom_client. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                  break;
          }
  
  
  
      //}



}


}




  /* Consulter */
/* la selection de tous les colums de la table lorque le bouton est cliqué*/

if(isset($_POST['btn_action_view'])) {

    if ($_POST['btn_action_view'] == 'consulter') {

        $query = "SELECT * FROM client WHERE id_client = :id_client";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_client' => $_POST["id_client_view"]
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
            if ($row['statut_client'] == 'Actif') {
                $status = '<span class=" badge badge-pill badge-success"> Actif </span>';
            } else {
                $status = '<span class="badge badge-pill badge-danger"> Inactif </span>';
            }

            $output .= '
            <tr>
                <td> Image </td>
                <td><img style="height: 100px; width: 100px;" src="..' . $row['photo_client'] .  '" alt="Image du client" /></td>
            </tr>
			<tr>
				<td>Nom du client: </td>
				<td>' . $row["nom_client"] . '</td>
			</tr>
			<tr>
				<td>DG client: </td>
				<td>' . $row["dg_client"] . '</td>
			</tr>
            <tr>
				<td> Ville client: </td>
				<td>' . $row["ville_client"] . '</td>
			</tr>
            <tr>
                <td>Téléphone client: </td>
                <td>' . $row["tel_client"] . '</td>
            </tr>
            <tr>
                <td>Nombre de commande client: </td>
                <td>' . $row["nombre_comm_client"] . '</td>
            </tr>
			<tr>
				<td>Date de création</td>
				<td>' . date("d-m-Y", strtotime($row["date_create_client"])) . ' à ' . date("H:i", strtotime($row["date_create_client"])) . '</td>
			</tr>
			<tr>
				<td>Date de modification</td>
				<td>' . date("d-m-Y", strtotime($row["date_last_modif_client"])) . ' à ' . date("H:i", strtotime($row["date_last_modif_client"])) . '</td>
			</tr>
			<tr>
				<td>Créé par: </td>
                <td>' . $row['user_create_client'] . '</td>
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
                addlog("Info-01-client", $row["nom_client"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Info-02-client", $row["nom_client"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Editeur":
                addlog("Info-03-client", $row["nom_client"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Auteur":
                addlog("Info-04-client", $row["nom_client"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Lecteur":
                addlog("Info-05-client", $row["nom_client"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }


    }

}


if(isset($_POST['btn_action_modif']))
{

   // fetch single
    if($_POST['btn_action_modif'] == 'fetch_single')
    {

        $query = "SELECT * FROM client WHERE id_client = :id_client";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_client'	=>	$_POST["id_client_modif"]
            )
        );
        $result = $statement->fetchAll();

        $nom = '';
        $dg = '';
        $ville = '';
        $pays = '';
        $tel = '';
        $adresse = '';
        $mail = '';
        $site = '';
        //$nbrecom = '';
        //$photo = '';
        $notes = '';

        foreach($result as $row)
        {
            $nom = $row['nom_client'];
            $dg = $row["dg_client"];
            $ville = $row["ville_client"];
            $pays = $row["pays_client"];
            $tel = $row["tel_client"];
            $adresse = $row["adresse_client"];
            $mail = $row["mail_client"];
            $site = $row["site_web_client"];
            //$nbrecom = $row["nombre_comm_client"];
            //$photo = $row["photo_client"];
            $notes = $row['note_client'];
        }

        $output = array(
            'nom_client' => $nom,
            'dg_client' => $dg,
            'ville_client' => $ville,
            'pays_client' => $pays,
            'tel_client' => $tel,
            'adresse_client' => $adresse,
            'mail_client' => $mail,
            'site_web_client' => $site,
            //'nombre_comm_client' => $nbrecom,
            //'photo_client' => $photo,
            'note_client' => $notes
        );

        echo json_encode($output);

    }


    // Modifier
    if($_POST['btn_action_modif'] == 'Modifier')
    {
        // Vérifier si l'client existe déjà dans la base
        $query0 = "
    	SELECT * 
        FROM ( 
            SELECT * 
        	FROM client 
        	WHERE id_client <> :id_client  
        ) AS JP 
        WHERE mail_client = :mail_client
    	";
        $statement0 = $connect->prepare($query0);
        $statement0->execute(
            array(
                ':id_client'	    =>	$_POST["id_client_modif"],
                ':mail_client'	    =>	$_POST["mail_client_modif"]
            )
        );
        $count = $statement0->rowCount();


        if($count > 0)
        {
            echo json_encode('Client existant');
        }else
        {
            
        $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
        $maxsize = 8*1048576;
        $nameImage = '/images/clients_images/' .  $_POST['nom_client_modif'] . md5(uniqid(rand(), true)) . '.';

        if(isset($_FILES['photo'])) {
            if($_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) {
                
                $info_file = pathinfo($_FILES['photo']['name']);

                if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo']['size']) {

                    $nameImage = $nameImage . $info_file['extension'];
                    $answer = move_uploaded_file($_FILES['photo']['tmp_name'], ".." . $nameImage);

                    if($answer) {

            $query = "UPDATE client SET photo_client = :photo_client, nom_client = :nom_client, dg_client = :dg_client, ville_client = :ville_client, pays_client = :pays_client, tel_client = :tel_client, adresse_client = :adresse_client, mail_client = :mail_client, site_web_client = :site_web_client, /*nombre_comm_client = :nombre_comm_client,*/note_client = :note_client, date_last_modif_client = :date_last_modif_client, user_last_modif_client = :user_last_modif_client
            WHERE client.id_client = :id_client";

                $statement = $connect->prepare($query);
                $correct = $statement->execute(
                array(
                    'id_client' => $_POST["id_client_modif"],
                    'nom_client' => $_POST["nom_client_modif"],
                    'photo_client' => $nameImage,
                    'dg_client' => $_POST["dg_client_modif"],
                    'ville_client' => $_POST["ville_client_modif"],
                    'pays_client' => $_POST["pays_client_modif"],
                    'tel_client' => $_POST["tel_client_modif"],
                    'adresse_client' => $_POST["adresse_client_modif"],
                    'mail_client' => $_POST["mail_client_modif"],
                    'site_web_client' => $_POST["site_web_client_modif"],
                    //'nombre_comm_client' => $_POST["nombre_comm_client_modif"],
                    'note_client' => $_POST["note_client_modif"],
                    'date_last_modif_client' => date("Y-m-d H:i:s"),
                    'user_last_modif_client' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                )
            );

            if($correct){
                //$reponse = "Modification réussie";
                echo json_encode('Modifié');
            }else{
                //$reponse = "ERREUR";
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

            // Log
            switch ($_SESSION['type_user']) {

                case "Super Administrateur":
                    addlog("Modif-01-client", $_POST['nom_client_modif'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                    break;
                case "Administrateur":
                    addlog("Modif-02-client", $_POST['nom_client_modif'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                    break;
                case "Editeur":
                    addlog("Modif-03-client", $_POST['nom_client_modif'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                    break;
            }


        }
   } 
}
