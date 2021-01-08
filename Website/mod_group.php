<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['nom']) && isset($_POST['mid']) && isset($_POST['gtid']) &&  isset($_POST['gid'])) {
    if (empty($_POST['nom']) || empty($_POST['mid']) || empty($_POST['gtid']) ||  empty($_POST['gid'])) {
    } else {
        try {
            $SQL = "UPDATE groupes set nom = ? , mid = ? , gtid = ?  where gid = ?";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['nom'], $_POST['mid'], $_POST['gtid'],  $_POST['gid']));
            $msg = "Modification effectuée";
        } catch (exception $e) {
           $msg =  "Echec de la modification ";
        }
    }
}?>
<script>
alert('<?php echo $msg ?>'); 
if(document.referrer == "") 
{
    window.location.assign('<?php echo $pathFor['root']; ?>');
} else {
    window.location.assign(document.referrer);
}
</script>

</div><?php include "footer.php" ?>