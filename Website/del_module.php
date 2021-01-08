<?php

require "auth/EtreAuthentifie.php";
$title ="Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
 $msg = "Retour à la page principal, information manquante";
if (isset($_POST['mid'])) {
    if (empty($_POST['mid'])) {
    } else {
        try {
            $SQL = "DELETE FROM modules where mid = ?";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['mid']));
            $msg = "Supression effectuée";
        } catch (exception $e) {
            $msg = "Echec de la supression ";
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