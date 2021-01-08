<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Supprimer un groupe";
include "header.php";
?>
<div class="container">
<h1 class = "header1">Supprimer un groupe</h1></br>
    <?php $SQL = "SELECT * from groupes join modules on groupes.mid = modules.mid where groupes.annee = " . $_SESSION['annee'];
$res = $db->query($SQL);?>
    <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
            <th scope="col">Numero de groupe</th>
                <th scope="col">Module associé</th>
                <th scope="col">Type du module</th>
                <th scope="col"></th>
            </tr>
        </thead>
<?php
foreach ($res as $row) {
    ?>
  <tbody>
    <tr>
    <td><?php echo htmlspecialchars($row['gid']); ?></td>
    <td><?php echo htmlspecialchars($row['intitule']); ?></td>
    <td><?php echo htmlspecialchars($row['nom']); ?></td>
      <td><button id=<?php echo $row['gid'] ?> type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#deletegr"  onclick="document.getElementById('butid').value=this.id";>Supprimer</button>
 </td>

    </tr>
 </tbody>

 <?php }?>
 </table>
 <div id="deletegr" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Êtes-vous sûr(e)?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form method = "post" action = "del_groupe.php">
            <input type="hidden" name="gid" value='' id='butid'>
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