<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$title = "Ajout d'un utilisateur";
include "header.php";
?>
<div class="container">
<?php 
if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    echo $_SESSION['error'];
    $_SESSION['error'] = "";
} ?>
<h1 class = "header1" >Ajouter un utilisateur</h1></br>
<div class = "add">
        <table>
                    <tr>
                    <form  method="post" action="adduser.php">

                        <td><label  for="inputLogin" class="control-label">Login</label></td>
                            <td><input type="text" name="login" maxlength = "30" class="form-control" id="inputLogin" placeholder="login" required value="<?=$data['login'] ?? ""?>"></td>
                    </tr>
                    <tr>  
                        <td><label for="inputMDP" class="control-label">MDP</label></td>
                            <td><input type="password" name="mdp" class="form-control" id="inputMDP" placeholder="Mot de passe" required value=""></td>
                    </tr>
                    <tr>
                        <td><label for="inputMDP2" class="control-label">Répéter MDP</label></td>
                            <td><input type="password" name="mdp2" class="form-control" id="inputMDP" placeholder="Répéter le mot de passe" required value=""></td>
                    </tr>
        </table>
                    <div class="form-group">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
    </form>
    </div>
    </div>
<?php
include "footer.php";
?>