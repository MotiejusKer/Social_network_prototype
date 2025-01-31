<?php
session_start();

function authenticateUser($username, $password) {
    $files = ['ekspertai.txt', 'investuotojai.txt', 'startup.txt'];
    foreach ($files as $file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $entries = explode('---', $content);  

            foreach ($entries as $entry) {
                $entry = trim($entry);  
                if (
                    preg_match('/Vardas: (.*)/', $entry, $nameMatch) &&
                    preg_match('/Slaptažodis: (.*)/', $entry, $passMatch)
                ) {
                    $storedUsername = trim($nameMatch[1]);
                    $storedPassword = trim($passMatch[1]);

                    if ($username === $storedUsername && $password === $storedPassword) {
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (authenticateUser($username, $password)) {
        $_SESSION['user'] = $username;
        echo "<script>window.location.href = 'forumas.php';</script>";
        exit();
    } else {
        $error = 'Neteisingas vardas arba slaptažodis.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .signin-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .signin-button:hover {
            background-color: #218838;
        }

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            padding: 40px 0;
        }

        .block {
            position: relative;
            text-align: center;
        }

        .block img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            transition: transform 0.5s ease, filter 0.5s ease;
        }

        .block:hover img {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

       /*.button {
            position: absolute;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }*/

        .button:hover {
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 150px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .button {
    position: absolute;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.block:nth-child(1) .button {
    animation: pulse-yellow 2s infinite;
}

.block:nth-child(2) .button {
    animation: pulse-cyan 2s infinite;
}

.block:nth-child(3) .button {
    animation: pulse-magenta 2s infinite;
}

@keyframes pulse-yellow {
    0% {
        box-shadow: 0 0 10px yellow;
    }
    50% {
        box-shadow: 0 0 20px yellow, 0 0 40px yellow;
    }
    100% {
        box-shadow: 0 0 10px yellow;
    }
}

@keyframes pulse-cyan {
    0% {
        box-shadow: 0 0 10px cyan;
    }
    50% {
        box-shadow: 0 0 20px cyan, 0 0 40px cyan;
    }
    100% {
        box-shadow: 0 0 10px cyan;
    }
}

@keyframes pulse-magenta {
    0% {
        box-shadow: 0 0 10px magenta;
    }
    50% {
        box-shadow: 0 0 20px magenta, 0 0 40px magenta;
    }
    100% {
        box-shadow: 0 0 10px magenta;
    }
}
    </style>
    <script>
        
        function openModal() {
            document.getElementById('signinModal').style.display = 'block';
            document.querySelector('.error')?.remove();
        }
    </script>
</head>
<body>
    <header>
        <h1>Verslo platforma</h1>
        <button class="signin-button" onclick="openModal()">Prisijungti</button>
    </header>

    <div class="content">
        <div class="block">
            <img src="pics2/hiparikas.png" alt="Block 1">
            <a href="Startup.php" class="button">IDĖJŲ GENERUOTOJAS</a>
        </div>
        <div class="block">
            <img src="pics2/verslininkas.png" alt="Block 2">
            <a href="Investuotojas.php" class="button">INVESTUOTOJAS</a>
        </div>
        <div class="block">
            <img src="pics2/eksperte.png" alt="Block 3">
            <a href="Ekspertas.php" class="button">ĮGALINTOJAS</a>
        </div>
    </div>

    <div id="signinModal" class="modal" <?php if ($error) echo 'style="display:block;"'; ?>>
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('signinModal').style.display='none'">&times;</span>
            <h2>Prisijungimas</h2>
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Vardas:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Slaptažodis:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Prisijungti</button>
            </form>
        </div>
    </div>
</body>
</html>
