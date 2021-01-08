<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Modifier un groupe";
include "header.php";
?>
<script>
function getParam(gid, mid, nom, gtid)
{
 document.getElementById('mid').value = mid;
  document.getElementById('gid').value = gid;
  document.getElementById('nom').value = nom;
  document.getElementById('gtid').value = gtid;
}
</script>
<div class="container">
<h1 class = "header1">Modifier un groupe</h1></br>
    <?php $SQL = "Select intitule, groupes.gtid, groupes.nom, groupes.mid, groupes.gid from groupes join 
    gtypes on groupes.gtid=gtypes.gtid join modules on groupes.mid=modules.mid where groupes.annee = " . $_SESSION['annee'];

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

      <td><button id="button_modif" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modifygroup"  onclick="getParam('<?php echo htmlspecialchars(addslashes($row['gid'])); ?>',
      '<?php echo htmlspecialchars(addslashes($row['mid'])); ?>', '<?php echo htmlspecialchars(addslashes($row['nom'])); ?>', '<?php echo htmlspecialchars(addslashes($row['gtid'])); ?>')">Modifier</button>
  </td></tr>
 </tbody>
 <?php }?>
 </table>
 <div id="modifygroup" class="modal fade" role="dialog" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Modifier un groupe</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form method = "POST" action = "mod_group.php" id = "modifgroupe">
       <span>Nom du groupe : </span>
            <input type="hidden" name="gid" id = "gid" value =''  size="20" class="form-control"  required placeholder="gid"></br>
            <input type="text" maxlength = "30"  name="nom" id = "nom"  value ='' size="20" class="form-control" required placeholder="nom"></br>
            <?php  $SQL = "Select * from modules where annee =  " . $_SESSION['annee'];
            $res = $db->query($SQL); ?> 
            <span>Module : </span></br>
            <select selected id = 'mid' value = '' name = "mid" form = "modifgroupe">
            <?php 
            foreach($res as $row)
            {?>
                <option  value="<?php echo htmlspecialchars($row['mid']);?>"><?php echo htmlspecialchars($row['intitule']) ?></option> 
            <?php } ?>
            </select></br></br>
            <?php  $SQL = "Select * from gtypes";
            $res = $db->query($SQL); ?> 
            <span>Type de Module : </span></br>
            <select selected id = 'gtid' value = '' name = "gtid" form = "modifgroupe">
            <?php 
            foreach($res as $row)
            {?>
                <option  value="<?php echo htmlspecialchars($row['gtid']);?>"><?php echo htmlspecialchars($row['nom']) ?></option> 
            <?php } ?>
            
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