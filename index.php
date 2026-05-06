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
    <?php include('nav.php'); ?>

    <div class="container">
        <main class="content home-padding">

<?php if (!$ruolo): ?>
            <!-- contenuto per utenti non loggati -->
            <div class="hero-section">
                <h1>Sistema Gestione Gite</h1>
                <p>Benvenuto nel portale per l'organizzazione dei viaggi d'istruzione. Accedi o registrati per iniziare.</p>
            </div>

            <div class="home-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>Accedi</h3>
                    </div>
                    <div class="card-body">
                        <p>Hai gia un account? Effettua il login per accedere alle funzionalita.</p>
                    </div>
                    <div class="card-footer">
                        <a href="login.php" class="button">Accedi</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Registrati</h3>
                    </div>
                    <div class="card-body">
                        <p>Non hai un account? Crea il tuo profilo per proporre e organizzare gite.</p>
                    </div>
                    <div class="card-footer">
                        <a href="register.php" class="button">Registrati</a>
                    </div>
                </div>
            </div>

<?php else: ?>
            <!-- contenuto per utenti loggati -->
            <?php
            // carica statistiche per la home
            $idUtente = intval($_SESSION['id_utente']);

            // proposte personali (stato 1,2,3)
            $rp1 = mysqli_query($conn, "SELECT COUNT(*) AS tot FROM gita1g WHERE idUtente = $idUtente AND idStato IN (1,2,3)");
            $rp2 = mysqli_query($conn, "SELECT COUNT(*) AS tot FROM gite5 WHERE idUtente = $idUtente AND idStato IN (1,2,3)");
            $totProposte = (mysqli_fetch_assoc($rp1)['tot'] ?? 0) + (mysqli_fetch_assoc($rp2)['tot'] ?? 0);

            // gite in organizzazione personali (stato 4)
            $ro1 = mysqli_query($conn, "SELECT COUNT(*) AS tot FROM gita1g WHERE idUtente = $idUtente AND idStato = 4");
            $ro2 = mysqli_query($conn, "SELECT COUNT(*) AS tot FROM gite5 WHERE idUtente = $idUtente AND idStato = 4");
            $totOrg = (mysqli_fetch_assoc($ro1)['tot'] ?? 0) + (mysqli_fetch_assoc($ro2)['tot'] ?? 0);

            // totale gite in programma (stato 4, tutti)
            $rip1 = mysqli_query($conn, "SELECT COUNT(*) AS tot FROM gita1g WHERE idStato = 4");
            $rip2 = mysqli_query($conn, "SELECT COUNT(*) AS tot FROM gite5 WHERE idStato = 4");
            $totInProgramma = (mysqli_fetch_assoc($rip1)['tot'] ?? 0) + (mysqli_fetch_assoc($rip2)['tot'] ?? 0);

            // bozze in attesa (solo per commissione, stato 1)
            $totBozze = 0;
            if ($ruolo == 2) {
                $rb1 = mysqli_query($conn, "SELECT COUNT(*) AS tot FROM gita1g WHERE idStato = 1");
                $rb2 = mysqli_query($conn, "SELECT COUNT(*) AS tot FROM gite5 WHERE idStato = 1");
                $totBozze = (mysqli_fetch_assoc($rb1)['tot'] ?? 0) + (mysqli_fetch_assoc($rb2)['tot'] ?? 0);
            }
            ?>

            <div class="hero-section">
                <h1>Benvenuto, <?php echo htmlspecialchars(explode(' ', $nome_utente)[0]); ?></h1>
                <p>
                    <?php if ($ruolo == 2): ?>
                        <span class="badge badge-commissione" style="font-size:0.75rem;">Commissione</span>
                        Gestisci le proposte, approva le bozze e organizza le gite scolastiche.
                    <?php else: ?>
                        Proponi nuove gite, organizza quelle approvate e segui lo stato delle tue proposte.
                    <?php endif; ?>
                </p>
            </div>

            <!-- riepilogo numerico -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
                <div class="card" style="text-align:center;padding:1.2rem;">
                    <span style="font-size:2rem;font-weight:700;color:var(--blue-600);"><?php echo $totProposte; ?></span>
                    <p style="font-size:0.85rem;color:var(--my-gray);margin-top:0.3rem;">Le mie proposte</p>
                </div>
                <div class="card" style="text-align:center;padding:1.2rem;">
                    <span style="font-size:2rem;font-weight:700;color:var(--blue-600);"><?php echo $totOrg; ?></span>
                    <p style="font-size:0.85rem;color:var(--my-gray);margin-top:0.3rem;">In organizzazione</p>
                </div>
                <div class="card" style="text-align:center;padding:1.2rem;">
                    <span style="font-size:2rem;font-weight:700;color:var(--blue-600);"><?php echo $totInProgramma; ?></span>
                    <p style="font-size:0.85rem;color:var(--my-gray);margin-top:0.3rem;">Gite in programma</p>
                </div>
                <?php if ($ruolo == 2): ?>
                <div class="card" style="text-align:center;padding:1.2rem;">
                    <span style="font-size:2rem;font-weight:700;color:<?php echo $totBozze > 0 ? 'var(--hex-orange)' : 'var(--blue-600)'; ?>;"><?php echo $totBozze; ?></span>
                    <p style="font-size:0.85rem;color:var(--my-gray);margin-top:0.3rem;">Bozze in attesa</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- card navigazione -->
            <div class="home-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>Catalogo Proposte</h3>
                    </div>
                    <div class="card-body">
                        <p>Consulta le proposte approvate e organizza una nuova gita.</p>
                    </div>
                    <div class="card-footer">
                        <a href="catalogo.php" class="button">Vai al Catalogo</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Le Mie Gite</h3>
                    </div>
                    <div class="card-body">
                        <p>Visualizza le gite che hai proposto o che stai organizzando.</p>
                    </div>
                    <div class="card-footer">
                        <a href="mieGite.php" class="button">Vai alle Mie Gite</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Gite in Programma</h3>
                    </div>
                    <div class="card-body">
                        <p>Elenco di tutte le gite attualmente in organizzazione.</p>
                    </div>
                    <div class="card-footer">
                        <a href="inProgramma.php" class="button">Vedi Programma</a>
                    </div>
                </div>

                <?php if ($ruolo == 2): ?>
                <div class="card">
                    <div class="card-header">
                        <h3>Bozze in Attesa</h3>
                    </div>
                    <div class="card-body">
                        <p>Approva o boccia le proposte inviate dai docenti.</p>
                    </div>
                    <div class="card-footer">
                        <a href="elencoBozze.php" class="button">Gestisci Bozze</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

<?php endif; ?>

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