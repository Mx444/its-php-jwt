<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

class BcryptProvider
{

    private function generateSalt()
    {
        $length = $_ENV['LENGTH'];
        return bin2hex(random_bytes($length));
    }

    public function hashPassword($data)
    {
        $salt = $this->generateSalt();
        $options = [
            'cost' => $_ENV['SALT'],
            'salt' => $salt
        ];

        $hashedPassword = password_hash($data, PASSWORD_BCRYPT, $options);
        return $hashedPassword;
    }

    public function comparePassword($data, $encrypted)
    {
        return password_verify($data, $encrypted);
    }
}
