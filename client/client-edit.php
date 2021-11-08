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
  header('location:../connexion.php');
}

// Renvoie au tableau de bord si l'utilisateur n'a pas accès à carac_chambre
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
  <title>Gestion commerciale - Editer clients</title>
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
            Modifier Clients
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
              <li class="breadcrumb-item active" aria-current="page"><a href="client.php">Clients</a></li>
              <li class="breadcrumb-item active" aria-current="page">éditer client</li>
            </ol>
          </nav>
        </div>
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Modifier les informations d'un client de la liste</h4>
              <p class="card-description">
                Editer les informations d'un client
              </p>
              <form class="forms-sample" method="POST" id="client_form_modif" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Nom / Société du client*</label>
                      <input type="text" name="nom_client_modif" class="form-control" required id="nom_client_modif" placeholder="Nom">
                    </div>


                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">DG</label>
                      <input type="text" name="dg_client_modif" id="dg_client_modif" class="form-control" placeholder="DG" required>
                    </div>

                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Ville du client</label>
                      <input type="text" name="ville_client_modif" class="form-control" id="ville_client_modif" placeholder="Ville du client" required>
                    </div>
                  </div>
                </div><br>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label>Pays</label>
                      <input type="text" class="form-control" name="pays_client_modif" id="pays_client_modif" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Téléphone</label>
                      <input type="tel" name="tel_client_modif" id="tel_client_modif" class="form-control" placeholder="Numéro de téléphone" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Adresse</label>
                      <input type="text" name="adresse_client_modif" class="form-control" id="adresse_client_modif" placeholder="Adresse du client" required>
                    </div>
                  </div>
                </div><br>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputEmail3">Email</label>
                      <input type="email" name="mail_client_modif" class="form-control" id="mail_client_modif" placeholder="Email du client" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Site web client</label>
                      <input type="text" name="site_web_client_modif" class="form-control" id="site_web_client_modif" placeholder="Site web client" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Photo</label>
                      <input type="file" name="photo" id="photo" class="file-upload-default" accept="image/png, image/jpg, image/jpeg, image/PNG, image/JPG, image/JPEG">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="(champ obligatoire)">
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
                      <textarea name="note_client_modif" id="note_client_modif" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <button type="submit" class="btn btn-primary mr-2 form-control">Modifier</button>
                  </div>
                  <div class="col-6">
                    <button type="reset" class="btn btn-light form-control">Annuler</button>
                  </div>
                  <input type="hidden" name="id_client_modif" id="id_client_modif" value="<?php echo htmlspecialchars($_GET['id']) ?>">
                  <input type="hidden" name="btn_action_modif" id="btn_action_modif" value="Modifier">

                </div>

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
      var id_client_modif = <?php echo htmlspecialchars($_GET['id']) ?>;
      //var id_client_modif = $_GET['id'];
      var btn_action_modif = 'fetch_single';
      $.ajax({
        url: "client_fetch_action.php",
        method: "POST",
        data: {
          id_client_modif: id_client_modif,
          btn_action_modif: btn_action_modif
        },
        dataType: "json",
        success: function(data) {
          //console.log(data.nom_client);
          $('#nom_client_modif').val(data.nom_client);
          $('#dg_client_modif').val(data.dg_client);
          $('#ville_client_modif').val(data.ville_client);
          $('#pays_client_modif').val(data.pays_client);
          $('#tel_client_modif').val(data.tel_client);
          $('#adresse_client_modif').val(data.adresse_client);
          $('#mail_client_modif').val(data.mail_client);
          $('#site_web_client_modif').val(data.site_web_client);
          //$('#nombre_comm_client_modif').val(data.nombre_comm_client);
          //$('#photo_client_modif').val(data.photo_client);
          $('#note_client_modif').val(data.note_client);
          $('.modal-title_modif').text("MODIFIER");
          $('.save-edit-bouton_modif').text("MODIFIER");
          $('#id_client_modif').val(id_client_modif);
          $('#btn_action_modif').val("Modifier");

        }
      })
    });


    /* Modifier Submit */

    $(document).on('submit', '#client_form_modif', function(event) {
      event.preventDefault();
      var form_data = new FormData(this);
      $.ajax({
        url: "client_fetch_action.php",
        method: "POST",
        enctype: 'multipart/form-data',
        data: form_data,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "json",
        success: function(data) {
          if (data == "Client existant") {
            $('#clientModal_modif').modal('hide');
            swal('Erreur',
              'Ce client existe déjà',
              'error');
          }


          if (data == "Modifié") {
            $('#clientModal_modif').modal('hide');
            $(document).ready(function() {
              swal({
                position: "top-end",

                title: "Modification réussie",
                text: "Le client a été modifié avec succès",
                icon: "success",
              }).then(function() {
                window.location.href = "client.php";
              })
            });
            // swal('Effectué',
            //   'Les modifications ont été enregistrées avec succès',
            //   'success');
            //window.location = "client.php";
          }

          if (data == "Erreur enregistrement image") {
            $('#clientModal_modif').modal('hide');
            swal('Error',
              data,
              'error');
          }

          if (data == "Extension non valide ou image trop volumineuse") {
            $('#clientModal_modif').modal('hide');
            swal('Error',
              data,
              'error');
          }

          if (data == "Erreur Telechargement") {
            $('#clientModal_modif').modal('hide');
            swal('Error',
              'Image non soumise',
              'error');
          }

          if (data == "Image non soumise") {
            $('#clientModal_modif').modal('hide');
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
    <script>
        $(window).on("load", function() {
            $(".loader").fadeOut("slow");
        });
    </script>
</body>

</html>