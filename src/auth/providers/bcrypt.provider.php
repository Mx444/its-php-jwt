<?php

require_once __DIR__ . '/../../../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(paths: __DIR__ . '/../../../');
$dotenv->load();

class BcryptProvider
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

        $hashedPassword = password_hash(password: $data, algo: PASSWORD_BCRYPT, options: $options);
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
        return password_verify(password: $data, hash: $encrypted);
    }
}
