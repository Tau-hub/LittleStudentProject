<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['uid']) && isset($_POST['Professeur'])) {
    if (empty($_POST['uid']) || empty($_POST['Professeur'])) {
    } else {
        try {
            $SQL = "UPDATE enseignants set uid=? where eid = ?";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['uid'], $_POST['Professeur']));
            $msg = "Association effectué";
        } catch (exception $e) {
           $msg = "Echec, association impossible";
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
<?php include "footer.php" ?>