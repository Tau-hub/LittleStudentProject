<?php

require "auth/EtreAuthentifie.php";
require "admin.php";
$title = 'Liste des gtypes';
include "header.php";
?>
<div class = "container">
<h1 class = "header1">Liste des gtypes</h1></br>
<?php
$SQL = "SELECT * from gtypes;";
$res = $db->query($SQL);
?>
       <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Nombre d'heures</th>
                <th scope="col">Coefficient</th>
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