<?php
session_start();
include 'database/Database.class.php';
include 'database/lib.php';
if (array_key_exists('role', $_SESSION)) {
    Header('Location: logout.php');
    exit();
}

$error = null;
if (empty($_POST) === false) {

    $query = $pdo->prepare(
        'SELECT * FROM forma_user WHERE UserName = ?'
    );

    $query->execute([$_POST['name']]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
        $error = "Utilisateur introuvable";
    } else {
        if (verifyPassword($_POST['password'], $user['Password'])) {
            $_SESSION['name'] = $user['UserName'];
            $_SESSION['role'] = $user['Role'];
            Header('Location: index.php');
            exit();
        } else {
            $error = "Mauvais mot de passe";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Liste de Formateurs</title>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-body">
    <main>
        <?php if ($error !== null) { ?>
            <p><?= $error ?></p>
        <?php } ?>

        <div class="login-box">
            <h2>Connexion</h2>
            <form action="login.php" method="POST">
                <div class="user-box">
                    <input type="text" name="name" required />
                    <label>Nom d'utilisateur <span style="color:red">*</span></label>
                </div>
                <div class="user-box">
                    <input type="password" name="password" required />
                    <label>Mot de passe <span style="color:red">*</span></label>
                </div>
                <button type="submit">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    Se connecter
                </button>
            </form>
        </div>
    </main>
</body>

</html>
