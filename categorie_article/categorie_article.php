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

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à categorie article
if (isset($_SESSION['type_user']) && !in_array($_SESSION['type_user'], array('Super Administrateur', 'Administrateur'))) {
    header('location:../tb/tb_admin.php');
}


// Log
// switch ($_SESSION['type_user']) {

//     case 1:
//         addlog("Cons-01-categorie_article", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
//     case 2:
//         addlog("Cons-02-categorie_article", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
//     case 3:
//         addlog("Cons-03-categorie_article", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
//     case 4:
//         addlog("Cons-04-categorie_article", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
//     case 5:
//         addlog("Cons-05-categorie_article", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
// }


?>






<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion commerciale - catégories d'article</title>
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
    <div class="container-scroller">
        <?php include('../parts/header.php');
        include('../parts/sidebar.php');
        ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <h3 class="page-title">
                        Catégories d'article
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Catégories d'article</li>
                        </ol>
                    </nav>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Liste des catégories d'article</h4>
                                <div class="row grid-margin">
                                    <div class="col-12">
                                        <div class="alert alert-success" role="alert">
                                            La liste comporte les informations éssentielles sur toutes les catégories d'article.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="width:300px; float:right;">
                                <a href="categorie-article-add.php" style="text-decoration: none; color: white;margin-left: 30px"><button type="button" class="btn btn-warning">Nouvelle catégorie d'article</button></a>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                        <form method="POST" action="../export/export-categorie-article.php">
                                            <div class="form-group" style="width:300px; float:right;">
                                                <div class="input-group">
                                                    <select name="export_categorie_article" class="custom-select" id="inputGroupSelect04">
                                                        <option value="pdf">Exporter en PDF</option>
                                                        <option value="word">Exporter en Word</option>
                                                        <option value="excel">Exporter en Excel</option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button name="btn_export_categorie_article" class="btn btn-primary" type="submit">Exporter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table id="categorie_article_data" class="table table-striped table-bordered" cellspacing="0" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important;">
                                                            Photo
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important;">
                                                            Nom
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            Description
                                                        </th>
                                                        <th class="border-top" style="background-color: #f1f1f1 !important; color: black !important; font-size: 15px !important; font-weight: bold !important; text-align: center !important;">
                                                            Nombre d'articles
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


    <div id="categorie_articleModal_view" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="categorie_article_form_view">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title_view"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="categorie_articles"></div>
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
    <script src="../js/file-upload.js"></script>

    <script type="text/javascript">
        /* Affichage de la liste */
        var categorie_articledataTable = $('#categorie_article_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "categorie_article_fetch.php", // modifiable
                type: "POST"
            },
            "columnDefs": [{
                "targets": [5], // modifiable
                "orderable": false,
            }, ],
            //"bSort" : false,
            "pageLength": 10
        });

        /* Consulter */
        /*  envoie l'id de la chambre et la valeur de consulter a chambre_action.php
            qui à son tour  va afficher tous les détails */
        $(document).on('click', '.view', function() {
            var id_categorie_article_view = $(this).attr("id");
            var btn_action_view = 'consulter';
            $.ajax({
                url: "categorie_article_fetch_action.php",
                method: "POST",
                data: {
                    id_categorie_article_view: id_categorie_article_view,
                    btn_action_view: btn_action_view
                },
                dataType: "json",
                success: function(data) {
                    $('#categorie_articleModal_view').modal('show'); // affiche le modal

                    $('.modal-title_view').text(" Détails sur une catégorie d'article");
                    $('#categorie_articles').html(data);

                }
            })
        });

        //Suppression

        //Delete

        $(document).on('click', '.remove', function() {
            var id_categorie_article = $(this).attr('id');
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
                            url: "categorie_article_fetch_action.php",
                            method: "POST",
                            data: {
                                id_categorie_article: id_categorie_article,
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

                                categorie_articledataTable.ajax.reload();
                            }
                        });
                        swal('Effectué', 'Element supprimé', 'success');
                        categorie_articledataTable.ajax.reload();
                    } else {}
                });

        });


        /* Changer statut */
        $(document).on('click', '.delete', function() {
            var id_categorie_article = $(this).attr('id');
            var status = $(this).data("status");
            var btn_action = 'delete';

            swal({
                    title: 'CHANGEMENT DE STATUT',
                    text: 'Voulez-vous vraiment changer le statut de la catégorie ?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            url: "categorie_article_fetch_action.php",
                            method: "POST",
                            data: {
                                id_categorie_article: id_categorie_article,
                                status: status,
                                btn_action: btn_action
                            },
                            dataType: "JSON",
                            success: function(data) {
                                if (data == "Actif") {
                                    swal('EFFECTUE', 'Le statut de la catégorie est maintenant: ' + data, 'success');
                                }
                                if (data == "Inactif") {
                                    swal('EFFECTUE', 'Le statut de la catégorie est maintenant: ' + data, 'success');
                                }

                                categorie_articledataTable.ajax.reload();
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