<!DOCTYPE html>

<html>

<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
 <title><?php echo $title; ?></title>
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 <link rel="stylesheet" href="style.css"/>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>

<div class="nav-side-menu">
    <div class="brand">Gestion de Services</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
   
<?php
if (!isset($_SESSION['annee'])) {
    $_SESSION['annee'] = 2018;
}

if (!isset($_SESSION['anneemin'])) {
    $_SESSION['anneemin'] = 2010;
}

if (!isset($_SESSION['anneemax'])) {
    $_SESSION['anneemax'] = 2020;
}

if (isset($_POST['annee'])) {
    if(is_numeric($_POST['annee']))
    $_SESSION['annee'] = htmlspecialchars($_POST['annee']);
}?>


        <div class="menu-list">
            <?php if ($idm->hasIdentity()) {
            $role = $idm->getRole(); ?>
            <ul id="menu-content" class="menu-content collapse in">

                <li  data-toggle="collapse" data-target="#products" class="collapsed">
                  <a href="#"><i class="fas fa-book-open"></i> Affichage des cours <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="products">
                <?php if ($role== "admin") {?>
     <li><a  href="affichage_cours_groupe.php" title="affichage_cours_groupe">Cours par groupes</a></li>
     <li> <a  href="affichage_cours_module.php" title="affichage_cours_module">Cours par module</a></li>
     <li> <a  href="affichage_cours_noncomplet.php" title="affichage_cours_noncomplet.php">Cours et professeurs non-complets</a></li>
  <?php } else {?>
    <li> <a  href="affichage_cours_enseignant.php" title="affichage_cours_enseignant">Mes cours</a></li>
    <?php }?>
                </ul>



                  <li  data-toggle="collapse" data-target="#users" class="collapsed">
                  <a href="#"><i class="fas fa-address-card"></i><?php if($role == "admin") echo "Gestion des utilisateurs"; else echo "Mon compte"; ?><span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="users">
              <li><a  href="mod_mdp_perso.php" title="mod_mdp_perso">Modifier mon mot de passe</a></li>
  <?php if ($role== "admin") {?>
    <li>   <a  href="liste_uti.php" title="liste_uti">Afficher la liste des utilisateurs</a></li>
    <li><a  href="modify_pass.php" title="mod_mdp">Modification mot de passe des utilisateurs</a></li>
    <li>  <a  href="adduser_form.php" title="ajout_uti">Ajouter un utilisateur</a></li>
    <li>  <a  href="sup_uti.php" title="supp_uti">Supprimer un utilisateur</a></li>
    <li>  <a   href="association_prof.php" title="association_prof">Association professeur</a></li>
            <?php }?>  </ul>


               
            <?php if ($role== "admin") {?>
                  <li  data-toggle="collapse" data-target="#groupes" class="collapsed">
                  <a href="#"><i class="fa fa-users fa-lg"></i> Gestion des groupes <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="groupes">
                <li><a  href="liste_groupe.php" title="liste_groupe">Liste des groupes</a></li>
                <li>  <a href="ajout_groupe.php" title="ajout_groupe">Ajouter des groupes</a></li>
                <li>   <a  href="modify_groupe.php" title="modify_groupe">Modification des groupes</a></li>
                <li>    <a  href="delete_groupe.php" title="delete_groupe">Suppression des groupes</a></li>
            </ul> <?php }?>

                  <?php if ($role== "admin") {?>
                  <li  data-toggle="collapse" data-target="#enseignants" class="collapsed">
                  <a href="#"><i class="fas fa-user-tie"></i> Gestion des enseignants <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="enseignants">
              <li><a href="liste_ens.php" title="list_ens">Liste des enseignants</a></li>
             <li><a  href="ajout_enseignant.php" title="ajout_enseignant">Ajouter des enseignants</a></li>
             <li><a  href="modify_enseignant.php" title="modifier_enseignant">Modification des enseignants</a></li>
             <li><a  href="delete_ens.php" title="delete_ens">Suppression des enseignants</a></li>
            </ul> <?php }?>

            <?php if ($role== "admin") {?>
                  <li  data-toggle="collapse" data-target="#modules" class="collapsed">
                  <a href="#"><i class="fas fa-scroll"></i> Gestion des modules <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="modules">
                <li><a  href="liste_module.php" title="liste_module">Liste des modules</a></li>
                <li><a  href="ajout_module.php" title="ajout_module">Ajouter des modules</a></li>
                <li>  <a  href="modify_module.php" title="modify_module">Modification des modules</a></li>
                <li>  <a  href="delete_module.php" title="delete_module">Suppression des modules</a></li>
            </ul> <?php }?>

            <?php if ($role== "admin") {?>
                  <li  data-toggle="collapse" data-target="#gtypes" class="collapsed">
                  <a href="#"><i class="fas fa-book"></i> Gestion des gtypes <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="gtypes">
               <li> <a  href="liste_gtype.php" title="liste_gtype">Liste des gtypes</a></li>
               <li><a  href="ajout_gtype.php" title="ajout_gtype">Ajouter des gtypes</a></li>
               <li> <a  href="modify_gtype.php" title="modify_gtype">Modification des gtypes</a></li>
               <li> <a  href="delete_gtype.php" title="delete_gtype.php">Suppression des gtypes</a></li>
            </ul> <?php }?>

            
            <?php if ($role== "admin") {?>
                  <li  data-toggle="collapse" data-target="#affectations" class="collapsed">
                  <a href="#"><i class="fas fa-chalkboard-teacher"></i> Affectation des professeurs <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="affectations">
               <li> <a  href="affectation_enseignant.php" title="affectation_enseignant">Ajout/Modification Affectation des enseignants</a></li>
               <li> <a  href="delete_affectations.php" title="delete_affectations">Suppressions des affectations</a></li>
            </ul> <?php }?>

                  <li  data-toggle="collapse" data-target="#annee" class="collapsed">
                  <a href="#"><i class="far fa-clock"></i> Année actuelle : <?php echo $_SESSION['annee'];?> <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="annee">
               <li>Changer d'année : 
               <form method = "POST" id = "form">
              <select class="browser-default custom-select" style = "width : 50%;" name="annee" value =<?php echo $_SESSION['annee']?> onchange="this.form.submit();">   
              <?php  for($i = $_SESSION['anneemin']; $i <= $_SESSION['anneemax'] ; $i++)
              { ?>  
                    <option <?php if($i == $_SESSION['annee']) { echo "selected";}?> value =<?php echo $i?>><?php echo $i?></option>
          <?php } ?>
         </select></br>
                </form>

               </li>
            </ul> 

            <?php if ($role== "admin") {?>
              <li  data-toggle="collapse" data-target="#anneecpy" class="collapsed">
                  <a href="#"><i class="fas fa-hourglass-half"></i> Copier l'année : <?php echo $_SESSION['annee'];?> <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="anneecpy">
               <li>Sur l'année : 
                <form method = "POST" action ="copie_annee.php">
                  <select class="browser-default custom-select" style = "width : 50%;" name="anneedes" value =<?php echo $_SESSION['annee']?> onchange="this.form.submit();">
                  <option selected value = ""></option>
                    <?php  for($i = $_SESSION['anneemin']; $i <= $_SESSION['anneemax'] ; $i++)
                      { ?>
                  <?php if($i != $_SESSION['annee'])  {
                             ?> 
                      <option value =<?php echo $i?> > <?php echo $i?> </option> 
                      <?php  } ?>
                      <?php }
                        ?>
                    </select></br>
 
                      </form>

               </li>
            </ul> 
            <?php } ?>

                  <li>
                  <a href="<?php echo $pathFor['logout'] ?>">
                  <i class="fas fa-sign-out-alt"></i> Déconnexion </a>
                  </li>

                </ul>
                <?php } else {?>

                  <li>
                  <a href="<?php echo $pathFor['login'] ?>">
                  <i class="fas fa-sign-in-alt"></i> Page de connexion </a>
                  </li>
                  <?php }?>
                  </ul>
     </div>

</div>

</head>


<body>
