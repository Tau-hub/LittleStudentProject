<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
      $msg = "Retour à la page principal, information manquante";
if (isset($_POST['Nom']) && isset($_POST['Prenom']) && isset($_POST['telephone']) &&  isset($_POST['email']) && isset($_POST['etid']) && isset($_POST['uid'])) {
    if (empty($_POST['Nom']) || empty($_POST['Prenom']) || empty($_POST['telephone']) ||  empty($_POST['email']) || empty($_POST['etid']) || empty($_POST['uid'])) {
    } else {
        try {
            $SQL = "UPDATE enseignants set nom = ? , prenom = ? , tel = ? , email = ? , etid = ?  where uid = ?";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['Nom'], $_POST['Prenom'], $_POST['telephone'],$_POST['email'],$_POST['etid'],$_POST['uid']));
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