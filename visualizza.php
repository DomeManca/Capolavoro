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
            cursor: pointer; /* Add cursor pointer to indicate clickability */
        }
        .form-group {
            margin-bottom: 20px;
        }
        .sortable-header {
            cursor: pointer;
        }
        .sorted-asc:after {
            content: ' ▲';
        }
        .sorted-desc:after {
            content: ' ▼';
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 onclick="resetSorting()">Elenco Giocatori</h1>
            <div class="table-responsive">
                <table class="table" id="giocatoriTable">
                    <thead>
                        <tr>
                            <th class="sortable-header" onclick="sortTable(0)">Nome Giocatore</th>
                            <th class="sortable-header" onclick="sortTable(1)">Ruolo</th>
                            <th class="sortable-header" onclick="sortTable(2)">Data di Nascita</th>
                            <th class="sortable-header" onclick="sortTable(3)">Genere</th>
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
    
    <script>
        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("giocatoriTable");
            switching = true;
            dir = "asc"; // Set the sorting direction to ascending by default
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];
                    if (dir === "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir === "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount ++;
                } else {
                    if (switchcount === 0 && dir === "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
            // Remove sorting indicators from all columns
            var headers = document.querySelectorAll('.sortable-header');
            headers.forEach(function(header) {
                header.classList.remove('sorted-asc');
                header.classList.remove('sorted-desc');
            });
            // Add sorting indicator to the clicked column
            var clickedHeader = headers[columnIndex];
            if (dir === "asc") {
                clickedHeader.classList.add('sorted-asc');
            } else if (dir === "desc") {
                clickedHeader.classList.add('sorted-desc');
            }
        }

        function resetSorting() {
            var headers = document.querySelectorAll('.sortable-header');
            headers.forEach(function(header) {
                header.classList.remove('sorted-asc');
                header.classList.remove('sorted-desc');
            });
        }
    </script>
</body>
</html>