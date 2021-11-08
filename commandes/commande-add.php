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

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à commande add
if (isset($_SESSION['type_user']) && !in_array($_SESSION['type_user'], array('Super Administrateur', 'Administrateur'))) {
    header('location:../tb/tb_admin.php');
}

// Log
// switch ($_SESSION['type_user']) {

//     case 1:
//         addlog("Cons-01-client", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//         break;
//     case 2:
//         addlog("Cons-02-client", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//         break;
//     case 3:
//         addlog("Cons-03-client", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//         break;
//     case 4:
//         addlog("Cons-04-client", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//         break;
//     case 5:
//         addlog("Cons-05-cliente", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//         break;
// }


?>






<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion commerciale - Ajouter commande</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../vendors/iconfonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.addons.css">
    <script src="../assets/modules/jquery.min.js"></script>
    <script src="../assets/modules/sweetalert/sweetalert.min.js"></script>
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
                        Ajouter Commande
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="commande.php">commandes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Ajouter une commande</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Ajouter une commande dans la liste</h4>
                            <p class="card-description">
                                Ajout des informations essentielles sur une commande
                            </p>
                            <form class="forms-sample" method="POST" id="commande_add">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Référence</label>
                                            <input type="text" name="reference_commande" class="form-control" placeholder="Référence de la commande" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Date commande</label>
                                            <input type="date" name="date_commande" class="form-control" placeholder="Date de la commande" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Client</label>
                                            <select class="form-control form-control-lg" name="client" required>
                                                <option value="">Choisissez un client</option>
                                                <?php
                                                $query = 'SELECT nom_client FROM client WHERE deleted_client = 0';

                                                $statement = $connect->query($query);

                                                if (!$statement) {
                                                    $mes_erreurs = $connect->errorInfo();
                                                    echo "Lecture impossible, code: ", $connect->errorCode(), $mes_erreurs[2];
                                                } else {
                                                    while ($ligne = $statement->fetch(PDO::FETCH_NUM)) {
                                                        foreach ($ligne as $value) {
                                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Article</label>
                                            <select class="form-control form-control-lg" name="produit" required id="produit">
                                                <option value="">Choisissez un article</option>
                                                <?php
                                                $query = 'SELECT nom_produit FROM produit WHERE deleted = 0';

                                                $statement = $connect->query($query);

                                                if (!$statement) {
                                                    $mes_erreurs = $connect->errorInfo();
                                                    echo "Lecture impossible, code: ", $connect->errorCode(), $mes_erreurs[2];
                                                } else {
                                                    while ($ligne = $statement->fetch(PDO::FETCH_NUM)) {
                                                        foreach ($ligne as $value) {
                                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Prix de vente</label>
                                            <input type="text" name="prix_de_vente" id="prix_de_vente" class="form-control fee" required readonly value="0">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Quantité</label>
                                            <input value="0" type="number" name="quantite" id="quantite" class="form-control fee" required>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Montant Hors Taxe (MHT)</label>
                                            <input type="text" name="montant_hors_taxe" id="mht" class="form-control fee1" required readonly value="0">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>TVA (Taxe à Valeur Ajoutée) en %</label>
                                            <input type="number" name="tva" id="tva" class="form-control fee1" required placeholder="Exemple: 18">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Montant Toutes Taxes Comprises (MTTC)</label>
                                            <input type="text" name="ttc" id="mttc" class="form-control" required readonly value="0">
                                        </div>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Mode de paiement</label>
                                            <input type="text" name="paiement" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Acompte reçu</label>
                                            <input type="text" name="acompte" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Date de livraison</label>
                                            <input type="date" name="date_livraison" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary mr-2 form-control">Ajouter</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="reset" class="btn btn-light form-control">Annuler</button>
                                    </div>
                                </div>
                                <input type="hidden" name="btn_action" id="btn_action" value="AJOUTER">
                                <!-- <input type="hidden" name="btn" id="btn"> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('../parts/footer.php'); ?>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#produit').on('change', function() {
                var id_produit = $('#produit').val();
                var btn = 'fetch';
                $.ajax({
                    url: "commande-action.php",
                    method: "POST",
                    data: {
                        id_produit: id_produit,
                        btn: btn
                    },
                    dataType: "json",
                    success: function(data) {
                        //console.log(data);
                        $('#prix_de_vente').val(data.prix_de_vente_produit);
                        $('#quantite').val(0);
                        $('#mht').val(0);
                        $('#mttc').val(0);
                        //$('#tva').val(0);
                    }
                })
            });
        })



        var $fees = $('.fee').change(function() {
            var total = 1;
            $fees.each(function() {
                total *= (parseFloat($.trim(this.value)) || 0)
            })
            $('#mht').val(+total.toFixed(2));
        });

        var $fees1 = $('.fee1').change(function() {
            var total1 = 0.01;
            $fees1.each(function() {
                total1 *= (parseFloat($.trim(this.value)) || 0)
            })
            $('#mttc').val(+total1.toFixed(2) + +$('#mht').val());
        });

        var cc = $('#quantite').change(function () {
            $('#mttc').val(0);
            //$('#tva').val(0);
        })

        // var ccc = $('#mht').change(function () {
        //     $('#mttc').val(2);
        //     //$('#tva').val(0);
        // })


        $(document).on('submit', '#commande_add', function(event) {
            event.preventDefault();
            var form_data = new FormData(this);
            $.ajax({
                url: "commande-action.php",
                type: "POST",
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                dataType: "json",
                success: function(data) {
                    //console.log(data);
                    if (data == "commande existante") {
                        $('#commande_add')[0].reset();
                        swal('Erreur',
                            'Cette commande existe déjà',
                            'error');
                    }

                    if (data == "Success") {
                        $(document).ready(function() {
                            swal({
                                position: "top-end",
                                title: "Enregistrement réussi",
                                text: "La commande a été enregistrée avec succès",
                                icon: "success",
                            }).then(function() {
                                window.location.href = "commande.php";
                            })
                        });
                    }

                    if (data == "error") {
                        swal('ERREUR', 'L\'enregistrement a échoué', 'error');
                    }
                    if (data == "quantite") {
                        swal('ERREUR', 'Quantité invalide', 'error');
                    }
                    if (data == "tva") {
                        swal('ERREUR', 'TVA incorrecte', 'error');
                    }
                    if (data == "acompte") {
                        swal('ERREUR', 'L\'acompte est invalide', 'error');
                    }
                }
            })
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
    <!-- <script src="../js/file-upload.js"></script> -->
    <!-- End custom js for this page-->
    <!-- End custom js for this page-->
    <script>
        $(window).on("load", function() {
            $(".loader").fadeOut("slow");
        });
    </script>

</body>

</html>