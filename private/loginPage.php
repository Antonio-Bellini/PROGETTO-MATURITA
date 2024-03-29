<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Associazione ZeroTre</title>
</head>

<!-- STAMPA del BODY in BASE al COOKIE SALVATO -->
<?php
    include "../util/cookie.php";
    importActualStyle();
?>

    <!-- SEZIONE PRINCIPALE della PAGINA DI LOGIN -->
    <?php
        include "../util/connection.php";
        include "../util/command.php";
        $connection = connectToDatabase(DB_HOST, "root", "", DB_NAME);
        session_start();

        if (!isset($_SESSION["is_logged"]))
            $_SESSION["is_logged"] = false;

        // controllo cosa mostrare in base a se é gia loggato oppure ancora no
        if ($_SESSION["is_logged"]) {
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
                                    <li><a href='../newsletter.php'     class='btn'>Newsletter   </a></li>
                                    <li><a href='../bacheca.php'        class='btn'>Bacheca       </a></li>
                                    <li><a href='https://stripe.com/it' class='btn'>Donazioni     </a></li>
                                    <li><a href='area_personale.php'    class='btn'>Area Personale</a></li>
                                </ul>
                            </div>
                        </nav>            
                    </section>
                </main>";
            welcome($connection, $_SESSION["username"]);
        } else {
            echo "<main>
                    <h1>Accedi al tuo account</h1>
                    <form action='login.php' id='form_login' method='POST'>
                        <label for='username'>Username</label>
                        <input type='text' name='username' id='username' required><br>

                        <label for='password'>Password</label>
                        <input type='password' name='password' id='password' required><br>

                        <input class='btn' type='submit' value='ACCEDI'><br><br><br>
                    </form>
                </main>";
        }
    ?>
</body>
</html>