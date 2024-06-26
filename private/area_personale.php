<?php
    require_once("../util/constants.php");
    include("../util/connection.php");
    include("../util/command.php");

    echo "<script src='https://kit.fontawesome.com/a730223cdf.js' crossorigin='anonymous'></script>";
    echo "<script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>";
    echo WEBALL;
    echo "<script src='../script/script.js'></script>";
    echo "<link rel='stylesheet' href='../style/style.css'>";
    echo "<title>Associazione Zero Tre</title>";
    session_start();

    if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]) {
        nav_menu();

        // stampa dell'esito dell'operazione
        check_operation();

        $connection = connectToDatabase(DB_HOST, DB_ADMIN, ADMIN_PW, DB_NAME);
        $result = getUserAuth($connection, $_SESSION["username"]);

        // salvo i permessi che ha l'utente che ha effettuato il login
        if ($result) {
            while($row = ($result->fetch_assoc())) {
                $_SESSION["profile_type"] = $row["tipo_profilo"];
                $_SESSION["profile_func"] = $row["tipo_funzione"];
                $_SESSION["user_auth"] = $row["operazione_permessa"];
            }
        } else
            echo ERROR_DB;

        // permetto determinate funzioni in base al tipo di profilo
        switch($_SESSION["profile_type"]) {
            case "presidente":
                try {
                    $connection = connectToDatabase(DB_HOST, DB_PRESIDENT, PRESIDENT_PW, DB_NAME);
                    welcome($connection, $_SESSION["username"]);
                    $_SESSION["is_president"] = true;
                
                    echo "<br><br><br><br><h2>Cosa vuoi fare?</h2><br>";
                    echo "  <section id='admin_btn'>
                                <button class='btn' data-operation='view_assisted'>GESTIONE ASSISTITI</button>
                            </section><br><br>";
                } catch (Exception $e) {
                    echo ERROR_GEN;
                }
            break;

            case "admin":
                try {
                    $connection = connectToDatabase(DB_HOST, DB_ADMIN, ADMIN_PW, DB_NAME);
                    welcome($connection, $_SESSION["username"]);
                    $_SESSION["is_admin"] = true;

                    echo "<br><br><br><br><h2>Cosa vuoi fare?</h2><br>";
                    echo "  <section id='admin_btn'>
                                <button class='btn' data-operation='view_user'>GESTIONE UTENTI</button>
                                <button class='btn' data-operation='view_volunteer'>GESTIONE VOLONTARI</button>
                                <button class='btn' data-operation='view_assisted'>GESTIONE ASSISTITI</button>
                                <button class='btn' data-operation='manage_release'>GESTIONE LIBERATORIE</button>
                                <button class='btn' data-operation='manage_event'>GESTIONE EVENTI</button>
                            </section><br><br>";
                } catch (Exception $e) {
                    echo ERROR_GEN;
                }
            break;

            case "terapista":
                try {
                    $connection = connectToDatabase(DB_HOST, DB_TERAPIST, TERAPIST_PW, DB_NAME);
                    welcome($connection, $_SESSION["username"]);
                    $_SESSION["is_terapist"] = true;

                    echo "<br><br><br><br><h2>Cosa vuoi fare?</h2><br>";
                    echo "  <section id='admin_btn'>
                                <button class='btn' data-operation='view_assisted'>GESTIONE ASSISTITI</button>
                            </section><br><br>";
                } catch (Exception $e) {
                    echo ERROR_GEN;
                }
            break;

            case "genitore":
                try {
                    $connection = connectToDatabase(DB_HOST, DB_USER, USER_PW, DB_NAME);
                    welcome($connection, $_SESSION["username"]);
                    $_SESSION["is_parent"] = true;

                    // ottengo i dati dell'utente e li stampo
                    $query = "SELECT u.id, 
                                    u.NOME,
                                    u.COGNOME,
                                    u.USERNAME,
                                    u.EMAIL,
                                    u.telefono_fisso AS 'TELEFONO FISSO',
                                    u.telefono_mobile AS 'TELEFONO MOBILE',
                                    u.NOTE
                                FROM utenti u
                                WHERE u.id = '" . $_SESSION["user_id"] . "'";
                    $result = dbQuery($connection, $query);
                    
                    if ($result) {
                        echo "<br><br>
                            <section id='table'><h3>I tuoi dati</h3>";
                                createTable($result, "user");

                        // ottengo i dati degli assistiti collegati a questo utente e li stampo
                        echo "<br><br><h3>I tuoi assistiti</h3>";
                        $query = "SELECT a.id,
                                        a.NOME,
                                        a.COGNOME, 
                                        a.ANAMNESI,
                                        a.NOTE, 
                                        l.LIBERATORIA
                                    FROM assistiti a 
                                    INNER JOIN utenti u ON a.id_referente = u.id
                                    INNER JOIN liberatorie l ON a.id_liberatoria = l.id
                                    WHERE u.id = '" . $_SESSION["user_id"] . "'";
                        $result = dbQuery($connection, $query);
                        
                        if ($result) {
                            createTable($result, "assisted");
                            echo "</section>";
                        } else 
                            echo ERROR_DB;
                    } else 
                        echo ERROR_DB;
                } catch (Exception $e) {
                    echo ERROR_GEN;
                }
            break;
        }
        
        show_footer2();
    } else
        header("Location: page_login.php");
?>