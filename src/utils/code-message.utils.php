<?php

enum CodeMessage: string
{
    case ERROR_REQUIRED_FIELDS = 'Email e password sono obbligatori.';
    case ERROR_UPDATE_FIELDS = 'Nuova email e vecchia password sono obbligatorie.';
    case ERROR_PASSWORD_FIELDS = 'Vecchia password e nuova password sono obbligatorie.';
    case ERROR_DELETE_FIELDS = 'La password è obbligatoria.';
    case SUCCESS_REGISTER = 'Registrazione effettuata con successo.';
    case SUCCESS_LOGIN = 'Accesso effettuato con successo.';
    case SUCCESS_UPDATE_EMAIL = 'Email aggiornata con successo.';
    case SUCCESS_UPDATE_PASSWORD = 'Password aggiornata con successo.';
    case SUCCESS_DELETE_ACCOUNT = 'Account eliminato con successo.';
}
