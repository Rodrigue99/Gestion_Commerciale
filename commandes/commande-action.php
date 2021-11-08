<?php


include('../database_connection.php');
include('../AddLogInclude.php');
include('../scripts_php/fonctions_sql.php');

// Langues
// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');




if(isset($_POST['btn_action'])){  

// AJOUTER
if($_POST['btn_action'] == 'AJOUTER')
{
    // Vérifier si la commande existe déjà dans la base
    $query0 = "
    SELECT * FROM commande 
    WHERE reference_commande = :reference_commande 
    ";
    $statement0 = $connect->prepare($query0);
    $statement0->execute(
        array(
            ':reference_commande'	=>	$_POST["reference_commande"]
        )
    );
    $count = $statement0->rowCount();
    if($count > 0)
    {
        echo json_encode('commande existante');
    }else
    {    
        if ($_POST["quantite"] <= 0){ 
            echo json_encode('quantite');
        }elseif ($_POST["tva"] < 0) {
            echo json_encode('tva');
        }elseif ($_POST["acompte"] > $_POST["ttc"]) {
            echo json_encode('acompte');
        }else {
        

        $query = "INSERT INTO commande (reference_commande, client_commande, total_commande, acompte_commande, produit_commande, prix_de_vente_commande, quantite_commande, mode_de_paiement_commande, date_de_livraison_commande, date_commande, montant_hors_taxe_commande, tva_commande, montant_ttc_commande, date_create_commande, date_last_modif_commande, user_create_commande, user_last_modif_commande) 
                                  VALUES (:reference_commande, :client_commande,:total_commande, :acompte_commande, :produit_commande, :prix_de_vente_commande, :quantite_commande, :mode_de_paiement_commande, :date_de_livraison_commande, :date_commande, :montant_hors_taxe_commande, :tva_commande, :montant_ttc_commande, :date_create_commande, :date_last_modif_commande, :user_create_commande, :user_last_modif_commande)";

                            $statement = $connect->prepare($query);
                            $correct = $statement->execute(
                            array(
                                'reference_commande' => $_POST["reference_commande"],
                                'date_commande' => $_POST["date_commande"],
                                'client_commande' => $_POST["client"],
                                'total_commande' => $_POST["montant_hors_taxe"],
                                'produit_commande' => $_POST["produit"],
                                'prix_de_vente_commande' => $_POST["prix_de_vente"],
                                'quantite_commande' => $_POST["quantite"],
                                'montant_hors_taxe_commande' => $_POST["montant_hors_taxe"],
                                'tva_commande' => $_POST["tva"],
                                'montant_ttc_commande' => $_POST['ttc'],
                                'mode_de_paiement_commande' => $_POST["paiement"],
                                'acompte_commande' => $_POST["acompte"],
                                'date_de_livraison_commande' => $_POST["date_livraison"],
                                'date_create_commande' => date("Y-m-d H:i:s"),
                                'date_last_modif_commande' => date("Y-m-d H:i:s"),
                                'user_create_commande' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
                                'user_last_modif_commande' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                            )
                        );

                        if($correct){
                            echo json_encode('Success');
                        }
                        else
                        {
                            echo json_encode('error');
                        }

                        $query000 = "SELECT * FROM commande WHERE deleted = 0 AND client_commande = :client_commande";
                        $statement000 = $connect->prepare($query000);
                        $statement000->execute(array(':client_commande'	=>	$_POST["client"]));
                        $result1 = $statement000->fetchAll();
                        //$count = $statement0->rowCount();
                        //if($statement000){echo json_encode('hhh');}

                         $som = 0;

                        foreach($result1 as $row)
                        {
                            $som += 1;
                        }

                        $query001 = "UPDATE client SET nombre_comm_client = :nombre_comm_client WHERE nom_client = :nom_client";
                        $answer001 = $connect->prepare($query001);
                        $answer001->execute(array('nom_client' => $_POST["client"],
                                                  'nombre_comm_client' => $som));
                        

       /* // Log
        switch ($_SESSION['type_user']) {
            case 1:
                addlog("Enr-01-boisson", $_POST["lib_boisson"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case 2:
                addlog("Enr-02-boisson", $_POST["lib_boisson"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case 3:
                addlog("Enr-03-boisson", $_POST["lib_boisson"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case 4:
                addlog("Enr-04-boisson", $_POST["lib_boisson"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        } */
    }
    }
}

    
//delete
if($_POST['btn_action'] == 'remove'){
    
    $status = 'Actif';
    if($_POST['status'] == 'Actif')
    {
        $status = 'Inactif';
    }
    $query = "UPDATE commande SET statut_commande = :statut_commande, deleted = :deleted, date_del_commande = :date_del_commande, user_del_commande = :user_del_commande WHERE commande.id_commande = :id_commande";
    $statement = $connect->prepare($query);
    $correct = $statement->execute(array(
        'id_commande' => $_POST["id_commande"],
        'statut_commande' => $status,
        'deleted' => 1,
        'date_del_commande' => date("Y-m-d H:i:s"),
        'user_del_commande' => $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
    ));
                        

    if($correct){ 
        echo json_encode("Supprime");
    }else{
        
        echo json_encode("erreur");
    }

                    $q = "SELECT client_commande, deleted FROM commande WHERE id_commande = :id_commande";
                    $s = $connect->prepare($q);
                    $s->execute(array(':id_commande' => $_POST["id_commande"]));
                    $r = $s->fetchColumn();

                    $query000 = "SELECT * FROM commande WHERE deleted = 0 AND client_commande = :client_commande";
                    $statement000 = $connect->prepare($query000);
                    $statement000->execute(array(':client_commande'	=>	$r));
                    $result1 = $statement000->fetchAll();
                        //$count = $statement0->rowCount();
                        //if($statement000){echo json_encode('hhh');}

                         $som = 0;

                        foreach($result1 as $row)
                        {
                            $som += 1;
                        }

                        $query001 = "UPDATE client SET nombre_comm_client = :nombre_comm_client WHERE nom_client = :nom_client";
                        $answer001 = $connect->prepare($query001);
                        $answer001->execute(array('nom_client' => $r,
                                                  'nombre_comm_client' => $som));

    

            //     // Log
            //     // On a besoin du nom de la boisson
            //     $query00 = "
            //     SELECT nom_commande
            //     FROM commande 
            //     WHERE id_commande = '".$_POST["id_commande"]."'
            //     ";
            //     $statement00 = $connect->prepare($query00);
            //     $statement00->execute();
            //     $result00 = $statement00->fetchAll();

            //     $nom = "";

            //     foreach($result00 as $row00)
            //     {
            //         $nom = $row00["nom_commande"];
            //     }
            
            // /*
            //     switch ($_SESSION['type_user']) {
            
            //         case 1:
            //             addlog("Chg-01-boisson", $lib_boisson. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            //             break;
            //         case 2:
            //             addlog("Chg-02-boisson", $lib_boisson. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
            //             break;
            //     }*/



}



      //changer statut
    if($_POST['btn_action'] == 'delete'){
       
          $status = 'Actif';
          if($_POST['status'] == 'Actif')
          {
              $status = 'Inactif';
              
          }else{$status = 'Actif';}
         
          $req = 'UPDATE commande SET statut_commande = :statut_commande, date_last_modif_commande = :date_last_modif_commande, user_last_modif_commande = :user_last_modif_commande  WHERE commande.id_commande = :id_commande';
          $result = $connect->prepare($req);
          $result->execute(array(
            'id_commande' =>$_POST["id_commande"],
            'statut_commande' => $status,
            'date_last_modif_commande' => date("Y-m-d H:i:s"),
            'user_last_modif_commande'=> $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
        ));
  
          echo json_encode($status);
  
            //           // Log
            //           // On a besoin du nom de la chambre
            //           $query00 = "
            //           SELECT nom_commande
            //           FROM commande
            //           WHERE id_commande = '".$_POST["id_commande"]."'
            //           ";
            //           $statement00 = $connect->prepare($query00);
            //           $statement00->execute();
            //           $result00 = $statement00->fetchAll();

            //           $nom_commande = "";

            //           foreach($result00 as $row00)
            //           {
            //               $nom_commande = $row00["nom_commande"];
            //           }
  
        //   switch ($_SESSION['type_user']) {
  
        //       case 1:
        //           addlog("Chg-01-chambre", $nom_commande. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //           break;
        //       case 2:
        //           addlog("Chg-02-chambre", $nom_commande. "," .$status, $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //           break;
        //   }
  
  
  
    }
// }

}

  /* Consulter */
/* la selection de tous les colums de la table lorque le bouton est cliqué*/

if(isset($_POST['btn_action_view'])) {

    if ($_POST['btn_action_view'] == 'consulter') {

        $query = "SELECT * FROM commande WHERE id_commande = :id_commande";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id_commande' => $_POST["id_commande_view"]
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


            
            $output .= '
			<tr>
				<td>Référence: </td>
				<td>' . $row["reference_commande"] . '</td>
			</tr>
			<tr>
				<td>Date: </td>
				<td>' . $row["date_commande"] . '</td>
			</tr>
            <tr>
				<td> Client: </td>
				<td>' . $row["client_commande"] . '</td>
			</tr>
            <tr>
                <td>Total: </td>
                <td>' . $row["total_commande"] . '</td>
            </tr>
            <tr>
                <td>Acompte: </td>
                <td>' . $row["acompte_commande"] . '</td>
            </tr>
			<tr>
				<td>Date de création</td>
				<td>' . date("d-m-Y", strtotime($row["date_create_commande"])) . ' à ' . date("H:i", strtotime($row["date_create_commande"])) . '</td>
			</tr>
			<tr>
				<td>Date de modification</td>
				<td>' . date("d-m-Y", strtotime($row["date_last_modif_commande"])) . ' à ' . date("H:i", strtotime($row["date_last_modif_commande"])) . '</td>
			</tr>
			<tr>
				<td>Créé par: </td>
                <td>' . $row['user_create_commande'] . '</td>
                </tr>
			<tr>
				<td>Facture: </td>
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
        //         addlog("Info-01-commande", $row["nom_commande"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 2:
        //         addlog("Info-02-commande", $row["nom_commande"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 3:
        //         addlog("Info-03-commande", $row["nom_commande"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 4:
        //         addlog("Info-04-commande", $row["nom_commande"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 5:
        //         addlog("Info-05-commande", $row["nom_commande"], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        // }


    }

}

if(isset($_POST['btn']))
{
  // fetch
  if($_POST['btn'] == 'fetch')
  {

      $query = "SELECT prix_de_vente_produit FROM produit WHERE nom_produit = :nom_produit";
      $statement = $connect->prepare($query);
      $statement->execute(
          array(
              ':nom_produit'	=>	$_POST["id_produit"]
          )
      );
      $result = $statement->fetchAll();

      $nom = '';

      foreach($result as $row)
      {
          $nom = $row['prix_de_vente_produit'];

      $output = array(
          'prix_de_vente_produit' => $nom,

      );

      echo json_encode($output);

      }
    }

    }

if(isset($_POST['btn_action_modif'])){

    if($_POST['btn_action_modif'] == 'fetch_single'){
        $query111 = "SELECT * FROM commande WHERE id_commande = :id_commande";
        $statement111 = $connect->prepare($query111);
        $statement111->execute(
            array(
                ':id_commande'   =>  $_POST["id_commande_modif"]
            )
        );
        $result111 = $statement111->fetchAll();

        $ref = '';
        $datcom = '';
        $client = '';
        $produit = '';
        $prix = '';
        $quantite = '';
        $mht = '';
        $tva = '';
        $ttc = '';
        $paiement = '';
        $acompte = '';
        $dateliv = '';


        foreach($result111 as $row)
        {
            $ref = $row["reference_commande"];
            $datcom = $row["date_commande"];
            $client = $row["client_commande"];
            $produit = $row["produit_commande"];
            $prix = $row["prix_de_vente_commande"];
            $quantite = $row["quantite_commande"];
            $mht = $row["montant_hors_taxe_commande"];
            $tva = $row["tva_commande"];
            $ttc = $row["montant_ttc_commande"];
            $paiement = $row["mode_de_paiement_commande"];
            $acompte = $row["acompte_commande"];
            $dateliv = $row["date_de_livraison_commande"];
         }

        $output = array(
            'reference_commande' => $ref,
            'date_commande' => $datcom,
            'client_commande' => $client,
            'produit_commande' => $produit,
            'prix_de_vente_commande' => $prix,
            'quantite_commande' => $quantite,
            'montant_hors_taxe_commande' => $mht,
            'tva_commande' => $tva,
            'montant_ttc_commande' => $ttc,
            'mode_de_paiement_commande' => $paiement,
            'acompte_commande' => $acompte,
            'date_de_livraison_commande' => $dateliv
        );

        echo json_encode($output);
    }  

    // Modifier
    if($_POST['btn_action_modif'] == 'Modifier')
    {
        // if ($request) {
        //    echo json_encode('bon');
        // }else{echo json_encode('non');}



        // Vérifier si la commande existe déjà dans la base
        $query0 = "
     SELECT * 
        FROM ( 
            SELECT * 
         FROM commande 
         WHERE id_commande <> :id_commande  
        ) AS JP 
        WHERE reference_commande = :reference_commande
     ";
        $statement0 = $connect->prepare($query0);
        $statement0->execute(
            array(
                ':id_commande'       =>  $_POST["id_commande_modif"],
                ':reference_commande'      =>  $_POST["reference_commande"]
            )
        );
        $count = $statement0->rowCount();


        if($count > 0)
        {
            echo json_encode('commande existante');
        }else
        {
            if ($_POST["quantite"] <= 0){ 
                echo json_encode('quantite');
            }elseif ($_POST["tva"] < 0) {
                echo json_encode('tva');
            }elseif ($_POST["acompte"] > $_POST["ttc"]) {
                echo json_encode('acompte');
            }else {
            

            $request = "SELECT client_commande, reference_commande FROM commande WHERE id_commande = :id_commande";
            $state = $connect->prepare($request);
            $state->execute(array(':id_commande' => $_POST["id_commande_modif"]));
            $res = $state->fetchColumn();
            //if($request){echo json_encode($res);}else{echo json_encode('sdgfd');}

            if ($res != $_POST["client"]) {
                $query = "UPDATE commande SET reference_commande = :reference_commande, date_commande = :date_commande, client_commande = :client_commande, total_commande = :total_commande, produit_commande = :produit_commande, prix_de_vente_commande = :prix_de_vente_commande, quantite_commande = :quantite_commande, montant_hors_taxe_commande = :montant_hors_taxe_commande, tva_commande = :tva_commande, montant_ttc_commande = :montant_ttc_commande, mode_de_paiement_commande = :mode_de_paiement_commande, acompte_commande = :acompte_commande, date_de_livraison_commande = :date_de_livraison_commande, date_last_modif_commande = :date_last_modif_commande, user_last_modif_commande = :user_last_modif_commande
                            WHERE commande.id_commande = :id_commande";

                $statement = $connect->prepare($query);
                $correct = $statement->execute(
                array(
                    'id_commande' => $_POST["id_commande_modif"],
                    'reference_commande' => $_POST["reference_commande"],
                    'date_commande' => $_POST["date_commande"],
                    'client_commande' => $_POST["client"],
                    'total_commande' => $_POST["montant_hors_taxe"],
                    'produit_commande' => $_POST["produit"],
                    'prix_de_vente_commande' => $_POST["prix_de_vente"],
                    'quantite_commande' => $_POST["quantite"],
                    'montant_hors_taxe_commande' => $_POST["montant_hors_taxe"],
                    'tva_commande' => $_POST["tva"],
                    'montant_ttc_commande' => $_POST['ttc'],
                    'mode_de_paiement_commande' => $_POST["paiement"],
                    'acompte_commande' => $_POST["acompte"],
                    'date_de_livraison_commande' => $_POST["date_livraison"],
                    'date_last_modif_commande' => date("Y-m-d H:i:s"),
                    'user_last_modif_commande' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                )
            );

            if($correct){
                //$reponse = "Modification réussie";
                echo json_encode('Modifié');
            }else{
                //$reponse = "ERREUR";
                    echo json_encode('error');
                }

                $r = "SELECT nombre_comm_client, nom_client FROM client WHERE nom_client = :nom_client";
                $a = $connect->prepare($r);
                $a->execute(array(':nom_client' => $res));
                $n = $a->fetchColumn();
                $n -= 1;

                $r1 = "UPDATE client SET nombre_comm_client = :nombre_comm_client WHERE nom_client = :nom_client";
                $a1 = $connect->prepare($r1);
                $a1->execute(array(':nombre_comm_client' => $n, ':nom_client' => $res));

                $rr = "SELECT nombre_comm_client, nom_client FROM client WHERE nom_client = :nom_client";
                $aa = $connect->prepare($rr);
                $aa->execute(array(':nom_client' => $_POST["client"]));
                $nn = $aa->fetchColumn();
                $nn += 1;

                $r11 = "UPDATE client SET nombre_comm_client = :nombre_comm_client WHERE nom_client = :nom_client";
                $a11 = $connect->prepare($r11);
                $a11->execute(array(':nombre_comm_client' => $nn, ':nom_client' => $_POST["client"]));
                
            }else{
                
                $query = "UPDATE commande SET reference_commande = :reference_commande, date_commande = :date_commande, client_commande = :client_commande, total_commande = :total_commande, produit_commande = :produit_commande, prix_de_vente_commande = :prix_de_vente_commande, quantite_commande = :quantite_commande, montant_hors_taxe_commande = :montant_hors_taxe_commande, tva_commande = :tva_commande, montant_ttc_commande = :montant_ttc_commande, mode_de_paiement_commande = :mode_de_paiement_commande, acompte_commande = :acompte_commande, date_de_livraison_commande = :date_de_livraison_commande, date_last_modif_commande = :date_last_modif_commande, user_last_modif_commande = :user_last_modif_commande
            WHERE commande.id_commande = :id_commande";

                $statement = $connect->prepare($query);
                $correct = $statement->execute(
                array(
                    'id_commande' => $_POST["id_commande_modif"],
                    'reference_commande' => $_POST["reference_commande"],
                    'date_commande' => $_POST["date_commande"],
                    'client_commande' => $_POST["client"],
                    'total_commande' => $_POST["montant_hors_taxe"],
                    'produit_commande' => $_POST["produit"],
                    'prix_de_vente_commande' => $_POST["prix_de_vente"],
                    'quantite_commande' => $_POST["quantite"],
                    'montant_hors_taxe_commande' => $_POST["montant_hors_taxe"],
                    'tva_commande' => $_POST["tva"],
                    'montant_ttc_commande' => $_POST['ttc'],
                    'mode_de_paiement_commande' => $_POST["paiement"],
                    'acompte_commande' => $_POST["acompte"],
                    'date_de_livraison_commande' => $_POST["date_livraison"],
                    'date_last_modif_commande' => date("Y-m-d H:i:s"),
                    'user_last_modif_commande' =>  $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]
                )
            );

            if($correct){
                //$reponse = "Modification réussie";
                echo json_encode('Modifié');
            }else{
                //$reponse = "ERREUR";
                    echo json_encode('error');
                }
            }

            
          
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
         //}
 } 
}}
}




    /* Fetch Montant */
    if (isset($_POST['btn_actionn'])) {
    
    if($_POST['btn_actionn'] == 'fetch_montant') {
        $queryy = "SELECT montant_hors_taxe_commande, tva_commande, montant_ttc_commande FROM commande WHERE id_commande = :id_commande";
        $statement = $connect->prepare($queryy);
        $statement->execute(
            array(
                'id_commande'  =>  $_POST['id_location_conf']
            )
        );
        $result = $statement->fetchAll();

        $montant_ht = '';
        foreach($result as $row) {
            $montant_ht = $row['montant_hors_taxe_commande'];
            $tva = $row['tva_commande'];
            $mttc = $row['montant_ttc_commande'];
        }
        $mtva = $tva * $montant_ht / 100;
        $output = [
            'montant_ht'    =>  $montant_ht,
            'mtva' => $mtva,
            'mttc' => $mttc

        ];
        echo json_encode($output);
        
    }

}

// Nouvelle facture
if (isset($_POST['btn_action_new_facture']) && $_POST['btn_action_new_facture'] == 'New Facture') {

    // Vérifier si une facture de ce numero existe déjà
    $query = "SELECT * FROM facture
    WHERE num_facture = :num_facture";
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':num_facture'  => $_POST['num_new_facture']
        )
    );
    $count = $statement->rowCount();

    if ($count > 0) {
        echo json_encode("Une facture porte déjà ce numéro.");
     } else {
        // if (isset($_POST['select_tva_new_facture']) && ($_POST['select_tva_new_facture']== 'on')) {
    //         $tva_selected = 'Oui';
    //         $pourcentage_tva = $_POST['tva_new_facture'];
    //     } else {
    //         $tva_selected = 'Non';
    //         $pourcentage_tva = 0;
    //     }
        
    //     $methode_paiement = getMethodePaiement($_POST['methode_paiement_new_facture']);

        $r = "INSERT INTO facture (num_facture, date_facture, methode_paiement_facture, valeur_tva_facture, montant_ht_facture, montant_ttc_facture, montant_ttc_en_lettre_facture, date_create_facture, date_last_modif_facture, user_create_facture, user_last_modif_facture, id_commande_fk_facture) 
            VALUES (:num_facture, :date_facture, :methode_paiement_facture, :valeur_tva_facture, :montant_ht_facture, :montant_ttc_facture, :montant_ttc_en_lettre_facture, :date_create_facture, :date_last_modif_facture, :user_create_facture, :user_last_modif_facture, :id_commande_fk_facture)";
        $s = $connect->prepare($r);
        $s->execute(array(

        'num_facture' => $_POST['num_new_facture'],
        'date_facture' => $_POST['date_new_facture'],
        'methode_paiement_facture' => $_POST['methode_paiement_new_facture'],
        'valeur_tva_facture' => $_POST['valeur_tva_new_facture'],
        'montant_ht_facture' => $_POST['montant_ht_new_facture'],
        'montant_ttc_facture' =>  $_POST['montant_ttc_new_facture'],
        'montant_ttc_en_lettre_facture' => $_POST['prix_en_lettres_new_facture'],
        'date_create_facture' => date("Y-m-d H:i:s"),
        'date_last_modif_facture' => date("Y-m-d H:i:s"),
        'user_create_facture' => $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
        'user_last_modif_facture' => $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
        'id_commande_fk_facture' => $_POST['id_location_conf_new_facture']
        ));

        $req = "UPDATE commande SET date_last_modif_commande = :date_last_modif_commande, user_last_modif_commande = :user_last_modif_commande, statut_commande = :statut_commande WHERE id_commande = :id_commande";
        $ans = $connect->prepare($req);
        $ans->execute(array(
             'id_commande' => $_POST['id_location_conf_new_facture'],
        'date_last_modif_commande' => date("Y-m-d H:i:s"),
        'user_last_modif_commande' => $_SESSION["prenom_user"]." ".$_SESSION["nom_user"],
        'statut_commande' => 'Oui'
        ));

       echo json_encode('La facture a été éditée avec succes.');
        
        // switch ($_SESSION['type_user']) {

        //     case 1:
        //         addlog("FacEdit-01-location-conf", $_POST['id_location_conf_new_facture'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 2:
        //         addlog("FacEdit-02-location-conf", $_POST['id_location_conf_new_facture'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 3:
        //         addlog("FacEdit-03-location-conf", $_POST['id_location_conf_new_facture'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 4:
        //         addlog("FacEdit-04-location-conf", $_POST['id_location_conf_new_facture'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        //     case 5:
        //         addlog("FacEdit-05-location-conf", $_POST['id_location_conf_new_facture'], $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        //         break;
        // }
    }
}

?>