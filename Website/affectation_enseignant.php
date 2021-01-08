<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = 'Affectation des professeurs';
include "header.php";
?>
<div  class="container">
  <h1 class ="header1">Affectation des professeurs</h1></br>
<script>
function getParam(gid, nbh, nbhgtypes,ancienh, ancieneid)
{
  document.getElementById('gid').value = gid;
  document.getElementById('gid2').value = gid;
  document.getElementById('nbh').value = nbh;
  document.getElementById('nbh2').value = nbh;
  document.getElementById('nbhgtypes').value = nbhgtypes;
  document.getElementById('nbhgtypes2').value = nbhgtypes;
  document.getElementById('ancienh').value = ancienh;
  document.getElementById('ancieneid').value = ancieneid;
}
</script>



<?php 

$SQL = "SELECT  groupes.gid, groupes.gtid, groupes.nom, enseignants.nom as ensnom, enseignants.eid, intitule, affectations.nbh, gtypes.nbh as nbhgtypes  from users join enseignants on users.uid=enseignants.uid
 join affectations on enseignants.eid = affectations.eid
 RIGHT OUTER join groupes on affectations.gid = groupes.gid
 join modules on groupes.mid = modules.mid
 join gtypes on groupes.gtid = gtypes.gtid  WHERE   groupes.annee = " . $_SESSION['annee'] . " ORDER BY groupes.gid, intitule DESC";
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
                 <th scope = "col">Heures restantes</th>
                 <th scope = "col"></th>
                 <th scope = "col"></th>
            </tr>
        </thead>
<?php   
    foreach ($res as $row) {
        $SQL =  "SELECT affectations.gid, SUM(nbh) from affectations join groupes on affectations.gid = groupes.gid where affectations.gid = ? GROUP BY affectations.gid";
        $res2 = $db->prepare($SQL);
        $res2->execute(array(htmlspecialchars($row['gid'])));
        $data = $res2->fetch();
        $heurerestantes = $row['nbhgtypes'] - $data['SUM(nbh)'];
           ?> 
  <tbody>
    <tr>
    <td><?php echo htmlspecialchars($row['ensnom']); ?></td>
      <td><?php echo htmlspecialchars($row['intitule']); ?></td>
      <td><?php echo htmlspecialchars($row['gid']); ?></td>
      <td><?php echo htmlspecialchars($row['nom']); ?> </td>
      <td><?php echo htmlspecialchars($row['nbh']); ?></td>
      <td><?php if ($heurerestantes > 0) {
            echo $heurerestantes;
        } else {
            echo "0";
        } ?> </td>
     <?php 
  ?>
      <?php if($heurerestantes  > 0 ){ ?>
           <td><button id="button_affecter" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#affectation"  onclick="getParam('<?php echo htmlspecialchars(addslashes($row['gid'])); ?>','<?php echo $heurerestantes ?>', '<?php echo htmlspecialchars(addslashes($row['nbhgtypes'])); ?>','<?php echo htmlspecialchars(addslashes($row['nbh'])) ?>','<?php echo $row['eid'] ?>')">Nouvelle affectation</button>
      <?php } else { ?>
        <td></td>
      <?php } ?>
   <?php  if ($heurerestantes != $row['nbhgtypes']) {  ?>
       <td><button id="button_modifier" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modification"  onclick="getParam('<?php echo htmlspecialchars(addslashes($row['gid'])); ?>','<?php echo $heurerestantes+$row['nbh'] ?>','<?php echo htmlspecialchars(addslashes($row['nbhgtypes'])) ?>','<?php echo htmlspecialchars(addslashes($row['nbh'])) ?>','<?php echo $row['eid'] ?>')">Modification</button>
       </td> <?php } ?> 
     
    </tr>
  </tbody>

<?php
}?>
        </table>
        <div class="modal fade" id="affectation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" >
      <h4 class="modal-title">Ajouter une affectation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form  lang = "en" method = "POST" action = "ajout_affectation_ens.php" id ="addaffec" >
            <input type="hidden" name="gid" id = "gid"  value = ''  class="form-control"  required placeholder="gtid">
            <input type="hidden" name="nbhgtypes" id = "nbhgtypes"  value = ''  class="form-control"  required placeholder="nbhgtypes">
            <span>Heures affectées : </span></br>
            <input type="number" name="nbh" maxlength ="3" id = "nbh" value = '' size="3" class="form-control" required placeholder="Heures"></br>
            <span>Professeur affecté(e) : </span></br>
            <select required name = "eid" form =  "addaffec">
            <option selected value =""></option>
            <?php $SQL = "SELECT  * from enseignants where annee = " . $_SESSION['annee'];
             $res = $db->query($SQL);
            foreach($res as $row)
            {?>
             <option value =<?php echo htmlspecialchars($row['eid']); ?>><?php echo htmlspecialchars($row['nom']) ?></option>
            <?php }?>
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
 <div id="modification" class="modal fade" role="dialog" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Modifier une affectation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form  lang = "en" method = "POST" action = "mod_affectation_ens.php" id ="modifaffec" >
            <input type="hidden" name="gid" id = "gid2"  value = '' size="20" class="form-control"  required placeholder="gid">
            <input type="hidden" name="ancienh" id = "ancienh"  value = '' size="20" class="form-control"  required placeholder="ancienh">
            <input type="hidden" name="ancieneid" id = "ancieneid"  value = '' size="20" class="form-control"  required placeholder="ancieneid">
            <input type="hidden" name="nbhgtypes" id = "nbhgtypes2"  value = ''  class="form-control"  required placeholder="nbhgtypes">
            <span>Heures affectées : </span></br>
            <input type="number" name="nbh"  id = "nbh2" value = '' size="20" class="form-control" required placeholder="Heures"></br>
            <span>Professeur affecté(e) : </span></br>
            <select required name = "eid" form =  "modifaffec">
            <option selected value =""></option>
            <?php $SQL = "SELECT  * from enseignants where annee = " . $_SESSION['annee'];
             $res = $db->query($SQL);
            foreach($res as $row)
            {?>
             <option value =<?php echo htmlspecialchars($row['eid']); ?>><?php echo htmlspecialchars($row['nom']) ?></option>
            <?php }?>
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
}
include "footer.php";
?>