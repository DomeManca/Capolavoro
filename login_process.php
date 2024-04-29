<?php
// Avvia la sessione
session_start();

// Include il file di configurazione del database
include_once 'config.php';

// Controlla se il modulo di login è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ottieni i valori inseriti dall'utente nel modulo di login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query per verificare l'esistenza dell'utente nel database
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se l'utente esiste
    if ($user) {
        // Se l'utente ha il campo "admin" impostato su true
        if ($user['admin'] == true) {
            // Imposta la sessione come "admin"
            $_SESSION['admin'] = $username;
        } else {
            // Altrimenti, imposta la sessione con il nome utente
            $_SESSION['username'] = $username;
        }
        // Reindirizza alla pagina di dashboard
        header('Location: index.php');
    } else {
        $message = "Credenziali errate";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'login.php';</script>";
    }
}
?>
