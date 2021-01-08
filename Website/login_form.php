<?php
$title = "Authentification";
include "header.php";?>
<div class ="container">
    <?php
echo "<p class=\"error\">" . ($error ?? "") . "</p>";
?>
        <h1 class ="header1">Authentifiez-vous</h1>
<div class = "login" >
                    <form style ="display: inline-block;"method="post">
                        <table class="center">
                            <tr>
                            <td><label for="inputNom" class="control-label">Login</label></td>
                            <td><input type="text" name="login" size="20" class="form-control" id="inputLogin" required placeholder="login"
                                   required value="<?=$data['login'] ?? ""?>"></td>
                            </tr>
                            <tr>
                            <td><label for="inputMDP" class="control-label">MDP</label></td>
                            <td><input type="password" name="password" size="20" class="form-control" required id="inputMDP"
                                   placeholder="Mot de passe"></td>
                            </tr>
                        </table>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Connexion</button>
                        </div>
                    </form>
    </div>
</div>
</div>
<?php

include "footer.php";