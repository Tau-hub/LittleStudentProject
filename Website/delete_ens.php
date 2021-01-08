<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Supprimer un enseignant";
include "header.php";
?>
<div class="container">
<h1 class = "header1">Supprimer un enseignant</h1></br>
    <?php $SQL = "Select * from enseignants where annee = " . $_SESSION['annee'];
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
      <td><?php echo htmlspecialchars($row['nom']); ?></td>
      <td><?php echo htmlspecialchars($row['prenom']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td><?php echo htmlspecialchars($row['tel']); ?></td>
      <td><?php echo htmlspecialchars($row['annee']); ?></td>
      <td><button id=<?php echo $row['eid'] ?> type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#deleteens"  onclick="document.getElementById('butid').value=this.id";>Supprimer</button>
 </td>

    </tr>
 </tbody>

 <?php }?>
 </table>
 <div id="deleteens" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Êtes-vous sûr(e)?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form method = "post" action = "del_ens.php">
            <input type="hidden" name="eid" value='' id='butid'>
            <input type ="submit" class="btn btn-info btn-lg" name = "checkyes" value = "Confirmer">
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