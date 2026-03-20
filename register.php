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
            align-items: center;
            padding: 2rem 1rem;
        }

        .register-container {
            width: min(100%, 460px);
        }

        .register-container .card {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <?php include('nav.php'); ?>

    <div class="register-wrapper">
        <div class="register-container">
            <div class="card centered">
                <div class="card-header">
                    <h2>Crea un Account</h2>
                    <p>Inserisci i tuoi dati per registrarti</p>
                </div>
                
                <form style="width: 100%;">
                    <div class="form-group">
                        <label for="name">Nome Completo</label>
                        <input type="text" id="name" name="name" placeholder="Mario Rossi" required>
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
                </form>
                
                <div class="card-footer" style="justify-content: center; border: none; padding-top: 0;">
                    <p style="font-size: 0.9rem;">Hai già un account? <a href="login.php" style="color: var(--my-blue); font-weight: bold;">Accedi</a></p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>