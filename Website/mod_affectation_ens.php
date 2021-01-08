<?php

require "auth/EtreAuthentifie.php";
$title = "Traitement";
require "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['nbh']) && isset($_POST['gid']) && isset($_POST['eid']) && isset($_POST['nbhgtypes']) && isset($_POST['ancienh']) && isset($_POST['ancieneid']) ) {
    if (empty($_POST['nbh']) || empty($_POST['gid']) || empty($_POST['eid']) || empty($_POST['nbhgtypes']) || empty($_POST['ancienh']) || empty($_POST['ancienh']) ) {
    } else {
        $heurerestantes = $_POST['nbhgtypes'] + $_POST['ancienh']; 
        $SQL =  "SELECT affectations.gid, SUM(affectations.nbh) from affectations 
        join groupes on affectations.gid = groupes.gid   where groupes.gid = ? GROUP BY groupes.gid";
        $res2 = $db->prepare($SQL);
        $res2->execute(array(htmlspecialchars($_POST['gid'])));
        if ($res2->rowCount() > 0) {
            $data = $res2->fetch();
            $heurerestantes -= $data['SUM(affectations.nbh)'];
        }
        $heurerestantes -= $_POST['nbh'];
        if ($heurerestantes >= 0 && $_POST['nbh'] >= 0) {
            $SQL = "Select * from affectations join enseignants on affectations.eid = enseignants.eid  where enseignants.annee = " . $_SESSION['annee'] . " AND affectations.eid  = ? AND affectations.gid = ? ";
            $res = $db->prepare($SQL);
            $res->execute(array(htmlspecialchars($_POST['eid']),htmlspecialchars($_POST['gid'])));
            try {
                $SQL = "SELECT * from affectations join groupes on affectations.gid = groupes.gid where affectations.gid = ? AND  affectations.eid = ? ";
                $res = $db->prepare($SQL);
                $res->execute(array($_POST['gid'], $_POST['eid']));
                if($res->rowCount() > 0)
                {
                    $SQL = "DELETE from affectations where eid = ? AND gid = ?";
                    $res = $db->prepare($SQL);
                    $res->execute(array($_POST['eid'], $_POST['gid']));
                    $SQL = "INSERT INTO affectations(nbh, eid, gid) VALUES (? , ?, ?)";
                    $rq = $db->prepare($SQL);
                    $rq->execute(array($_POST['nbh'], $_POST['eid'], $_POST['gid']));
                }
                else {
                    $SQL = "UPDATE affectations set nbh = ?, eid  = ?  where gid = ? AND eid = ?";
                    $rq = $db->prepare($SQL);
                    $rq->execute(array($_POST['nbh'], $_POST['eid'], $_POST['gid'], $_POST['ancieneid']));
                }
                $msg = "Modification effectuée";
            } catch (exception $e) {
                $msg = "Echec de la modification ";
            }
        }
        else {
            $msg = "Heure(s) incorrecte(s) peut être avez-vous mis une valeur négative ou trop d'heure(s)";
        }
    }
}?>
<script>
alert('<?php echo addslashes($msg) ?>'); 
if(document.referrer == "") 
{
    window.location.assign('<?php echo $pathFor['root']; ?>');
} else {
    window.location.assign(document.referrer);
}
</script>

</div>
<?php include "footer.php" ?>