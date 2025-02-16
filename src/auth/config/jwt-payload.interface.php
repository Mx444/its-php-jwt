<?php
interface JwtPayload
{
    public function getId(): int;
    public function getEmail(): string;
    public function getRole(): string;
}
