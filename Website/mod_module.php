<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
 if (isset($_POST['intitule']) && isset($_POST['code']) && isset($_POST['eid']) &&  isset($_POST['cid']) && isset($_POST['mid'])) {
     try {
         if (empty($_POST['code']) || empty($_POST['eid']) ||  empty($_POST['cid']) || empty($_POST['mid']) || empty($_POST['intitule'])) {
         } else {
             $SQL = "UPDATE modules set intitule = ? , code = ? , eid = ?, cid = ?   where mid = ?";
             $rq = $db->prepare($SQL);
             $rq->execute(array($_POST['intitule'], $_POST['code'], $_POST['eid'],  $_POST['cid'],$_POST['mid']));
             $msg = "Modification effectuée";
         }
     } catch (exception $e) {
         $msg = "Echec de la modification ";
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