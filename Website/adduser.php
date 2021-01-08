<?php
require "auth/EtreAuthentifie.php";
require "admin.php";
$_SESSION['error'] = "";

foreach (['login', 'mdp', 'mdp2'] as $name) {
    if (empty($_POST[$name])) {
        $_SESSION['error'] = "L'ajout a echoué";
    } else {
        $data[$name] = $_POST[$name];
    }
}

// Vérification si l'utilisateur existe
$SQL = "SELECT uid FROM users WHERE login=?";
$stmt = $db->prepare($SQL);
$res = $stmt->execute([$data['login']]);

if ($res && $stmt->fetch()) {
    $_SESSION['error'] .= "Login déjà utilisé";
}

if ($data['mdp'] != $data['mdp2']) {
    $_SESSION['error'] .= "MDP ne correspondent pas";
}

if (!empty($_SESSION['error'])) {
    $_SESSION['error'] = $_SESSION['error'];
    redirect("adduser_form.php");
    exit();
}

foreach (['login', 'mdp'] as $name) {
    $clearData[$name] = $data[$name];
}

$passwordFunction =
function ($s) {
    return password_hash($s, PASSWORD_DEFAULT);
};

$clearData['mdp'] = $passwordFunction($data['mdp']);

try {
    $SQL = "INSERT INTO users(login,mdp) VALUES (:login,:mdp)";
    $stmt = $db->prepare($SQL);
    $res = $stmt->execute($clearData);
    $_SESSION['error'] = "Ajout effectué";?>
    <script>
     window.location.href = document.referrer;
    windows.location.href.click();
    </script>
    <?php
} catch (\PDOException $e) {
    http_response_code(500);
    echo "Erreur de serveur.";
    redirect("adduser.php");
    exit();
}
