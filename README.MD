# PHP Exercise 🚀

Benvenuti nel progetto **Steve Jobs Academy PHP**! Questo progetto è un esempio di applicazione PHP che utilizza diverse librerie e strumenti per la gestione delle variabili d'ambiente, autenticazione JWT e altro ancora.

## Struttura del Progetto 📂

- **src/**: Contiene il codice sorgente dell'applicazione.
    - **auth/**: Moduli di autenticazione.
    - **database/**: Provider di connessione al database.
    - **utilis/**: Utilità varie.
- **vendor/**: Contiene le dipendenze del progetto gestite da Composer.
- **.env**: File per le variabili d'ambiente.
- **composer.json**: File di configurazione di Composer.
- **README.md**: Questo file!

## Dipendenze 📦

Il progetto utilizza le seguenti librerie:

- **vlucas/phpdotenv**: Per la gestione delle variabili d'ambiente.
- **firebase/php-jwt**: Per la gestione dei JSON Web Token.
- **symfony/polyfill-php80**: Per la compatibilità con PHP 8.0.
- **phpoption/phpoption**: Per la gestione delle opzioni in PHP.
- **graham-campbell/result-type**: Implementazione del tipo Result.

## Installazione 🛠️

1. Clona il repository:
     ```bash
     git clone https://github.com/tuo-username/steve-jobs-accademy-php.git
     ```
2. Installa le dipendenze:
     ```bash
     composer install
     ```
3. Crea un file `.env` nella root del progetto e configura le tue variabili d'ambiente:
     ```env
     DB_HOST=localhost
     DB_USER=root
     DB_PASSWORD=secret
     DB_NAME=steve_jobs_academy
     ```

## Utilizzo 🚀

Per avviare l'applicazione, esegui il server PHP integrato:
```bash
php -S localhost:8000 -t public
```

## Contribuire 🤝

Se desideri contribuire al progetto, sentiti libero di fare una fork del repository e inviare una pull request. Ogni contributo è benvenuto!

## Licenza 📄

Questo progetto è distribuito sotto la licenza MIT. Vedi il file [LICENSE](LICENSE) per maggiori dettagli.

---