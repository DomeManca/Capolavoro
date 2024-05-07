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
            <h3>Formazione: <?php echo $formazione['nome']; ?></h3>
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
