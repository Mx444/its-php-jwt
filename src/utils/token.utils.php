<?php

function isAuthenticated(): void
{
    $isAuthenticated = isset($_SESSION['access_token']);
    if ($isAuthenticated) {
        header("Location: ../dashboard/index.php");
        exit();
    }
}

function isNotAuthenticated(): void
{
    $isAuthenticated = isset($_SESSION['access_token']);
    if (!$isAuthenticated) {
        header("Location: ./login.php");
        exit();
    }
}
