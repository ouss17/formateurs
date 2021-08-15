<?php
include('database/Database.class.php');

//récupérer les données envoyées par js
$adresse = $_GET['adresse'];
$lat = $_GET['lat'];
$lon = $_GET['lon'];

// définir latitude et longitude a null pour éviter des erreurs sql si l'api n'en a pas trouvé
if ($lat === "null" || $lat === null) {
    $lat = null;
}
if ($lon === "null" || $lon === null) {
    $lon = null;
}

//SQL requete Ajout centre en base de données
$query = $pdo->prepare(
    'INSERT into `center` (Adresse, lat, lon)
                   values (?,?,?)'
);
$var = $query->execute([
    $adresse,
    $lat,
    $lon,
]);