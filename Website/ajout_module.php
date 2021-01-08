<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Ajout d'un module";
include "header.php";
?>
<div class="container">
<h1 class = "header1">Ajout d'un module</h1></br>
<div class = "add">
    <form method="post" action="ajout_mod.php" id = "addmodule">
            <span>Intitulé  : </span>
            <input type="text" maxlength = "30"  name="intitule" size="20" class="form-control"  required placeholder="Nom"></br>
            <span>Code : </span>
            <input type="text" name="code"  maxlength = "10"  size="20" class="form-control" required placeholder="Code"></br>
            <?php $SQL = "Select  * from enseignants where annee = " . $_SESSION['annee'];
            $res = $db->query($SQL);?>
             <span>Enseignants responsable : </span></br>
            <select  required name = "eid" form = "addmodule">
            <option selected value =""></option>
            <?php 
            foreach($res as $row)
            {?>
                <option value ="<?php echo htmlspecialchars($row['eid']);?>"><?php echo htmlspecialchars($row['nom']) . " " . htmlspecialchars($row['prenom']);  ?></option>  
            <?php } ?>
            </select></br></br>
            <span>Année :</span></br>
            <?php $SQL = "Select * from categories";
            $res = $db->query($SQL);?>
            <select required name = "cid" form = "addmodule">
            <option selected value =""></option>
            <?php
            foreach($res as $row)
            { ?>
                <option  value="<?php echo htmlspecialchars($row['cid']);?>"><?php echo htmlspecialchars($row['nom']) ?></option>  <?php
            } ?>
            </select></br></br>
            <input type ="submit" class="btn btn-info btn-lg" name = "check" value = "Confirmer">
    </form>
    </div>
        </div>
<?php
include "footer.php";
?>