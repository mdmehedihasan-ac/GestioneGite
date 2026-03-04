<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nome_utente = $_SESSION['username'] ?? 'Utente Sconosciuto';
?>

<header>
    <div class="header-container header-left" style="flex: 1; flex-basis: 0;">
        <h2>GestioneGite</h2>
    </div>
    
    <?php
    if (isset($_SESSION['ruolo'])) {
        
        switch ($_SESSION['ruolo']) {
            case 1: //  COMMISSIONE
                ?>
                <nav class="header-nav">
                    <a href="index.php">Home</a>
                    <a href="catalogo.php">Catalogo</a>
                    <a href="mieGite.php">Le mie Gite</a>
                    <a href="inProgramma.php">In Programma</a>
                </nav>

                <div class="header-container header-right" style="flex: 1; flex-basis: 0;">
                    <div class="profile-container">
                        <div class="profile-picture"></div>
                        <div class="profile-info">
                            <span class="user-name"><?= htmlspecialchars($nome_utente) ?></span>
                            <span class="user-role">Commissione</span>
                        </div>
                    </div>
                </div>
                <?php
                break;

            case 2: //  DOCENTE
                ?>
                <nav class="header-nav">
                    <a href="index.php">Home</a>
                    <a href="catalogo.php">Catalogo</a>
                    <a href="mieGite.php">Le mie Gite</a>
                    <a href="inProgramma.php">In Programma</a>
                </nav>

                <div class="header-container header-right" style="flex: 1; flex-basis: 0;">
                    <div class="profile-container">
                        <div class="profile-picture"></div>
                        <div class="profile-info">
                            <span class="user-name"><?= htmlspecialchars($nome_utente) ?></span>
                            <span class="user-role">Docente</span>
                        </div>
                    </div>
                </div>
                <?php
                break;
        }

    } else {
        //  UTENTE NON LOGGATO
        ?>
        <nav class="header-nav">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </nav>

        <div class="header-container header-right" style="flex: 1; flex-basis: 0;">
        </div>
        <?php
    }
    ?>
</header>