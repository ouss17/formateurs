<?php
include('database/Database.class.php');

//récupérer les données envoyées par js
$firstName = $_GET['firstName'];
$lastName = $_GET['lastName'];
$email = $_GET['email'];
$city = $_GET['city'];
$inter = $_GET['inter'];
$postal1 = $_GET['postal1'];
$postal2 = $_GET['postal2'];
$id = $_GET['id'];
$date = $_GET['date'];
$adresse = str_replace("-", " ", $_GET['adresse']);
$num = $_GET['num'];
$lat = $_GET['lat'];
$lon = $_GET['lon'];

if ($postal2 === "null" || $postal2 === null) {
    $postal2 = null;
}
if ($lat === "null" || $lat === null) {
    $lat = null;
}
if ($lon === "null" || $lon === null) {
    $lon = null;
}

//remplacement de mauvaise lecture html par des espaces, accents etc...
$fullUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$splitJob = strpos($fullUrl, "job=");
$urlJob = substr($fullUrl, $splitJob);
$splitCity = strpos($urlJob, "city=");
$jobs = substr($urlJob, 4, $splitCity - 5);
$jobSpace = str_replace("%20", " ", $jobs);
$jobAccent = str_replace("%C3%A9", "é", $jobSpace);
$jobAcc = str_replace("%C3%A8", "è", $jobAccent);
$job = str_replace("%E2%80%99", "'", $jobAcc);



//SQL REQUEST
$query = $pdo->prepare(
    'INSERT into `table 1` (Nom,Prenom,Mail,Specialite,Ville,Interventions,Departement,Zip, date, Adresse, Tel, lat, lon)
                   values (?,?,?,?,?,?,?,?,?,?,?,?,?)'
);
$var = $query->execute([
    $firstName,
    $lastName,
    $email,
    $job,
    $city,
    $inter,
    $postal1,
    $postal2,
    $date,
    $adresse,
    $num,
    $lat,
    $lon,
]);


//VERIFY IF THERE IS ERRORS 
if ($var) {
    // echo $urlForm;
    // echo $formation;
} else {
    echo "fail " . $firstName . " " . $lastName . " " . $email . " " . $job . " " . $city . " " . $postal1 . " " . $postal2 . " " . $adresse . " " . $date . " " . $lat . " " . $lon . " " . $num;
}
