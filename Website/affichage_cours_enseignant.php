<?php

require "auth/EtreAuthentifie.php";
$title = 'Affichage de vos cours';
include "header.php";
?>

<div  class="container">
<h1 class = "header1">Cours</h1>
<?php  

$SQL = "SELECT  etypes.nbh as nbhtotal, coeff, groupes.nom, groupes.gid, intitule, affectations.nbh from users join enseignants on users.uid=enseignants.uid
   join affectations on enseignants.eid = affectations.eid
   join groupes on affectations.gid = groupes.gid
   join modules on groupes.mid = modules.mid
   join gtypes on groupes.gtid = gtypes.gtid
   join etypes on enseignants.etid = etypes.etid WHERE users.uid = " . $idm->getUid() . " AND  groupes.annee = " . $_SESSION['annee'] . " ORDER BY intitule DESC";
$res = $db->query($SQL);
if ($res->rowCount() == 0) {
    echo "</br></br>Pas de cours dans cette année</br>";
} else {
    ?>
       <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                 <th scope="col">Module</th>
                 <th scope="col">Numéro</th>
                 <th scope="col">Groupes</th>
                <th scope="col">Heures affectées</th>
            </tr>
        </thead>
<?php
$sommecoeff = 0;
    $somme = 0;
    foreach ($res as $row) {
        $total = (int) $row['nbhtotal'];?>

  <tbody>
    <tr>
      <td><?php echo htmlspecialchars($row['intitule']); ?></td>
      <td><?php echo htmlspecialchars($row['gid']); ?> </td>
      <td><?php echo htmlspecialchars($row['nom']); ?> </td>
      <td><?php echo htmlspecialchars($row['nbh']); ?></td>
      <?php $somme += (int) $row['nbh'];
        $sommecoeff += (float) $row['coeff'] * (float) $row['nbh'];?>

    </tr>
  </tbody>

<?php
}?>
 <tbody>
    <tr>
      <td>Total : </td>
      <td><?php echo $somme; ?> </td>
      <td></td>
    </tr>
  </tbody>
  <tbody>
    <tr>
      <td>Total  eqtd : </td>
      <td><?php echo (int)$sommecoeff; ?> </td>
      <td></td>

    </tr>
  </tbody>
  <tbody>
    <tr>
      <td>Heure restantes pour vous : </td>
      <td><?php $heurerestantes = ($total - $somme);
      if($heurerestantes > 0)
      {
        echo $heurerestantes;
      }
      else
      {
        echo "0";
      }
      ?> </td>
      <td></td>

    </tr>
  </tbody>
        </table>
</div>
<?php
}
include "footer.php";
?>