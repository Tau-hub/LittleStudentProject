<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php 
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['nom']) && isset($_POST['nbh']) && isset($_POST['coeff'])) {
    if (empty($_POST['nom']) || empty($_POST['nbh']) || empty($_POST['coeff'])) {
    } else {
        try {
            $SQL = "INSERT INTO gtypes(nom, nbh, coeff) VALUES (?,?, ?)";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['nom'],$_POST['nbh'],$_POST['coeff']));
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