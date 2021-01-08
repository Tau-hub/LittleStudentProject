<?php

require "auth/EtreAuthentifie.php";
require "admin.php";
$title = 'Liste des groupes';
include "header.php";
?>

<div class = "container">
<h1 class = "header1">Liste des groupes</h1></br>
<?php
$SQL = "SELECT * from groupes join modules on groupes.mid = modules.mid where groupes.annee = " . $_SESSION['annee'];
$res = $db->query($SQL);
?>
        <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Numero de groupe</th>
                <th scope="col">Module associé</th>
                <th scope="col">Type du module</th>
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