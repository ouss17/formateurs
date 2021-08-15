<?php

include('database/Database.class.php');

$query = $pdo->prepare(
    'TRUNCATE `center`'
);
$query->execute([]);
