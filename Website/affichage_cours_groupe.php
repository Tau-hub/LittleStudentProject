<?php

require "auth/EtreAuthentifie.php";
$title = 'Cour par groupe';
require "admin.php";
include "header.php";
?>

<div  class="container">
<?php $SQL = "Select * from enseignants where annee = " . $_SESSION['annee'];
      $res = $db->query($SQL);
      ?>
<p>Professeur   : </p>
<form method = "POST" >
<select class = "browser-default custom-select" required name="eid" value =<?php echo $_SESSION['annee']?> onchange="this.form.submit();">
<option value = ""></option>
<option  <?php if(isset($_POST['eid'])) {
        if($_POST['eid'] == "is_null")
         echo "selected";
     } ?> value ="is_null">Cours non affectés</option>
<?php  foreach($res as $row)
{ ?>
     
     <option <?php if(isset($_POST['eid'])) {
        if($_POST['eid'] == $row['eid'])
         echo "selected";
     } ?> value =<?php echo $row['eid']?> > <?php echo htmlspecialchars($row['nom']) . " " . htmlspecialchars($row['prenom']);?> </option> 
<?php }
     ?>
   </select></br>
 
</form>


<?php
if (isset($_POST['eid'])) {
  ?> <h1 class = "header1">Cours</h1> <?php
  if ($_POST['eid'] == "is_null") {
 $SQL = "SELECT  coeff, groupes.nom, intitule, affectations.nbh, groupes.gid, enseignants.nom as ensnom, gtypes.nbh as nbhgtypes  from users join enseignants on users.uid=enseignants.uid
 join affectations on enseignants.eid = affectations.eid
 RIGHT OUTER join groupes on affectations.gid = groupes.gid
 join modules on groupes.mid = modules.mid
 join gtypes on groupes.gtid = gtypes.gtid  WHERE   groupes.annee = " . $_SESSION['annee'] . " AND enseignants.nom IS NULL  ORDER BY intitule DESC";
  $res = $db->query($SQL);
  }
  else
  {
    $SQL = "SELECT  etypes.nbh as nbhtotal, coeff, groupes.nom, groupes.gid, intitule, affectations.nbh, enseignants.eid, enseignants.nom as ensnom, gtypes.nbh as nbhgtypes  from users join enseignants on users.uid=enseignants.uid
    join affectations on enseignants.eid = affectations.eid
    RIGHT OUTER join groupes on affectations.gid = groupes.gid
    join modules on groupes.mid = modules.mid
    join etypes on enseignants.etid = etypes.etid
    join gtypes on groupes.gtid = gtypes.gtid  WHERE   groupes.annee = " . $_SESSION['annee'] . " AND enseignants.eid = ? ORDER BY intitule DESC";
    $res = $db->prepare($SQL);
    $res->execute(array($_POST['eid']));
  }
    if ($res->rowCount() == 0) {
    } else {
        ?>
<table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                 <th scope="col">Enseignants</th>
                 <th scope="col">Module</th>
                 <th scope="col">Numéro</th>
                 <th scope="col">Groupes</th>
                 <th scope="col">Heures affectées</th>
                 <th scope = "col">Heures restantes</th>
            </tr>
        </thead>
<?php
$sommecoeff = 0;
        $somme = 0;
        foreach ($res as $row) {
          if($_POST['eid'] != "is_null") $maxheure = $row['nbhtotal'];
            ?>

  <tbody>
    <tr>
    <td><?php echo htmlspecialchars($row['ensnom']); ?></td>
      <td><?php echo htmlspecialchars($row['intitule']); ?></td>
      <td><?php echo htmlspecialchars($row['gid']); ?></td>
      <td><?php echo htmlspecialchars($row['nom']); ?> </td>
      <td><?php echo htmlspecialchars($row['nbh']); ?></td>
      <?php $somme += (int) $row['nbh'];
            $sommecoeff += (float) $row['coeff'] * (float) $row['nbh']; ?>
      <td><?php $SQL =  "SELECT affectations.gid, SUM(nbh) from affectations join groupes on affectations.gid = groupes.gid where affectations.gid = ? GROUP BY affectations.gid";
             $res2 = $db->prepare($SQL);
             $res2->execute(array(htmlspecialchars($row['gid'])));
              $data = $res2->fetch();
              $heurerestantes = $row['nbhgtypes'] - $data['SUM(nbh)'];
            if ($heurerestantes > 0) {
                echo $heurerestantes;
            } else {
                echo "0";
            } ?>
    </tr>
  </tbody>

<?php
        }
        if ($_POST['eid'] != "is_null") {
          $heurerestantes =  ($maxheure - $somme);
            ?>
         <tbody>
         <tr>
          <td> Total d'heures pour le professeur : </td>
          <td></td>
          <td></td>
          <td></td>
          <td><?php echo  $somme   ?></td>
          <td><?php if($heurerestantes > 0) {
            echo $heurerestantes;

          } else
          {
            echo "0";
          } ?></td>
        </tr>
     </tbody>
     <tbody>
         <tr>
          <td> Total d'heures  affectés eqtd  : </td>
          <td></td>
          <td></td>
          <td></td>
          <td><?php echo  (int) $sommecoeff  ?></td>
        
        </tr>
     </tbody>
    
<?php
        }
    }?>
    </table>
    </div>
<?php }
include "footer.php";
?>