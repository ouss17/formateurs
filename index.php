<?php
session_start();
if ((array_key_exists('role', $_SESSION) === false)) {
    Header('Location: login.php');
    exit();
}
include 'database/Database.class.php';
?>
<!DOCTYPE html>

<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css">
</head>
<?php

//FORMATION SQL REQUEST FOR THE SELECT INPUT
$pdo->exec('SET NAMES UTF8');
$query = $pdo->prepare(
    "SELECT * FROM `formations`"
);
$query->execute();
$datasSpe = $query->fetchAll(PDO::FETCH_ASSOC);

$pdo->exec('SET NAMES UTF8');
$query = $pdo->prepare(
    "SELECT title FROM `formations` GROUP BY title"
);
$query->execute();
$datasf = $query->fetchAll(PDO::FETCH_ASSOC);

//SPCEIALITE SQL REQUEST TO MATCH FORMATION SQL REQUEST
$tab = [];
$pdo->exec('SET NAMES UTF8');
$query = $pdo->prepare(
    "SELECT formName FROM `formations` GROUP BY formName"
);
$query->execute();
$datasForm = $query->fetchAll(PDO::FETCH_ASSOC);

$pdo->exec('SET NAMES UTF8');
$query = $pdo->prepare(
    "SELECT * FROM `center`"
);
$query->execute();
$datasCenter = $query->fetch(PDO::FETCH_ASSOC);

//CITY SQL REQUEST FOR THE SELECT INPUT
$pdo->exec('SET NAMES UTF8');
$query = $pdo->prepare(
    "SELECT * FROM `table 1` GROUP BY Ville"
);
$query->execute();
$datasVille = $query->fetchAll(PDO::FETCH_ASSOC);

//ZIP SQL REQUEST FOR THE SELECT INPUT
$pdo->exec('SET NAMES UTF8');
$query = $pdo->prepare(
    "SELECT * FROM `table 1` GROUP BY Departement"
);
$query->execute();
$datasDepart = $query->fetchAll(PDO::FETCH_ASSOC);

//COMING TO THE PAGE WITH GET
if (empty($_POST['searchButton'])) {

    //ALL USERS SQL REQUEST
    $query = $pdo->prepare(
        "SELECT * FROM `table 1`"
    );
    $query->execute();
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);
    $pdo->exec('SET NAMES UTF8');

    //GESTION PHP AJOUT MANUEL DE FORMATEUR (en appuyant sur fm3sn)
    if (empty($_POST['addFormateurSubmit']) === false) {
        $query = $pdo->prepare(
            'INSERT into `table 1` (Nom,Prenom,Mail,Specialite,Ville,Departement,Zip, date, Adresse, Tel, lat, lon)
                   values (?,?,?,?,?,?,?,NOW(),?,?,null, null)'
        );
        $var = $query->execute([
            $_POST['addFirstName'],
            $_POST['addLastName'],
            $_POST['addMail'],
            $_POST['addSpe'],
            $_POST['addVille'],
            $_POST['addDepart'],
            $_POST['addZip'],
            $_POST['addAdresse'],
            $_POST['addPhone']
        ]);
    }
} else {

    //FORMATION SQL SEARCH REQUEST 
    if ($_POST['city'] == "Ville" && $_POST['depart'] == "Département" && $_POST['titleFormation'] == "Formation") {
        $query = $pdo->prepare(
            'SELECT * FROM `table 1`
    WHERE Specialite LIKE "%"?"%"'
        );
        $query->execute([strtolower($_POST['formation'])]);
        $datas = $query->fetchAll(PDO::FETCH_ASSOC);

        //CITY SQL SEARCH REQUEST
    } elseif ($_POST['formation'] == "Sous Formation" && $_POST['depart'] == "Département" && $_POST['titleFormation'] == "Formation") {
        $query = $pdo->prepare(
            'SELECT * FROM `table 1`
    WHERE Ville = ?'
        );
        $query->execute([$_POST['city']]);
        $datas = $query->fetchAll(PDO::FETCH_ASSOC);

        //ZIP SQL SEARCH REQUEST
    } elseif ($_POST['formation'] == "Sous Formation" && $_POST['city'] == "Ville" && $_POST['titleFormation'] == "Formation") {
        $query = $pdo->prepare(
            'SELECT * FROM `table 1`
    WHERE Departement = ?'
        );
        $query->execute([$_POST['depart']]);
        $datas = $query->fetchAll(PDO::FETCH_ASSOC);

        //FORM SQL SEARCH REQUEST
    } elseif ($_POST['formation'] == "Sous Formation" && $_POST['city'] == "Ville" && $_POST['depart'] == "Département") {
        $datas = [];
        $query = $pdo->prepare(
            'SELECT * FROM `formations`
    WHERE title LIKE "%"?"%"'
        );
        $query->execute([strtolower($_POST['titleFormation'])]);
        $dats = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($dats as $dat) {
            $query = $pdo->prepare(
                'SELECT * FROM `table 1`
    WHERE Specialite LIKE "%"?"%"'
            );
            $query->execute([$dat['formName']]);
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $data) {
                if (in_array($data, $datas) == false) {
                    array_push($datas, $data);
                }
            }
        }

        //CITY & FORMATION SQL SEARCH REQUEST
    } elseif ($_POST['city'] !== "Ville" && $_POST['formation'] !== "Sous Formation") {
        $query = $pdo->prepare(
            'SELECT * FROM `table 1`
    WHERE Ville = ? && Specialite LIKE "%"?"%"'
        );
        $query->execute([$_POST['city'], strtolower($_POST['formation'])]);
        $datas = $query->fetchAll(PDO::FETCH_ASSOC);

        //FORMATION & ZIP SQL SEARCH REQUEST
    } elseif ($_POST['city'] !== "Ville" && $_POST['titleFormation'] !== "Formation") {
        $datas = [];
        $query = $pdo->prepare(
            'SELECT * FROM `formations`
    WHERE title LIKE "%"?"%"'
        );
        $query->execute([strtolower($_POST['titleFormation'])]);
        $dats = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($dats as $dat) {
            $query = $pdo->prepare(
                'SELECT * FROM `table 1`
    WHERE Specialite LIKE "%"?"%" && Ville = ?'
            );
            $query->execute([$dat['formName'], $_POST['city']]);
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $data) {
                if (in_array($data, $datas) == false) {
                    array_push($datas, $data);
                }
            }
        }

    } elseif ($_POST['depart'] !== "Departement" && $_POST['formation'] !== "Sous Formation") {
        $query = $pdo->prepare(
            'SELECT * FROM `table 1`
    WHERE Specialite LIKE "%"?"%" && Departement = ?'
        );
        $query->execute([strtolower($_POST['formation']), $_POST['depart']]);
        $datas = $query->fetchAll(PDO::FETCH_ASSOC);
        
    } elseif ($_POST['depart'] !== "Departement" && $_POST['titleFormation'] !== "Formation") {
        $datas = [];
        $query = $pdo->prepare(
            'SELECT * FROM `formations`
    WHERE title LIKE "%"?"%"'
        );
        $query->execute([strtolower($_POST['titleFormation'])]);
        $dats = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($dats as $dat) {
            $query = $pdo->prepare(
                'SELECT * FROM `table 1`
    WHERE Departement = ? && Specialite LIKE "%"?"%"'
            );
            $query->execute([$_POST['depart'], $dat['formName']]);
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $data) {
                if (in_array($data, $datas) == false) {
                    array_push($datas, $data);
                }
            }
        }
    }
}
?>

<body>
    <div id="block">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <main style="position: relative;">
            <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
            <script type='text/javascript' src='https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js'></script>
            <script>
                //MAP 
                var lati = 48.866835;
                var long = 2.438741;
                var macarte = null;
                var markerClusters;
                var markers = [];

                //DEFINE USERS AND COORDINATES
                var villes = {
                    <?php if ($datas) {
                        foreach ($datas as $data) {
                            if ($data["lat"] !== null || $data["lon"] !== null) {
                                echo '"<p>' . $data["Prenom"] . ' ' . $data["Nom"] . '</p>" : { "lati":' . $data["lat"] . ', "long":' . $data["lon"] . ', "job": "' . $data["Specialite"] . '", "adresse":"' . $data["Adresse"] . '", "postal":' . $data["Departement"] . $data["Zip"] . ', "nom":"' . $data["Nom"] . '", "prenom":"' . $data["Prenom"] . '"},'; ?>
                    <?php }
                        }
                    } ?>
                };

                var center = {
                    <?php if ($datasCenter) {
                        if ($datasCenter["lat"] !== null || $datasCenter["lon"] !== null) {
                            echo '"<p>Centre de formation</p>" : { "latiCenter":' . $datasCenter["lat"] . ', "longCenter":' . $datasCenter["lon"] . ', "adresseCenter": "' . $datasCenter["Adresse"] . '"},'; ?>
                    <?php }
                    } ?>
                };

                function initMap() {

                    //MAP DEFAULT VIEW
                    macarte = L.map('map').setView([lati, long], 5);
                    markerClusters = L.markerClusterGroup();
                    markerRefClusters = L.markerClusterGroup();
                    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                        attribution: 'données © OpenStreetMap/ODbL - rendu OSM France',
                        minZoom: 1,
                        maxZoom: 20
                    }).addTo(macarte);

                    //ADD USERS MARKERS IN MAP WITH COORDINATES
                    for (ville in villes) {

                        if (villes[ville].lati !== null || villes[ville].lati !== undefined) {
                            var marker = L.marker([villes[ville].lati, villes[ville].long]) //.addTo(macarte);
                            marker.bindPopup("<div>" + "<p>" + villes[ville].prenom + " " + villes[ville].nom + "</p>" + "<p>" + villes[ville].job + "</p>" + "</div>");
                            markerClusters.addLayer(marker);
                            markers.push(marker);
                        }
                    }
                    var iconBase = 'images/';
                    var myIcon = L.icon({
                        iconUrl: iconBase + "marker.png",
                        iconSize: [50, 50],
                    });
                    for (pos in center) {
                        var markerRef = L.marker([center[pos].latiCenter, center[pos].longCenter], {
                            icon: myIcon
                        }).addTo(macarte);
                        markerRef.bindPopup("<div>" + "<p>Centre de formation</p>" + "</div>");
                    }
                    var group = new L.featureGroup(markers); // Nous créons le groupe des marqueurs pour adapter le zoom
                    macarte.fitBounds(group.getBounds().pad(0.2));
                    macarte.addLayer(markerClusters);

                };

                //LOAD MAP
                window.onload = function() {
                    initMap();
                };
            </script>

            <div id="map" style="height:500px"></div>
            <div class="container">
                <h2>Tableau des formateurs</h2>

                <!-- SEARCH FORM -->
                <form action="index.php" id="center">
                    <label for="center-input" id="label-center"><input type="text" placeholder="Veuillez entrer l'adresse du centre de formation" id="center-input" name="center">
                        <i class="fas fa-search" id="center-button" style="cursor: pointer;"></i></label>
                </form>
                <form method="POST" action="index.php" id="form-select">
                    <select class="select sidebar-content" name="titleFormation" id="titleFormation">
                        <option selected>Formation</option>
                        <?php foreach ($datasf as $data) {
                            if ($data['title'] !== '') { ?>
                                <option value="<?= $data['title'] ?>"><?= $data['title'] ?></option>
                        <?php }
                        } ?>
                    </select>
                    <select class="select sidebar-content" name="formation" id="formation">
                        <option selected>Sous Formation</option>
                        <?php foreach ($datasForm as $data) {
                            if ($data !== '') { ?>
                                <option value="<?= $data["formName"] ?>"><?= $data["formName"] ?></option>
                        <?php }
                        } ?>
                    </select>
                    <select class="select sidebar-content" name="city" id="city">
                        <option selected>Ville</option>
                        <?php foreach ($datasVille as $data) {
                            if ($data['Ville'] !== '') { ?>
                                <option value="<?= $data['Ville'] ?>"><?= $data['Ville'] ?></option>
                        <?php }
                        } ?>
                    </select>
                    <select class="select sidebar-content" name="depart" id="depart">
                        <option selected>Département</option>
                        <?php foreach ($datasDepart as $data) {
                            if ($data['Departement'] !== '') { ?>
                                <option value="<?= $data['Departement'] ?>"><?= $data['Departement'] ?></option>
                        <?php }
                        } ?>
                    </select>
                    <input id="search-form" type="submit" value="Rechercher" name="searchButton" />
                </form>
                <!-- END SEARCH FORM -->
                <!-- SI AUCUNE RECHERCHE N'A ETE EFFECTUEE, CONTENU DE LA PAGE NORMALE -->
                <?php if (empty($_POST['searchButton'])) { ?>
                    <?php
                    if (file_exists("Tableau des formateurs.xlsx")) {
                        require_once "Classes/PHPExcel.php";
                        $path = "Tableau des formateurs.xlsx";
                        $reader = PHPExcel_IOFactory::createReaderForFile($path);
                        $excel_Obj = $reader->load($path);

                        //Get the first sheet in excel
                        $worksheet = $excel_Obj->getSheet('0');
                        $lastRow = $worksheet->getHighestRow();
                        $colomncount = $worksheet->getHighestDataColumn();
                        $colomncount_number = PHPExcel_Cell::columnIndexFromString($colomncount);
                        echo "<div id='table' class='table1 sidebar-content'><table>";
                        for ($row = 1; $row <= $lastRow; $row++) {
                            echo "<tr>";
                            for ($col = 0; $col < $colomncount_number; $col++) {
                                if ($col != 7) {
                                    echo "<th>";
                                    echo $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col) . $row)->getValue();
                                    echo "</th>";
                                }
                            }
                            echo "</tr>";
                        }
                        echo "</table></div>";
                    }
                    ?>
                    <!-- END OF XLSX FILE -->
            </div>
            <!-- UPLOAD FILE FORM -->
            <?php if ($_SESSION['role'] === "admin") { ?>

                <div id="objForm">
                    <p>Déposer un fichier (.xlsx)</p>
                </div>
                <div style="position: relative;">
                    <div class="form-add">
                        <span id="cross">X</span>
                        <h2>Déposer votre fichier excel</h2>
                        <form action="index.php" method="post" enctype="multipart/form-data" id="form-upload">
                            <div class="style-input">
                                <label for="excel-file"><span id="filename">Votre fichier(.xlsx)</span><i class="fas fa-search"></i><input type="file" id="excel-file" accept=".xlsx" name="excelFile"></label>
                            </div>
                            <p id="wait"></p>
                        </form>
                    </div>
                </div>
                <!-- AJOUT FORMATEUR MANUEL -->
                <div id="add-formateur">
                    Voulez-vous ajouter un formateur?
                    <form action="index.php" id="form-add-formateur" method="post">
                        <label for="add-firstname">Nom :</label>
                        <input type="text" name="addFirstName" id="add-firstname">
                        <label for="add-lastname">Prénom :</label>
                        <input type="text" name="addLastName" id="add-lastname">
                        <label for="add-mail">Mail :</label>
                        <input type="mail" name="addMail" id="add-mail">
                        <label for="add-spe">Specialité :</label>
                        <textarea name="addSpe" id="add-spe" cols="30" rows="10"></textarea>
                        <label for="add-ville">Ville :</label>
                        <input type="text" name="addVille" id="add-ville">
                        <label for="add-depart">Département :</label>
                        <input type="number" name="addDepart" id="add-depart">
                        <label for="add-zip">Zip :</label>
                        <input type="number" name="addZip" id="add-zip">
                        <label for="add-adresse">Adresse :</label>
                        <input type="text" name="addAdresse" id="add-adresse">
                        <label for="add-phone">Téléphone :</label>
                        <input type="text" name="addPhone" id="add-phone">
                        <button type="button" name="addFormateurSubmit" id="add-formateur-submit">Ajouter</button>
                    </form>
                </div>
                <!-- END OF UPLOAD FILE FORM -->
            <?php } ?>
            <!-- SI UNE RECHERCHE A ETE EFFECTUEE -->
        <?php } else { ?>
            <!-- RETOUR A LA PAGE INDEX -->
            <button id="back-tab" class="back"><a href="index.php">Retour au tableau</a></button>

            <!-- TITRE AVEC LA RECHERCHE EFFECTUEE -->
            <h1 class="title-search"><?php if (empty($_POST) === false) { ?>
                    <span style="color:red"><?= count($datas) ?></span> résultats de recherches pour :
                    <?php if ($_POST['city'] == "Ville" && $_POST['depart'] == "Département" && $_POST['titleFormation'] == "Formation") { ?>
                        "<?= $_POST['formation'] ?>"
                    <?php } elseif ($_POST['formation'] == "Sous Formation" && $_POST['depart'] == "Département" && $_POST['titleFormation'] == "Formation") { ?>
                        "<?= $_POST['city'] ?>"
                    <?php } elseif ($_POST['formation'] == "Sous Formation" && $_POST['depart'] == "Département" && $_POST['city'] == "Ville") { ?>
                        "<?= $_POST['titleFormation'] ?>"
                    <?php } elseif ($_POST['formation'] == "Sous Formation" && $_POST['city'] == "Ville" && $_POST['titleFormation'] == "Formation") { ?>
                        "<?= $_POST['depart'] ?>"
                    <?php } elseif ($_POST['depart'] == "Département" && $_POST['titleFormation'] == "Formation") { ?>
                        "<?= $_POST['formation'] ?> à <?= $_POST['city'] ?>"
                    <?php } elseif ($_POST['city'] == "Ville" && $_POST['titleFormation'] == "Formation") { ?>
                        "<?= $_POST['formation'] ?> dans le <?= $_POST['depart'] ?> "
                    <?php } elseif ($_POST['formation'] == "Sous Formation" && $_POST['depart'] == "Département") { ?>
                        "<?= $_POST['titleFormation'] ?> à <?= $_POST['city'] ?>"
                    <?php } elseif ($_POST['city'] == "Ville" && $_POST['formation'] == "Sous Formation") { ?>
                        "<?= $_POST['titleFormation'] ?> dans le <?= $_POST['depart'] ?> "
                    <?php } elseif ($_POST['city'] == "Ville" && $_POST['formation'] == "Sous Formation" && $_POST['depart'] == "Département" && $_POST['titleFormation'] == "Formation") { ?>
                        <p>Veuillez saisir un champ</p>
                <?php }
                                        } ?>

            </h1>
            <!-- RESULTATS AFFICHES DANS UN TABLEAU OU UNE LISTE SELON LA TAILLE DE L'ECRAN -->
            <?php if (empty($datas)) { ?>
                <p>Désolé, ce résultat ne correspond à aucun formateur dans nos données</p>
            <?php } else { ?>
                <div id='table' class="sidebar-content">
                    <table>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Mail</th>
                            <th>Spécialité</th>
                            <th>Ville</th>
                            <th>Département</th>
                            <th>Zip</th>
                            <th>Adresse</th>
                            <th>Tel</th>
                        </tr>


                        <?php foreach ($datas as $data) { ?>
                            <tr>
                                <th><?= $data['Nom'] ?></th>
                                <th><?= $data['Prenom'] ?></th>
                                <th><?= $data['Mail'] ?></th>
                                <th>
                                    <?php if (($_POST['titleFormation']) !== 'Formation') {
                                        $exx = explode(",", $data['Specialite']);
                                        $pdo->exec('SET NAMES UTF8');
                                        $query = $pdo->prepare(
                                            'SELECT formName FROM `formations` WHERE title LIKE "%"?"%"'
                                        );
                                        $query->execute([strtolower($_POST['titleFormation'])]);
                                        $resultqs = $query->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($exx as $ex) {
                                            foreach ($resultqs as $result) {
                                                if (strtolower($ex) == strtolower($result['formName'])) {
                                                    echo $ex . ', ';
                                                }
                                            }
                                        }
                                    } else {
                                        echo $data['Specialite'];
                                    }
                                    ?>
                                </th>
                                <th><?= $data['Ville'] ?></th>
                                <th><?= $data['Departement'] ?></th>
                                <th><?= $data['Zip'] ?></th>
                                <th><?= $data['Adresse'] ?></th>
                                <th><?= $data['Tel'] ?></th>
                            </tr>
                    <?php }
                    } ?>
                    </table>
                </div>
                <!-- SI ECRAN PLUS PETIT QUE TABLETTE, AFFICHAGE EN LISTE -->
                <div id="donnees">
                    <ul>
                        <?php foreach ($datas as $data) { ?>
                            <li>
                                <strong><?= $data['Nom'] ?> <?= $data['Prenom'] ?></strong>
                                <p>Email - <?= $data['Mail'] ?></p>
                                <p>Formation - <?= $data['Specialite'] ?></p>
                                <p>Ville - <?= $data['Ville'] ?></p>
                                <p>Code Postal - <?= $data['Departement'] ?> <?= $data['Zip'] ?></p>
                                <p>Adresse - <?= $data['Adresse'] ?></p>
                                <p>Téléphone - <?= $data['Tel'] ?></p>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- END OF RESULTS -->

            <?php } ?>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
            <?php if ($_SESSION['role'] === "admin") { ?>
                <script src="getData.js"></script>
            <?php } ?>
            <script src="main.js"></script>
        </main>
    </div>
</body>

</html>