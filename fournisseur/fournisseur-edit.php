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

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à fournisseur edit
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
    <title>Gestion commerciale - Editer fournisseur</title>
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
                        Modifier Fournisseur
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="fournisseur.php">Fournisseurs</a></li>
                            <li class="breadcrumb-item active" aria-current="page">éditer fournisseur</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Modifier les informations d'un fournisseur de la liste</h4>
                  <p class="card-description">
                    Editer les informations d'un fournisseur
                  </p>
                  <form class="forms-sample" method="POST" id="fournisseur_form_modif" enctype="multipart/form-data">
                  <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Nom du fournisseur*</label>
                      <input type="text" name="nom_fournisseur_modif" class="form-control" required id="nom_fournisseur_modif">
                    </div>
                  </div>
                    <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">DG</label>
                      <input type="text" name="dg_fournisseur_modif" class="form-control" id="dg_fournisseur_modif" required placeholder="DG">
                    </div>
                    </div>
                    <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Ville du fournisseur</label>
                      <input type="text" name="ville_fournisseur_modif" class="form-control" id="ville_fournisseur_modif" required placeholder="ville">
                    </div>
                    </div>
                  </div><br>
                  <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                          <label>Pays du fournisseur</label>
                            <input type="text" class="form-control"  name="pays_fournisseur_modif" id="pays_fournisseur_modif" required>
                        </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Téléphone</label>
                      <input type="tel" name="tel_fournisseur_modif" class="form-control" id="tel_fournisseur_modif" placeholder="telephone" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputEmail3">Email</label>
                      <input type="email" name="mail_fournisseur_modif" class="form-control" id="mail_fournisseur_modif" placeholder="Email" required>
                    </div>
                  </div>
                  </div><br>
                  <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="exampleInputName1">Site web fournisseur</label>
                      <input type="text" name="site_web_fournisseur_modif" class="form-control" id="site_web_fournisseur_modif" required placeholder="site web fournisseur">
                    </div>
                  </div>
                  <div class="col-6">
                  <div class="form-group">
                      <label>Photo</label>
                      <input type="file" name="photo_fournisseur_modif" id="photo_fournisseur_modif" class="file-upload-default" accept="image/png, image/jpg, image/jpeg, image/PNG, image/JPG, image/JPEG">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="champ obligatoire">
                        <span class="input-group-append">
                          <button class="file-upload-browse btn btn-primary" type="button">Charger</button>
                        </span>
                      </div>
                    </div>
                    
                  </div>
                  </div>
                    <!-- <div class="form-group">
                      <label>Photo</label>
                      <input type="file" name="photo_fournisseur_modif" class="form-control" required accept="image/png, image/jpg, image/jpeg, image/PNG, image/JPG, image/JPEG">
                    </div> -->
                    <div class="row">
                    <div class="col-12">
                    <div class="form-group">
                      <label for="exampleInputName1">Note</label>
                      <textarea cols="30" rows="10" name="note_fournisseur_modif" id="note_fournisseur_modif" class="form-control"></textarea>
                    </div>
                    <div>
                    <div><br>
                    <div class="row">
                    <div class="col-6">
                    <button type="submit" class="btn btn-primary mr-2 form-control">Modifier</button>
                    </div>
                    <div class="col-6">
                    <button type="reset" class="btn btn-light form-control">Annuler</button>
                    </div>
                    <input type="hidden" name="id_fournisseur_modif" id="id_fournisseur_modif" value="<?php echo htmlspecialchars($_GET['id']) ?>">
                    <input type="hidden" name="btn_action_modif" id="btn_action_modif" value="Modifier">
                    </div><br>
                  </form>
                </div>
              </div>
            </div>
            </div>
        </div> 
        <div><?php include('../parts/footer.php'); ?></div>
    </div>

    <script type="text/javascript">
    /* fetch single */
    $(document).ready(function() {
      var id_fournisseur_modif = <?php echo htmlspecialchars($_GET['id']) ?>;
      //var id_fournisseur_modif = $_GET['id'];
      var btn_action_modif = 'fetch_single';
      $.ajax({
        url: "fournisseur_fetch_action.php",
        method: "POST",
        data: {
          id_fournisseur_modif: id_fournisseur_modif,
          btn_action_modif: btn_action_modif
        },
        dataType: "json",
        success: function(data) {
          //console.log(data.nom_fournisseur);
          $('#nom_fournisseur_modif').val(data.nom_fournisseur);
          $('#dg_fournisseur_modif').val(data.dg_fournisseur);
          $('#ville_fournisseur_modif').val(data.ville_fournisseur);
          $('#pays_fournisseur_modif').val(data.pays_fournisseur);
          $('#tel_fournisseur_modif').val(data.tel_fournisseur);
          $('#mail_fournisseur_modif').val(data.mail_fournisseur);
          $('#site_web_fournisseur_modif').val(data.site_web_fournisseur);
          // $('#photo_fournisseur_modif').val(data.photo_fournisseur);
          $('#note_fournisseur_modif').val(data.note_fournisseur);
          //$('.modal-title_modif').text("MODIFIER");
          $('.save-edit-bouton_modif').text("MODIFIER");
          $('#id_fournisseur_modif').val(id_fournisseur_modif);
          $('#btn_action_modif').val("Modifier");

        }
      })
    });


    /* Modifier Submit */

    $(document).on('submit', '#fournisseur_form_modif', function(event) {
      event.preventDefault();
      var form_data = new FormData(this);
      $.ajax({
        url: "fournisseur_fetch_action.php",
        method: "POST",
        enctype: 'multipart/form-data',
        data: form_data,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "json",
        success: function(data) {
          if (data == "Fournisseur existant") {
            $('#fournisseurModal_modif').modal('hide');
            swal('Erreur',
              'Ce fournisseur existe déjà',
              'error');
          }


          if (data == "Modifié") {
            $('#fournisseurModal_modif').modal('hide');
            $(document).ready(function(){
                                    swal({
                                        position: "top-end",
                                        
                                        title: "Modification réussie",
                                        text: "Le fournisseur a été modifié avec succès",
                                        icon: "success",
                                    }).then(function(){
                                            window.location.href = "fournisseur.php";
                                        })
                                });
            // swal('Effectué',
            //   'Les modifications ont été enregistrées avec succès',
            //   'success');
            //window.location = "fournisseur.php";
          }

          if (data == "Erreur enregistrement image") {
            $('#fournisseurModal_modif').modal('hide');
            swal('Error',
              data,
              'error');
          }

          if (data == "Extension non valide ou image trop volumineuse") {
            $('#fournisseurModal_modif').modal('hide');
            swal('Error',
              data,
              'error');
          }

          if (data == "Erreur Telechargement") {
            $('#fournisseurModal_modif').modal('hide');
            swal('Error',
              'Image non soumise',
              'error');
          }

          if (data == "Image non soumise") {
            $('#fournisseurModal_modif').modal('hide');
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