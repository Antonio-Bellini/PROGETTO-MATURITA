<?php
    include "../command.php";
    include "../connection.php";
    require_once("../constants.php");

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $oldPassword = $_POST['old_psw'];
        $newPassword = $_POST['new_psw'];

        $connection = connectToDatabase(DB_HOST, DB_ADMIN, ADMIN_PW, DB_NAME);

        // confronto tra vecchia e nuova password per aggiornamento
        if ($oldPassword === $newPassword)
            echo "same_password";
        else {
            $query = "SELECT password
                        FROM utenti
                        WHERE id = '" . $_SESSION['pw_user_sel'] . "'";
            $result = dbQuery($connection, $query);
            
            if (($result->num_rows) > 0) {
                while ($row = ($result->fetch_assoc())) {
                    if (checkPassword($oldPassword, $row['password']))
                        echo "correct";
                    else
                        echo "not_correct";
                }
            }
        }
    }
?>