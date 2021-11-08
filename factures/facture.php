<?php
include('../database_connection.php');
include('../AddLogInclude.php');

// Langues
// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');

// $hebergement = 'active';
// $carac_chambre = 'active';


if (!isset($_SESSION['type_user'])) {
    header('location:../connexion.php');
}

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à facture
if (isset($_SESSION['type_user']) && !in_array($_SESSION['type_user'], array('Super Administrateur', 'Administrateur'))) {
    header('location:../tb/tb_admin.php');
}

switch ($_SESSION['type_user']) {

    case 1:
        addlog("Cons-01-facture-conf", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
    case 2:
        addlog("Cons-02-facture-conf", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
    case 3:
        addlog("Cons-03-facture-conf", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
    case 4:
        addlog("Cons-04-facture-conf", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
    case 5:
        addlog("Cons-05-facture-conf", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
}

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion commerciale - Factures</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../vendors/iconfonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.addons.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../images/auth/gc.jpg" />
    <style>
        .section-header h4,
        breadcrumb-item {
            display: inline;
        }
        .loader{
            position:fixed;
            left:0px;top:0px;
            width:100%;
            height:100%;
            z-index:9999;
            background:url("../images/auth/loading.gif") 50% 50% no-repeat #f9f9f9;
            opacity:1
        }
    </style>
</head>

<body>
    <div class="loader"></div>
    <div class="container-scroller">
        <?php include('../parts/header.php');
        include('../parts/sidebar.php');
        ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <h3 class="page-title">
                        Factures
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page">factures</li>
                        </ol>
                    </nav>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Factures</h4>
                                <div class="row grid-margin">
                                    <div class="col-12">
                                        <div class="alert alert-success" role="alert">
                                            La liste des factures
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="width:300px; float:right" ;>
                                <a href="../commandes/commande.php"><button type="button" name="add" id="add_button" style="margin-left: 30px; text-decoration: none; color: white;" class="btn btn-warning">Nouvelle facture</button></a>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                    <form method="POST" action="../export/export-facture.php">
                                                <div class="form-group" style="width:300px; float:right;">
                                                    <div class="input-group">
                                                        <select name="export_facture" class="custom-select" id="inputGroupSelect04">
                                                            <option value="pdf">Exporter en PDF</option>
                                                            <option value="word">Exporter en Word</option>
                                                            <option value="excel">Exporter en Excel</option>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <button name="btn_export_facture" class="btn btn-primary" type="submit">Exporter</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        <div class="table-responsive">
                                            <table id="facture_data" class="table table-striped table-bordered" cellspacing="0" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            N° Facture
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            Date d'édition
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            Client
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            Montant TTC
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            Statut
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            Actions
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <?php include('../parts/footer.php'); ?>
            </div>
        </div>
    </div>

        <!-- Consulter Modal -->
    <!-- affichage du formulaire si on masque on ne voie rien qui s'affiche. Le consuler ne renvoie rien-->


    <!-- Formulaire Afficher Facture -->
    <form id="afficher_facture_form" style="display:none" method="POST" action="../factures/facture-fac.php">
        <input type="hidden" name="id_location_conf" value="" />
        <input type="submit" name="" />
    </form>
    



    <!-- General JS Scripts -->
    <script src="../assets/modules/jquery.min.js"></script>


    <!-- JS Libraies -->
    <script src="../assets/modules/datatables/datatables.min.js"></script>
    <script src="../assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <script src="../assets/modules/jquery-ui/jquery-ui.min.js"></script>
    <script src="../assets/modules/sweetalert/sweetalert.min.js"></script>

    <!-- Page Specific JS File -->
    <script src="../assets/js/page/modules-datatables.js"></script>

    <!-- Template JS File -->
    <script src="../assets/js/scripts.js"></script>
    <script src="../assets/js/custom.js"></script>


    <script type="text/javascript">
        /* Affichage de la liste */
        var facturedataTable = $('#facture_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "facture-fetch.php", // modifiable 
                // data: {
                //     "id": <?php //echo $_GET['id'] ?>
                // },
                type: "POST"
            },
            "columnDefs": [{
                "targets": [5], // modifiable
                "orderable": false,
            }, ],
            //"bSort" : false,
            "pageLength": 10
        });


   
    // // AFFICHER FACTURE
    $(document).on('click', '.view_facture', function(event){
        event.preventDefault();
        $id_location_conf = $(this).attr('id');
        $('input[name="id_location_conf"]').val($id_location_conf);
        $('#afficher_facture_form').submit();
    })



        /* Changer statut */
      // changer au besoin
      $(document).on('click', '.delete_facture', function(){
          var id_facture = $(this).attr('id');
          var btn_action = 'delete';

          swal({
              title: 'ANNULER FACTURE',
              text: 'Voulez-vous annuler la facture ?',
              icon: 'warning',
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
              if (willDelete) {

                  $.ajax({
                      url:"facture-action.php",
                      method:"POST",
                      data:{id_facture:id_facture, btn_action:btn_action},
                      dataType: "JSON",
                      success:function(data)
                      {
                        if(data == "Annulé") {
                            swal('EFFECTUE', 'La facture a été annulée avec succès', 'success');
                        }

                          facturedataTable.ajax.reload();
                      }
                  });

              } else {
              }
          });

      });


    </script>


    <!-- plugins:js -->
    <script src="../vendors/js/vendor.bundle.base.js"></script>
    <script src="../vendors/js/vendor.bundle.addons.js"></script>

    <script src="../js/off-canvas.js"></script>
    <script src="../js/hoverable-collapse.js"></script>
    <script src="../js/misc.js"></script>
    <script src="../js/settings.js"></script>
    <script src="../js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../js/dashboard.js"></script>
    <!-- End custom js for this page-->
    <script>
      $(window).on("load", function () {
  $(".loader").fadeOut("slow");
});
    </script> 
</body>

</html>