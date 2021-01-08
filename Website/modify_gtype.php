<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Modifier un gtype";
include "header.php";
?>
<script>
function getParam(nom, gtid, coeff, nbh)
{
  document.getElementById('nom').value = nom;
  document.getElementById('gtid').value = gtid;
  document.getElementById('coeff').value = coeff;
  document.getElementById('nbh').value = nbh;
}
</script>
<div class="container">
<h1 class = "header1">Modifier un gtype</h1></br>
    <?php 
 $SQL = "SELECT * from gtypes;";
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
    ?>
  <tbody>
    <tr>
    <td><?php echo htmlspecialchars($row['nom']); ?></td>
    <td><?php echo htmlspecialchars($row['nbh']); ?></td>
    <td><?php echo htmlspecialchars($row['coeff']); ?></td>
    <td><button id="button_modif" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modifygtype"  onclick="getParam('<?php echo htmlspecialchars(addslashes($row['nom'])); ?>','<?php echo htmlspecialchars(addslashes($row['gtid'])); ?>','<?php echo htmlspecialchars(addslashes($row['coeff'])); ?>', '<?php echo htmlspecialchars(addslashes($row['nbh'])); ?>')">Modifier</button>
  </td></tr>
 </tbody>
 <?php }?>
 </table>
 <div id="modifygtype" class="modal fade" role="dialog" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Modifier un gtype</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form  lang = "en" method = "POST" action = "mod_gt.php" >
            <input type="hidden" name="gtid" id = "gtid"  value = '' size="20" class="form-control"  required placeholder="gtid"></br>
            <span>Nom :</span></br>
            <input type="text" name="nom" required maxlength = "30" id = "nom" value ='' size="20" class="form-control"></br>
            <span>Nombre d'heures :</span></br>
            <input type="number" name="nbh"  id = "nbh" value = '' size="20" class="form-control" required placeholder="nbh"></br>
            <span>Coefficient :</span></br>
            <input type="number" step = "0.01" id = "coeff" value ='' name="coeff" size="20" class="form-control" required placeholder="coeff"></br>
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