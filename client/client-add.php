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


// Log
// switch ($_SESSION['type_user']) {

//   case 1:
//     addlog("Cons-01-client", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
//   case 2:
//     addlog("Cons-02-client", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
//   case 3:
//     addlog("Cons-03-client", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
//   case 4:
//     addlog("Cons-04-client", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
//   case 5:
//     addlog("Cons-05-cliente", "", $_SESSION["prenom_user"] . " " . $_SESSION["nom_user"]);
//     break;
// }


?>




<!DOCTYPE html>
<html lang="en">


<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Gestion commerciale - Ajouter client</title>
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
            Ajouter Clients
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="../tb/tb_admin.php">Tableau de bord</a></li>
              <li class="breadcrumb-item active" aria-current="page"><a href="client.php">Clients</a></li>
              <li class="breadcrumb-item active" aria-current="page">Ajouter un client</li>
            </ol>
          </nav>
        </div>
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Ajouter un client dans la liste</h4>
              <p class="card-description">
                Ajout des informations essentielles d'un client
              </p>
              <form class="forms-sample" method="POST" id="client_add" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Nom / Société du client*</label>
                      <input type="text" name="nom_client" class="form-control" id="exampleInputName1" placeholder="Nom" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">DG</label>
                      <input type="text" name="dg_client" class="form-control" id="exampleInputName1" placeholder="DG" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Ville du client</label>
                      <input type="text" name="ville_client" class="form-control" id="exampleInputName1" placeholder="ville" required>
                    </div>
                  </div>
                </div><br>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label>Pays</label>
                     <input type="text" name="pays_client" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Téléphone</label>
                      <input type="tel" name="tel_client" class="form-control" id="exampleInputName1" placeholder="Numéro de téléphone" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Adresse</label>
                      <input type="text" name="adresse_client" class="form-control" id="exampleInputName1" placeholder="adresse" required>
                    </div>
                  </div>
                </div><br>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputEmail3">Email</label>
                      <input type="email" name="mail_client" class="form-control" id="exampleInputEmail3" placeholder="Email" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="exampleInputName1">Site web client</label>
                      <input type="text" name="site_web_client" class="form-control" id="exampleInputName1" placeholder="site web client" required>
                    </div>
                  </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label>Photo</label>
                        <input type="file" name="photo_client" class="file-upload-default" required accept="image/png, image/jpg, image/jpeg, image/PNG, image/JPG, image/JPEG">
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
                      <textarea name="note_client" id="exampleTextarea1" cols="30" rows="10" class="form-control" required></textarea>
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
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php include('../parts/footer.php'); ?>
    </div>
  </div>


<script>
            $(document).on('submit','#client_add', function(event){
              event.preventDefault();
              var form_data = new FormData(this);
              $.ajax({
                url:"client_fetch_action.php",
                type:"POST",
                enctype: 'multipart/form-data',
                data:form_data,
                processData: false,
                contentType: false,
                cache: false,
                dataType:"json",
                success:function(data)
                {
                      if(data == "Client existant") {
                        $('#client_add')[0].reset();
                        swal('Erreur',
                           'Le nom de ce client existe déjà',
                           'error');
                      }


                      if(data == "Success") {
                        // swal('Effectué',
                        //   'Le client a été enregistré avec succès',
                        //   'success');
                        // window.location = "client.php";
                        $(document).ready(function(){
                                    swal({
                                        position: "top-end",
                                        
                                        title: "Enregistrement réussi",
                                        text: "Le client a été enregistré avec succès",
                                        icon: "success",
                                    }).then(function(){
                                            window.location.href = "client.php";
                                        })
                                });
                      }

                      if(data == "Formulaire invalide") {
                          swal('Erreur', data, 'error');
                      }

                      if(data == "error")
                      {
                        swal('ERREUR', 'L\'enregistrement a échoué', 'error');
                      }

                      if(data == "Erreur enregistrement image") {
                        $('#enclosModal_modif').modal('hide');
                        swal('Error',
                          data,
                          'error');
                      }

                      if(data == "Extension non valide ou image trop volumineuse") {
                        $('#enclosModal_modif').modal('hide');
                        swal('Error',
                          data,
                          'error');
                      }

                      if(data == "Erreur Telechargement") {
                        $('#enclosModal_modif').modal('hide');
                        swal('Error',
                          data,
                          'error');
                      }

                      if(data == "Image non soumise") {
                        $('#enclosModal_modif').modal('hide');
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