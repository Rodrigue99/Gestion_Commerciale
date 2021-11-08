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



if(isset($_POST['btn_export_produit'])) {

    if($_POST['export_produit'] == 'pdf') {

        $query = "
        SELECT * 
        FROM produit WHERE deleted = 0
        ";
        
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        
        $date = gmdate("d-m-Y");
        $hour = gmdate("H:i");
        
        $hour2 = gmdate("H-i");
        
        $output = '
        <div class="table-responsive" style="font-size: 16px !important;">
        <b>Liste des produits à la date du '.$date.' à '.$hour.'</b>
            <table border="1" style="border-collapse:collapse;" >
                <tr bgcolor="#c6efce">
                
                <th>N°</th>
                <th>Code barre</th>
                <th>Référence</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Coût de revient</th>
                <th>Prix de vente</th>
                <th>Statut</th>
        
                </tr>
        ';
        foreach($result as $row)
        {
            $status = '';
            if($row['statut_produit'] == 'Actif')
            {
                $status = '<center><span class="badge badge-pill badge-success"> Actif </span></center>';
            }
            else
            {
                $status = '<center><span class="badge badge-pill badge-danger"> Inactif </span></center>';
            }
            
        $output .= '
                <tr>
                <td>'.$row["id_produit"].'</td>
                <td>'.$row["code_barre_produit"].'</td>
                <td>'.$row["reference_produit"].'</td>
                <td>'.$row["nom_produit"].'</td>
                <td>'.$row["categorie_produit"].'</td>
                <td>'.$row["cout_de_revient_produit"].'</td>
                <th>'.$row["prix_de_vente_produit"].'</th>
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
            $file_name = 'Liste des produits_'.$date.'_'.$hour2.'.pdf';
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


    if($_POST['export_produit'] == 'word') {

        $query = "
        SELECT * 
        FROM produit WHERE deleted = 0
        ";
        
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        
        $date = gmdate("d-m-Y");
        $hour = gmdate("H:i");
        
        $hour2 = gmdate("H-i");

		$output = '
		    <b>Liste des produits à la date du '.$date.' à '.$hour.'</b>
			<table style="width: 100%; border: 1px #000000 solid;">
			    <tr style="background-color:#c6efce; font-size: 15px; font-weight:bold; text-align: center; height: 20px ">
			    
                <th>N°</th>
                <th>Code barre</th>
                <th>Référence</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Coût de revient</th>
                <th>Prix de vente</th>
                <th>Statut</th>
            
			    </tr>
		';
		foreach($result as $row)
		{
		    
            $status = '';
            if($row['statut_produit'] == 'Actif')
            {
                $status = '<center><span class="badge badge-pill badge-success"> Actif </span></center>';
            }
            else
            {
                $status = '<center><span class="badge badge-pill badge-danger"> Inactif </span></center>';
            }
		    
			
			$output .= '
			
			<tr style="font-size: 15px; height:30px;">
            <td>'.$row["id_produit"].'</td>
            <td>'.$row["code_barre_produit"].'</td>
            <td>'.$row["reference_produit"].'</td>
            <td>'.$row["nom_produit"].'</td>
            <td>'.$row["categorie_produit"].'</td>
            <td>'.$row["cout_de_revient_produit"].'</td>
            <th>'.$row["prix_de_vente_produit"].'</th>
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
            $file_name = 'Liste des produits_'.$date.'_'.$hour2.'.docx';
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


    if($_POST['export_produit'] == 'excel') {

        $query = "
        SELECT * 
        FROM produit 
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
        <b>Liste des produits à la date du '.$date.' à '.$hour.'</b>
            <table class="table table-boredered">
                <tr bgcolor="#c6efce">

                <th>N°</th>
                <th>Code barre</th>
                <th>Référence</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Coût de revient</th>
                <th>Prix de vente</th>
                <th>Statut</th>
            
                </tr>
        ';
        foreach($result as $row)
        {
            
            $status = '';
            if($row['statut_produit'] == 'Actif')
            {
                $status = '<center><span class="badge badge-pill badge-success"> Actif </span></center>';
            }
            else
            {
                $status = '<center><span class="badge badge-pill badge-danger"> Inactif </span></center>';
            }
            
        $output .= '
            
            <tr>
            <td>'.$row["id_produit"].'</td>
                <td>'.$row["code_barre_produit"].'</td>
                <td>'.$row["reference_produit"].'</td>
                <td>'.$row["nom_produit"].'</td>
                <td>'.$row["categorie_produit"].'</td>
                <td>'.$row["cout_de_revient_produit"].'</td>
                <th>'.$row["prix_de_vente_produit"].'</th>
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
            $file_name = 'Liste des produits_'.$date.'_'.$hour2.'.xls';
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
