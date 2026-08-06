<?php

function createNewUser($dbConnection)
{
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
    $email = $_POST['email'];

    $query = "INSERT INTO users (username, password_hash, email)
            VALUES (:login, :password, :email)";

    $user = $dbConnection->prepare($query);

    $user->execute(['login' => $login, 'password' => $password, 'email' => $email]);
}
