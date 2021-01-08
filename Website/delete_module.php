<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Supprimer un groupe";
include "header.php";
?>
<div class="container">
<h1 class = "header1">Supprimer un module</h1></br>
    <?php $SQL = "SELECT  modules.mid, modules.intitule, modules.code, modules.eid,  enseignants.nom as ensnom, categories.nom as catnom, modules.cid from modules 
    join enseignants on modules.eid = enseignants.eid 
    join categories on modules.cid = categories.cid where modules.annee = " . $_SESSION['annee']  . " ORDER BY categories.nom";
$res = $db->query($SQL);?>
    <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
            <th scope="col">Année de licence</th>
                <th scope="col">Intitule</th>
                <th scope="col">Code</th>
                <th scope="col">Enseignant Responsable</th>
                <th scope="col"></th>
            </tr>
        </thead>
<?php
foreach ($res as $row) {
    ?>
  <tbody>
    <tr>
    <td><?php echo htmlspecialchars($row['catnom']); ?></td>
    <td><?php echo htmlspecialchars($row['intitule']); ?></td>
    <td><?php echo htmlspecialchars($row['code']); ?></td>
    <td><?php echo htmlspecialchars($row['ensnom']); ?></td>
      <td><button id=<?php echo $row['mid'] ?> type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#deletemod"  onclick="document.getElementById('butid').value=this.id";>Supprimer</button>
 </td>

    </tr>
 </tbody>

 <?php }?>
 </table>
 <div id="deletemod" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Êtes-vous sûr(e)?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form method = "post" action = "del_module.php">
            <input type="hidden" name="mid" value='' id='butid'>
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