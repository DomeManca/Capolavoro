<?php
// Credenziali di accesso al database
$db_host = 'localhost'; // Indirizzo del server del database
$db_name = 'roverino'; // Nome del database
$db_user = 'programma'; // Nome utente del database
$db_password = 'roverino'; // Password del database

try {
    // Connessione al database usando PDO
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    
    // Imposta l'attributo di PDO per gestire automaticamente gli errori di PDOException
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Se si verifica un errore di connessione, mostra un messaggio di errore
    $message = "Errore di connessione al database: " . $e->getMessage();
    echo "<script type='text/javascript'>alert('$message'); window.location.href = 'index.php';</script>";
    die(); // Interrompe l'esecuzione dello script
}
?>
