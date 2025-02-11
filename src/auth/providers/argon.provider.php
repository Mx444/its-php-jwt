<?php

include __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(paths: __DIR__ . '/../../../');
$dotenv->load();

class Argon2idProvider
{
    public function hashPassword(string $data): string
    {
        $options = [
            'memory_cost' => $_ENV['ARGON2ID_MEMORY_COST'] ?? PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost' => $_ENV['ARGON2ID_TIME_COST'] ?? PASSWORD_ARGON2_DEFAULT_TIME_COST,
            'threads' => $_ENV['ARGON2ID_THREADS'] ?? PASSWORD_ARGON2_DEFAULT_THREADS,
        ];

        $hashedPassword = password_hash(password: $data, algo: PASSWORD_ARGON2ID, options: $options);
        return $hashedPassword;
    }

    public function comparePassword(string $data, string $encrypted): bool
    {
        return password_verify(password: $data, hash: $encrypted);
    }
}
