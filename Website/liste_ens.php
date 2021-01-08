<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Liste des enseignants";
include "header.php";
?>
<div class="container">
<h1 class = "header1">Liste des enseignants</h1></br>
    <?php $SQL = "Select * from enseignants where annee = " . $_SESSION['annee'];
    $res = $db->query($SQL);?>
   <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prenom</th>
                <th scope="col">Email</th>
                <th scope="col">Telephone</th>
                <th scope="col">Annee</th>
            </tr>
        </thead>
<?php
foreach ($res as $row) {
    ?>
  <tbody>
    <tr>
      <td><?php echo htmlspecialchars($row['nom']);?></td>
      <td><?php echo htmlspecialchars($row['prenom']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td><?php echo htmlspecialchars($row['tel']); ?></td>
      <td><?php echo htmlspecialchars($row['annee']); ?></td>
    </tr>
 </tbody>

 <?php } ?>
 </table>
 </div>
 <?php

include "footer.php";
?>