<?php

namespace Utilis;

use Exception;

function validateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email non valida.");
    }
}

function validatePassword($password)
{
    if (strlen($password) < 6) {
        throw new Exception("La password deve contenere almeno 6 caratteri.");
    }

    if (!preg_match('/[A-Za-z]/', $password)) {
        throw new Exception("La password deve contenere almeno una lettera.");
    }
    if (!preg_match('/\d/', $password)) {
        throw new Exception("La password deve contenere almeno un numero.");
    }
    if (!preg_match('/[\W_]/', $password)) {
        throw new Exception("La password deve contenere almeno un carattere speciale.");
    }
}
