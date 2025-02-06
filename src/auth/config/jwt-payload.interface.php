<?php

namespace Auth\Interfaces;

interface JwtPayload
{
    public function getId(): int;
    public function getEmail(): string;
}
