<?php
session_start();
include_once 'config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: errore.php');
    exit();
}

$giocatoreId = isset($_POST['giocatore_id']) ? $_POST['giocatore_id'] : null;
$formazioneId = isset($_POST['formazione_id']) ? $_POST['formazione_id'] : null;

// Ottieni la lista dei ruoli dal database
$query = "SELECT * FROM ruolo";
$stmt = $pdo->query($query);
$ruoli = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ottieni la lista dei giocatori dal database
$query = "SELECT * FROM giocatori";
$stmt = $pdo->query($query);
$giocatori = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Carica i dettagli del giocatore selezionato, se disponibile
$giocatore = null;
if ($giocatoreId !== null) {
    $query = "SELECT giocatori.*, ruolo.nome AS ruolo_nome FROM giocatori INNER JOIN ruolo ON giocatori.ruolo = ruolo.id WHERE giocatori.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $giocatoreId);
    $stmt->execute();
    $giocatore = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ottieni la lista delle formazioni dal database
$query = "SELECT * FROM formazioni";
$stmt = $pdo->query($query);
$formazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Carica i dettagli della formazione selezionata, se disponibile
$formazione = null;
if ($formazioneId !== null) {
    $query = "SELECT * FROM formazioni WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $formazioneId);
    $stmt->execute();
    $formazione = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Modifica dati del giocatore
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $giocatoreId = $_POST['giocatore_id'];
    $nome = $_POST['nome'];
    $ruolo = $_POST['ruolo'];
    $data_di_nascita = $_POST['data_di_nascita'];
    $sesso = $_POST['sesso'];

    $query = "UPDATE giocatori SET nome = :nome, ruolo = :ruolo, data_di_nascita = :data_di_nascita, sesso = :sesso WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':ruolo', $ruolo);
    $stmt->bindParam(':data_di_nascita', $data_di_nascita);
    $stmt->bindParam(':sesso', $sesso);
    $stmt->bindParam(':id', $giocatoreId);
    $stmt->execute();
    header('Location: visualizza.php');
    exit();
}

// Cancellazione dei dati del giocatore
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $giocatoreId = $_POST['giocatore_id'];
    $query = "DELETE FROM giocatori WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $giocatoreId);
    $stmt->execute();
    header('Location: visualizza.php');
    exit();
}

// Cancellazione dei dati della formazione
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete2'])) {
    $formazioneId = $_POST['formazione_id'];
    $query = "DELETE FROM formazione WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $formazioneId);
    $stmt->execute();
    header('Location: index.php');
    exit();
}

// Modifica lo stato di admin dell'utente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_admin'])) {
    $username = $_POST['username'];

    // Ottieni lo stato attuale dell'amministratore
    $query = "SELECT admin FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Inverti lo stato dell'amministratore
    $adminStatus = $userData['admin'] == 1 ? 0 : 1;

    // Aggiorna lo stato admin dell'utente nel database
    $query = "UPDATE users SET admin = :admin WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':admin', $adminStatus, PDO::PARAM_INT);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    // Redirect o aggiornamento della pagina
    header('Location: admin.php');
    exit();
}

// Elimina l'utente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $username = $_POST['username'];
    // Elimina l'utente dal database
    $query = "DELETE FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    // Redirect o aggiornamento della pagina
    header('Location: admin.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Admin</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(231deg, rgba(233, 233, 233, 0.01) 0%, rgba(233, 233, 233, 0.01) 25%,rgba(10, 10, 10, 0.01) 25%, rgba(10, 10, 10, 0.01) 50%,rgba(237, 237, 237, 0.01) 50%, rgba(237, 237, 237, 0.01) 75%,rgba(200, 200, 200, 0.01) 75%, rgba(200, 200, 200, 0.01) 100%),linear-gradient(344deg, rgba(2, 2, 2, 0.03) 0%, rgba(2, 2, 2, 0.03) 20%,rgba(10, 10, 10, 0.03) 20%, rgba(10, 10, 10, 0.03) 40%,rgba(100, 100, 100, 0.03) 40%, rgba(100, 100, 100, 0.03) 60%,rgba(60, 60, 60, 0.03) 60%, rgba(60, 60, 60, 0.03) 80%,rgba(135, 135, 135, 0.03) 80%, rgba(135, 135, 135, 0.03) 100%),linear-gradient(148deg, rgba(150, 150, 150, 0.03) 0%, rgba(150, 150, 150, 0.03) 14.286%,rgba(15, 15, 15, 0.03) 14.286%, rgba(15, 15, 15, 0.03) 28.572%,rgba(74, 74, 74, 0.03) 28.572%, rgba(74, 74, 74, 0.03) 42.858%,rgba(175, 175, 175, 0.03) 42.858%, rgba(175, 175, 175, 0.03) 57.144%,rgba(16, 16, 16, 0.03) 57.144%, rgba(16, 16, 16, 0.03) 71.42999999999999%,rgba(83, 83, 83, 0.03) 71.43%, rgba(83, 83, 83, 0.03) 85.71600000000001%,rgba(249, 249, 249, 0.03) 85.716%, rgba(249, 249, 249, 0.03) 100.002%),linear-gradient(122deg, rgba(150, 150, 150, 0.01) 0%, rgba(150, 150, 150, 0.01) 20%,rgba(252, 252, 252, 0.01) 20%, rgba(252, 252, 252, 0.01) 40%,rgba(226, 226, 226, 0.01) 40%, rgba(226, 226, 226, 0.01) 60%,rgba(49, 49, 49, 0.01) 60%, rgba(49, 49, 49, 0.01) 80%,rgba(94, 94, 94, 0.01) 80%, rgba(94, 94, 94, 0.01) 100%),linear-gradient(295deg, rgba(207, 207, 207, 0.02) 0%, rgba(207, 207, 207, 0.02) 25%,rgba(47, 47, 47, 0.02) 25%, rgba(47, 47, 47, 0.02) 50%,rgba(142, 142, 142, 0.02) 50%, rgba(142, 142, 142, 0.02) 75%,rgba(76, 76, 76, 0.02) 75%, rgba(76, 76, 76, 0.02) 100%),linear-gradient(73deg, rgba(81, 81, 81, 0.03) 0%, rgba(81, 81, 81, 0.03) 12.5%,rgba(158, 158, 158, 0.03) 12.5%, rgba(158, 158, 158, 0.03) 25%,rgba(136, 136, 136, 0.03) 25%, rgba(136, 136, 136, 0.03) 37.5%,rgba(209, 209, 209, 0.03) 37.5%, rgba(209, 209, 209, 0.03) 50%,rgba(152, 152, 152, 0.03) 50%, rgba(152, 152, 152, 0.03) 62.5%,rgba(97, 97, 97, 0.03) 62.5%, rgba(97, 97, 97, 0.03) 75%,rgba(167, 167, 167, 0.03) 75%, rgba(167, 167, 167, 0.03) 87.5%,rgba(22, 22, 22, 0.03) 87.5%, rgba(22, 22, 22, 0.03) 100%),linear-gradient(90deg, rgb(0,105,217),rgb(0,105,217));
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
    <h1>Area Amministrativa</h1>
    <div class="card mb-4">
    <div class="card-header">
        Selezione GIOCATORE da modificare/eliminare
    </div>
    <div class="card-body">
    <form method="POST">
        <div class="form-group">
            <label for="giocatore_id">Seleziona un giocatore:</label>
            <select class="form-control" name="giocatore_id" id="giocatore_id">
                <?php foreach ($giocatori as $it): ?>
                    <option value="<?php echo $it['id']; ?>" <?php if ($it['id'] == $giocatoreId) echo 'selected'; ?>>
                        <?php echo $it['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <div class="card mb-4">
    <div class="card-header">
        Modifica GIOCATORE
    </div>
    <div class="card-body">
    <form method="POST">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" name="nome" value="<?php echo $giocatore['nome'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="ruolo">Ruolo:</label>
            <select class="form-control" name="ruolo" required>
                <?php foreach ($ruoli as $ruolo): ?>
                    <option value="<?php echo $ruolo['id']; ?>" <?php if ($ruolo['id'] == ($giocatore['ruolo'] ?? '')) echo 'selected'; ?>>
                        <?php echo $ruolo['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="data_di_nascita">Data di Nascita:</label>
            <input type="date" class="form-control" name="data_di_nascita" value="<?php echo $giocatore['data_di_nascita'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="sesso">Sesso:</label>
            <select class="form-control" name="sesso" value="<?php echo $giocatore['sesso'] ?? ''; ?>" required>
                <option value="1">Maschio</option>
                <option value="2">Femmina</option>
            </select>
        </div>
        <input type="hidden" name="giocatore_id" value="<?php echo $giocatore['id'] ?? ''; ?>">
        <button type="submit" class="btn btn-primary" name="edit">Modifica</button>
    </form>
    </div>
    </div>
    <div class="card mb-4">
    <div class="card-header">
        Elimina GIOCATORE
    </div>
    <div class="card-body">
        <form id="deleteForm" method="POST">
            <button type="button" class="btn btn-danger" id="confirmDelete">Elimina giocatore</button>
            <input type="hidden" name="giocatore_id" value="<?php echo $giocatore['id'] ?? ''; ?>">
            <button type="submit" class="btn btn-danger d-none" id="deleteButton" name="delete">Conferma eliminazione</button>
        </form>
    </div>
    </div>
    </div>
    </div>
    <br>
    <div class="card mb-4">
    <div class="card-header">
        Elimina FORMAZIONE
    </div>
    <div class="card-body">
    <form method="POST">
        <div class="form-group">
            <label for="formazione_id">Seleziona una formazione:</label>
            <select class="form-control" name="formazione_id" id="formazione_id">
                <?php foreach ($formazioni as $it): ?>
                    <option value="<?php echo $it['id']; ?>" <?php if ($it['id'] == $formazioneId) echo 'selected'; ?>>
                        <?php echo $it['nome'];?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
        <form id="deleteForm" method="POST">
            <button type="button" class="btn btn-danger" id="confirmDelete2">Elimina formazione</button>
            <input type="hidden" name="formazione_id" value="<?php echo $formazione['id'] ?? ''; ?>">
            <button type="submit" class="btn btn-danger d-none" id="deleteButton2" name="delete2">Conferma eliminazione</button>
        </form>
    </div>
    </div>
    <br>
    <div class="card mb-4">
    <div class="card-header">
        Gestione Utenti
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Admin</th>
                        <th>Elimina</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Ottieni la lista degli utenti dal database
                        $query = "SELECT * FROM users";
                        $stmt = $pdo->query($query);
                        $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Cicla attraverso gli utenti e visualizza le informazioni
                        foreach ($utenti as $utente):
                    ?>
                        <tr>
                            <td><?php echo $utente['username']; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="username" value="<?php echo $utente['username']; ?>">
                                    <button type="submit" class="btn btn-primary" name="toggle_admin" <?php echo $utente['proprietario'] == true ? 'disabled' : ''; ?>>
                                        <?php echo $utente['admin'] == 1 ? 'Rimuovi Admin' : 'Rendi Admin'; ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="username" value="<?php echo $utente['username']; ?>">
                                    <button type="submit" class="btn btn-danger" name="delete_user" <?php echo $utente['proprietario'] == true ? 'disabled' : ''; ?>>
                                        Elimina Utente
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </form>
    </div>
    </div>
    <a href="index.php">Torna alla home</a>
    </div>

    <script>
        document.getElementById('giocatore_id').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (confirm('Sei sicuro di voler eliminare il giocatore <?php echo $giocatore['nome'];?>?')) {
                document.getElementById('deleteButton').click();
            }
        });

        document.getElementById('formazione_id').addEventListener('change', function() {
            this.form.submit();
        });
        
        document.getElementById('confirmDelete2').addEventListener('click', function() {
            if (confirm('Sei sicuro di voler eliminare la formazione <?php echo $formazione['id'];?>?')) {
                document.getElementById('deleteButton2').click();
            }
        });
    </script>
</body>
</html>
