<?php

function isAuthenticated(): void
{
    $isAuthenticated = isset($_SESSION['access_token']);
    if ($isAuthenticated) {
        header("Location: /php-auth/src/public/index.php");
        exit();
    }
}

function isNotAuthenticated(): void
{
    $isAuthenticated = isset($_SESSION['access_token']);
    if (!$isAuthenticated) {
        header("Location: /php-auth/src/public/auth/login.php");
        exit();
    }
}
