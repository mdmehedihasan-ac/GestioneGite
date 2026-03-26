<?php
    session_start();
    include('config.php');

    $errore = "";
    $successo = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = trim($_POST['nome'] ?? '');
        $cognome = trim($_POST['cognome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $conferma_password = $_POST['confirm-password'] ?? '';

        if ($password !== $conferma_password) {
            $errore = "Le password non coincidono.";
        } elseif (strlen($password) < 6) {
            $errore = "La password deve contenere almeno 6 caratteri.";
        } else {
            // Controllo email esistente
            $stmt_check = mysqli_prepare($conn, "SELECT Mail FROM utente WHERE Mail = ?");
            mysqli_stmt_bind_param($stmt_check, "s", $email);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);
            
            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $errore = "L'indirizzo email è già in uso.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $idTipo = 1; // Docente
                
                $stmt = mysqli_prepare($conn, "INSERT INTO utente (Nome, Cognome, Mail, Password, IDTipo) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssssi", $nome, $cognome, $email, $hash, $idTipo);
                
                if (mysqli_stmt_execute($stmt)) {
                    $successo = "Registrazione completata! Puoi ora accedere.";
                } else {
                    $errore = "Errore durante la registrazione.";
                }
                mysqli_stmt_close($stmt);
            }
            mysqli_stmt_close($stmt_check);
        }
    }
?>
<?php include('nav.php'); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Registrati - Gestione Gite</title>
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

        .register-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0.5rem 1rem;
            overflow-y: auto;
        }

        .register-container {
            width: min(100%, 460px);
            margin: auto;
        }

        .register-container .card {
            width: 100%;
            box-sizing: border-box;
            padding: 1rem !important;
            gap: 0.5rem !important;
        }

        .register-container .card-header {
            padding-bottom: 0.5rem !important;
            margin-bottom: 0.25rem !important;
        }

        .register-container .card-header h2 {
            margin-bottom: 0.25rem !important;
        }

        .register-container .form-group {
            margin-bottom: 0.4rem !important;
        }

        .register-container .card-footer {
            padding-top: 0.25rem !important;
            margin-top: 0.25rem !important;
        }
    </style>
</head>
<body>
    
    <div class="register-wrapper">
        <div class="register-container">
            <div class="card centered">
                <div class="card-header">
                    <h2>Crea un Account</h2>
                    <p>Inserisci i tuoi dati per registrarti</p>
                </div>
                
                <?php if($errore): ?>
                    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 10px; font-size: 0.9rem;">
                        <?php echo $errore; ?>
                    </div>
                <?php endif; ?>
                <?php if($successo): ?>
                    <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 10px; font-size: 0.9rem;">
                        <?php echo $successo; ?>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST" style="width: 100%;">
                    <div class="form-group" style="display: flex; gap: 0.5rem; flex-direction: row !important;">
                        <div style="flex: 1; display: flex; flex-direction: column;">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" placeholder="Mario" required>
                        </div>
                        <div style="flex: 1; display: flex; flex-direction: column;">
                            <label for="cognome">Cognome</label>
                            <input type="text" id="cognome" name="cognome" placeholder="Rossi" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="mario.rossi@esempio.it" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="********" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Conferma Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="********" required>
                    </div>
                    
                    <div class="checkbox-group" style="justify-content: flex-start; width: 100%; padding-left: 0.5rem;">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">Accetto i Termini e Condizioni</label>
                    </div>
                    
                    <button type="submit" class="m full-width">Registrati</button>

                    <div class="divider">oppure</div>

                    <button type="button" class="m full-width outline" onclick="alert('Funzionalità Portale Calvino non ancora disponibile')">Registrati con Portale Calvino</button>
                </form>
                
                <div class="card-footer" style="justify-content: center; border: none; padding-top: 0;">
                    <p style="font-size: 0.9rem;">Hai già un account? <a href="login.php" style="color: var(--my-blue); font-weight: bold;">Accedi</a></p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>