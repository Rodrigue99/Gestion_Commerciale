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
  header('location:connexion.php');
}

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à categ article edit
if (isset($_SESSION['type_user']) && !in_array($_SESSION['type_user'], array('Super Administrateur', 'Administrateur'))) {
  header('location:../tb/tb_admin.php');
}


//Log
// switch ($_SESSION['type_user']) {

//     case 1:
//         addlog("Cons-01-carac-chambre", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
//     case 2:
//         addlog("Cons-02-carac-chambre", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
//     case 3:
//         addlog("Cons-03-carac-chambre", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
//     case 4:
//         addlog("Cons-04-carac-chambre", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
//     case 5:
//         addlog("Cons-05-carac-chambre", "", $_SESSION["prenom_user"]." ".$_SESSION["nom_user"]);
//         break;
// }


?>






<!DOCTYPE html>
<html lang="en">


<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Gestion commerciale - Editer catégorie d'article</title>
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
            Modifier Catégories d'article
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
              <li class="breadcrumb-item active" aria-current="page"><a href="categorie_article.php">Catégorie article</a></li>
              <li class="breadcrumb-item active" aria-current="page">editer categorie article</li>
            </ol>
          </nav>
        </div>
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Modifier les informations d'une categorie d'article de la liste</h4>
              <p class="card-description">
                Editer les informations d'une categorie d'articles
              </p>
              <form class="forms-sample" method="POST" enctype="multipart/form-data" id="categorie_article_form_modif">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="exampleInputName1">Nom de la catégorie*</label>
                      <input type="text" name="nom_categorie_article_modif" class="form-control" required id="nom_categorie_article_modif">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="exampleInputName1">Description</label>
                      <input type="text" name="description_categorie_article_modif" class="form-control" id="description_categorie_article_modif" placeholder="Description" required>
                    </div>
                  </div>
                </div><br>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="exampleInputName1">Nombre d'articles</label>
                      <input type="text" name="nombre_article_categorie_article_modif" class="form-control" id="nombre_article_categorie_article_modif" required>
                    </div>
                  </div>
                  <div class="col-6">
                    <!-- <div class="form-group">
                      <label>Photo</label>
                      <input type="file" name="photo_categorie_article_modif" class="form-control" required accept="image/png, image/jpg, image/jpeg, image/PNG, image/JPG, image/JPEG">
                    </div> -->
                    <div class="form-group">
                      <label>Photo</label>
                      <input type="file" name="photo" id="photo" class="file-upload-default" accept="image/png, image/jpg, image/jpeg, image/PNG, image/JPG, image/JPEG">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="charger une image (champ obligatoire)">
                        <span class="input-group-append">
                          <button class="file-upload-browse btn btn-primary" type="button">Charger</button>
                        </span>
                      </div>
                    </div>
                  </div>
                </div><br>
                <div class="row">
                  <div class="col-6">
                    <button type="submit" class="btn btn-primary mr-2 form-control">MODIFIER</button>
                  </div>
                  <div class="col-6">
                    <button type="reset" class="btn btn-light form-control">ANNULER</button>
                  </div>
                </div><br>
                <input type="hidden" name="id_categorie_article_modif" id="id_categorie_article_modif" value="<?php echo htmlspecialchars($_GET['id']) ?>">
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
      var id_categorie_article_modif = <?php echo htmlspecialchars($_GET['id']) ?>;
      var btn_action_modif = 'fetch_single';
      $.ajax({
        url: "categorie_article_fetch_action.php",
        method: "POST",
        data: {
          id_categorie_article_modif: id_categorie_article_modif,
          btn_action_modif: btn_action_modif
        },
        dataType: "json",
        success: function(data) {
          //console.log(data.nom);
          $('#nom_categorie_article_modif').val(data.nom_categorie_article);
          $('#description_categorie_article_modif').val(data.description_categorie_article);
          $('#nombre_article_categorie_article_modif').val(data.nombre_article_categorie_article);
          // $('#superficie_modif').val(data.superficie);
          // $('#notes_modif').val(data.notes);
          //$('.modal-title_modif').text("MODIFIER");
          $('.save-edit-bouton_modif').text("MODIFIER");
          $('#id_categorie_article_modif').val(id_categorie_article_modif);
          $('#btn_action_modif').val("Modifier");

        }
      })
    });


    /* Modifier Submit */

    $(document).on('submit', '#categorie_article_form_modif', function(event) {
      event.preventDefault();
      var form_data = new FormData(this);
      $.ajax({
        url: "categorie_article_fetch_action.php",
        method: "POST",
        enctype: 'multipart/form-data',
        data: form_data,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "json",
        success: function(data) {
          if (data == "Catégorie existante") {
            $('#categorie_articleModal_modif').modal('hide');
            swal('Erreur',
              'Le nom de cette catégorie existe déjà',
              'error');
          }


          if (data == "Modifié") {
            $('#clientModal_modif').modal('hide');
            $(document).ready(function() {
              swal({
                position: "top-end",
                title: "Modification réussie",
                text: "La catégorie a été modifiée avec succès",
                icon: "success",
              }).then(function() {
                window.location.href = "categorie_article.php";
              })
            });
            $('#categorie_articleModal_modif').modal('hide');
          }

          if (data == "Erreur enregistrement image") {
            $('#categorie_articleModal_modif').modal('hide');
            swal('Error',
              data,
              'error');
          }

          if (data == "bad") {
            swal('Erreur', 'Nombre d\'artcles invalide', 'error');
          }

          if (data == "Extension non valide ou image trop volumineuse") {
            $('#categorie_articleModal_modif').modal('hide');
            swal('Error',
              data,
              'error');
          }

          if (data == "Erreur Telechargement") {
            $('#categorie_articleModal_modif').modal('hide');
            swal('Error',
              'Image non soumise',
              'error');
          }

          if (data == "Image non soumise") {
            $('#categorie_articleModal_modif').modal('hide');
            swal('Error',
              data,
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
  </script>

  <script>
    $(window).on("load", function() {
      $(".loader").fadeOut("slow");
    });
  </script>
</body>

</html>