<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$passwordFunction =
function ($s) {
    return password_hash($s, PASSWORD_DEFAULT);
};
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['password']) && isset($_POST['uid'])) {
    if (empty($_POST['password']) || empty($_POST['uid'])) {
    } else {
        try {
            $password = $passwordFunction($_POST['password']);
            $SQL = "UPDATE users set mdp=? where uid = ?";
            $rq = $db->prepare($SQL);
            $rq->execute(array($password, $_POST['uid']));
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