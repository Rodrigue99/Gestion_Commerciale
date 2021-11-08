<?php

include('../database_connection.php');
include('../AddLogInclude.php');

require_once '../scripts_php/pdf.php';
require_once '../vendors/autoload.php';
// Langues
include('../lang/fr-lang.php');
include('../lang/en-lang.php');

// if($_SESSION['type_compte'] != 1 && $_SESSION['type_compte'] != 2)
// {
// 	header("location:../pages/tableau-de-bord-admin.php");
// }

function formatProduit($str, $nom_salle, $debut, $fin) {
  $date_debut = date("d-m-Y", strtotime($debut));
  $heure_debut = date("H:i", strtotime($debut));
  $date_fin = date("d-m-Y", strtotime($fin));
  $heure_fin = date("H:i", strtotime($fin));

  return sprintf($str, $nom_salle, $date_debut, $heure_debut, $date_fin, $heure_fin);
}


// if ($_SESSION['lang'] == 'EN') {
//   $label_num_facture = LABEL_NUM_FACTURE_EN;
//   $label_client = LABEL_CLIENT_FACTURE_EN;
//   $label_date = LABEL_DATE_FACTURE_EN;
//   $label_paiement = LABEL_PAIEMENT_FACTURE_EN;
//   $label_titre_facture = LABEL_TITRE_FACTURE_EN;
//   $label_produit = LABEL_PRODUIT_FACTURE_EN;
//   $label_prix = LABEL_PRIX_FACTURE_EN;
//   $label_qte = LABEL_QTE_FACTURE_EN;
//   $label_total = LABEL_TOTAL_FACTURE_EN;
//   $label_prix_ht = LABEL_PRIX_HT_FACTURE_EN;
//   $label_tva = LABEL_TVA_FACTURE_EN;
//   $label_prix_ttc = LABEL_PRIX_TTC_FACTURE_EN;
//   $label_prix_ttc_en_lettre = LABEL_PRIX_TTC_LETTRE_FACTURE_EN;
//     $str_produit = MODEL_PRODUIT_FACTURE_EN;
    // $nom_type_chambre = NOM_TYPE_CHAMBRE_EN;
    // $desc_type_chambre =  DESC_TYPE_CHAMBRE_EN;
    // $statut =  STATUT_EN;
    // $statut_actif = STATUT_ACTIF_EN;
    // $statut_inactif = STATUT_INACTIF_EN;
    // $titre = 'List of room types on '.$date.' at '.$hour.' (GMT)';
// } else {
  $label_num_facture = LABEL_NUM_FACTURE_FR;
  $label_client = LABEL_CLIENT_FACTURE_FR;
  $label_date = LABEL_DATE_FACTURE_FR;
  $label_paiement = LABEL_PAIEMENT_FACTURE_FR;
  $label_titre_facture = LABEL_TITRE_FACTURE_FR;
  $label_produit = LABEL_PRODUIT_FACTURE_FR;
  $label_prix = LABEL_PRIX_FACTURE_FR;
  $label_qte = LABEL_QTE_FACTURE_FR;
  $label_total = LABEL_TOTAL_FACTURE_FR;
  $label_prix_ht = LABEL_PRIX_HT_FACTURE_FR;
  $label_tva = LABEL_TVA_FACTURE_FR;
  $label_prix_ttc = LABEL_PRIX_TTC_FACTURE_FR;
  $label_prix_ttc_en_lettre = LABEL_PRIX_TTC_LETTRE_FACTURE_FR;
  $str_produit = MODEL_PRODUIT_FACTURE_FR;
    // $nom_type_chambre = NOM_TYPE_CHAMBRE_FR;
    // $desc_type_chambre =  DESC_TYPE_CHAMBRE_FR;
    // $statut =  STATUT_FR;
    // $statut_actif = STATUT_ACTIF_FR;
    // $statut_inactif = STATUT_INACTIF_FR;
    // $titre = 'Liste des types de chambre en date du '.$date.' à '.$hour.' (Heure GMT)';
// }

// var_dump($_POST);
// exit;

if (isset($_POST['id_location_conf'])){
  $id_facture_conf = '';
  //$id_facture_conf = $_POST['id_facture'];

  // $query = "SELECT id_facture FROM facture
  // WHERE id_commande_fk_facture = :id_location_conf
  // ";        
  // $statement = $connect->prepare($query);
  // $statement->execute(
  //   array(
  //     ':id_location_conf'  =>  $_POST['id_location_conf']
  //   )
  // );
  // $result = $statement->fetchAll();
  // $id_facture_conf = '';
  // foreach($result as $row) {
  //   $id_facture_conf = $row['id_facture'];
  // }

} //else {
  
//}

        $query = "SELECT * FROM commande, facture, produit, client
        WHERE commande.client_commande = client.nom_client
        AND commande.produit_commande = produit.nom_produit
        AND facture.id_commande_fk_facture = commande.id_commande
        AND id_facture = :id_facture
        ";        
        $statement = $connect->prepare($query);
        $statement->execute(
          array(
            ':id_facture'  =>  $_POST['id_location_conf']
          )
        );
        $result = $statement->fetchAll();

        foreach($result as $row) {
          $infos_client = $row['nom_client'].'<br>' .
            $row['tel_client'];

          $num_facture = $row['num_facture'];
          $date = $row['date_facture'];
          $methode_paiement = $row['methode_paiement_facture'];
          $montant_ht = $row['montant_ht_facture'];
          $montant_ttc = $row['montant_ttc_facture'];
          $montant_ttc_en_lettre = $row['montant_ttc_en_lettre_facture'];
          $tva = $row['tva_commande'];
          $produit = $row["produit_commande"];
          $quantite = $row["quantite_commande"];
          $prix = $row["prix_de_vente_commande"];
          //$tva = (double) $row['valeur_tva_facture'] * (double) $montant_ht / 100;

         // $produit = formatProduit($str_produit, $row['nom_produit'], $row['date_debut_location_conf'], $row['date_fin_location_conf']);
        }
        
        $date = gmdate("d-m-Y");
        $hour = gmdate("H:i");
        
        $hour2 = gmdate("H-i");
        

        $nom_societe = 'Gestion Commerciale';
        // // $num_facture = '3304';
        // // $infos_client = 
        // //   'Ujang Maman<br>
        // //   1234 Main<br>
        // //   Apt. 4B<br>
        // //   Bogor Barat, Indonesia';
        // // $date = 'September 19, 2018';
        // // $methode_paiement = 'VISA';

        
        $output = '
        <style>
        th, td {
            padding: 5px 15px;
        }
        .section-title {
            margin-left: 30px;
            margin-top:30px;
            font-size: 18px;
            color: green;
            font-weight: bold;
        }
        .right{
          display: flex;
          text-align: right;
        }
        </style>
        <div class="section-body">
        <div class="invoice">
          <div class="invoice-print">
            <div class="row">
              <div class="col-lg-12">
                <div class="invoice-title" style="display:flex">
                  <h2>' .$nom_societe. '</h2>
                  <div class="invoice-number" style="text-align:right">' .$label_num_facture . $num_facture .'</div>
                </div>
                <hr>
                <div class="row" style="display:flex;">
                  <div class="col-md-6" >
                    <address>
                      <strong>' .$label_client. '</strong><br>
                      '.$infos_client .'
                    </address>
                  </div>
                  <div class="col-md-6 text-md-right" style="text-align:right">
                    <address>
                      <strong>' .$label_date. '</strong><br>
                      '.$date.'<br><br>
                    </address>
              </div>
                </div>
                <div class="row" style="display:flex">
                  <div class="col-md-6">
                    <address>
                      <strong>.' .$label_paiement.'  </strong><br>
                       '.$methode_paiement .'
                    </address>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row mt-4">
              <div class="col-md-12">
                <div class="section-title">' .$label_titre_facture.' </div>
                <div class="table-responsive">
                  <table class="table table-striped table-hover table-md">
                    <tr>
                      <th data-width="40">#</th>
                      <th>Article</th>
                      <th class="text-center">  '.$label_prix.' </th>
                      <th class="text-center"> '.$label_qte .'</th>
                      <th class="text-right"> '.$label_total.' </th>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td> .$produit.</td>
                      <td class="text-center"> '.$prix.'</td>
                      <td class="text-center">'.$quantite.'</td>
                      <td class="text-right"> '.$montant_ht.'</td>
                    </tr>
                  </table>
                </div>
                <div class="row mt-4">
                  <div class="col-lg-4 text-right" >
                    <div class="invoice-detail-item">
                      <div class="invoice-detail-name right"><em><b> '.$label_prix_ht.' </b></em></div>
                      <div class="invoice-detail-value right">' .$montant_ht.'</div>
                    </div>
                    <div class="invoice-detail-item">
                      <div class="invoice-detail-name right"><em><b>'.$label_tva .'</b></em></div>
                      <div class="invoice-detail-value right">' .$tva.' %</div>
                    </di
                    <hr class="mt-2 mb-2">
                    <div class="invoice-detail-item">
                      <div class="invoice-detail-name right"><strong>'. $label_prix_ttc.' </strong></div>
                      <div class="invoice-detail-value invoice-detail-value-lg right"> '.$montant_ttc.' </div>
                    </div>
                    <div class="invoice-detail-item">
                      <div class="invoice-detail-name right"><strong>' .$label_prix_ttc_en_lettre.' </strong></div>
                      <div class="invoice-detail-value invoice-detail-value-lg right"> '.$montant_ttc_en_lettre.'</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>

          </div>
      </div>

      </body>
';

        $pdf = new Pdf();
        $file_name = 'Facture_'.$date.'_'.$hour2.'.pdf';

        // if ($_SESSION['lang'] == 'EN') {
        //     $file_name = 'List of room types_'.$date.'_'.$hour2.'.pdf';
        // } else {
        //     $file_name = 'Liste des types de chambre_'.$date.'_'.$hour2.'.pdf';
        // }

        $pdf->loadHtml($output);
        $pdf->setPaper('A4', '');
        $pdf->render();
        $pdf->stream($file_name, array("Attachment" => false));
        
        // Log
        // switch ($_SESSION['type_compte']) {
        
        //     case 1:
        //         addlog("Exp-01-type-chambre", "PDF", $_SESSION["prenom_personne"]." ".$_SESSION["nom_personne"]);
        //         break;
        //     case 2:
        //         addlog("Exp-02-type-chambre", "PDF", $_SESSION["prenom_personne"]." ".$_SESSION["nom_personne"]);
        //         break;
        // }
