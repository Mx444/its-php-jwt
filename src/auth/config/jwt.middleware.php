<?php
include __DIR__ . '/../../auth/config/jwt-strategy.php';


class AuthMiddleware
{
    private JwtStrategy $jwtService;

    public function __construct(JwtStrategy $jwtService)
    {
        $this->jwtService =  $jwtService;
    }

    public function validateToken(): ?array
    {
        if (!isset($_SESSION['access_token']) || !isset($_SESSION['refresh_token'])) {
            header(header: "Location: index.php");
            exit();
        }

        try {
            $tokenData = $this->jwtService->validateJwt(jwt: $_SESSION['access_token']);
            if (is_array(value: $tokenData) && isset($tokenData['id'])) {
                return [
                    'id' => $tokenData['id'],
                    'email' => $tokenData['email'],
                    'role' => $tokenData['role'],
                    'token' => $_SESSION['access_token']
                ];
            } else {
                throw new Exception(message: 'Invalid access token');
            }
        } catch (Exception $e) {
            try {
                $newAccessToken = $this->jwtService->refreshAccessToken(refreshToken: $_SESSION['refresh_token']);
                $_SESSION['access_token'] = $newAccessToken;
                $tokenData = $this->jwtService->validateJwt(jwt: $newAccessToken);
                return [
                    'id' => $tokenData['id'],
                    'email' => $tokenData['email'],
                    'role' => $tokenData['role'],
                    'token' => $newAccessToken
                ];
            } catch (Exception $e) {
                header(header: "Location: index.php");
                exit();
            }
        }
    }
}
