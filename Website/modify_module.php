<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Modifier un enseignant";
include "header.php";
?>
<script>
function getParam(intitule, mid, cid, code, eid)
{
  document.getElementById('intitule').value = intitule;
  document.getElementById('mid').value = mid;
  document.getElementById('cid').value = cid;
  document.getElementById('code').value = code;
  document.getElementById('eid').value = eid;
}
</script>
<div class="container">
<h1 class = "header1">Modifier un module</h1></br>
    <?php 
    $SQL = "SELECT  modules.mid, modules.intitule, modules.code, modules.eid,  enseignants.nom as ensnom, categories.nom as catnom, modules.cid from modules 
    join enseignants on modules.eid = enseignants.eid 
    join categories on modules.cid = categories.cid where modules.annee = " . $_SESSION['annee'] . " ORDER BY categories.nom";
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
    <td><button id="button_modif" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modifymodule"  onclick="getParam('<?php echo htmlspecialchars(addslashes($row['intitule'])); ?>','<?php echo htmlspecialchars(addslashes($row['mid'])); ?>','<?php echo htmlspecialchars(addslashes($row['cid'])); ?>','<?php echo htmlspecialchars(addslashes($row['code'])); ?>', '<?php echo htmlspecialchars(addslashes($row['eid'])); ?>')">Modifier</button>
  </td></tr>
 </tbody>
 <?php }?>
 </table>
 <div id="modifymodule" class="modal fade" role="dialog" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Modifier un module</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form method = "POST" action = "mod_module.php" id = "modifymodulee">
      
            <input type="hidden" name="mid" id = "mid" value =''  size="20" class="form-control"  required placeholder="mid"></br>
            <span>Intitulé : </span></br>
            <input type="text" name="intitule" required maxlength = "30"  id = "intitule"  value ='' size="20" class="form-control" ></br>
            <span>Code : </span></br>
            <input type="text" name="code" id = "code"  maxlength = "10"  value ='' size="20" class="form-control" required placeholder="code"></br> 
            <span>Proffesseur responsable : </span></br>
            <select id = "eid" name  = "eid">
            <?php $SQL = "Select * from enseignants where annee = " . $_SESSION['annee'];
            $res = $db->query($SQL);
            foreach($res as $row)
            {?>
                 <option  value="<?php echo htmlspecialchars($row['eid']);?>"><?php echo htmlspecialchars($row['nom']) ?></option> 
           <?php } ?>
           </select></br></br>
           <span>Annee : </span></br>
           <select id = "cid" name  = "cid">
            <?php $SQL = "Select * from categories";
            $res = $db->query($SQL);
            foreach($res as $row)
            {?>
                 <option  value="<?php echo htmlspecialchars($row['cid']);?>"><?php echo htmlspecialchars($row['nom']) ?></option> 
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