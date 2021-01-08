<?php

require "auth/EtreAuthentifie.php";
$title = 'Cours non complets/Professeurs non complets';
require "admin.php";
include "header.php";

?>

<div  class="container">
<h1 class = "header1">Cours non complets/Professeurs non complets</h1></br>
<h2>Module : </h2>
<?php
$SQL = "SELECT SUM(gtypes.nbh), intitule, SUM(affectations.nbh) from users join enseignants on users.uid=enseignants.uid
   join affectations on enseignants.eid = affectations.eid
   RIGHT OUTER join groupes on affectations.gid = groupes.gid
   join modules on groupes.mid = modules.mid
   join gtypes on groupes.gtid = gtypes.gtid  WHERE groupes.annee = " . $_SESSION['annee'] . " GROUP BY intitule";
$res = $db->query($SQL);
if ($res->rowCount() == 0) {
    echo "</br></br>Pas de cours dans cette année</br>";
} else {
    ?>
        <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                 <th scope="col">Module</th>
                 <th scope="col">Heures affectées</th>
                <th scope="col">Heures restantes</th>
            </tr>
        </thead>
<?php
$somme = 0;
    $somme_affecte = 0;
    ?>
    <tbody>
    <?php
foreach ($res as $row) {
        $heurerestantes = $row['SUM(gtypes.nbh)'] - $row['SUM(affectations.nbh)'];
        if ($heurerestantes > 0) {
            ?>
        <tr>
      <td><?php echo htmlspecialchars($row['intitule']); ?></td>
      <td><?php echo htmlspecialchars($row['SUM(affectations.nbh)']); ?></td>
      <?php $somme += $heurerestantes;
            ?>
      <td><?php echo htmlspecialchars($heurerestantes); ?></td>
      </tr><?php
}?>
  </tbody>

<?php
$somme_affecte += $row['SUM(affectations.nbh)'];
    }?>
    <tbody>
    <tr>
    <td>Total : </td>
    <td><?php echo $somme_affecte; ?></td>
    <td><?php echo $somme; ?></td>
    </tr>
    </tbody>
    
        </table></br>
        <h2>Professeurs :  </h2>
<?php
    $SQL = "SELECT  etypes.nbh as nbhtotal, enseignants.nom, SUM(affectations.nbh) from users join enseignants on users.uid=enseignants.uid
    join affectations on enseignants.eid = affectations.eid
    join groupes on affectations.gid = groupes.gid
    join etypes on enseignants.etid = etypes.etid WHERE groupes.annee = " . $_SESSION['annee'] . " GROUP BY  enseignants.nom, etypes.nbh";
    $res = $db->query($SQL);
    ?>

    <table class="table table-hover">
    <thead class="thead-dark">
            <tr>
                 <th scope="col">Enseignants</th>
                 <th scope="col">Heures affectées</th>
                <th scope="col">Heures restantes</th>
            </tr>
        </thead>

<?php
$somme = 0;
    $somme_affecte = 0;
    ?>
    <tbody>
    <?php
foreach ($res as $row) {
        $heurerestantes = $row['nbhtotal'] - $row['SUM(affectations.nbh)'];
        if ($heurerestantes > 0) {
            ?>
        <tr>
      <td><?php echo htmlspecialchars($row['nom']); ?></td>
      <?php $somme += $heurerestantes;
            ?>
            <td><?php echo htmlspecialchars($row['SUM(affectations.nbh)']); ?></td>
      <td><?php echo htmlspecialchars($heurerestantes); ?></td>
      </tr><?php $somme_affecte += $row['SUM(affectations.nbh)'];
}?>
  </tbody>

<?php
}?>
<tbody>
    <tr>
    <td>Total : </td>
    <td><?php echo $somme_affecte; ?></td>
    <td><?php echo $somme; ?></td>
    </tr>
    </tbody>
        </table>
</div>

<?php
}
include "footer.php";
?>