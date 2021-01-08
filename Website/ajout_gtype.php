<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Ajout d'un gtype";
include "header.php";
?>
<div class="container">
<h1 class = "header1">Ajout d'un gtype</h1></br>
<div class = "add">
    <form lang = "en" method="post" action="ajout_gt.php">
            <span>Nom :</span></br>
            <input type="text" maxlength = "30"  name="nom" size="20" class="form-control"  required placeholder="nom"></br>
            <span>Nombre d'heures : </span></br>
            <input type="number" name="nbh" size="20" class="form-control" required placeholder="nbh"></br>
            <span>Coefficient : </span></br>
            <input type="number" step = "0.01" name="coeff" size="20" class="form-control" required placeholder="coeff"></br>
            <input   type ="submit" class="btn btn-info btn-lg" name = "check" value = "Confirmer">
    </form>
    </div>
</div>
<?php
include "footer.php";
?>