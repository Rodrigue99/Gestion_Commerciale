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



if(isset($_POST['btn_export_client'])) {

    if($_POST['export_client'] == 'pdf') {

        $query = "
        SELECT * 
        FROM client WHERE deleted_client = 0
        ";
        
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        
        $date = gmdate("d-m-Y");
        $hour = gmdate("H:i");
        
        $hour2 = gmdate("H-i");
        
        $output = '
        <div class="table-responsive" style="font-size: 16px !important;">
        <b>Liste des clients à la date du '.$date.' à '.$hour.'</b>
            <table border="1" style="border-collapse:collapse;" >
                <tr bgcolor="#c6efce">
                
                <th>N°</th>
                <th>Nom du client</th>
                <th>DG</th>
                <th>Ville</th>
                <th>Téléphone</th>
                <th>Nombre de commande</th>
                <th>Statut</th>
        
                </tr>
        ';
        foreach($result as $row)
        {
            $status = '';
            if($row['statut_client'] == 'Actif')
            {
                $status = '<center><span class="badge badge-pill badge-success"> Actif </span></center>';
            }
            else
            {
                $status = '<center><span class="badge badge-pill badge-danger"> Inactif </span></center>';
            }
            
        $output .= '
                <tr>
                <td>'.$row["id_client"].'</td>
                <td>'.$row["nom_client"].'</td>
                <td>'.$row["dg_client"].'</td>
                <td>'.$row["ville_client"].'</td>
                <td>'.$row["tel_client"].'</td>
                <td>'.$row["nombre_comm_client"].'</td>
                <td>'.$status.'</td>
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
            $file_name = 'Liste des clients_'.$date.'_'.$hour2.'.pdf';
        //}

        $pdf->loadHtml($output);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        $pdf->stream($file_name, array("Attachment" => false));
        
        // Log
        switch ($_SESSION['type_user']) {
        
            case "Super Administrateur":
                addlog("Exp-01-client", "PDF", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Exp-02-client", "PDF", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }
    }


    if($_POST['export_client'] == 'word') {

        $query = "
        SELECT * 
        FROM client WHERE deleted_client = 0
        ";
        
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        
        $date = gmdate("d-m-Y");
        $hour = gmdate("H:i");
        
        $hour2 = gmdate("H-i");

		$output = '
		    <b>Liste des clients à la date du '.$date.' à '.$hour.'</b>
			<table style="width: 100%; border: 1px #000000 solid;">
			    <tr style="background-color:#c6efce; font-size: 15px; font-weight:bold; text-align: center; height: 20px ">
			    
                    <th>N°</th>
                    <th>Nom du client</th>
                    <th>DG</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th>Nombre de commande</th>
                    <th>Statut</th>
            
			    </tr>
		';
		foreach($result as $row)
		{
		    
            $status = '';
            if($row['statut_client'] == 'Actif')
            {
                $status = '<center><span class="badge badge-pill badge-success"> Actif </span></center>';
            }
            else
            {
                $status = '<center><span class="badge badge-pill badge-danger"> Inactif </span></center>';
            }
		    
			
			$output .= '
			
			<tr style="font-size: 15px; height:30px;">
                <td>'.$row["id_client"].'</td>
                <td>'.$row["nom_client"].'</td>
                <td>'.$row["dg_client"].'</td>
                <td>'.$row["ville_client"].'</td>
                <td>'.$row["tel_client"].'</td>
                <td>'.$row["nombre_comm_client"].'</td>
                <td>'.$status.'</td>
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
            $file_name = 'Liste des clients_'.$date.'_'.$hour2.'.docx';
        //}
        header("Content-type: application/vnd.ms-word");  
        header('Content-Disposition: attachment; filename='. $file_name);
        
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        
        // Log
        switch ($_SESSION['type_user']) {
        
            case "Super Administrateur":
                addlog("Exp-01-client", "Word", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Exp-02-client", "Word", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }


    }


    if($_POST['export_client'] == 'excel') {

        $query = "
        SELECT * 
        FROM client 
       WHERE deleted_client=0
        ";
        
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        
        $date = gmdate("d-m-Y");
        $hour = gmdate("H:i");
        
        $hour2 = gmdate("H-i");
        
       

        $output = '
        <div class="table-responsive">
        <b>Liste des clients à la date du '.$date.' à '.$hour.'</b>
            <table class="table table-boredered">
                <tr bgcolor="#c6efce">

                    <th>N°</th>
                    <th>Nom du client</th>
                    <th>DG</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th>Nombre de commande</th>
                    <th>Statut</th>
            
                </tr>
        ';
        foreach($result as $row)
        {
            
            $status = '';
            if($row['statut_client'] == 'Actif')
            {
                $status = '<center><span class="badge badge-pill badge-success"> Actif </span></center>';
            }
            else
            {
                $status = '<center><span class="badge badge-pill badge-danger"> Inactif </span></center>';
            }
            
        $output .= '
            
            <tr>
            <td>'.$row["id_client"].'</td>
            <td>'.$row["nom_client"].'</td>
            <td>'.$row["dg_client"].'</td>
            <td>'.$row["ville_client"].'</td>
            <td>'.$row["tel_client"].'</td>
            <td>'.$row["nombre_comm_client"].'</td>
            <td>'.$status.'</td>        
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
            $file_name = 'Liste des clients_'.$date.'_'.$hour2.'.xls';
        //}

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='. $file_name);
        echo "\xEF\xBB\xBF";
        echo $output;

        // Log
        switch ($_SESSION['type_user']) {
        
            case "Super Administrateur":
                addlog("Exp-01-client", "Excel", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
            case "Administrateur":
                addlog("Exp-02-client", "Excel", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
                break;
        }

    }    
}
