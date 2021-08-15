<?php

include 'database/Database.class.php';

//requete sql pour avoir tout de la table 'formations'
$pdo->exec('SET NAMES UTF8');
$query = $pdo->prepare(
    "SELECT * FROM `formations`"
);
$query->execute();
$datas = $query->fetchAll(PDO::FETCH_ASSOC);

//requete sql pour avoir tous les titres de formation
$query = $pdo->prepare(
    "SELECT title FROM `formations` GROUP BY title"
);
$query->execute();
$titles = $query->fetchAll(PDO::FETCH_ASSOC);

//ajout manuel d'une formation
if (empty($_POST['submitFo']) === false) {
    $query = $pdo->prepare(
        'INSERT into `formations` (formName, title)
                   values (?,?)'
    );
    $query->execute([
        $_POST['formName'],
        $_POST['title']
    ]);
    Header('Location: formations.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="formations.css">
    <title>Formations</title>
</head>

<body>
    <div class="container">
        <h1>Liste des formations</h1>
        <div id="surround">
            <div id="fo">
                <h2>Formations</h2>
                <div class="table">

                    <table>
                        <tr>
                            <th>Formation</th>
                            <th>Groupe</th>
                        </tr>
                        <!-- Liste des formations en base de données -->
                        <?php foreach ($datas as $data) { ?>
                            <tr>
                                <th><?= $data['formName'] ?></th>
                                <th><?= $data['title'] ?></th>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <!-- formulaire ajout de formation -->
                <form action="formations.php" method="post">
                    <p>Ajouter une formation</p>
                    <label for="formName">Nom de formation</label>
                    <input type="text" name="formName" id="formName">
                    <label for="title">Groupe de formation associé</label>
                    <input type="text" name="title" id="title">
                    <input type="submit" name="submitFo" value="ajouter">
                </form>
            </div>
            <div id="gr">
                <h2>Groupes Formations</h2>
                <div class="table">

                    <table>
                        <tr>
                            <th>Groupes Formations</th>
                        </tr>
                        <!-- Liste des groupes de formations en base de données -->
                        <?php foreach ($titles as $data) { ?>
                            <tr>
                                <th><?= $data['title'] ?></th>
                            </tr>
                        <?php } ?>
                        </ul>
                    </table>
                </div>
            </div>
        </div>
</body>

</html>