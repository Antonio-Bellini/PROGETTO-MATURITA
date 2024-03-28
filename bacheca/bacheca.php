<?php
    require_once("../util/constants.php");
    include("../util/connection.php");
    include("../util/command.php");
    include("../util/cookie.php");

    echo "<script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>";
    echo "<script src='../script/script.js'></script>";
    echo "<link rel='stylesheet' href='../style/style.css'>";
    importActualStyle();
    session_start();
    $connection = null;

    if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]) {
        // menu di navigazione
        nav_menu();

        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
            if (($_SESSION["profile_func"] === "gestione DB") && ($_SESSION["user_auth"] === "CRUD")) {
                $connection = connectToDatabase(DB_HOST, DB_ADMIN, ADMIN_PW, DB_NAME);

                echo "<br><br><button><a href='manage_bacheca.php?operation=add'>Aggiungi contenuto</a></button>";
                echo "&nbsp;<button><a href='manage_bacheca.php?operation=del'>Elimina contenuto</a></button>";
            }
        } else if (isset($_SESSION["is_parent"]) && $_SESSION["is_parent"]) {
            $connection = connectToDatabase(DB_HOST, DB_USER, USER_PW, DB_NAME);
        }

        $query = "SELECT bacheca, data FROM bacheca";
        $result = dbQuery($connection, $query);
        echo "<br><br><label>Ecco cosa é presente in bacheca</label><br><br>";

        if ($result) {
            while ($row = ($result->fetch_assoc())) {
                echo "<div id='bacheca'>
                        <embed src='" . $row["bacheca"] . "' type='application/pdf' width='80%' height='100%'>
                    </div>";
            }
        }
    } else 
        header("Location: ../private/page_login.php");


    // menu di navigazione
    function nav_menu() {
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
                                <li><a href='../newsletter/newsletter.php'  class='btn'>Newsletter   </a></li>
                                <li><a href='bacheca.php'                   class='btn'>Bacheca       </a></li>
                                <li><a href='https://stripe.com/it'         class='btn' target='blank'>Donazioni</a></li>
                                <li><a href='../private/area_personale.php' class='btn'>Area Personale</a></li>
                            </ul>
                        </div>
                    </nav>            
                </section>
            </main>";
    }
?>