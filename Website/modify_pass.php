<?php

require "auth/EtreAuthentifie.php";

require "admin.php";
$title = 'Modifier le mot de passe des utilisateurs';
include "header.php";
?>


<div class ="container">
<h1 class = "header1">Modifier les mots de passes</h1></br>
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
echo $row['login']; ?></td>
      <td>
<button id=<?php echo $row['uid'] ?> type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modifypass"  onclick="document.getElementById('butid').value=this.id";>Modifier</button>
  </td>
    </tr>
  </tbody>
<?php
}
?>
       </table>
<div id="modifypass" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Modifier mot de passe</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form method = "post" action = "mod_mdp.php">
            <p> Mot de passe : </p>
            <input type="password" name="password"  maxlength ="255"  class="form-control" id="password" required placeholder="password">
            <input type="hidden" name="uid" value='' id='butid'></br>
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