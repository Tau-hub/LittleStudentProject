<?php

require "auth/EtreAuthentifie.php";
$title = 'Affichage de vos cours';
include "header.php";
require "admin.php";
?>

<script>
function getParam(gid, eid)
{
  document.getElementById('gid').value = gid;
  document.getElementById('eid').value = eid;
}
</script>
<div class = "container">
<h1 class = "header1">Supprimer une affectation</h1></br>
<?php

$SQL = "SELECT  enseignants.nom as ensnom, groupes.gid, groupes.gtid, enseignants.eid,  groupes.nom, intitule, affectations.nbh, gtypes.nbh as nbhgtypes  from users join enseignants on users.uid=enseignants.uid
 join affectations on enseignants.eid = affectations.eid
 RIGHT OUTER join groupes on affectations.gid = groupes.gid
 join modules on groupes.mid = modules.mid
 join gtypes on groupes.gtid = gtypes.gtid  WHERE   groupes.annee = " . $_SESSION['annee'] . " ORDER BY groupes.gid ASC";
$res = $db->query($SQL);
if ($res->rowCount() == 0) {
    echo "</br></br>Pas de cours dans cette année</br>";
} else { ?>
   
    
   <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Enseignants</th>
                 <th scope="col">Module</th>
                 <th scope="col">Numéro</th>
                 <th scope="col">Groupes</th>
                 <th scope="col">Heures affectées</th>
                 <th scope="col"></th>
            </tr>
        </thead>
<?php   
    foreach ($res as $row) {
        if ($row['nbh'] > 0) {
           ?> 
  <tbody>
    <tr>
      <td><?php echo htmlspecialchars($row['ensnom']); ?></td>
      <td><?php echo htmlspecialchars($row['intitule']); ?></td>
      <td><?php echo htmlspecialchars($row['gid']); ?> </td>
      <td><?php echo htmlspecialchars($row['nom']); ?> </td>
      <td><?php echo htmlspecialchars($row['nbh']); ?></td>
      <td><button id="buttonmodal" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#deletegtype"  onclick="getParam('<?php echo htmlspecialchars($row['gid']); ?>','<?php echo htmlspecialchars($row['eid']); ?>')";>Supprimer</button></td>
    </tr>
  </tbody>
    <?php } ?>

<?php
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
       <form method = "post" action = "del_affectation.php">
            <input type="hidden" name="gid" value='' id='gid'>
            <input type="hidden" name="eid" value='' id='eid'>
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

}
include "footer.php";
?>