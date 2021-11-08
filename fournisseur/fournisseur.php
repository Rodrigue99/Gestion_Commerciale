<?php
include('../database_connection.php');
include('../AddLogInclude.php');

// Langues
// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');

// if ($_SESSION['lang'] == 'EN') {
//     $page_name = CLIENT_EN;
// } else {
//     $page_name = CLIENT_FR;
// }

// $hebergement = 'active';
// $carac_chambre = 'active';


if (!isset($_SESSION['type_user'])) {
    header('location:../connexion.php');
}

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à fournisseur 
if (isset($_SESSION['type_user']) && !in_array($_SESSION['type_user'], array('Super Administrateur', 'Administrateur'))) {
    header('location:../tb/tb_admin.php');
}


// Log
switch ($_SESSION['type_user']) {

    case "Super Administrateur":
        addlog("Cons-01-fournisseur", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
    case "Administrateur":
        addlog("Cons-02-fournisseur", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
    case "Editeur":
        addlog("Cons-03-fournisseur", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
    case "Auteur":
        addlog("Cons-04-fournisseur", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
    case "Lecteur":
        addlog("Cons-05-fournisseur", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
        break;
}


?>






<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion commerciale - Fournisseur</title>
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

        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("../images/auth/loading.gif") 50% 50% no-repeat #f9f9f9;
            opacity: 1
        }
    </style>
</head>

<body>
    <div class="loader"></div>

    <body>
        <div class="container-scroller">
            <?php include('../parts/header.php');
            include('../parts/sidebar.php');
            ?>
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="page-header">
                        <h3 class="page-title">
                            Fournisseurs
                        </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Fournisseurs</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Liste des fournisseurs</h4>
                                    <div class="row grid-margin">
                                        <div class="col-12">
                                            <div class="alert alert-success" role="alert">
                                                La liste des fournisseurs comporte les informations éssentielles sur tous les fournisseurs.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="width:300px; float:right;">
                                    <a href="fournisseur-add.php" style="text-decoration: none; color: white; margin-left: 30px;"><button type="button" class="btn btn-warning">Nouveau fournisseur</button></a>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-body">
                                            <form method="POST" action="../export/export-fournisseur.php">
                                                <div class="form-group" style="width:300px; float:right;">
                                                    <div class="input-group">
                                                        <select name="export_fournisseur" class="custom-select" id="inputGroupSelect04">
                                                            <option value="pdf">Exporter en PDF</option>
                                                            <option value="word">Exporter en Word</option>
                                                            <option value="excel">Exporter en Excel</option>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <button name="btn_export_fournisseur" class="btn btn-primary" type="submit">Exporter</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="table-responsive">
                                                <table id="fournisseur_data" class="table table-striped table-bordered" cellspacing="0" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important;">
                                                                Photo
                                                            </th>
                                                            <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important;">
                                                                Nom
                                                            </th>
                                                            <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                                DG
                                                            </th>
                                                            <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                                Ville
                                                            </th>
                                                            <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                                Tel
                                                            </th>
                                                            <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                                Email
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
                    </div><?php include('../parts/footer.php'); ?>
                </div>
            </div>
        </div>



        <!-- Consulter Modal -->
        <!-- affichage du formulaire si on masque on ne voie rien qui s'affiche. Le consuler ne renvoie rien-->


        <div id="fournisseurModal_view" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="fournisseur_form_view">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title_view"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div id="fournisseurs"></div>
                        </div>

                    </div>
                </form>
            </div>
        </div>


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
            var fournisseurdataTable = $('#fournisseur_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "fournisseur_fetch.php", // modifiable
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [7], // modifiable
                    "orderable": false,
                }, ],
                //"bSort" : false,
                "pageLength": 10
            });


            /* Consulter */
            /*  envoie l'id de la chambre et la valeur de consulter a chambre_action.php
                qui à son tour  va afficher tous les détails */
            $(document).on('click', '.view', function() {
                var id_fournisseur_view = $(this).attr("id");
                var btn_action_view = 'consulter';
                $.ajax({
                    url: "fournisseur_fetch_action.php",
                    method: "POST",
                    data: {
                        id_fournisseur_view: id_fournisseur_view,
                        btn_action_view: btn_action_view
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#fournisseurModal_view').modal('show'); // affiche le modal

                        $('.modal-title_view').text(" Détails sur un fournisseur");
                        $('#fournisseurs').html(data);

                    }
                })
            });

            //Suppression
            //Delete

            $(document).on('click', '.remove', function() {
                var id_fournisseur = $(this).attr('id');
                var status = $(this).data("status");
                var btn_action = 'remove';

                swal({
                        title: 'SUPPRIMER',
                        text: 'Voulez-vous vraiment supprimer cet element',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "fournisseur_fetch_action.php",
                                method: "POST",
                                data: {
                                    id_fournisseur: id_fournisseur,
                                    status: status,
                                    btn_action: btn_action
                                },
                                dataType: "JSON",
                                success: function(data) {

                                    if (data == "Supprime") {
                                        swal('Effectué', 'Element supprimé', 'success');
                                    } else {
                                        swal('Erreur', 'Probleme de suppression', 'error');
                                    }

                                    fournisseurdataTable.ajax.reload();
                                }
                            });
                            swal('Effectué', 'Element supprimé', 'success');
                            fournisseurdataTable.ajax.reload();

                        } else {}
                    });
            });


            /* Changer statut */
            $(document).on('click', '.delete', function() {
                var id_fournisseur = $(this).attr('id');
                var status = $(this).data("status");
                var btn_action = 'delete';

                swal({
                        title: 'CHANGEMENT DE STATUT',
                        text: 'Voulez-vous vraiment changer le statut du fournisseur ?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            $.ajax({
                                url: "fournisseur_fetch_action.php",
                                method: "POST",
                                data: {
                                    id_fournisseur: id_fournisseur,
                                    status: status,
                                    btn_action: btn_action
                                },
                                dataType: "JSON",
                                success: function(data) { console.log(data);
                                    if (data == "Actif") {
                                        swal('EFFECTUE', 'Le statut du fournisseur est maintenant: ' + data, 'success');
                                    }
                                    if (data == "Inactif") {
                                        swal('EFFECTUE', 'Le statut du fournisseur est maintenant: ' + data, 'success');
                                    }

                                    fournisseurdataTable.ajax.reload();
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
            $(window).on("load", function() {
                $(".loader").fadeOut("slow");
            });
        </script>
    </body>

</html>