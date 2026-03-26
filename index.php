<?php 
    include('nav.php');
    include('config.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Gite - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
</head>
<body>

    <div class="container">
        <main class="content home-padding">
            
            <div class="hero-section">
                <h1>Sistema Gestione Gite</h1>
                <p>Benvenuto nel portale per l'organizzazione dei viaggi d'istruzione. Seleziona una sezione per procedere.</p>
            </div>

            <div class="home-grid">
                
                <div class="card">
                    <div class="card-header">
                        <h3>Catalogo Proposte</h3>
                    </div>
                    <div class="card-body">
                        <p>Consulta il catalogo</p>
                    </div>
                    <div class="card-footer">
                        <a href="catalogo.php" class="button">Vai al Catalogo</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Gite in Programma</h3>
                    </div>
                    <div class="card-body">
                        <p>Visualizza le gite</p>
                    </div>
                    <div class="card-footer">
                        <a href="inProgramma.php" class="button">Vedi Programma</a>
                    </div>
                </div>

            </div>

        </main>

        <footer>
            <div class="footer-container">
                <div class="footer-left">
                    <p><strong>Gestione Gite Scolastiche</strong></p>
                </div>
            </div>
        </footer>
    </div>

</body>
</html>