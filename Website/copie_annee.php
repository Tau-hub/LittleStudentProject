<?php

require "auth/EtreAuthentifie.php";
$title = "Copie";
include "header.php";
require "admin.php";
?> <div class = "container">
<?php
$msg = "Echec de la copie";
try{
if (isset($_POST['anneedes'])) {
    if (!empty($_POST['anneedes'])) {
        if (is_numeric($_POST['anneedes']) && $_POST['anneedes'] != $_SESSION['annee']) {
          $SQL = "SELECT * FROM enseignants where enseignants.annee = " . $_SESSION['annee'];
          $res = $db->query($SQL);
          if($res->rowCount() > 0)
          {
            $anneedes =  htmlspecialchars($_POST['anneedes']);
            $SQL = "DELETE from enseignants where annee = " . $anneedes;
            $db->query($SQL);
            $SQL = "DELETE from modules where annee = " . $anneedes;
            $db->query($SQL);
            $SQL = "DELETE from groupes where annee = " . $anneedes;
            $db->query($SQL);
            $SQL = "SELECT * from enseignants where annee = " .  $_SESSION['annee'];
            $res = $db->query($SQL);
            foreach ($res as $row) {
                $SQL = "INSERT INTO enseignants(uid, nom, prenom, email,tel,etid,annee) VALUES(?,?,?,?,?,?,?);";
                $res = $db->prepare($SQL);
                $res->execute(array(($row['uid']),($row['nom']),($row['prenom']),($row['email']),($row['tel']),($row['etid']),$anneedes));
            }
            $SQL = "SELECT * from modules join enseignants on modules.eid = enseignants.eid where enseignants.annee = " . $_SESSION['annee'];
            $res = $db->query($SQL);
            foreach ($res as $row) {
                $SQL = "SELECT * from enseignants where annee = " . $anneedes;
                $res2 = $db->query($SQL);
                foreach ($res2 as $row2) {
                    if ($row['uid'] == $row2['uid']) {
                        $eid = $row2['eid'];
                    }
                }
                $SQL = "INSERT INTO modules(intitule, code, eid, cid,annee) VALUES(?,?,?,?,?);";
                $insert = $db->prepare($SQL);
                $insert->execute(array(($row['intitule']),($row['code']),($eid),($row['cid']),$anneedes));
            }

            $SQL = "SELECT * from groupes join modules on groupes.mid = modules.mid where modules.annee = " . $_SESSION['annee'];
            $res = $db->query($SQL);
            foreach ($res as $row) {
                $SQL = "SELECT * from modules where annee = " . $anneedes;
                $res2 = $db->query($SQL);
                foreach ($res2 as $row2) {
                    if ($row['code'] == $row2['code']) {
                        $mid = $row2['mid'];
                    }
                }
                $SQL = "INSERT INTO groupes(mid, nom, annee, gtid) VALUES(?,?,?,?);";
                $insert = $db->prepare($SQL);
                $insert->execute(array(($mid),($row['nom']),$anneedes,($row['gtid'])));
            }

            $SQL = "SELECT *, enseignants.eid as eeid, enseignants.nom as ensnom,  affectations.nbh as anbh, groupes.nom as grnom, groupes.gtid as grtid from affectations join enseignants  on enseignants.eid = affectations.eid
                                       join groupes on groupes.gid = affectations.gid
                                       join modules on modules.mid = groupes.mid  where enseignants.annee = " . $_SESSION['annee'];
            $res = $db->query($SQL);
            foreach ($res as $row) {
                $SQL = "SELECT * from enseignants  where enseignants.annee = " . $anneedes;
                $res2 = $db->query($SQL);
                foreach ($res2 as $row2) {
                    if ($row['uid'] == $row2['uid']) {
                        $eid = $row2['eid'];
                    }
                }
                $SQL = "SELECT * from groupes join modules on groupes.mid = modules.mid where groupes.annee = " . $anneedes;
                $res3 = $db->query($SQL);
                foreach ($res3 as $row3) {
                    if ($row['code'] == $row3['code']  && $row['grnom'] == $row3['nom'] && $row['grtid'] == $row3['gtid']) {
                        $gid = $row3['gid'];
                    }
                }
                $SQL = "INSERT INTO affectations(eid, gid, nbh) VALUES(?,?,?);";
                $insert = $db->prepare($SQL);
                $insert->execute(array(($eid),($gid),($row['anbh'])));
            }
            $msg = "Copie effectuée";
            $_SESSION['annee'] = $anneedes;
          }
        }
    }
}
}catch(exception $e)
{
  echo  "Echec de la copie";
}
finally { ?>
<script>
alert('<?php echo $msg ?>'); 
if(document.referrer == "") 
{
    window.location.assign('<?php echo $pathFor['root']; ?>');
} else {
    window.location.assign(document.referrer);
}
</script>

<?php } ?>


<?php 
include "footer.php";
?>
