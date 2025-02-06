<?php

namespace Auth\Providers;

require __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

class ArgonProvider
{
    /**
     * Hashes the given password using bcrypt.
     * 
     * @param string $data The password to hash.
     * @return string The hashed password.
     */
    public function hashPassword(string $data): string
    {
        $options = [
            'cost' => $_ENV['SALT'],
        ];

        $hashedPassword = password_hash($data, PASSWORD_BCRYPT, $options);
        return $hashedPassword;
    }

    /**
     * Compares a given password with a hashed password.
     * 
     * @param string $data The password to compare.
     * @param string $encrypted The hashed password.
     * @return bool True if the passwords match, false otherwise.
     */
    public function comparePassword(string $data, string $encrypted): bool
    {
        return password_verify($data, $encrypted);
    }
}

class Argon2idProvider
{
    /**
     * Hashes the given password using Argon2id.
     * 
     * @param string $data The password to hash.
     * @return string The hashed password.
     */
    public function hashPassword(string $data): string
    {
        $options = [
            'memory_cost' => $_ENV['ARGON2ID_MEMORY_COST'] ?? PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost' => $_ENV['ARGON2ID_TIME_COST'] ?? PASSWORD_ARGON2_DEFAULT_TIME_COST,
            'threads' => $_ENV['ARGON2ID_THREADS'] ?? PASSWORD_ARGON2_DEFAULT_THREADS,
        ];

        $hashedPassword = password_hash($data, PASSWORD_ARGON2ID, $options);
        return $hashedPassword;
    }

    /**
     * Compares a given password with a hashed password.
     * 
     * @param string $data The password to compare.
     * @param string $encrypted The hashed password.
     * @return bool True if the passwords match, false otherwise.
     */
    public function comparePassword(string $data, string $encrypted): bool
    {
        return password_verify($data, $encrypted);
    }
}
