<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['uid'])) {
    if (empty($_POST['uid'])) {
    } else {
        try {
            $SQL = "DELETE FROM users where uid = ?";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['uid']));
            $msg = "Supression effectuée";
        } catch (exception $e) {
            echo $msg = "Echec de la supression ";
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

</div>