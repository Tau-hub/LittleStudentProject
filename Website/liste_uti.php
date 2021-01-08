<?php

require "auth/EtreAuthentifie.php";
require "admin.php";
$title = 'Liste des utilisateurs';
include "header.php";
?>

<div class = "container">
<h1 class = "header1">Liste des utilisateurs</h1></br>
<?php
$SQL = "SELECT login, role from users";
$res = $db->query($SQL);
?>
       <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Role</th>
                <th scope="col">Login</th>
            </tr>
        </thead>
<?php
foreach ($res as $row) {
    ?>

  <tbody>
    <tr>
    <td><?php echo htmlspecialchars($row['role']); ?></td>
    <td><?php echo htmlspecialchars($row['login']); ?></td>
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