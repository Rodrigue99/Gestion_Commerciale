<?php
include('../database_connection.php');
include('../AddLogInclude.php');

// Langues
// include('../lang/fr-lang.php');
// include('../lang/en-lang.php');

// if ($_SESSION['lang'] == 'EN') {
//   $page_name = CLIENT_EN;
// } else {
//   $page_name = CLIENT_FR;
// }

// $hebergement = 'active';
// $carac_chambre = 'active';


if (!isset($_SESSION['type_user'])) {
  header('location:connexion.php');
}

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à produit edit
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
  <title>Gestion commerciale - Editer un produit</title>
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
  <script src="../assets/modules/jquery.min.js"></script>
  <script src="../assets/modules/sweetalert/sweetalert.min.js"></script>
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
            Modifier articles
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
              <li class="breadcrumb-item active" aria-current="page"><a href="produit.php">articles</a></li>
              <li class="breadcrumb-item active" aria-current="page">editer article</li>
            </ol>
          </nav>
        </div>
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Modifier les informations d'un article de la liste</h4>
              <p class="card-description">
                Editer les informations d'un article
              </p>
              <form class="forms-sample" method="POST" id="produit_form_modif" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Nom de l'article*</label>
                      <input type="text" name="nom_produit_modif" class="form-control" id="nom_produit_modif" placeholder="Nom" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Code barre</label>
                      <input type="text" name="code_barre_produit_modif" class="form-control" id="code_barre_produit_modif" placeholder="code barre" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Référence</label>
                      <input type="text" name="reference_produit_modif" class="form-control" id="reference_produit_modif" required placeholder="reference">
                    </div>
                  </div>
                </div><br>
                <div class="row">

                  <div class="col-6">
                    <div class="form-group">
                      <label>Catégorie</label>
                      <select class="form-control form-control-lg" name="categorie_produit_modif" id="categorie_produit_modif" required>
                        <?php
                        $query = 'SELECT nom_categorie_produit FROM categorie_produit WHERE deleted = 0';

                        $statement = $connect->query($query);


                        if (!$statement) {
                          $mes_erreurs = $connect->errorInfo();
                          echo "Lecture impossible, code: ", $connect->errorCode(), $mes_erreurs[2];
                        } else {
                          while ($ligne = $statement->fetch(PDO::FETCH_NUM)) {
                            foreach ($ligne as $value) {
                              echo '<option value="' . $value . '">'. $value .'</option>';
                              //echo '<option value="'.$value.'">'. $value .'</option>';
                            }
                          }
                        }
                        ?>
                      </select>

                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="exampleInputName1">Coût de revient</label>
                      <input type="text" name="cout_de_revient_produit_modif" class="form-control" id="cout_de_revient_produit_modif" placeholder="coût de revient" required>
                    </div>
                  </div>

                </div><br>
                <div class="row">

                  <div class="col-6">
                    <div class="form-group">
                      <label for="exampleInputEmail3">Prix de vente</label>
                      <input type="text" name="prix_de_vente_produit_modif" class="form-control" id="prix_de_vente_produit_modif" required placeholder="Prix de vente">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label>Photo</label>
                      <input type="file" name="photo" class="file-upload-default" accept="image/png, image/jpg, image/jpeg, image/PNG, image/JPG, image/JPEG">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="charger une image (champ obligatioire)">
                        <span class="input-group-append">
                          <button class="file-upload-browse btn btn-primary" type="button">Charger</button>
                        </span>
                      </div>
                    </div>
                  </div>

                </div><br>
                <div class="row">

                  <div class="col-12">
                    <div class="form-group">
                      <label for="exampleInputName1">Note</label>
                      <textarea name="note_produit_modif" id="note_produit_modif" required cols="30" rows="10" class="form-control"></textarea>
                    </div>
                  </div>

                </div><br>
                <div class="row">

                  <div class="col-6">
                    <button type="submit" class="btn btn-primary mr-2 form-control">Modifier</button>
                  </div>
                  <div class="col-6">
                    <button type="reset" class="btn btn-light form-control">Annuler</button>
                  </div>

                </div><br>


                <!-- <div class="form-group">
                      <label>Photo</label>
                      <input type="file" name="photo_produit_modif" class="form-control" required accept="image/png, image/jpg, image/jpeg, image/PNG, image/JPG, image/JPEG">
                    </div> -->
                  <input type="hidden" name="id_produit_modif" id="id_produit_modif" value="<?php echo htmlspecialchars($_GET['id']) ?>">
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

/* fetch single */
        $(document).ready(function() {
            var id_produit_modif = <?php echo htmlspecialchars($_GET['id']) ?>;
            var btn_action_modif = 'fetch_single';
            $.ajax({
                url:"produit_fetch_action.php",
                method:"POST",
                data:{id_produit_modif:id_produit_modif, btn_action_modif:btn_action_modif},
                dataType:"json",
                success:function(data)
                {
                    //console.log(data.nom_produit);
                    $('#nom_produit_modif').val(data.nom_produit);
                    $('#code_barre_produit_modif').val(data.code_barre_produit);
                    $('#reference_produit_modif').val(data.reference_produit);
                    $('#categorie_produit_modif').val(data.categorie_produit);
                    $('#cout_de_revient_produit_modif').val(data.cout_de_revient_produit);
                    $('#prix_de_vente_produit_modif').val(data.prix_de_vente_produit);
                    // //$('#photo_produit').val(data.photo_produit;)                    
                    $('#note_produit_modif').val(data.note_produit);
                    //$('.modal-title_modif').text("MODIFIER");
                    $('.save-edit-bouton_modif').text("MODIFIER");
                    $('#id_produit_modif').val(id_produit_modif);
                    $('#btn_action_modif').val("Modifier");

                }
            })
        });


    /* Modifier Submit */

        $(document).on('submit','#produit_form_modif', function(event){
            event.preventDefault();
            var form_data = new FormData(this);
              $.ajax({
                  url:"produit_fetch_action.php",
                  method: "POST",
                  enctype: 'multipart/form-data',
                  data: form_data,
                  processData: false,
                  contentType: false,
                  cache: false,
                  dataType: "json",
                success:function(data)
                {
                    if(data == "Produit existant") {
                        $('#produitModal_modif').modal('hide');
                        swal('Erreur',
                         'Cet article existe déjà',
                         'error');
                    }


                    if(data == "Modifié") {
                      $('#clientModal_modif').modal('hide');
                      $(document).ready(function(){
                                  swal({
                                      position: "top-end",
                                      title: "Modification réussie",
                                      text: "L'article a été modifié avec succès",
                                      icon: "success",
                                  }).then(function(){
                                          window.location.href = "produit.php";
                                      })
                              });
                      $('#produitModal_modif').modal('hide');
                      // swal('Effectué',
                      //   'Les modifications ont été enregistrées avec succès',
                      //   'success');
                      // window.location = "produit.php";
                    }

                    if(data == "Erreur enregistrement image") {
                      $('#produitModal_modif').modal('hide');
                      swal('Error',
                        data,
                        'error');
                    }

                    if(data == "Extension non valide ou image trop volumineuse") {
                      $('#produitModal_modif').modal('hide');
                      swal('Error',
                        data,
                        'error');
                    }

                    if(data == "Erreur Telechargement") {
                      $('#produitModal_modif').modal('hide');
                      swal('Error',
                        'Image non soumise',
                        'error');
                    }

                    if(data == "Image non soumise") {
                      $('#produitModal_modif').modal('hide');
                      swal('Error',
                        data,
                        'error');
                    }
                    if(data == "bad") {
                      swal('Error',
                        'Un ou des montant(s) son invalides',
                        'error');
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