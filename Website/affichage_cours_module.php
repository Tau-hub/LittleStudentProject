<?php

require "auth/EtreAuthentifie.php";
$title = 'Cours par module';
require "admin.php";
include "header.php";
?>

<div  class="container">
<h1 class = "header1">Cours par module</h1></br>

<?php
$SQL = "SELECT  SUM(gtypes.nbh), modules.mid, intitule, SUM(affectations.nbh) from users join enseignants on users.uid=enseignants.uid
   join affectations on enseignants.eid = affectations.eid
   RIGHT OUTER join groupes on affectations.gid = groupes.gid
   join modules on groupes.mid = modules.mid
   join gtypes on groupes.gtid = gtypes.gtid  WHERE groupes.annee = " . $_SESSION['annee'] . " GROUP BY intitule, modules.mid";

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
                <th scope ="col">Professeurs affectés</th>
                <th scope="col">Heures restantes</th>
            </tr>
        </thead>
<?php
$sommecoeff = 0;
    $somme = 0;
    $somme_restantes = 0;
    foreach ($res as $row) {
        ?>

  <tbody>
    <tr>
      <td><?php echo htmlspecialchars($row['intitule']); ?></td>
      <td><?php echo htmlspecialchars($row['SUM(affectations.nbh)']); ?></td>
      <td><?php $SQL2 = "SELECT enseignants.nom from users join enseignants on users.uid=enseignants.uid
                        join affectations on enseignants.eid = affectations.eid
                        join groupes on affectations.gid = groupes.gid
                        join modules on groupes.mid = modules.mid
                        WHERE groupes.annee = " . $_SESSION['annee'] . " AND modules.mid =  ?  GROUP BY enseignants.nom";
                        $res2 = $db->prepare($SQL2);
                        $res2->execute(array($row['mid']));
                        foreach($res2 as $row2)
                        {
                            echo htmlspecialchars($row2['nom']) . "</br>";
                        }
                        ?></td>

      <?php $somme += (int) $row['SUM(affectations.nbh)'];?>
      <td><?php 
      $SQL = "SELECT gtypes.nbh  FROM affectations 
              RIGHT OUTER  join groupes on   affectations.gid = groupes.gid 
              join gtypes on groupes.gtid = gtypes.gtid 
              join modules on modules.mid = groupes.mid 
              where modules.mid = ? AND groupes.annee = ? GROUP BY groupes.gid";
      $rq = $db->prepare($SQL);
      $rq->execute(array(htmlspecialchars($row['mid']), $_SESSION['annee']));
      $sommegnbh = 0;
      foreach($rq as $row2)
      {
        $sommegnbh += $row2['nbh'];
      }
      $somme_restantes += $heurerestantes = $sommegnbh - $row['SUM(affectations.nbh)'];
        if ($heurerestantes > 0) {
            echo $heurerestantes;
        } else {
            echo "0";
        }

        ?>
    </tr>
  </tbody>

<?php
}?>
  <tbody>
         <tr>
          <td> Total :  </td>
          <td><?php echo  $somme   ?></td>
          <td></td>
          <td><?php echo  $somme_restantes   ?></td>
        </tr>
     </tbody>
        </table>
</div>
<?php
}
include "footer.php";
?>