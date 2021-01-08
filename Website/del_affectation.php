<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['gid']) && isset($_POST['eid'])) {
    if (empty($_POST['gid']) || empty($_POST['eid']) ) {
    } else {
        try {
            $SQL = "DELETE FROM affectations where gid = ? AND eid = ?";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['gid'], $_POST['eid']));
            $msg = "Supression effectuée";
        } catch (exception $e) {
            $msg = "Echec de la supression ";
        }
    }
} ?>
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