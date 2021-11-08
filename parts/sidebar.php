<!-- partial:partials/_sidebar.html -->
<?php
$req = "SELECT photo_user FROM user WHERE email_user = :email_user";
$sta = $connect->prepare($req);
$sta->execute(array(':email_user' => $_SESSION['email_user']));
$res = $sta->fetchColumn();

// $req2 = "SELECT prenom_user FROM user WHERE email_user = :email_user";
// $sta2 = $connect->prepare($req2);
// $sta2->execute(array(':email_user' => $_SESSION['email_user']));
// $res2 = $sta2->fetchColumn();

// $req3 = "SELECT nom_user FROM user WHERE email_user = :email_user";
// $sta3 = $connect->prepare($req3);
// $sta3->execute(array(':email_user' => $_SESSION['email_user']));
// $res3 = $sta3->fetchColumn();

// $req4 = "SELECT type_user FROM user WHERE email_user = :email_user";
// $sta4 = $connect->prepare($req4);
// $sta4->execute(array(':email_user' => $_SESSION['email_user']));
// $res4 = $sta4->fetchColumn();
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <div class="nav-link">
        <div class="profile-image">
          <?php echo'<img src="..'.$res.' " alt="image" />'; ?>
        </div>
        <div class="profile-name">
          <p class="name">Bienvenue</p>
          <?php
          echo '<p class="designation">
            '.$_SESSION['type_user'].'
          </p>';
          ?>
        </div>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../tb/tb_admin.php">
        <i class="fa fa-home menu-icon"></i>
        <span class="menu-title">Tableau de bord</span>
      </a>
    </li>
    <li class="nav-item d-none d-lg-block">
      <a class="nav-link" data-toggle="collapse" href="#sidebar-layouts" aria-expanded="false" aria-controls="sidebar-layouts">
        <i class="fas fa-columns menu-icon"></i>
        <span class="menu-title">Approvisionnement</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="sidebar-layouts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="../categorie_produit/categorie_produit.php">Catégories d'articles</a></li>
          <li class="nav-item"> <a class="nav-link" href="../produit/produit.php">Articles</a></li>
          <li class="nav-item"> <a class="nav-link" href="../stock_commercial/stock_produit.php">Stock d'articles</a></li>
          <!-- <li class="nav-item"> <a class="nav-link" href="#">Achats</a></li> -->
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="far fa-compass menu-icon"></i>
        <span class="menu-title">Tiers</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="../client/client.php">Clients</a></li>
          <li class="nav-item"> <a class="nav-link" href="../fournisseur/fournisseur.php">Fournisseurs</a></li>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-advanced" aria-expanded="false" aria-controls="ui-advanced">
        <i class="fas fa-shopping-cart menu-icon"></i>
        <span class="menu-title">Commercialisation</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-advanced">
        <ul class="nav flex-column sub-menu">
       <!--    <li class="nav-item"> <a class="nav-link" href="../categorie_produit/categorie_produit.php">Catégorie de produits</a></li>
          <li class="nav-item"> <a class="nav-link" href="../produit/produit.php">Produits</a></li>
          <li class="nav-item"> <a class="nav-link" href="../stock_commercial/stock_produit.php">Stock commercial</a></li> -->
          <li class="nav-item"> <a class="nav-link" href="../commandes/commande.php">Commandes</a></li>
          <!-- <li class="nav-item"> <a class="nav-link" href="#">Ventes</a></li> -->
          <li class="nav-item"> <a class="nav-link" href="../factures/facture.php">Factures</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
        <i class="far fa-user-circle menu-icon"></i>
        <span class="menu-title">Utilisateurs</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="form-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="../utilisateurs/user.php">Tous les utilisateurs</a></li>
          <li class="nav-item"><a class="nav-link" href="../utilisateurs/user-add.php">Nouvel utilisateur</a></li>
          <li class="nav-item"><a class="nav-link" href="../utilisateurs/profile.php">Mon profil</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../journaux/journaux.php">
        <i class="fa fa-history menu-icon"></i>
        <span class="menu-title">Journaux</span>
      </a>
    </li>
    <!-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
        <i class="fa fa-cogs menu-icon"></i>
        <span class="menu-title">Paramètres</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="icons">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="#">Paramètres généraux</a></li>
        </ul>
      </div>
    </li> -->
    <!-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#maps" aria-expanded="false" aria-controls="maps">
        <i class="fas fa-qrcode menu-icon"></i>
        <span class="menu-title">Divers</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="maps">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="#">Documentation</a></li>
          <li class="nav-item"> <a class="nav-link" href="#">Contacter le support</a></li>
        </ul>
      </div>
    </li> -->
  </ul>
</nav>