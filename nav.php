<?php
include('config.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nome_utente = $_SESSION['username'] ?? 'Utente Sconosciuto';
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                    <a href="elencoBozze.php" >Bozze</a>
                </nav>

                <div class="header-container header-right" style="flex: 1; flex-basis: 0; position: relative;">
                    <div class="profile-container" onclick="toggleProfile(event)">
                        <div class="profile-picture"></div>
                        <div class="profile-info">
                            <span class="user-name"><?= htmlspecialchars($nome_utente) ?></span>
                            <span class="user-role">Commissione</span>
                        </div>
                        <div class="profile-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="white">
                                <path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="profile-modal hidden">
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

                <div class="header-container header-right" style="flex: 1; flex-basis: 0; position: relative;">
                    <div class="profile-container" onclick="toggleProfile(event)">
                        <div class="profile-picture"></div>
                        <div class="profile-info">
                            <span class="user-name"><?= htmlspecialchars($nome_utente) ?></span>
                            <span class="user-role">Docente</span>
                        </div>
                        <div class="profile-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="white">
                                <path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="profile-modal hidden">
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