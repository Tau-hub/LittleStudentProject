<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['Nom']) && isset($_POST['gtid']) && isset($_POST['mid'])) {
    if (empty($_POST['Nom']) || empty($_POST['gtid']) || empty($_POST['mid'])) {
    } else {
        try {
            $SQL = "INSERT INTO groupes(mid, nom, annee, gtid) VALUES (?,?, ?,?)";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['mid'],$_POST['Nom'], $_SESSION['annee'],$_POST['gtid']));
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

</div>

<?php include "footer.php" ?>