<?php
function sendResponse(int $statusCode, string $type, string $message, string $location): void
{
    http_response_code($statusCode);
    $_SESSION[$type] = $message;
    header(header: "Location: $location");
    exit();
}

function validateRequiredFields(array $data, array $requiredFields, string $errorMessage, string $location): bool
{
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            $_SESSION['error'] = $errorMessage;
            http_response_code(400);
            header(header: "Location: $location");
            exit();
        }
    }
    return false;
}
