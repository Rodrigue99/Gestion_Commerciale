<?php

include('../database_connection.php');
include('../AddLogInclude.php');
require_once '../scripts_php/pdf.php';
require_once '../vendors/autoload.php';
// Langues
include('../lang/fr-lang.php');
include('../lang/en-lang.php');

if (isset($_SESSION['type_user']) && !in_array($_SESSION['type_user'], array('Super Administrateur', 'Administrateur'))) {
    header('location:../tb/tb_admin.php');
}


if(isset($_POST['btn_export_commande'])) {

    if($_POST['export_commande'] == 'pdf') {

        $query = "
        SELECT * 
        FROM commande WHERE deleted = 0
        ";
        
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        
        $date = gmdate("d-m-Y");
        $hour = gmdate("H:i");
        
        $hour2 = gmdate("H-i");
        
        $output = '
        <div class="table-responsive" style="font-size: 16px !important; text-align: center;">
        <b>Liste des commandes à la date du '.$date.' à '.$hour.'</b>
            <table border="1" style="border-collapse:collapse; width: 800px; margin: auto; text-align: center; margin-top: 20px;" >
                <tr bgcolor="#c6efce">
                
                <th>N°</th>
                <th>Référence</th>
                <th>Date</th>
                <th>Client</th>
                <th>Total</th>
                <th>Acompte</th>
                <th>Facture</th>
                </tr>
        ';
        foreach($result as $row)
        {
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
                <td>'.$row["id_commande"].'</td>
                <td>'.$row["reference_commande"].'</td>
                <td>'.$row["date_commande"].'</td>
                <td>'.$row["client_commande"].'</td>
                <td>'.$row["total_commande"].'</td>
                <td>'.$row["acompte_commande"].'</td>
                <td>'.$row["statut_commande"].'</td>
                </tr>
        ';
        
        }
        $output .= '
            </table>
        </div>
        ';
        
        $pdf = new Pdf();
        // if ($_SESSION['lang'] == 'EN') {
        //     $file_name = 'List of rooms_'.$date.'_'.$hour2.'.pdf';
        // } else {
            $file_name = 'Liste des commandes_'.$date.'_'.$hour2.'.pdf';
        //}

        $pdf->loadHtml($output);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        $pdf->stream($file_name, array("Attachment" => false));
        
        // Log
        switch ($_SESSION['type_user']) {
        
            case 1:
                addlog("Exp-01-chambre", "PDF", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case 2:
                addlog("Exp-02-chambre", "PDF", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }
    }


    if($_POST['export_commande'] == 'word') {

        $query = "
        SELECT * 
        FROM commande WHERE deleted = 0
        ";
        
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        
        $date = gmdate("d-m-Y");
        $hour = gmdate("H:i");
        
        $hour2 = gmdate("H-i");

		$output = '
		    <b>Liste des commandes à la date du '.$date.' à '.$hour.'</b>
			<table style="width: 100%; border: 1px #000000 solid; width: 800px; margin: auto; text-align: center; margin-top: 20px;">
			    <tr style="background-color:#c6efce; font-size: 15px; font-weight:bold; text-align: center; height: 20px ">
			    
                <th>N°</th>
                <th>Référence</th>
                <th>Date</th>
                <th>Client</th>
                <th>Total</th>
                <th>acompte</th>
                <th>Facture</th>
            
			    </tr>
		';
		foreach($result as $row)
		{
		    
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
			
			<tr style="font-size: 15px; height:30px;">
            <td>'.$row["id_commande"].'</td>
            <td>'.$row["reference_commande"].'</td>
            <td>'.$row["date_commande"].'</td>
            <td>'.$row["client_commande"].'</td>
            <td>'.$row["total_commande"].'</td>
            <td>'.$row["acompte_commande"].'</td>
               <td>'.$row["statut_commande"].'</td>
           
			</tr>	
			';

		}
		$output .= '
			</table>
		
		';

	    // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        /* Note: any element you append to a document must reside inside of a Section. */
        
        // Adding an empty Section to the document...
        $section = $phpWord->addSection(
            
            array('marginLeft' => 600, 'marginRight' => 600,
            'marginTop' => 300, 'marginBottom' => 600)
            
            );
        
        $sectionStyle = $section->getStyle();
        $sectionStyle->setOrientation($sectionStyle::ORIENTATION_LANDSCAPE);
        
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $output);
        
        // if ($_SESSION['lang'] == 'EN') {
        //     $file_name = 'List of rooms_'.$date.'_'.$hour2.'.docx';
        // } else {
            $file_name = 'Liste des commandes_'.$date.'_'.$hour2.'.docx';
        //}
        header("Content-type: application/vnd.ms-word");  
        header('Content-Disposition: attachment; filename='. $file_name);
        
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        
        // Log
        switch ($_SESSION['type_user']) {
        
            case 1:
                addlog("Exp-01-chambre", "Word", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case 2:
                addlog("Exp-02-chambre", "Word", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }


    }


    if($_POST['export_commande'] == 'excel') {

        $query = "
        SELECT * 
        FROM commande 
       WHERE deleted = 0
        ";
        
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        
        $date = gmdate("d-m-Y");
        $hour = gmdate("H:i");
        
        $hour2 = gmdate("H-i");
        
       

        $output = '
        <div class="table-responsive">
        <b>Liste des commandes à la date du '.$date.' à '.$hour.'</b>
            <table class="table table-boredered">
                <tr bgcolor="#c6efce">

                <th>N°</th>
                <th>Référence</th>
                <th>Date</th>
                <th>Client</th>
                <th>Total</th>
                <th>acompte</th>
                <th>Facture</th>
            
                </tr>
        ';
        foreach($result as $row)
        {
            
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
            <td>'.$row["id_commande"].'</td>
            <td>'.$row["reference_commande"].'</td>
            <td>'.$row["date_commande"].'</td>
            <td>'.$row["client_commande"].'</td>
            <td>'.$row["total_commande"].'</td>
            <td>'.$row["acompte_commande"].'</td>
               <td>'.$row["statut_commande"].'</td>
                
            </tr>	
                
        ';
            
                    
        }
        $output .= '
            </table>
        </div>
        ';
        
        // if ($_SESSION['lang'] == 'EN') {
        //     $file_name = 'List of rooms_'.$date.'_'.$hour2.'.xls';
        // } else {
            $file_name = 'Liste des commandes_'.$date.'_'.$hour2.'.xls';
        //}

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='. $file_name);
        echo "\xEF\xBB\xBF";
        echo $output;

        // Log
        switch ($_SESSION['type_user']) {
        
            case 1:
                addlog("Exp-01-chambre", "Excel", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case 2:
                addlog("Exp-02-chambre", "Excel", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }

    }    
}
