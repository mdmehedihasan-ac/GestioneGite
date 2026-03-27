<?php
    session_start();
    include('config.php');

    $errore = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $istruzione = mysqli_prepare($conn, "SELECT IDUtente, Nome, Cognome, Password, IDTipo FROM utente WHERE Mail = ?");
        mysqli_stmt_bind_param($istruzione, "s", $email);
        mysqli_stmt_execute($istruzione);
        $result = mysqli_stmt_get_result($istruzione);
        
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['Password'])) {
                
                $_SESSION['id_utente'] = $row['IDUtente'];
                $_SESSION['username'] = $row['Nome'] . " " . $row['Cognome'];
                $_SESSION['ruolo'] = $row['IDTipo'];
                
                header("Location: index.php");
                exit;
            } else {
                $errore = "Password errata.";
            }
        } else {
            $errore = "Nessun account trovato con questa email.";
        }
        mysqli_stmt_close($istruzione);
    }
?>
<?php include('nav.php'); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Login - Gestione Gite</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--my-background);
            margin: 0;
        }

        .login-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0.5rem 1rem;
            overflow-y: auto;
        }

        .login-container {
            width: min(100%, 460px);
            margin: auto;
        }

        .login-container .card {
            width: 100%;
            box-sizing: border-box;
        }

        .login-container .card-header {
            padding-bottom: 0.5rem !important;
        }

        .login-container .form-group {
            margin-bottom: 0.5rem !important;
        }

        .login-container .card-footer {
            padding-top: 0.25rem !important;
        }
    </style>
</head>
<body>

    

    <div class="login-wrapper">
        <div class="login-container">
            <div class="card centered">
                <div class="card-header">
                    <h2>Accedi</h2>
                    <p>Inserisci le tue credenziali per accedere</p>
                </div>
                
                <?php if($errore): ?>
                    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 10px; font-size: 0.9rem;">
                        <?php echo $errore; ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST" style="width: 100%;">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="mario.rossi@esempio.it" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="********" required>
                    </div>
                    
                    <div class="checkbox-group" style="justify-content: flex-start; width: 100%; padding-left: 0.5rem;">
                        <input type="checkbox" id="remember">
                        <label for="remember">Ricordami</label>
                    </div>
                    
                    <button type="submit" class="m full-width">Accedi</button>
                    
                    <div class="divider">oppure</div>

                    <button type="button" class="m full-width outline" onclick="alert('Funzionalità Portale Calvino non ancora disponibile')">Accedi con Portale Calvino</button>

                </form>
                
                <div class="card-footer" style="justify-content: center; border: none; padding-top: 0;">
                    <p style="font-size: 0.9rem;">Non hai un account? <a href="register.php" style="color: var(--my-blue); font-weight: bold;">Registrati</a></p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>