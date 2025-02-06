<?php
include __DIR__ . '/../../auth/config/jwt-strategy.php';


class AuthMiddleware
{
    private JwtService $jwtService;

    /**
     * AuthMiddleware constructor.
     * Initializes the JwtService.
     */
    public function __construct(JwtService $jwtService)
    {
        $this->jwtService =  $jwtService;
    }

    /**
     * Validates the JWT token stored in the session.
     * 
     * @return array|null Returns user data if the token is valid, otherwise redirects to index.php.
     */
    public function validateToken(): ?array
    {
        if (!isset($_SESSION['access_token']) || !isset($_SESSION['refresh_token'])) {
            header("Location: index.php");
            exit();
        }

        try {
            // Validate the access token
            $tokenData = $this->jwtService->validateJwt($_SESSION['access_token']);
            if (is_array($tokenData) && isset($tokenData['id'])) {
                return [
                    'id' => $tokenData['id'],
                    'email' => $tokenData['email'],
                    'token' => $_SESSION['access_token']
                ];
            } else {
                throw new Exception('Invalid access token');
            }
        } catch (Exception $e) {
            // If access token is invalid, try to refresh it using the refresh token
            try {
                $newAccessToken = $this->jwtService->refreshAccessToken($_SESSION['refresh_token']);
                $_SESSION['access_token'] = $newAccessToken;
                $tokenData = $this->jwtService->validateJwt($newAccessToken);
                return [
                    'id' => $tokenData['id'],
                    'email' => $tokenData['email'],
                    'token' => $newAccessToken
                ];
            } catch (Exception $e) {
                // If refresh token is also invalid, redirect to login
                header("Location: index.php");
                exit();
            }
        }
    }
}
