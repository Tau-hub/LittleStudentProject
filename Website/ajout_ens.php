<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
include "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['uid']) && isset($_POST['Nom']) && isset($_POST['Prenom']) && isset($_POST['email']) && isset($_POST['telephone']) && isset($_POST['etid'])) {
    if (empty($_POST['uid']) || empty($_POST['Nom']) || empty($_POST['Prenom']) || empty($_POST['email']) || empty($_POST['telephone']) || empty($_POST['etid'])) {
    } else {
        try {
            $SQL = "INSERT INTO enseignants(uid, nom, prenom, email, tel, etid, annee) VALUES (?,?, ?,?,?,?,?)";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['uid'],$_POST['Nom'], $_POST['Prenom'],$_POST['email'],$_POST['telephone'], $_POST['etid'],$_SESSION['annee']));
            $msg = "Ajout effectué";
        } catch (exception $e) {
             $msg = "Echec, ajout impossible";
        }
    }
}
?> 


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



