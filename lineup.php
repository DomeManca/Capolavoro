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
