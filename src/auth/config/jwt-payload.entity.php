<?php

namespace Auth\Models;

use Auth\Interfaces\JwtPayload;

class JwtPayloadData implements JwtPayload
{
    private int $id;
    private string $email;

    /**
     * JwtPayloadData constructor.
     * 
     * @param int $id The user ID.
     * @param string $email The user email.
     */
    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    /**
     * Get the user ID.
     * 
     * @return int The user ID.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the user email.
     * 
     * @return string The user email.
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
