<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Ajout d'un enseignant";
include "header.php";
?>
<div class="container">
<h1 class = "header1">Ajout d'un enseignant</h1></br>
<div class = "add">
    <form method="post" action="ajout_ens.php" id = "addens">
            <span>Nom : </span>
            <input type="text" name="Nom"  maxlength = "30"  size="20" class="form-control" id="Nom" required placeholder="Nom"></br>
            <span>Prénom : </span>           
            <input type="text" name="Prenom" maxlength = "30"  size="20" class="form-control" id="Prenom" required placeholder="Prenom"></br>
            <span>Numéro de téléphone : </span>
            <input type="text" name="telephone"  maxlength = "15"  size = "20" class="form-control" id="telephone" required placeholder="telephone"></br>
            <span>Email : </span>
            <input type="email" name="email" size="20" maxlength = "30"  class="form-control" id="email" required placeholder="email"></br>
            <span>Type de professeur : </span></br>
            <select  required name = "etid" form = "addens">
            <option selected value =""></option>
            <option  value="1">MCF</option> 
            <option  value="2">PR</option>  
            <option  value="3">ATER</option>  
            <option  value="4">VAC1</option>   
            </select></br></br>
            <span>Utilisateur associé : </span></br>
            <?php $SQL = "Select login, uid from users where role = 'user'";
            $res = $db->query($SQL);?>
            <select required name = "uid" form = "addens">
            <option selected value =""></option>
            <?php
            foreach($res as $row)
            { ?>
                <option  value="<?php echo htmlspecialchars($row['uid']);?>"><?php echo htmlspecialchars($row['login']) ?></option>  <?php
            } ?>
            </select></br></br>
            <input type ="submit" class="btn btn-info btn-lg" name = "check" value = "Confirmer">
    </form>
    </div>
        </div>
<?php
include "footer.php";
?>