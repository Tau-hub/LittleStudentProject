<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Modifier un enseignant";
include "header.php";
?>
<script>
function getParam(ensnom, prenom, tel, email, etid, uid)
{
  document.getElementById('uid').value = uid;
  document.getElementById('tel').value = tel;
  document.getElementById('email').value = email;
  document.getElementById('etid').value = etid;
  document.getElementById('ensnom').value = ensnom;
  document.getElementById('prenom').value = prenom;

}
</script>
<div class="container">
<h1 class = "header1">Modifier un enseignant</h1></br>
    <?php $SQL = "SELECT  enseignants.uid, enseignants.nom as ensnom, prenom, prenom, annee, tel, email, enseignants.etid, enseignants.eid from enseignants where annee = " . $_SESSION['annee'] . " ORDER BY enseignants.nom";

$res = $db->query($SQL);?>
    <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prenom</th>
                <th scope="col">Email</th>
                <th scope="col">Telephone</th>
                <th scope="col">Annee</th>
                <th scope="col"></th>
            </tr>
        </thead>
<?php
foreach ($res as $row) {
    ?>
  <tbody>
    <tr>
      <td><?php echo htmlspecialchars($row['ensnom']); ?></td>
      <td><?php echo htmlspecialchars($row['prenom']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td><?php echo htmlspecialchars($row['tel']); ?></td>
      <td><?php echo htmlspecialchars($row['annee']); ?></td>
      <td><button id="button_modif" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modifyens"  onclick="getParam('<?php echo htmlspecialchars(addslashes($row['ensnom'])); ?>', '<?php echo htmlspecialchars(addslashes($row['prenom'])); ?>', '<?php echo htmlspecialchars(addslashes($row['tel'])); ?>', '<?php echo htmlspecialchars(addslashes($row['email'])); ?>','<?php echo htmlspecialchars(addslashes($row['etid'])); ?>', '<?php echo htmlspecialchars(addslashes($row['uid'])); ?>')">Modifier</button>
  </td></tr>
 </tbody>
 <?php }?>
 </table>
 <div id="modifyens" class="modal fade" role="dialog" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Modifier un enseignant</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form method = "POST" action = "mod_ens.php" id = "modifens">
            <input type ="hidden" name = "uid" value = '' id = 'uid'>
            <span>Nom : </span>
            <input type="text" name="Nom" maxlength = "30"  id = "ensnom" value =''  size="20" class="form-control"  required placeholder="Nom"></br>
            <span>Prénom : </span>
            <input type="text" name="Prenom" maxlength = "30"  id = "prenom"  value ='' size="20" class="form-control" required placeholder="Prenom"></br>
            <span>Numéro de téléphone :  </span>
            <input type="text" name="telephone" id ="tel" maxlength = "15"  value =''  size = "20" class="form-control"  required placeholder="telephone"></br>
            <span>Email : </span>
            <input type="email" name="email" maxlength = "30"  size="20"  id="email" value ='' class="form-control"  required placeholder="email"></br>
            <span>Type de professeur : </span></br>
            <select  id = 'etid' name = "etid" form = "modifens">
            <option  value="1">MCF</option>
            <option  value ="2">PR</option>
            <option  value="3">ATER</option>
            <option  value="4">VAC1</option>
            </select></br></br>
            <input type ="submit" class="btn btn-info btn-lg" name= "Valider" value = "Confirmer">
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
 </div>
</div>
 <?php

include "footer.php";
?>