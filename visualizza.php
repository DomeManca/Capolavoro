<?php
include_once 'config.php';

$stmt = $pdo->query("SELECT giocatori.nome AS nome_giocatore, ruolo.nome AS ruolo, CASE WHEN giocatori.sesso = 1 THEN 'Maschio' ELSE 'Femmina' END AS genere, giocatori.data_di_nascita FROM giocatori INNER JOIN ruolo ON giocatori.ruolo = ruolo.id");
$giocatori = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza Giocatori</title>
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
        <h1>Elenco Giocatori</h1>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome Giocatore</th>
                            <th>Ruolo</th>
                            <th>Data di Nascita</th>
                            <th>Genere</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($giocatori as $giocatore): ?>
                            <tr>
                                <td><?php echo $giocatore['nome_giocatore']; ?></td>
                                <td><?php echo $giocatore['ruolo']; ?></td>
                                <td><?php echo $giocatore['data_di_nascita']; ?></td>
                                <td><?php echo $giocatore['genere']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <p><a href="index.php">Torna alla home</a></p>
    </div>
    
</body>
</html>
