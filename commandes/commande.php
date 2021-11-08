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

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à commande
if (isset($_SESSION['type_user']) && !in_array($_SESSION['type_user'], array('Super Administrateur', 'Administrateur'))) {
    header('location:../tb/tb_admin.php');
}

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion commerciale - Commandes</title>
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
                        Commandes d'articles
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page">commandes</li>
                        </ol>
                    </nav>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Commandes</h4>
                                <div class="row grid-margin">
                                    <div class="col-12">
                                        <div class="alert alert-success" role="alert">
                                            La liste des commandes
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="width:300px; float:right" ;>
                                <a href="commande-add.php"><button type="button" name="add" id="add_button" style="margin-left: 30px; text-decoration: none; color: white;" class="btn btn-warning">Nouvelle commande</button></a>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                    <form method="POST" action="../export/export-commande.php">
                                                <div class="form-group" style="width:300px; float:right;">
                                                    <div class="input-group">
                                                        <select name="export_commande" class="custom-select" id="inputGroupSelect04">
                                                            <option value="pdf">Exporter en PDF</option>
                                                            <option value="word">Exporter en Word</option>
                                                            <option value="excel">Exporter en Excel</option>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <button name="btn_export_commande" class="btn btn-primary" type="submit">Exporter</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        <div class="table-responsive">
                                            <table id="commande_data" class="table table-striped table-bordered" cellspacing="0" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important;">
                                                            Référence
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important;">
                                                            Date
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important;">
                                                            Client
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important;">
                                                            Total
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            Acompte
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            Facture
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


    <div id="commandeModal_view" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="commande_form_view">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title_view"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div id="commandes"></div>
                    </div>

                </div>
            </form>
        </div>
    </div>


    <!-- Nouvelle Facture Modal -->
  <div id="commande_new_facture" class="modal fade">
      <div class="modal-dialog">
          <form method="post" id="commande_new_facture_form">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title_new_facture"></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">

                    <div class="row">
                      <div class="form-group col-lg-6">
                          <label>
                          Date de la facture
                         *</label>
                          <input type="datetime-local" name="date_new_facture" id="date_new_facture" class="form-control" required />
                      </div>

                      <div class="form-group col-lg-6">
                          <label>
                          Numéro de facture
                         *</label>
                          <input type="text" name="num_new_facture" id="num_new_facture" class="form-control" required />
                      </div>
                    </div>

                      <div class="form-group">
                          <label>
                          Méthode de paiement
                         *</label>
                         <input name="methode_paiement_new_facture" id="methode_paiement_new_facture" class="form-control" required >
                      </div>


                    <div class="row">
                      <div class="form-group col-lg-6">
                          <label>
                          Montant Hors Taxe
                         </label>
                          <input type="text" name="montant_ht_new_facture" id="montant_ht_new_facture" class="form-control" readonly />
                      </div>

                      <div class="form-group col-lg-6">
                          <label>
                          valeur TVA
                         </label>
                          <input type="text" name="valeur_tva_new_facture" id="valeur_tva_new_facture" class="form-control" readonly />
                      </div>
                    </div>


                    <div class="row">
                      <div class="form-group col-lg-12">
                          <label>
                         Montant TTC
                         </label>
                          <input type="text" name="montant_ttc_new_facture" id="montant_ttc_new_facture" class="form-control" readonly />
                      </div>
                    </div>

                    <div class="form-group">
                        <label>
                        Montant en lettres
                        *</label>
                        <textarea type="text" name="prix_en_lettres_new_facture" id="prix_en_lettres_new_facture" class="form-control" required></textarea>
                    </div>

                  </div>
                  <div class="modal-footer bg-whitesmoke">
                      <input type="hidden" name="id_location_conf_new_facture" id="id_location_conf_new_facture"/>
                      <input type="hidden" name="btn_action_new_facture" id="btn_action_new_facture"/>
                      <button type="submit" class="btn btn-primary btn-shadow save-edit-bouton_new_facture" name="action_new_facture" id="action_new_facture"></button>
                  </div>
              </div>
          </form>
      </div>
  </div>



    <!-- Formulaire Afficher Facture -->
    <form id="afficher_facture_form" style="display:none" method="POST" action="../factures/facture-conf.php">
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
        var commandedataTable = $('#commande_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "commande-fetch.php", // modifiable 
                // data: {
                //     "id": <?php //echo $_GET['id'] ?>
                // },
                type: "POST"
            },
            "columnDefs": [{
                "targets": [6], // modifiable
                "orderable": false,
            }, ],
            //"bSort" : false,
            "pageLength": 10
        });


    /* Nouvelle Facture */
    $(document).on('click', '.new_facture', function(){
        var id_location_conf = $(this).attr("id");
        var btn_actionn = 'fetch_montant';
        $.ajax({
            url:"commande-action.php",
            method:"POST",
            data:{id_location_conf:id_location_conf, btn_actionn:btn_actionn},
            dataType:"json",
            success: function(data) {
                $('#commande_new_facture').modal('show');
                // // resets
                $('#commande_new_facture_form')[0].reset();
                // tva
                // $('#tva_new_facture').val(0);
                // $('#select_tva_new_facture').parents('.form-group').siblings().hide();
                // $('#tva_new_facture').removeAttr('required');

                 $('#montant_ht_new_facture').val(data.montant_ht);
                 $('#valeur_tva_new_facture').val(data.mtva);
                 $('#montant_ttc_new_facture').val(data.mttc);
                // calculerTTC();

                $('.modal-title_new_facture').text("Editer la facture");
                $('.save-edit-bouton_new_facture').text("Editer la facture");
                $('#id_location_conf_new_facture').val(id_location_conf);
                $('#btn_action_new_facture').val("New Facture");
            }
        })
    });


    /* Nouvelle Facture Submit */
    $(document).on('submit','#commande_new_facture_form', function(event){
        event.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url:"commande-action.php",
            method:"POST",
            data:form_data,
            dataType:"json",
            success: function(data) {
                //console.log(data);
                if(data == "La facture a été éditée avec succes.") {
                    $('#commande_new_facture').modal('hide');
                    swal('EFFECTUE', 'Facture éditée avec succès', 'success');
                }

                if(data == "Une facture porte déjà ce numéro.") {
                    $('#commande_new_facture').modal('hide');
                    swal('ERREUR', 'Une facture porte déja ce numéro', 'error');
                }
                

                $('#commande_new_facture_form')[0].reset();
                commandedataTable.ajax.reload();
            }
        })
    });


    // // AFFICHER FACTURE
    $(document).on('click', '.view_facture', function(event){
        event.preventDefault();
        $id_location_conf = $(this).attr('id');
        $('input[name="id_location_conf"]').val($id_location_conf);
        $('#afficher_facture_form').submit();
    })



         /* Consulter */
      
            $(document).on('click', '.view', function() {
            var id_commande_view = $(this).attr("id");
            var btn_action_view = 'consulter';
            $.ajax({
                url: "commande-action.php",
                method: "POST",
                data: {
                    id_commande_view: id_commande_view,
                    btn_action_view: btn_action_view
                },
                dataType: "json",
                success: function(data) {
                    $('#commandeModal_view').modal('show'); // affiche le modal

                    $('.modal-title_view').text(" Détails sur une commande");
                    $('#commandes').html(data);

                }
            })
        });

        /* Changer statut */
        $(document).on('click', '.delete', function() {
            var id_commande = $(this).attr('id');
            var status = $(this).data("status");
            var btn_action = 'delete';

            swal({
                    title: 'CHANGEMENT DE STATUT',
                    text: 'Voulez-vous changer le statut de la commande ?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            url: "commande-action.php",
                            method: "POST",
                            data: {
                                id_commande: id_commande,
                                status: status,
                                btn_action: btn_action
                            },
                            dataType: "JSON",
                            success: function(data) {
                                if (data == "Actif") {
                                    swal('EFFECTUE', 'Le statut de la commande est maintenant: Actif', 'success');
                                }
                                if (data == "Inactif") {
                                    swal('EFFECTUE', 'Le statut de la commande est maintenant: Inactif', 'success');
                                }

                                commandedataTable.ajax.reload();
                            }
                        });

                    } else {}
                });

        });


        //Delete

        $(document).on('click', '.remove', function() {
            var id_commande = $(this).attr('id');
            var status = $(this).data("status");
            var btn_action = 'remove';

            swal({
                    title: 'SUPPRIMER',
                    text: 'Voulez-vous vraiment supprimer cette commande',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            url: "commande-action.php",
                            method: "POST",
                            data: {
                                id_commande: id_commande,
                                status: status,
                                btn_action: btn_action
                            },
                            dataType: "JSON",
                            success: function(data) {
                                console.log(data);
                                if (data == "Supprime") {
                                    swal('Effectué', 'commande supprimée', 'success');
                                } else {
                                    swal('Erreur', 'Probleme de suppression', 'error');
                                }

                                commandedataTable.ajax.reload();
                            }
                        });

                    } else {}
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