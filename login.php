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
        /* Rende il body un contenitore colonna */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--my-background);
            margin: 0;
        }

        /* La login-container occupa tutto lo spazio rimanente e centra la card */
        .login-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>

    <?php include('nav.php'); ?>

    <div class="login-wrapper">
        <div class="login-container">
            <div class="card centered">
                <div class="card-header">
                    <h2>Accedi</h2>
                    <p>Inserisci le tue credenziali per accedere</p>
                </div>
                
                <form style="width: 100%;">
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

                    <button type="button" class="m full-width outline">Accedi con Portale Calvino</button>
                </form>
                
                <div class="card-footer" style="justify-content: center; border: none; padding-top: 0;">
                    <p style="font-size: 0.9rem;">Non hai un account? <a href="register.php" style="color: var(--my-blue); font-weight: bold;">Registrati</a></p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>