<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesso Negato</title>
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
        <h1>Accesso Negato</h1>
        <p>Non hai il permesso di accedere a questa pagina.</p>
        <div class="btn-container">
            <a href="login.php"><button type="button">Accedi</button></a>
            <a href="index.php"><button type="button">Home</button></a>
        </div>
    </div>
</body>
</html>
