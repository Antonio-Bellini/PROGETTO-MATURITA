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
    $connection = connectToDatabase(DB_HOST, DB_ADMIN, ADMIN_PW, DB_NAME);

    $operation = null;
    $userId = null;
    $profile = null;

    if (isset($_GET["operation"]))
        $operation = $_GET["operation"];

    if (isset($_GET["user"]))
        $userId = $_GET["user"];

    if (isset($_GET["profile"]))
        $profile = $_GET["profile"];

    // possibili bottoni cliccati
    switch ($operation) {
        case "modify":
            // menu di navigazione
            nav_menu();
            
            if (!isset($userId))
                $userId = $_SESSION["user_id"];
            
            switch ($profile) {
                case "user":
                    if ((isset($_SESSION["is_parent"]) && $_SESSION["is_parent"]) ||
                        (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"])) {
                        
                        if (isset($_SESSION["is_parent"]) && $_SESSION["is_parent"]) {
                            $connection = connectToDatabase(DB_HOST, DB_USER, USER_PW, DB_NAME);
                            if ($userId != $_SESSION["user_id"])
                                $userId = $_SESSION["user_id"];
                        }
                        modifyForm($connection, "user", $userId);
                    }
                    break;

                case "assisted":
                    if ((isset($_SESSION["is_parent"]) && $_SESSION["is_parent"]) ||
                        (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]))
                        $connection = connectToDatabase(DB_HOST, DB_USER, USER_PW, DB_NAME);
                        modifyForm($connection, "assisted", $userId);
                    break;

                case "volunteer":
                    if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"])
                        modifyForm($connection, "volunteer", $userId);
                    break;
            }
            break;

        case "delete":
            // menu di navigazione
            nav_menu();
            
            if ((isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) ||
                (isset($_SESSION["is_terapist"]) && $_SESSION["is_terapist"])) {
                switch ($profile) {
                    case "user":
                        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
                            $query = "DELETE FROM utenti WHERE id = '$userId'";
                            $result = dbQuery($connection, $query);

                            if ($result) {
                                $_SESSION["user_deleted"] = true;
                                header("Location: admin_operation.php?operation=view_user");
                            }
                        }
                        break;

                    case "assisted":
                        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
                            $query = "DELETE FROM assistiti WHERE id = '$userId'";
                            $result = dbQuery($connection, $query);

                            if ($result) {
                                $_SESSION["user_deleted"] = true;
                                header("Location: admin_operation.php?operation=view_assi");
                            }
                        }
                        break;

                    case "volunteer":
                        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
                            $query = "DELETE FROM volontari WHERE id = '$userId'";
                            $result = dbQuery($connection, $query);

                            if ($result) {
                                $_SESSION["user_deleted"] = true;
                                header("Location: admin_operation.php?operation=view_volu");
                            }
                        }
                        break;

                    case "anamnesi":
                        $query = "UPDATE assistiti SET anamnesi = null WHERE id = '$userId'";
                        $result = dbQuery($connection, $query);

                        if ($result) {
                            $_SESSION["file_deleted"] = true;
                            header("Location: area_personale.php");
                        }
                        break;
                }
            }
            break;
        
        case "LOGOUT":
            if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]) {
                $_SESSION["is_logged"] = false;

                if (session_destroy()) {
                    // menu di navigazione
                    nav_menu__notLogged();
                    echo DISCONNECTION; 
                }
            } else
                header("Location: page_login.php");
            break;

        case null:
            header("Location: ../index.php");
            break;
    }


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
                                <li><a href='../bacheca/bacheca.php'        class='btn'>Bacheca       </a></li>
                                <li><a href='https://stripe.com/it'         class='btn' target='blank'>Donazioni</a></li>
                                <li><a href='area_personale.php'            class='btn'>Area Personale</a></li>
                                <li><a href='crud.php?operation=LOGOUT'     class='btn'>Logout</a></li>
                            </ul>
                        </div>
                    </nav>            
                </section>
            </main>";
    }

    function nav_menu__notLogged() {
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
                                <li><a href='../bacheca/bacheca.php'        class='btn'>Bacheca       </a></li>
                                <li><a href='https://stripe.com/it'         class='btn' target='blank'>Donazioni</a></li>
                                <li><a href='area_personale.php'            class='btn'>Area Personale</a></li>
                            </ul>
                        </div>
                    </nav>            
                </section>
            </main>";
    }
?>