<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Ajout d'un groupe";
include "header.php";
?>
<div class="container">
<h1 class = "header1">Ajout d'un groupe</h1></br>
<div class = "add">
    <form method="post" action="ajout_gp.php" id = "addgroupe">
            <span>Nom du groupe : </span></br>
            <input type="text" name="Nom" maxlength = "30" size="20" class="form-control" id="Nom" required placeholder="Nom"></br>
            <?php $SQL = "Select  * from modules where annee = " . $_SESSION['annee'];
            $res = $db->query($SQL);?>
             <span>Module : </span></br>
            <select  required name = "mid" form = "addgroupe">
            <option selected value =""></option>
            <?php 
            foreach($res as $row)
            {?>
                <option value ="<?php echo htmlspecialchars($row['mid']);?>"><?php echo htmlspecialchars($row['intitule']) ?></option>  
            <?php } ?>
            </select></br></br>
            <span>Type de module :  </span></br>
            <?php $SQL = "Select * from gtypes";
            $res = $db->query($SQL);?>
            <select required name = "gtid" form = "addgroupe">
            <option selected value =""></option>
            <?php
            foreach($res as $row)
            { ?>
                <option  value="<?php echo htmlspecialchars($row['gtid']);?>"><?php echo htmlspecialchars($row['nom']) ?></option>  <?php
            } ?>
            </select></br></br>
            <input type ="submit" class="btn btn-info btn-lg" name = "check" value = "Confirmer">
    </form>
    </div>
        </div>
<?php
include "footer.php";
?>