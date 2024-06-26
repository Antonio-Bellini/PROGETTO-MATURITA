<!DOCTYPE html>
<html lang="en">
<?php
    include "../util/constants.php";
    include "../util/command.php";

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
    
    if (!isset($_SESSION["is_admin"]))
        $_SESSION["is_admin"] = false;

    if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]) {
        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
            echo "<br>
                    <section id='form'>
                        <h2>Pagina di registrazione di un genitore/refente</h2><br>
                        <form name='form_user' action='register.php' id='form_register__user' method='POST'>
                            <input type='hidden' name='form_user'>
                            
                            <div class='div__label'>
                                <label for='nome'>Inserisci il nome *</label>
                                <label for='cognome'>Inserisci il cognome *</label>
                            </div>
                            <div class='div__input'>
                                <input type='text' name='name' id='name' maxlength='255' required>
                                &nbsp;&nbsp;
                                <input type='text' name='surname' id='surname' maxlength='255' required>
                            </div>
                
                            <div class='div__label'>
                                <label for='username'>Inserisci lo username *</label>
                                <label for='password'>Crea una password *</label>
                            </div>
                            <div class='div__input'>
                                <input type='text' name='username' id='username' maxlength='255' required>
                                <span id='usernameError' class='span_error'></span>                        
                                <input type='password' name='password' id='password' maxlength='255' required>
                                <span id='togglePassword' class='toggle-password'>👁️</span>
                            </div>

                            <label for='confirm_password'>Riscrivi la password inserita</label>
                            <input type='password' name='confirm_password' id='confirm_password' maxlength='255' required>
                            <span id='confirm_passwordError' class='span_error'></span>
                            
                            <label for='email'>Inserisci l'email *</label>
                            <input type='email' name='email' id='email' maxlength='255' required>
                
                            <div class='div__label'>
                                <label for='phone_f'>Inserisci il numero di telefono fisso</label>
                                <label for='phone_m'>Inserisci il numero di telefono mobile *</label>
                            </div>
                            <div class='div__input'>
                                <input type='number' name='phone_f' id='phone_f'>
                                &nbsp;&nbsp;
                                <input type='number' name='phone_m' id='phone_m' required>
                            </div>
                            
                            <label for='notes'>Inserisci qualche nota aggiuntiva</label>
                            <textarea name='notes' id='notes' cols='30' rows='10' placeholder='Altre info utili'></textarea> 
                
                            <input type='submit' class='btn' value='CREA ACCOUNT GENITORE/REFERENTE'>
                        </form>
                    </section>";

            show_footer();
        } else 
            header("Location: ../index.php");
    } else 
        header("Location: ../private/page_login.php");
?>    
</body>
</html>