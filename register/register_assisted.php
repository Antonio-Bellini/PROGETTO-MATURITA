<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../script/script.js"></script>
    <link rel="stylesheet" href="../style/style.css">
    <title>Associazione ZeroTre</title>
</head>
<!-- STAMPA del BODY in BASE al COOKIE SALVATO -->
<?php
    include "../util/cookie.php";
    importActualStyle();
?>
    <?php
        include "../util/command.php";
        include "../util/connection.php";
        $connection = connectToDatabase(DB_HOST, USER_ADMIN, ADMIN_PW, DB_NAME);

        // menu di navigazione
        echo "<main>
                <section class='header'>
                    <nav>
                        <a href='../index.php'>
                            <img 
                                src='../image/logos/logo.png'
                                class='logo'
                                id='logoImg'
                                alt='logo associazione'
                            />
                        </a>
                        <div class='nav_links' id='navLinks'>
                            <ul>
                                <li><a href='../newsletter.php'             class='btn'>Newsletter   </a></li>
                                <li><a href='../bacheca.php'                class='btn'>Bacheca       </a></li>
                                <li><a href='https://stripe.com/it'         class='btn'>Donazioni     </a></li>
                                <li><a href='../private/area_personale.php'    class='btn'>Area Personale</a></li>
                            </ul>
                        </div>
                    </nav>            
                </section>
            </main>";

        session_start();
        if (!isset($_SESSION["is_admin"]))
            $_SESSION["is_admin"] = false;
        
        if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]) {
            if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
                if (($_SESSION["profile_func"] === "gestione DB") && ($_SESSION["user_auth"] === "CRUD")) {
                    echo "<h1>Pagina di registrazione di un assistito</h1>";
                    echo "<br>Chi é il referente?";
                    $query = "SELECT id, nome, cognome FROM utenti";
                    $result = dbQuery($connection, $query);

                    if ($result) {
                        echo "<main>
                                <form action='register.php' name='form_assisted' id='form_register__assisted' method='POST' enctype='multipart/form-data'>
                                    <input type='hidden' name='form_assisted'>

                                    <select name='parent'>";
                                        while ($row = ($result->fetch_assoc()))
                                            echo "<option value='" . $row["id"] . "'>" . $row["nome"] . " " . $row["cognome"] . "</option>";
                                    echo "</select><br>

                                    <label for='name'>Inserisci il nome</label>
                                    <input type='text' name='name' id='name' maxlength='30' required> <br>

                                    <label for='surname'>Inserisci il cognome</label>
                                    <input type='text' name='surname' id='surname' maxlength='30' required> <br>

                                    <label for='med'>Inserisci il file dell'anamnesi</label>
                                    <input type='file' name='med' id='med' accept='.pdf' enctype='multipart/form-data' required> <br>

                                    <label for='notes'>Inserisci qualche nota aggiuntiva</label> <br>
                                    <textarea name='notes' id='notes' cols='30' rows='10' placeholder='Altre info utili'></textarea><br><br>

                                    <input type='submit' value='REGISTRA'><br><br>
                                </form>
                            </main>";
                    }
                }
            } else 
            header("Location: ../private/loginPage.php");
        } else 
            header("Location: ../private/loginPage.php");
    ?>
</body>
</html>