<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Supprimer un gtype";
include "header.php";
?>
<div class="container">
<h1 class = "header1">Supprimer un gtype</h1></br>
    <?php $SQL = "SELECT  * from gtypes ";
$res = $db->query($SQL);?>
    <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
            <th scope="col">Nom</th>
                <th scope="col">Nombre d'heures</th>
                <th scope="col">Coefficient</th>
                <th scope="col"></th>
            </tr>
        </thead>
<?php
foreach ($res as $row) {
    $SQL2 = "Select * from groupes  where gtid = ? ";
    $res2 = $db->prepare($SQL2);
    $res2->execute(array($row['gtid']));
    if ($res2->rowCount() == 0) {?>
  <tbody>
    <tr>
    <td><?php echo htmlspecialchars($row['nom']); ?></td>
    <td><?php echo htmlspecialchars($row['nbh']); ?></td>
    <td><?php echo htmlspecialchars($row['coeff']); ?></td>
      <td><button id=<?php echo $row['gtid'] ?> type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#deletegtype"  onclick="document.getElementById('butid').value=this.id";>Supprimer</button>
 </td>

    </tr>
 </tbody>
 <?php }
}?>
 </table>
 <div id="deletegtype" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Êtes-vous sûr(e)?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form method = "post" action = "del_gt.php">
            <input type="hidden" name="gtid" value='' id='butid'>
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