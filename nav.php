<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include('config.php');

    $nome_utente = $_SESSION['username'] ?? 'Utente Sconosciuto';
    $ruolo = $_SESSION['ruolo'] ?? null;
    $paginaCorrente = basename($_SERVER['PHP_SELF']);

    // Protezione per chi non è loggato
    if (!$ruolo && !in_array($paginaCorrente, ['login.php', 'register.php', 'index.php'])) {
        header("Location: login.php");
        exit;
    }

    // Protezione per pagine riservate alla Commissione (Ruolo 2)
    if ($ruolo == 1 && $paginaCorrente == 'elencoBozze.php') {
        header("Location: index.php");
        exit;
    }
?>

<header>
    <div class="header-container header-left" style="flex: 1; flex-basis: 0;">
        <h2>Gestione Gite</h2>
    </div>
    
    <nav class="header-nav">
        <a href="index.php">Home</a>
        <?php if ($ruolo): ?>
            <a href="catalogo.php">Catalogo</a>
            <a href="mieGite.php">Le mie Gite</a>
            <a href="inProgramma.php">In Programma</a>
            <?php if ($ruolo == 2): ?>
                <a href="elencoBozze.php">Bozze</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php">Accedi</a>
            <a href="register.php">Registrati</a>
        <?php endif; ?>
    </nav>

    <div class="header-container header-right" style="flex: 1; flex-basis: 0; position: relative;">
        <?php if ($ruolo): ?>
            <div class="profile-container" id="pulsanteProfilo" onclick="toggleMenuTendina(event)">
                <div class="profile-picture"></div>
                <div class="profile-info">
                    <span class="user-name"><?php echo htmlspecialchars($nome_utente); ?></span>
                    <span class="user-role"><?php echo ($ruolo == 2) ? 'Commissione' : 'Docente'; ?></span>
                </div>
                <div class="profile-arrow" id="frecciaTendina">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="white">
                        <path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/>
                    </svg>
                </div>
            </div>

            <div class="profile-modal hidden" id="menuTendina">
                <a href="profilo.php" class="profile-modal-row">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/>
                    </svg>
                    <p>Profilo</p>
                </a>
                <hr>
                <a href="logout.php" class="profile-modal-row profile-modal-row-exit">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/>
                    </svg>
                    <p>Esci</p>
                </a>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
    function toggleMenuTendina(e) {
        if (e) e.stopPropagation();
        var menu = document.getElementById('menuTendina');
        var freccia = document.getElementById('frecciaTendina');
        if (menu) menu.classList.toggle('hidden');
        if (freccia) freccia.classList.toggle('open');
    }

    document.addEventListener('click', function(e) {
        var menu = document.getElementById('menuTendina');
        var pulsante = document.getElementById('pulsanteProfilo');
        var freccia = document.getElementById('frecciaTendina');
        if (menu && pulsante && !pulsante.contains(e.target)) {
            menu.classList.add('hidden');
            if (freccia) freccia.classList.remove('open');
        }
    });
</script>