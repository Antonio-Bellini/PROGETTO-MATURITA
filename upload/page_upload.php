<!DOCTYPE html>
<html lang="en">
<?php
    include "../util/constants.php";
    include "../util/command.php";
    include "../util/connection.php";

    echo "
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <script src='https://kit.fontawesome.com/a730223cdf.js' crossorigin='anonymous'></script>
            <script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>";
    echo    WEBALL;
    echo "  <script src='../script/script.js'></script>
            <link rel='stylesheet' href='../style/style.css'>
            <link rel='icon' href='../image/logos/logo.png' type='x-icon'>
            <title>Associazione Zero Tre</title>
        </head>";

        session_start();

        $connection = connectToDatabase(DB_HOST, DB_ADMIN, ADMIN_PW, DB_NAME);

    nav_menu();

    if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]) {
        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
            echo " <br>
            <section id='form'>
                <h2>Pagina per il caricamento delle liberatorie</h2>
                <h3>Assicurati di caricare solo il file PDF firmato</h3><br><br>
                <label for='choice'>Per chi vuoi caricare la liberatoria?</label>
                <select name='choice' id='upload_choice'>
                    <option value='1'>Assistito</option>
                    <option value='2'>Volontario</option>
                </select>

                <br><br>

                <!-- form per caricamento del file per gli assistiti -->
                <section id='form_assisted'>
                    <form action='upload.php' method='POST' enctype='multipart/form-data' name='release_assisted'>
                        <input type='hidden' name='user_type' value='assisted'>

                        <label for='assisted'>Per quale assistito vuoi caricare la liberatoria?</label>
                        <select name='assisted' id='assisted'>";
                            if (isset($_SESSION["profile"]) && $_SESSION["profile"] === "release") {
                                $release = $_SESSION["user"];
                                $query = "SELECT a.id, a.nome, a.cognome 
                                            FROM assistiti a 
                                            LEFT JOIN liberatorie l ON a.id_liberatoria = l.id
                                            WHERE l.id = $release";
                                $result = dbQuery($connection, $query);

                                if ($result) {
                                    while ($row = ($result->fetch_assoc()))
                                        echo "<option value=" . $row["id"] . ">" . $row["nome"] . " " . $row["cognome"] . "</option>";
                                } else 
                                    echo ERROR_DB;
                            } else {
                                $query = "SELECT id, nome, cognome FROM assistiti";
                                $result = dbQuery($connection, $query);

                                if ($result) {
                                    while ($row = ($result->fetch_assoc()))
                                        echo "<option value=" . $row["id"] . ">" . $row["nome"] . " " . $row["cognome"] . "</option>";
                                } else 
                                    echo ERROR_DB;
                            }
            echo "      </select>

                        <label for='release'>Seleziona il file che vuoi caricare</label>
                        <input type='file' name='release' accept='.pdf' required>

                        <label for='notes'>Inserisci qualche nota aggiuntiva sul file</label>
                        <textarea name='notes' id='notes' cols='30' rows='10' placeholder='Info utili sul file'></textarea>

                        <input type='submit' value='CARICA FILE'></input>
                    </form>
                </section>

                <!-- form per caricamento del file per i volontari -->
                <section id='form_volunteer'>
                    <form action='upload.php' method='POST' enctype='multipart/form-data' name='release_volunteer'>
                        <input type='hidden' name='user_type' value='volunteer'>

                        <label for='assisted'>Per quale volontario vuoi caricare la liberatoria?</label>
                        <select name='volunteer' id='volunteer'>";
                            $connection = connectToDatabase(DB_HOST, DB_ADMIN, ADMIN_PW, DB_NAME);

                            if (isset($_GET["release"])) {
                                $release = $_GET["release"];
                                $query = "SELECT a.id, nome, cognome 
                                            FROM volontari a 
                                            LEFT JOIN liberatorie l ON a.id_liberatoria = l.id
                                            WHERE l.id = $release";
                                $result = dbQuery($connection, $query);

                                if ($result) {
                                    while ($row = ($result->fetch_assoc()))
                                        echo "<option value=" . $row["id"] . ">" . $row["nome"] . " " . $row["cognome"] . "</option>";
                                } else 
                                    echo ERROR_DB;
                            } else {
                                $query = "SELECT id, nome, cognome FROM volontari";
                                $result = dbQuery($connection, $query);

                                if ($result) {
                                    while ($row = ($result->fetch_assoc()))
                                        echo "<option value=" . $row["id"] . ">" . $row["nome"] . " " . $row["cognome"] . "</option>";
                                } else 
                                    echo ERROR_DB;
                            }
            echo "      </select>

                        <label for='release'>Seleziona il file che vuoi caricare</label>
                        <input type='file' name='release' accept='.pdf' required>

                        <label for='notes'>Inserisci qualche nota aggiuntiva sul file</label>
                        <textarea name='notes' id='notes' cols='30' rows='10' placeholder='Info utili sul file'></textarea>

                        <input type='submit' value='CARICA FILE'></button>
                    </form>
                </section>
            </section>";
        } else 
            header("Location: ../index.php");
    } else 
        header("Location: ../index.php");
    
    show_footer(); 
?>
</body>
</html>