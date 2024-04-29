<?php
session_start();
include_once 'config.php';

// Verifica se l'utente è autenticato o è un admin
if (!isset($_SESSION['admin'])) {
    header('Location: errore.php');
    exit();
}

// Moduli preimpostati per la formazione
$moduli = [
    '2-1-2-1' => ['Portiere', 'Difensore', 'Difensore', 'Centrocampista', 'Laterale', 'Laterale', 'Punta'],
    '1-2-2-1' => ['Portiere', 'Difensore', 'Centrocampista', 'Centrocampista', 'Laterale', 'Laterale', 'Punta'],
];

// Inizializza array per memorizzare i giocatori selezionati
$formazione = [];

// Variabili per gestire gli errori
$erroreQuotaRosa = false;
$erroreGiocatoriDuplicati = false;
$erroreNumGiocatori = false;

// Controlla se la formazione è valida
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitFormazione'])) {
    // Conta il numero di donne nella formazione
    $countDonneFormazione = 0;

    // Array per memorizzare i giocatori selezionati
    $giocatoriSelezionati = [];

    // Controllo dei giocatori selezionati e conta delle donne
    foreach ($_POST['formazione'] as $ruolo => $giocatore) {
        // Verifica se è stato selezionato un giocatore
        if (!empty($giocatore)) {
            // Controlla il sesso del giocatore
            $querySesso = "SELECT sesso FROM giocatori WHERE nome = :giocatore";
            $stmtSesso = $pdo->prepare($querySesso);
            $stmtSesso->bindParam(':giocatore', $giocatore);
            $stmtSesso->execute();
            $sesso = $stmtSesso->fetchColumn();

            // Controlla se è una donna e incrementa il contatore
            if ($sesso == 2) {
                $countDonneFormazione++;
            }

            // Aggiungi il giocatore all'array
            $giocatoriSelezionati[] = $giocatore;
        }
    }

    // Controllo della quota rosa
    if ($countDonneFormazione < 2) {
        $erroreQuotaRosa = true;
    } else {
        // Controllo per evitare giocatori duplicati
        $giocatoriUnici = array_unique($giocatoriSelezionati);
        if (count($giocatoriUnici) != count($giocatoriSelezionati)) {
            $erroreGiocatoriDuplicati = true;
        } else {
            // Tutti i controlli sono passati, inserisci la formazione nel database
            $queryInserimentoFormazione = "INSERT INTO formazioni (id_giocatore_1, id_giocatore_2, id_giocatore_3, id_giocatore_4, id_giocatore_5, id_giocatore_6, id_giocatore_7)
                                           VALUES (:giocatore1, :giocatore2, :giocatore3, :giocatore4, :giocatore5, :giocatore6, :giocatore7)";
            $stmtInserimentoFormazione = $pdo->prepare($queryInserimentoFormazione);

            // Associa i giocatori selezionati agli ID dei giocatori nel database
            $giocatoriIDs = [];
            foreach ($giocatoriSelezionati as $giocatore) {
                $queryIDGiocatore = "SELECT id FROM giocatori WHERE nome = :giocatore";
                $stmtIDGiocatore = $pdo->prepare($queryIDGiocatore);
                $stmtIDGiocatore->bindParam(':giocatore', $giocatore);
                $stmtIDGiocatore->execute();
                $giocatoreID = $stmtIDGiocatore->fetchColumn();
                $giocatoriIDs[] = $giocatoreID;
            }

            // Assicurati che ci siano esattamente 7 giocatori selezionati
            $numGiocatori = count($giocatoriIDs);
            if ($numGiocatori == 7) {
                // Esegui l'inserimento della formazione nel database
                $stmtInserimentoFormazione->bindParam(':giocatore1', $giocatoriIDs[0]);
                $stmtInserimentoFormazione->bindParam(':giocatore2', $giocatoriIDs[1]);
                $stmtInserimentoFormazione->bindParam(':giocatore3', $giocatoriIDs[2]);
                $stmtInserimentoFormazione->bindParam(':giocatore4', $giocatoriIDs[3]);
                $stmtInserimentoFormazione->bindParam(':giocatore5', $giocatoriIDs[4]);
                $stmtInserimentoFormazione->bindParam(':giocatore6', $giocatoriIDs[5]);
                $stmtInserimentoFormazione->bindParam(':giocatore7', $giocatoriIDs[6]);
                $stmtInserimentoFormazione->execute();

                header('Location: #.php');
            } else {
                // Numero di giocatori selezionati non valido
                $erroreNumGiocatori = true;
            }
        }
    }
}

// Funzione per ottenere i giocatori di un determinato ruolo dal database
function getGiocatoriByRuolo($pdo, $ruolo) {
    $query = "SELECT * FROM giocatori WHERE ruolo IN (SELECT id FROM ruolo WHERE nome = :ruolo) OR ruolo = 6";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':ruolo', $ruolo);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Formazione</title>
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
        <h1>Aggiungi Formazione</h1>
        <form method="POST">
            <div class="form-group">
                <label for="modulo">Seleziona un modulo preimpostato:</label>
                <select class="form-control" name="modulo">
                    <option value="">Seleziona un modulo</option>
                    <?php foreach ($moduli as $nomeModulo => $ruoliModulo): ?>
                        <option value="<?php echo $nomeModulo; ?>"><?php echo $nomeModulo; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Utilizza modulo</button>
        </form>
        <br>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])): ?>
            <?php
            $moduloScelto = $_POST['modulo'];
            $ruoliFormazione = $moduli[$moduloScelto];
            ?>
            <h2>Formazione con modulo <?php echo $moduloScelto; ?>:</h2>
            <form method="POST">
                <?php foreach ($ruoliFormazione as $ruolo): ?>
                    <div class="form-group">
                        <label for="<?php echo $ruolo; ?>"><?php echo $ruolo; ?>:</label>
                        <select class="form-control" name="formazione[<?php echo $ruolo; ?>]">
                            <option value="">Seleziona un giocatore</option>
                            <?php $giocatori = getGiocatoriByRuolo($pdo, $ruolo); ?>
                            <?php foreach ($giocatori as $giocatore): ?>
                                <option value="<?php echo $giocatore['nome']; ?>"><?php echo $giocatore['nome']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-primary" name="submitFormazione">Salva Formazione</button>
            </form>
            <?php if ($erroreQuotaRosa): ?>
                <p class="text-danger">La formazione deve includere almeno 2 giocatrici.</p>
            <?php endif; ?>
            <?php if ($erroreGiocatoriDuplicati): ?>
                <p class="text-danger">I giocatori della formazione non possono ripetersi.</p>
            <?php endif; ?>
            <?php if ($erroreNumGiocatori): ?>
                <p class="text-danger">La formazione deve includere 7 giocatori.</p>
            <?php endif; ?>
        <?php endif; ?>
        <p><a href="index.php">Torna alla home</a></p>
    </div>
</body>
</html>
