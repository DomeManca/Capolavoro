<?php
session_start();
include_once 'config.php';

if(isset($_SESSION['username']) || isset($_SESSION['admin'])){
}
else{
    header('Location: errore.php');
    exit();
}

$stmtFormazioni = $pdo->query("SELECT * FROM formazioni");
$formazioni = $stmtFormazioni->fetchAll(PDO::FETCH_ASSOC);

// Funzione per ottenere il nome del giocatore dal suo ID
function getNomeGiocatore($pdo, $id_giocatore) {
    $query = "SELECT nome FROM giocatori WHERE id = :id_giocatore";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_giocatore', $id_giocatore);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['nome'] : null;
}

// Funzione per ottenere il ruolo del giocatore dal suo ID
function getRuoloGiocatore($pdo, $id_giocatore) {
    $query = "SELECT ruolo.nome FROM giocatori JOIN ruolo WHERE giocatori.ruolo = ruolo.id AND giocatori.id = :id_giocatore";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_giocatore', $id_giocatore);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['nome'] : null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza Formazioni</title>
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
        .formazione {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .giocatore {
            margin: 5px;
            text-align: center;
            flex-grow: 1;
        }
        .nome-giocatore {
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Elenco Formazioni</h1>
        <?php foreach ($formazioni as $formazione): ?>
            <div class="card mb-4">
            <div class="card-body">
            <h3>Formazione <?php echo $formazione['id']; ?></h3>
            <div class="formazione">
                <?php
                $giocatori = [];
                $giocatori[] = ['nome' => getNomeGiocatore($pdo, $formazione['id_giocatore_1']), 'ruolo' => getRuoloGiocatore($pdo, $formazione['id_giocatore_1'])];
                $giocatori[] = ['nome' => getNomeGiocatore($pdo, $formazione['id_giocatore_2']), 'ruolo' => getRuoloGiocatore($pdo, $formazione['id_giocatore_2'])];
                $giocatori[] = ['nome' => getNomeGiocatore($pdo, $formazione['id_giocatore_3']), 'ruolo' => getRuoloGiocatore($pdo, $formazione['id_giocatore_3'])];
                $giocatori[] = ['nome' => getNomeGiocatore($pdo, $formazione['id_giocatore_4']), 'ruolo' => getRuoloGiocatore($pdo, $formazione['id_giocatore_4'])];
                $giocatori[] = ['nome' => getNomeGiocatore($pdo, $formazione['id_giocatore_5']), 'ruolo' => getRuoloGiocatore($pdo, $formazione['id_giocatore_5'])];
                $giocatori[] = ['nome' => getNomeGiocatore($pdo, $formazione['id_giocatore_6']), 'ruolo' => getRuoloGiocatore($pdo, $formazione['id_giocatore_6'])];
                $giocatori[] = ['nome' => getNomeGiocatore($pdo, $formazione['id_giocatore_7']), 'ruolo' => getRuoloGiocatore($pdo, $formazione['id_giocatore_7'])];
                ?>
                <?php foreach ($giocatori as $giocatore): ?>
                    <div class="giocatore">
                        <div><?php echo $giocatore['nome']; ?></div>
                        <div class="nome-giocatore"><?php echo $giocatore['ruolo']; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            </div>
            </div>
        <?php endforeach; ?>
        <br>
        <p><a href="index.php">Torna alla home</a></p>
    </div>
</body>
</html>
