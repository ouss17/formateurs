<?php

include('database/Database.class.php');

$query = $pdo->prepare(
    'TRUNCATE `table 1`'
);
$query->execute([]);
