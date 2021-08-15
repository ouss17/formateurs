<?php

function verifyPassword($password, $hashedPassword){
    return crypt($password, $hashedPassword) == $hashedPassword;
}

function hashPassword($password){

    $salt = '$2y$11$'.substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);

    return crypt($password, $salt);
}


?>