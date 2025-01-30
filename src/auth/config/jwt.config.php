<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

class JwtConfig
{
    public $secret;
    public $issuer;
    public $audience;
    public $expiration;
    public $head;


    public function __construct($secret, $issuer, $audience, $expiration, $head)
    {
        $this->secret = $secret;
        $this->issuer = $issuer;
        $this->audience = $audience;
        $this->expiration = $expiration;
        $this->head = $head;
    }
}

function jwtConfig()
{
    $secret = $_ENV['SECRET'];
    $issuer = $_ENV['ISSUER'];
    $audience = $_ENV['AUDIENCE'];
    $expiration = $_ENV['EXP'];
    $head = $_ENV['HEAD'];

    return new JwtConfig($secret, $issuer, $audience, $expiration, $head);
}
