<?php

require "auth/EtreAuthentifie.php";
$title = "Association des professeur";
include "header.php";
?>

<div class ="container">
<h1 class = "header1">Association d'un professeur</h1></br>
<?php
$SQL = "SELECT * from users WHERE role = 'user'";
$res = $db->query($SQL);
?>
       <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Login</th>
                <th scope="col"></th>
            </tr>
        </thead>
<?php
foreach ($res as $row) {
    ?>
  <tbody>
    <tr>
      <td><?php
echo htmlspecialchars($row['login']);
    ?></td>
      <td>
<button id="<?php echo $row['uid'] ?>" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#assocprof"  onclick="document.getElementById('butid').value=this.id";>Association professeur à un utilisateur</button>
  </td>
    </tr>
  </tbody>
<?php
}
?>
       </table>
       <div id="assocprof" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Association professeur à un utilisateur</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form method = "post" action = "assoc_utilisateur.php" id = "assoc">
       <input type="hidden" name="uid" value='' id='butid'></br>
       <p><?php $res = $db->query("Select * from enseignants");
?> <select required class = "form-control" name ="Professeur" form = "assoc">
       <?php
foreach ($res as $row) {?>
        <option  value="<?php echo htmlspecialchars($row['eid']); ?>"><?php echo htmlspecialchars($row['nom']) . " " . htmlspecialchars($row['prenom']) . " Annee : " . $row['annee']; ?></option>  <?php
}?>
       </select></br>
       <input type ="submit" class="btn btn-info btn-lg" name = "check" value = "Confirmer">
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