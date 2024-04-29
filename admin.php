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

// Aggiornamento dello stato admin degli utenti
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_admin'])) {
    foreach ($_POST['utenti'] as $utenteId => $admin) {
        // Aggiorna lo stato admin dell'utente nel database
        $query = "UPDATE users SET admin = :admin WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':admin', $admin, PDO::PARAM_INT);
        $stmt->bindParam(':id', $utenteId, PDO::PARAM_INT);
        $stmt->execute();
    }
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
                        <?php echo $it['id']; ?>
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
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Admin</th>
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
                    $uid = $utente['id'];
                    ?>
                    <tr>
                        <td><?php echo $utente['username']; ?></td>
                        <td>
                            <input type="checkbox" name="utenti[<?php echo $uid?>]" value="1" <?php echo $utente['admin'] == 1 ? 'checked' : ''; ?>>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary" name="update_admin">Salva modifiche</button>
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
