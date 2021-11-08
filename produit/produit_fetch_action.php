<?php


include('../database_connection.php');
include('../AddLogInclude.php');
include('../scripts_php/fonctions_sql.php');

// // Langues
// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');



if(isset($_POST['btn_action']))
{
         // AJOUTER
if($_POST['btn_action'] == 'AJOUTER')
{
    // Vérifier si le produit existe déjà dans la base
    $query0 = "
    SELECT * FROM produit
    WHERE code_barre_produit = :code_barre_produit";
   
    $statement0 = $connect->prepare($query0);
    $statement0->execute(
        array(
            'code_barre_produit'	=>	$_POST["code_barre_produit"]
        )
    );
    $count = $statement0->rowCount();
    if($count > 0)
    {
        echo json_encode('produit existant'); 
        
    }
    
    else
    {
        if ($_POST["cout_de_revient_produit"] <= 0 || $_POST["prix_de_vente_produit"] <= 0) {
            echo json_encode('bad');
        }
        else {
            $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
            $maxsize = 8*1048576;
            $nameImage = '/images/produits_images/' .  $_POST['nom_produit'] . md5(uniqid(rand(), true)) . '.';

            if(isset($_FILES['photo_produit'])) {
                if($_FILES['photo_produit']['error'] != UPLOAD_ERR_NO_FILE) {
                    
                    $info_file = pathinfo($_FILES['photo_produit']['name']);

                    if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo_produit']['size']) {

                        $nameImage = $nameImage . $info_file['extension'];
                        $answer = move_uploaded_file($_FILES['photo_produit']['tmp_name'], ".." . $nameImage);

                        if($answer) {

                            $query = "INSERT INTO produit (code_barre_produit, reference_produit, nom_produit, categorie_produit, cout_de_revient_produit, prix_de_vente_produit, note_produit, photo_produit, date_create_produit, date_last_modif_produit, user_create_produit, user_last_modif_produit) 
                                      VALUES (:code_barre_produit, :reference_produit, :nom_produit, :categorie_produit, :cout_de_revient_produit, :prix_de_vente_produit, :note_produit, :photo_produit, :date_create_produit, :date_last_modif_produit, :user_create_produit, :user_last_modif_produit)";

                            // $query = "INSERT INTO produit (nom_produit) VALUES (:nom_produit)";

                                $statement = $connect->prepare($query);
                                $success = $statement->execute(
                                array(
                                    'code_barre_produit' => $_POST["code_barre_produit"],
                                    'reference_produit' => $_POST["reference_produit"],
                                    'nom_produit' => $_POST["nom_produit"],
                                    'categorie_produit' => $_POST["categorie_produit"],
                                    'cout_de_revient_produit' => $_POST["cout_de_revient_produit"],
                                    'prix_de_vente_produit' => $_POST["prix_de_vente_produit"],
                                    'note_produit' => $_POST["note_produit"],
                                    'photo_produit' => $nameImage,
                                    'date_create_produit' => date("Y-m-d H:i:s"),
                                    'date_last_modif_produit' => date("Y-m-d H:i:s"),
                                    'user_create_produit' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                    'user_last_modif_produit' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                                )
                            );

                            if ($success) {
                            
                                echo json_encode('Success');
                            }else{
                                echo json_encode(('error'));
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

        }}}
    

    //delete
if($_POST['btn_action'] == 'remove')
{
    
    $status = 'Actif';
    if($_POST['status'] == 'Actif')
    {
        $status = 'Inactif';
    }
    $query = "UPDATE produit SET statut_produit = :statut_produit, deleted = :deleted, date_del_produit = :date_del_produit, user_del_produit = :user_del_produit WHERE produit.id_produit = :id_produit";
    $statement = $connect->prepare($query);
    $correct = $statement->execute(array(
        'id_produit' => $_POST["id_produit"],
        'statut_produit' => $status,
        'deleted' => 1,
        'date_del_produit' => date("Y-m-d H:i:s"),
        'user_del_produit' => $_SESSION['prenom_user'] . ' ' . $_SESSION['nom_user']
    ));

    echo json_encode("Supprime");

    // Log
    // On a besoin du nom de la boisson
    // $query00 = "
    // SELECT nom_produit
    // FROM produit 
    // WHERE id_produit = '".$_POST["id_produit"]."'
    // ";
    // $statement00 = $connect->prepare($query00);
    // $statement00->execute();
    // $result00 = $statement00->fetchAll();

    // $nom = "";

    // foreach($result00 as $row00)
    // {
    //     $nom = $row00["nom_produit"];
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
         
          
          $req = 'UPDATE produit SET statut_produit = :statut_produit, date_last_modif_produit = :date_last_modif_produit, user_last_modif_produit = :user_last_modif_produit
                    WHERE produit.id_produit = :id_produit';
          $result = $connect->prepare($req);
          $result->execute(array(
            'id_produit' =>$_POST["id_produit"],
            'statut_produit' => $status,
            'date_last_modif_produit' =>  date("Y-m-d H:i:s"),
            'user_last_modif_produit' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
  ));
  
          echo json_encode($status);
  
          // Log
          // On a besoin du nom de la chambre
        //   $query00 = "
        //   SELECT nom_produit
        //   FROM produit
        //   WHERE id_produit = '".$_POST["id_produit"]."'
        //   ";
        //   $statement00 = $connect->prepare($query00);
        //   $statement00->execute();
        //   $result00 = $statement00->fetchAll();
  
        //   $nom_produit = "";
  
        //   foreach($result00 as $row00)
        //   {
        //       $nom_produit = $row00["nom_produit"];
        //   }
  
        //   switch ($_SESSION['type_user']) {
  
        //       case 1:
        //           addlog("Chg-01-chambre", $nom_client. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //           break;
        //       case 2:
        //           addlog("Chg-02-chambre", $nom_client. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //           break;
        //   }
  
  
  
      //}



}

}
 
 /* Consulter */
/* la selection de tous les colums de la table lorque le bouton est cliqué*/

if(isset($_POST['btn_action_view'])) {

    if ($_POST['btn_action_view'] == 'consulter') {

        $query = "SELECT * FROM produit WHERE id_produit = :id_produit";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_produit' => $_POST["id_produit_view"]
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
            if ($row['statut_produit'] == 'Actif') {
                $status = '<span class=" badge badge-pill badge-success">Actif</span>';
            } else {
                $status = '<span class="badge badge-pill badge-danger">Inactif</span>';
            }



            $output .= '
            <tr>
				<td>Photo: </td>
                <td><img style="height: 100px; width: 100px;" src="..' . $row['photo_produit'] .  '" alt="Image du produit" /></td>
			</tr>
			<tr>
				<td>Nom de l\'article </td>
				<td>' . $row["nom_produit"] . '</td>
			</tr>
			<tr>
				<td>Code barre: </td>
				<td>' . $row["code_barre_produit"] . '</td>
			</tr>
            <tr>
				<td>Catégorie: </td>
				<td>' . $row["categorie_produit"] . '</td>
			</tr>
            <tr>
                <td>Coût de revient: </td>
                <td>' . $row["cout_de_revient_produit"] . '</td>
            </tr>
            <tr>
                <td>Prix de vente: </td>
                <td>' . $row["prix_de_vente_produit"] . '</td>
            </tr>
			<tr>
				<td>Date de création: </td>
				<td>' . date("d-m-Y", strtotime($row["date_create_produit"])) . ' à ' . date("H:i", strtotime($row["date_create_produit"])) . '</td>
			</tr>
			<tr>
				<td>Date de la dernière modification: </td>
				<td>' . date("d-m-Y", strtotime($row["date_last_modif_produit"])) . ' à ' . date("H:i", strtotime($row["date_last_modif_produit"])) . '</td>
			</tr>
			<tr>
				<td>Créé le</td>
                <td>' . $row['user_create_produit'] . '</td>
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
        //         addlog("Info-01-produit", $row["nom_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 2:
        //         addlog("Info-02-produit", $row["nom_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 3:
        //         addlog("Info-03-produit", $row["nom_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 4:
        //         addlog("Info-04-produit", $row["nom_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 5:
        //         addlog("Info-05-produit", $row["nom_produit"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        // }


    }

}


if(isset($_POST['btn_action_modif']))
{

    /* fetch single */
    if($_POST['btn_action_modif'] == 'fetch_single')
    {

        $query = "SELECT * FROM produit WHERE id_produit = :id_produit";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_produit'	=>	$_POST["id_produit_modif"]
            )
        );
        
        
        $result = $statement->fetchAll();

        $nom_produit = '';
        $code = '';
        $reference = '';
        $categorie = '';
        $coutrevient = '';
        $prixvente = '';
        $note = '';
        //$photo = '';


        foreach($result as $row)
        {
            $nom_produit = $row['nom_produit'];
            $code = $row['code_barre_produit'];
            $reference = $row['reference_produit'];
            $categorie = $row['categorie_produit'];
            $coutrevient = $row['cout_de_revient_produit'];
            $prixvente = $row['prix_de_vente_produit'];
            $note = $row["note_produit"];
            //$photo = $row["photo_produit"];
        }

        $output = array(
            'nom_produit' => $nom_produit,
            'code_barre_produit' => $code,
            'reference_produit' => $reference,
            'categorie_produit' => $categorie,
            'cout_de_revient_produit' => $coutrevient,
            'prix_de_vente_produit' => $prixvente,
            'note_produit' => $note,
            //'photo' => $photo
        );

        echo json_encode($output);
    }
    
    
     // Modifier

     if($_POST['btn_action_modif'] == 'Modifier')
     {
         
 
         // Vérifier si le produit existe déjà dans la base
         $query0 = "
         SELECT * 
         FROM ( 
             SELECT * 
             FROM produit 
             WHERE id_produit <> :id_produit  
         ) AS JP 
         WHERE code_barre_produit = :code_barre_produit
         ";
         $statement0 = $connect->prepare($query0);
         $statement0->execute(
             array(
                 'id_produit'	    =>	$_POST["id_produit_modif"],
                 'code_barre_produit'	    =>	$_POST["code_barre_produit_modif"],
             )
         );
         $count = $statement0->rowCount();
         if($count > 0)
         {
             echo json_encode('Produit existant');

         }else
         {
            if ($_POST["cout_de_revient_produit_modif"] <= 0 || $_POST["prix_de_vente_produit_modif"] <= 0) {
                echo json_encode('bad');
            }
            else {
             $correctExt = array('jpg' , 'jpeg' , 'png', 'JPG', 'JPEG', 'PNG');
             $maxsize = 8*1048576;
             $nameImage = '/images/categories_produit_images/' .  $_POST['nom_produit_modif'] . md5(uniqid(rand(), true)) . '.';
 
             if(isset($_FILES['photo'])) {
                 if($_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) {
                     
                     $info_file = pathinfo($_FILES['photo']['name']);
 
                     if(in_array($info_file['extension'], $correctExt) && $maxsize >= $_FILES['photo']['size']) {
 
                         $nameImage = $nameImage . $info_file['extension'];
                         $answer = move_uploaded_file($_FILES['photo']['tmp_name'], ".." . $nameImage);
 
                         if($answer) {
 
             $query = "UPDATE produit SET photo_produit = :photo_produit, nom_produit = :nom_produit, code_barre_produit = :code_barre_produit, reference_produit = :reference_produit, categorie_produit = :categorie_produit, cout_de_revient_produit = :cout_de_revient_produit, prix_de_vente_produit = :prix_de_vente_produit, note_produit = :note_produit, date_last_modif_produit = :date_last_modif_produit, user_last_modif_produit = :user_last_modif_produit
             WHERE produit.id_produit = :id_produit";
 
                 $statement = $connect->prepare($query);
                 $correct = $statement->execute(
                 array(
                     'id_produit' => $_POST["id_produit_modif"],
                     'nom_produit' => $_POST["nom_produit_modif"],
                     'photo_produit' => $nameImage,
                     'code_barre_produit' => $_POST["code_barre_produit_modif"],
                     'reference_produit' => $_POST["reference_produit_modif"],
                     'categorie_produit' => $_POST["categorie_produit_modif"],
                     'cout_de_revient_produit' => $_POST["cout_de_revient_produit_modif"],
                     'prix_de_vente_produit' => $_POST["prix_de_vente_produit_modif"],
                     'note_produit' => $_POST["note_produit_modif"],
                     'date_last_modif_produit' => date("Y-m-d H:i:s"),
                     'user_last_modif_produit' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                 )
             );
             
             if($correct){
                 echo json_encode('Modifié');
                 } else { 
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
             // switch ($_SESSION['type_user']) {
 
             //     case 1:
             //         addlog("Modif-01-produit", $_POST['nom_produit_modif'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
             //         break;
             //     case 2:
             //         addlog("Modif-02-produit", $_POST['nom_carac_produit_modif'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
             //         break;
             //     case 3:
             //         addlog("Modif-03-produit", $_POST['nom_carac_produit_modif'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
             //         break;
                 
             // }
 
         }
 
     }}
 }
?>