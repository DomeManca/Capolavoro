<?php
session_start();
include_once 'config.php';

if(isset($_SESSION['username']) || isset($_SESSION['admin'])){
}
else{
    header('Location: errore.php');
    exit();
}

// Ottieni la lista dei ruoli dal database
$queryRuoli = "SELECT * FROM ruolo";
$stmtRuoli = $pdo->query($queryRuoli);
$ruoli = $stmtRuoli->fetchAll(PDO::FETCH_ASSOC);

// Aggiunta dati
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $nome = $_POST['nome'];
    $ruolo = $_POST['ruolo'];
    $data_di_nascita = $_POST['data_di_nascita'];
    $sesso = $_POST['sesso'];

    $query = "INSERT INTO giocatori (nome, ruolo, data_di_nascita, sesso) VALUES (:nome, :ruolo, :data_di_nascita, :sesso)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':ruolo', $ruolo);
    $stmt->bindParam(':data_di_nascita', $data_di_nascita);
    $stmt->bindParam(':sesso', $sesso);
    $stmt->execute();
    header('Location: visualizza.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Giocatore</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding-top: 20px;
        }
        .container {
            max-width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1,p {
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>Aggiungi un nuovo giocatore</h1>
    <div class="card mb-4">
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="ruolo">Ruolo:</label>
                        <select class="form-control" name="ruolo" required>
                            <?php foreach ($ruoli as $ruolo): ?>
                                <option value="<?php echo $ruolo['id']; ?>">
                                    <?php echo $ruolo['nome']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="data_di_nascita">Data di Nascita:</label>
                        <input type="date" class="form-control" name="data_di_nascita" required>
                    </div>
                    <div class="form-group">
                        <label for="sesso">Genere:</label>
                        <select class="form-control" name="sesso" required>
                            <option value="1">Maschio</option>
                            <option value="2">Femmina</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add">Aggiungi</button>
                </form>
            </div>
        </div>
        <a href="index.php">Torna alla home</a>
    </div>
</body>
</html>
