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

        nav_menu();
    
    $connection = connectToDatabase(DB_HOST, DB_ADMIN, ADMIN_PW, DB_NAME);

    if (!isset($_SESSION["is_admin"]))
        $_SESSION["is_admin"] = false;
    
    if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]) {
        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
            $query = "SELECT id, nome, cognome FROM utenti WHERE id_tipo_profilo = 4";
            $result = dbQuery($connection, $query);

            if ($result) {
                echo "<br>
                        <section id='form'>
                            <h2>Pagina di registrazione di un assistito</h2><br>
                            <form name='form_assisted' action='register.php' id='form_register__assisted' method='POST' enctype='multipart/form-data'>
                                <input type='hidden' name='form_assisted'>

                                <label for='parent'>Chi é il referente?&nbsp;</label;>
                                <select name='parent'>";
                                    while ($row = ($result->fetch_assoc()))
                                        echo "<option value='" . $row["id"] . "'>" . $row["nome"] . " " . $row["cognome"] . "</option>";
                                echo "</select>

                                <div class='div__label'>
                                    <label for='name'>Inserisci il nome dell'assistito *</label>
                                    <label for='surname'>Inserisci il cognome dell'assistito *</label>
                                </div>
                                <div class='div__input'>
                                    <input type='text' name='name' id='name' maxlength='255' required>
                                    &nbsp;&nbsp;
                                    <input type='text' name='surname' id='surname' maxlength='255' required>
                                </div>

                                <div class='div__label'>
                                    <label for='med'>Inserisci il file dell'anamnesi</label>
                                    <label for='rel'>Inserisci il file liberatoria *</label>
                                </div>
                                <div class='div__input'>
                                    <input type='file' name='med' id='med' accept='.pdf'>
                                    &nbsp;&nbsp;
                                    <input type='file' name='rel' id='rel' accept='.pdf' required>
                                </div>

                                <label for='notes'>Inserisci qualche nota aggiuntiva sull'assistito</label>
                                <textarea name='notes' id='notes' cols='30' rows='10' placeholder='Altre info utili'></textarea>

                                <input type='submit' value='CREA ACCOUNT ASSISTITO'>
                            </form>
                        </section>";
            } else 
                echo ERROR_DB;

            show_footer();
        } else 
            header("Location: ../index.php");
    } else 
        header("Location: ../private/page_login.php");
?>
</body>
</html>