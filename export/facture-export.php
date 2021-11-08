<?php
include('../database_connection.php');
include('../AddLogInclude.php');
require_once '../pdf.php';
require_once '../vendor/autoload.php';
// Langues
include('../lang/fr-lang.php');
include('../lang/en-lang.php');

if($_SESSION['type_compte'] != 1 && $_SESSION['type_compte'] != 2)
{
	header("location:../pages/tableau-de-bord-admin.php");
}

// formatter les dates
if ($_SESSION['lang'] == 'EN') {
    $MONTHS_SHORT = MONTHS_SHORT_EN;
} else {
    $MONTHS_SHORT = MONTHS_SHORT_FR;
}
function formatDatetime($datetime, $lang) {
    $datetime = strtotime($datetime);
    $day = date('d', $datetime);
    $month = date('m', $datetime);
    $year = date('Y', $datetime);
    $hour_12 = date('h', $datetime);
    $hour_24 = date('H', $datetime);
    $min = date('i', $datetime);
    // $sec = date('s', $datetime);
    global $MONTHS_SHORT;

    $format_fr = '%s %s %s à %sh%s';
    $format_en = '%s %s %s at %s:%s %s';
    if ($lang == 'FR') {
        return sprintf($format_fr, $day, $MONTHS_SHORT[(integer) $month], $year, $hour_24, $min);
    } else if ($lang == 'EN') {
        $suffix = 'AM';
        if ((integer) $hour_24 >= 12) {
            $suffix = 'PM';
        }
        return sprintf($format_en, $MONTHS_SHORT[(integer) $month], $day, $year, $hour_12, $min, $suffix);
    }
    return '';
}


if(isset($_POST['btn_export_facture_conf'])) {
    // code commun à tous les types de fichier
    $query = "SELECT * FROM facture_conf, location_conf, client, personne
    WHERE facture_conf.id_location_conf_fk_facture_conf = location_conf.id_location_conf
    AND location_conf.id_client_fk_location_conf = client.id_client
    AND client.id_personne_fk_client = personne.id_personne
    ORDER BY date_facture_conf DESC
    ";
    
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    
    $date = gmdate("d-m-Y");
    $hour = gmdate("H:i");
    $hour2 = gmdate("H-i");

    if ($_SESSION['lang'] == 'EN') { 
        $label_num_facture = NUMERO_FACTURE_CONF_EN;
        $label_date = DATE_FACTURE_CONF_EN;
        $label_client = CLIENT_EN;
        $label_montant_ttc = MTTC_FACTURE_CONF_EN;
        $label_statut = STATUT_EN;
        $actif = STATUT_ACTIF_EN;
        $annule = ANNULE_EN;
        $titre = 'List of invoices of conference rooms rentals on '.$date.' at '.$hour.' (GMT)';
        $nom_fichier = 'List of invoices of conference rooms rentals_'.$date.'_'.$hour2;
    } else {
        $label_num_facture = NUMERO_FACTURE_CONF_FR;
        $label_date = DATE_FACTURE_CONF_FR;
        $label_client = CLIENT_FR;
        $label_montant_ttc = MTTC_FACTURE_CONF_FR;
        $label_statut = STATUT_FR;
        $actif = STATUT_ACTIF_FR;
        $annule = ANNULE_FR;
        $titre = 'Liste des factures des locations de salles de conférence en date du '.$date.' à '.$hour.' (Heure GMT)';
        $nom_fichier = 'Liste des factures des locations de salles de conférence_'.$date.'_'.$hour2;
    }




    // PDF
    if($_POST['export_facture_conf'] == 'pdf') {
        
        $output = '
        <div class="table-responsive" style="font-size: 16px !important;">
            <h1>'. $titre .'</h1>
            <table border="1" style="border-collapse:collapse;" >
                <tr bgcolor="#c6efce">
                
                    <th>'. $label_num_facture .'</th>
                    <th>'. $label_date .'</th>
                    <th>'. $label_client .'</th>
                    <th>'. $label_montant_ttc .'</th>
                    <th>'. $label_statut .'</th>
    
                </tr>
        ';
    
        foreach($result as $row) {
            $num_facture = $row['num_facture_conf'];
            $date_facture = formatDatetime($row['date_facture_conf'], $_SESSION['lang']);
            $client = $row['nom_personne']. ' ' .$row['prenom_personne'];
            $montant_ttc = $row['montant_ttc_facture_conf'];
        
            if($row['statut_facture_conf'] == 'Actif') {
                $statut = '<center><span class="badge badge-primary">'. $actif .'</span></center>';
            } else {
                $statut = '<center><span class="badge badge-warning">' . $annule . '</span></center>';
            }

            $output .= '
                <tr>
                    <td>'. $num_facture .'</td>
                    <td>'. $date_facture .'</td>
                    <td>'. $client .'</td>
                    <td>'. $montant_ttc .'</td>
                    <td>'. $statut .'</td>
                </tr>
        ';
        }

        $output .= '
            </table>
        </div>
        ';

        $pdf = new Pdf();
        $file_name = $nom_fichier . '.pdf';

        $pdf->loadHtml($output);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        $pdf->stream($file_name, array("Attachment" => false));
        
        // Log
        switch ($_SESSION['type_compte']) {
            case 1:
                addlog("Exp-01-facture-conf", "PDF", $_SESSION["prenom_personne"]." ".$_SESSION["nom_personne"]);
                break;
            case 2:
                addlog("Exp-02-facture-conf", "PDF", $_SESSION["prenom_personne"]." ".$_SESSION["nom_personne"]);
                break;
        }
    }

    if($_POST['export_facture_conf'] == 'word') {

		$output = '
		    <b>'. $titre .'</b>
			<table style="width: 100%; border: 1px #000000 solid;">
			    <tr style="background-color:#c6efce; font-size: 15px; font-weight:bold; text-align: center; height: 20px ">
			    
                <th>'. $label_num_facture .'</th>
                <th>'. $label_date .'</th>
                <th>'. $label_client .'</th>
                <th>'. $label_montant_ttc .'</th>
                <th>'. $label_statut .'</th>

			    </tr>
		';

        foreach($result as $row) {
            $num_facture = $row['num_facture_conf'];
            $date_facture = formatDatetime($row['date_facture_conf'], $_SESSION['lang']);
            $client = $row['nom_personne']. ' ' .$row['prenom_personne'];
            $montant_ttc = $row['montant_ttc_facture_conf'];
        
            if($row['statut_facture_conf'] == 'Actif') {
                $statut = '<center><span class="badge badge-primary">'. $actif .'</span></center>';
            } else {
                $statut = '<center><span class="badge badge-warning">' . $annule . '</span></center>';
            }

            $output .= '
    			<tr style="font-size: 15px; height:30px;">
                <td>'. $num_facture .'</td>
                <td>'. $date_facture .'</td>
                <td>'. $client .'</td>
                <td>'. $montant_ttc .'</td>
                <td>'. $statut .'</td>
            </tr>
        ';
        }

        $output .= '
            </table>
        ';

	    // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $file_name = $nom_fichier .'.docx';

        /* Note: any element you append to a document must reside inside of a Section. */        
        // Adding an empty Section to the document...
        $section = $phpWord->addSection(            
            array('marginLeft' => 600, 'marginRight' => 600,
            'marginTop' => 300, 'marginBottom' => 600)
        );
        $sectionStyle = $section->getStyle();
        $sectionStyle->setOrientation($sectionStyle::ORIENTATION_LANDSCAPE);
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $output);
        header("Content-type: application/vnd.ms-word");  
        header('Content-Disposition: attachment; filename='. $file_name);        
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        
        // Log
        switch ($_SESSION['type_compte']) {        
            case 1:
                addlog("Exp-01-facture-conf", "Word", $_SESSION["prenom_personne"]." ".$_SESSION["nom_personne"]);
                break;
            case 2:
                addlog("Exp-02-facture-conf", "Word", $_SESSION["prenom_personne"]." ".$_SESSION["nom_personne"]);
                break;
        }
    }


    if($_POST['export_facture_conf'] == 'excel') {

        $output = '
        <div class="table-responsive">
            <b>'. $titre .'</b>
            <table class="table table-boredered">
                <tr bgcolor="#c6efce">

                <th>'. $label_num_facture .'</th>
                <th>'. $label_date .'</th>
                <th>'. $label_client .'</th>
                <th>'. $label_montant_ttc .'</th>
                <th>'. $label_statut .'</th>

                </tr>
        ';

        foreach($result as $row) {
            $num_facture = $row['num_facture_conf'];
            $date_facture = formatDatetime($row['date_facture_conf'], $_SESSION['lang']);
            $client = $row['nom_personne']. ' ' .$row['prenom_personne'];
            $montant_ttc = $row['montant_ttc_facture_conf'];
        
            if($row['statut_facture_conf'] == 'Actif') {
                $statut = '<center><span class="badge badge-primary">'. $actif .'</span></center>';
            } else {
                $statut = '<center><span class="badge badge-warning">' . $annule . '</span></center>';
            }


            $output .= '
            <tr>
            <td>'. $num_facture .'</td>
            <td>'. $date_facture .'</td>
            <td>'. $client .'</td>
            <td>'. $montant_ttc .'</td>
            <td>'. $statut .'</td>
        </tr>            
            ';
        }

        $output .= '
            </table>
        </div>
        ';
        
        $file_name = $nom_fichier .'.xls';

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='. $file_name);
        echo "\xEF\xBB\xBF";
        echo $output;

        // Log
        switch ($_SESSION['type_compte']) {
        
            case 1:
                addlog("Exp-01-facture-conf", "Excel", $_SESSION["prenom_personne"]." ".$_SESSION["nom_personne"]);
                break;
            case 2:
                addlog("Exp-02-facture-conf", "Excel", $_SESSION["prenom_personne"]." ".$_SESSION["nom_personne"]);
                break;
        }

    }    
}
