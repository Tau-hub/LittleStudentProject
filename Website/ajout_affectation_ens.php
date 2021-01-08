<?php

require "auth/EtreAuthentifie.php";
$title = "Tratiement";
require "admin.php";
include "header.php";
?>
<div class = "container">
<?php
$msg = "Retour à la page principal, information manquante";
if (isset($_POST['nbhgtypes']) && isset($_POST['gid']) && isset($_POST['eid']) && isset($_POST['nbh'])) {
    if (empty($_POST['nbhgtypes']) || empty($_POST['gid']) || empty($_POST['eid']) || empty($_POST['nbh'])) {
    } else {
        $heurerestantes = $_POST['nbhgtypes'];
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
            if ($res->rowCount() == 0) {
                try {
                        $SQL = "INSERT INTO affectations(eid, gid,nbh) VALUES (?,?,?)";
                        $rq = $db->prepare($SQL);
                        $rq->execute(array($_POST['eid'],$_POST['gid'],$_POST['nbh']));
                        $msg  = "Affectation effectué";
                } catch (exception $e) {
                    $msg  = "Echec, affectation impossible";
                }
            } else {
                $msg = "Professeur déjà affecté(e), veuillez utiliser la modification";
            }
        } else {
            $msg = "Heure(s) incorrecte(s) peut être avez-vous mis une valeur négative ou trop d'heure(s)";
        }
    }
}
?>
<script>
 alert('<?php echo addslashes($msg) ?>'); 
 window.location.href = document.referrer;
 windows.location.href.click();
</script>
</div>

<?php include "footer.php" ?>