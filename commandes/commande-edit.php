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

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à commande edit
if (isset($_SESSION['type_user']) && !in_array($_SESSION['type_user'], array('Super Administrateur', 'Administrateur'))) {
    header('location:../tb/tb_admin.php');
}


//Log
// switch ($_SESSION['type_user']) {

//   case 1:
//     addlog("Cons-01-carac-chambre", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
//   case 2:
//     addlog("Cons-02-carac-chambre", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
//   case 3:
//     addlog("Cons-03-carac-chambre", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
//   case 4:
//     addlog("Cons-04-carac-chambre", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
//   case 5:
//     addlog("Cons-05-carac-chambre", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
// }


?>






<!DOCTYPE html>
<html lang="en">


<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Gestion commerciale - Editer Commande</title>
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
    }.loader {
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
                        Editer Commande
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="commande.php">commandes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">éditer une commande</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Editer une commande dans la liste</h4>
                            <p class="card-description">
                                Modification des informations essentielles sur une commande
                            </p>
                            <form class="forms-sample" method="POST" id="commande_form_modif">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Référence</label>
                                            <input type="text" name="reference_commande" id="reference_commande" class="form-control" placeholder="Référence de la commande" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Date commande</label>
                                            <input type="date" name="date_commande" id="date_commande" class="form-control" placeholder="Date de la commande" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Client</label>
                                            <select class="form-control form-control-lg" name="client" id="client" required>
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
                                                            echo '<option value="'.$value.'">'. $value .'</option>';
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
                                                            echo '<option value="'.$value.'">'. $value .'</option>';
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
                                            <input type="text" name="montant_hors_taxe" id="montant_hors_taxe" class="form-control fee1" required readonly value="0">
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
                                            <input type="text" name="ttc" id="ttc" class="form-control" required readonly value="0">
                                        </div>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Mode de paiement</label>
                                            <input type="text" name="paiement" id="paiement" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Acompte reçu</label>
                                            <input type="text" name="acompte" id="acompte" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Date de livraison</label>
                                            <input type="date" name="date_livraison" id="date_livraison" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary mr-2 form-control">MODIFIER</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="reset" class="btn btn-light form-control">ANNULER</button>
                                    </div>
                                </div>
                                <input type="hidden" name="id_commande_modif" id="id_commande_modif" value="<?php echo htmlspecialchars($_GET['id']) ?>">
                                <!-- <input type="hidden" name="btn_action_modif" id="btn_action_modif"> -->
                                <input type="hidden" name="btn_action_modif" id="btn_action_modif" value="Modifier">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('../parts/footer.php'); ?>
        </div>
  </div>



  <script type="text/javascript">

  $(document).ready(function() {
             $('#produit').on('change', function () {
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
          $('#montant_hors_taxe').val(0);
          $('#ttc').val(0);
          $('#tva').val(0);
        }
      })
    });
})



    var $fees = $('.fee').change(function () {
        var total = 1;
        $fees.each(function () {
            total *= (parseFloat($.trim(this.value)) || 0)
        })
        $('#montant_hors_taxe').val(+total.toFixed(2));
    });

    var $fees1 = $('.fee1').change(function () {
        var total1 = 0.01;
        $fees1.each(function () {
            total1 *= (parseFloat($.trim(this.value)) || 0)
        })
        $('#ttc').val(+total1.toFixed(2) + +$('#montant_hors_taxe').val());
    });

    var cc = $('#quantite').change(function () {
            $('#ttc').val(0);
            $('#tva').val(0);
        })



     /* fetch single */
     $(document).ready(function() {
      var id_commande_modif = <?php echo htmlspecialchars($_GET['id']) ?>;
      
      var btn_action_modif = 'fetch_single';
      $.ajax({
        url: "commande-action.php",
        method: "POST",
        data: {
          id_commande_modif: id_commande_modif,
          btn_action_modif:btn_action_modif
        },
        dataType: "json",
        success: function(data) {
          //console.log(data);
          $('#reference_commande').val(data.reference_commande);
          $('#date_commande').val(data.date_commande);
          $('#client').val(data.client_commande);
          $('#produit').val(data.produit_commande);
          $('#prix_de_vente').val(data.prix_de_vente_commande);
          $('#quantite').val(data.quantite_commande);
          $('#montant_hors_taxe').val(data.montant_hors_taxe_commande);
          $('#tva').val(data.tva_commande);
          $('#ttc').val(data.montant_ttc_commande);
          $('#paiement').val(data.mode_de_paiement_commande);
          $('#acompte').val(data.acompte_commande);
          $('#date_livraison').val(data.date_de_livraison_commande);
          //$('.save-edit-bouton_modif').text("MODIFIER");
          $('#id_commande_modif').val(id_commande_modif);
          $('#btn_action_modif').val("Modifier");

        }
      })
    });

    
    /* Modifier Submit */

    $(document).on('submit', '#commande_form_modif', function(event) {
      event.preventDefault();
      var form_data = new FormData(this);
      $.ajax({
        url: "commande-action.php",
        method: "POST",
        enctype: 'multipart/form-data',
        data: form_data,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "json",
        success: function(data) {
            //console.log(data);
          if (data == "commande existante") {
            swal('Erreur',
              'Cette commande existe déjà',
              'error');
          }


          if (data == "Modifié") {
            $(document).ready(function() {
              swal({
                position: "top-end",

                title: "Modification réussie",
                text: "La commande a été modifiée avec succès",
                icon: "success",
              }).then(function() {
                window.location.href = "commande.php";
              })
            });
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
    <script src="../js/file-upload.js"></script>
    <!-- End custom js for this page-->
    <!-- End custom js for this page-->
    <script>
        $(window).on("load", function() {
            $(".loader").fadeOut("slow");
        });
    </script>
</body>

</html>