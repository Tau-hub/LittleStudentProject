<?php

require "auth/EtreAuthentifie.php";
require "admin.php";
$title = 'Liste des groupes';
include "header.php";
?>

<div class = "container">
<h1 class = "header1">Liste des modules</h1></br>
<?php
$SQL = "SELECT intitule, code, enseignants.nom as ensnom, categories.nom as catnom from modules 
                      join enseignants on modules.eid = enseignants.eid 
                      join categories on modules.cid = categories.cid where modules.annee = " . $_SESSION['annee'] . " ORDER BY categories.nom";
$res = $db->query($SQL);
?>
       <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Année de licence</th>
                <th scope="col">Intitule</th>
                <th scope="col">Code</th>
                <th scope="col">Enseignant Responsable</th>
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
    </tr>
  </tbody>
<?php
}
?>
        </table>
        </div>
<?php
include "footer.php";
?>