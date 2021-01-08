<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['intitule']) && isset($_POST['code']) && isset($_POST['eid']) &&  isset($_POST['cid'])) {
    if (empty($_POST['intitule']) || empty($_POST['code']) || empty($_POST['eid']) ||  empty($_POST['cid'])) {
    } else {
        try {
            $SQL = "INSERT INTO modules(intitule, code, eid, cid,annee) VALUES (?,?, ?,?,?)";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['intitule'],$_POST['code'], $_POST['eid'],$_POST['cid'], $_SESSION['annee']));
            $msg = "Ajout effectué";
        } catch (exception $e) {
            $msg = "Echec, ajout impossible";
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