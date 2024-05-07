<?php
// Avvia la sessione
session_start();

// Include il file di configurazione del database
include_once 'config.php';

// Controlla se il modulo di registrazione è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ottieni i valori inseriti dall'utente nel modulo di registrazione
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query per verificare se il nome utente esiste già nel database
    $check_query = "SELECT COUNT(*) FROM users WHERE username = :username";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->bindParam(':username', $username);
    $check_stmt->execute();
    $user_exists = $check_stmt->fetchColumn();

    if ($user_exists) {
        // Se il nome utente esiste già, mostra un messaggio di errore
        $message = "Il nome utente è già stato utilizzato. Si prega di sceglierne un altro.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'register.php';</script>";
    } else {
        // Se il nome utente non esiste già, procedi con l'inserimento nel database
        // Query per inserire l'utente nel database
        $insert_query = "INSERT INTO users (username, password, admin, proprietario) VALUES (:username, :password, 0, 0)";
        $insert_stmt = $pdo->prepare($insert_query);
        $insert_stmt->bindParam(':username', $username);
        $insert_stmt->bindParam(':password', $hashed_password);

        // Esegui la query di inserimento
        if ($insert_stmt->execute()) {
            // Se l'inserimento è riuscito, reindirizza alla pagina di login
            header('Location: login.php');
        } else {
            // Se si è verificato un errore durante l'inserimento, mostra un messaggio di errore generico
            $message = "Si è verificato un errore durante la registrazione.";
            echo "<script type='text/javascript'>alert('$message'); window.location.href = 'register.php';</script>";
        }
    }
}
?>
