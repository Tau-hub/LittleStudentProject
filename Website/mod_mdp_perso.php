<?php

require "auth/EtreAuthentifie.php";
$title = 'Modifier votre mot de passe';
include "header.php";
?>

<div class="container">
  <h1 class = "header1">Modifier votre mot de passe</h1>
  <form method = "post" action ="mod_mdp.php">
      <input type="hidden" name ="uid" value = <?php echo $idm->getUid(); ?> />
      <p> Mot de passe : </p>
      <input type="password" name="password" maxlength ="255"  class="form-control" id="password" required placeholder="Password"></br>
      <input type ="submit" class="btn btn-info btn-lg" name = "check" value = "Confirmer">
      </form>
</div>
</div>
<?php
include "footer.php";
?>