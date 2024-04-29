<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto</title>
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
        h1 {
            text-align: center;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .btn-container a {
            flex: 0 0 calc(50% - 10px);
            margin-bottom: 20px;
        }
        button {
            width: 100%;
            padding: 8px;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            color: #fff;
            background-color: #0069d9;
            border-color: #0062cc;
        }
    </style>
</head>
<body>
    <div class="container">
    <?php
    session_start();
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "<h1>Benvenuto, $username</h1>";
    } else if(isset($_SESSION['admin'])) {
        echo "<h1>Benvenuto, Amministratore</h1>";
    } else{
        echo "<h1>Benvenuto</h1>";
    }
    ?>
    
    <p>Questo sito ti permette di visualizzare, aggiungere giocatori o formazioni di roverino.</p>
    <p>Scegli l'azione che desideri effettuare:</p>
    
    <div class="btn-container">
        <a href="visualizza.php"><button type="button">Visualizzare GIOCATORI</button></a>
        <a href="aggiungi.php"><button type="button">Aggiungere GIOCATORI</button></a>
        <a href="formazioni.php"><button type="button">Visualizzare FORMAZIONI</button></a>
        <?php
            if(isset($_SESSION['admin'])) {
                echo "<a href='lineup.php'><button type='button'>Aggiungere FORMAZIONI</button></a>";
                echo "<a href='admin.php'><button type='button'>Area amministratore</button></a>";
            }
            if(isset($_SESSION['username']) || isset($_SESSION['admin'])) {
                echo "<a href=logout.php><button type=button>Logout</button></a>";
                echo "</div>";
            } else{
                echo "<a href=login.php><button type=button>Accedi</button></a>";
                echo "</div>";
                echo "<p>Per aggiungere giocatori o visualizzare formazioni devi essere iscritto</p>";
            }
        ?>

    
    </div>
</body>
</html>