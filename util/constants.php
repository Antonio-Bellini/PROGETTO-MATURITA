<?php
    // reindirizzo alla home se si cerca di accedere a questa pagina
    if (strpos($_SERVER['REQUEST_URI'], "/util/constants.php") !== false) {
        header('Location: ../index.php');
        exit;
    }

    // impostazione rapida del db
    define("DB_HOST", "localhost");
    define("DB_NAME", "my_testzerotre");
    define("DB_USER", "root");
    define("DB_ADMIN", "root");
    define("DB_PRESIDENT", "root");
    define("DB_TERAPIST", "root");
    define("PRESIDENT_PW", "");
    define("ADMIN_PW", "");
    define("TERAPIST_PW", "");
    define("USER_PW", "");

    // web service web all (Salerno-Rocca)
    define("WEBALL", "<script src='http://52.47.171.54:8080/init.js'></script>");

    // stampa degli errori
    define("ERROR_GEN", "<div class='error' id='message'>Si é verificato un errore imprevisto, riprova piú tardi</div>");
    define("ERROR_DB", "<div class='error' id='message'>Si é verificato un problema interrogando il database, riprova piú tardi</div>");
    define("ERROR_PW", "<div class='error' id='message'>Hai inserito una password errata, riprova</div>");
    define("ERROR_FILE", "<div class='error' id='message'>Si é verificato un errore durante il caricamento dei file</div>");
    define("ERROR_UNK_USER", "<div class='error' id='message'>L'username inserito non esiste</div>");
    define("ERROR_ALREADY_ADDED", "<div class='error' id='message'>L'utente é gia stato inserito a questo evento</div>");
    define("IMPB_DEL", "<div class='error'>Non puoi eliminare l'admin del sito!</div>");

    // stampa degli esiti
    define("MOD_OK", "<div class='success' id='message'>Modifiche eseguite correttamente</div>");
    define("MOD_NONE", "<div class='success' id='message'>Nessuna modifica eseguita</div>");
    define("DEL_OK", "<div class='success' id='message'>Account eliminato correttamente</div>");
    define("ACC_OK", "<div class='success' id='message'>Account creato correttamente</div>");
    define("EVENT_OK", "<div class='success' id='message'>Evento aggiunto correttamente</div>");
    define("EVENT_ADD", "<div class='success' id='message'>Aggiunto all'evento correttamente</div>");
    define("EVENT_DEL", "<div class='success' id='message'>Evento eliminato correttamente</div>");
    define("FILE_OK", "<div class='success' id='message'>File caricato correttamente</div>");
    define("FILE_DEL", "<div class='success' id='message'>File eliminato correttamente</div>");

    define("NO_FILE", "<div class='warning' id='message'>Nessun file selezionato</div>");
    define("NO_FORM", "<div class='warning' id='message'>Nessun form compilato</div>");

    define("RESULT_NONE", "<br>Nessun risultato trovato<br>");

    define("DISCONNECTION", "<div class='success'>Disconnessione eseguita con successo, a presto!</div>");
?>