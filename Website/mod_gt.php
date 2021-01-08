<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['nbh']) && isset($_POST['nom']) && isset($_POST['coeff']) && isset($_POST['gtid']) && isset($_POST['nom'])) {
    try {
        if (empty($_POST['nbh']) || empty($_POST['nom']) || empty($_POST['coeff']) || empty($_POST['gtid']) || empty($_POST['nom'])) {
        } else {
            $SQL = "UPDATE gtypes set nom = ? , nbh = ? , coeff = ? where gtid = ?";
            $rq = $db->prepare($SQL);
            $rq->execute(array($_POST['nom'], $_POST['nbh'], $_POST['coeff'],$_POST['gtid']));
            $msg = "Modification effectuée";
        }
    } catch (exception $e) {
        $msg =  "Echec de la modification ";
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