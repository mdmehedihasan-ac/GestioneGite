<?php
include('nav.php');

$idUtenteLoggato = $_SESSION['id_utente'];
$messaggio = "";
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Mie Gite</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
</head>
<body>
<div class="container">
<main class="content bozze-padding">

<div class="hero-section">
    <h2 style="margin-bottom:0.5rem;color:var(--blue-700);">Le Mie Gite</h2>
    <p>Tutte le gite che hai proposto o stai organizzando.</p>
</div>

<?php echo $messaggio; ?>

<?php
// ─── QUERY: proposte create da me (stato 1,2,3) ──────────────────────────────
$proposte = [];

$r1 = $conn->query("SELECT g.*, s.Stato, '1g' AS tipo FROM gita1g g JOIN statogita s ON g.idStato = s.IDStato WHERE g.idUtente = $idUtenteLoggato AND g.idStato IN (1,2,3) ORDER BY g.idGita DESC");
if ($r1) { while ($row = $r1->fetch_assoc()) $proposte[] = $row; }

$r2 = $conn->query("SELECT g.*, s.Stato, '5g' AS tipo FROM gite5 g JOIN statogita s ON g.idStato = s.IDStato WHERE g.idUtente = $idUtenteLoggato AND g.idStato IN (1,2,3) ORDER BY g.idGita DESC");
if ($r2) { while ($row = $r2->fetch_assoc()) $proposte[] = $row; }

// ─── QUERY: gite in organizzazione/concluse (stato 4,5) ──────────────────────
$organizzate = [];

$r3 = $conn->query("SELECT g.*, s.Stato, '1g' AS tipo FROM gita1g g JOIN statogita s ON g.idStato = s.IDStato WHERE g.idUtente = $idUtenteLoggato AND g.idStato IN (4,5) ORDER BY g.idGita DESC");
if ($r3) { while ($row = $r3->fetch_assoc()) $organizzate[] = $row; }

$r4 = $conn->query("SELECT g.*, s.Stato, '5g' AS tipo FROM gite5 g JOIN statogita s ON g.idStato = s.IDStato WHERE g.idUtente = $idUtenteLoggato AND g.idStato IN (4,5) ORDER BY g.idGita DESC");
if ($r4) { while ($row = $r4->fetch_assoc()) $organizzate[] = $row; }

// Funzione badge colore
function badgeClass($stato) {
    switch ($stato) {
        case 'Approvata':    return 'badge-success';
        case 'Bocciata':     return 'badge-danger';
        case 'Bozza':        return 'badge-warning';
        case 'Organizzazione': return 'badge-primary';
        case 'Conclusa':     return 'badge-secondary';
        default:             return 'badge-secondary';
    }
}
?>

<!-- ══════════════ SEZIONE 1: PROPOSTE CREATE DA ME ══════════════ -->
<div class="table-section" style="margin-top:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;">
        <h3 style="margin:0;color:var(--blue-700);">📋 Proposte create da me</h3>
        <span style="font-size:0.85rem;color:var(--blue-400);"><?php echo count($proposte); ?> proposta<?php echo count($proposte) != 1 ? 'e' : ''; ?></span>
    </div>

    <?php if (count($proposte) === 0): ?>
        <p style="color:#64748b;font-style:italic;">Non hai ancora creato nessuna proposta.</p>
    <?php else: ?>
    <div class="miegite-grid">
        <?php foreach ($proposte as $row): ?>
        <?php
            $tipo       = $row['tipo'] === '1g' ? 'Gita 1 Giorno' : 'Gita Più Giorni';
            $dest       = htmlspecialchars($row['destinazione']);
            $mezzo      = htmlspecialchars($row['mezzo'] ?? '—');
            $periodo    = htmlspecialchars($row['periodo'] ?? '—');
            $costo      = $row['costoAPersona'] !== null ? '€ ' . number_format($row['costoAPersona'], 2, ',', '.') : '—';
            $stato      = $row['Stato'];
            $badge      = badgeClass($stato);

            if ($row['tipo'] === '1g') {
                $data = $row['giorno'] ? date('d/m/Y', strtotime($row['giorno'])) : '—';
                $dataLabel = 'Giorno';
            } else {
                $ini = $row['giornoInizio'] ? date('d/m/Y', strtotime($row['giornoInizio'])) : '—';
                $fin = $row['giornoFine']   ? date('d/m/Y', strtotime($row['giornoFine']))   : '—';
                $data = "$ini → $fin";
                $dataLabel = 'Date';
            }
        ?>
        <div class="miegite-card">
            <div class="miegite-card-header">
                <h4 class="miegite-card-title"><?php echo $dest; ?></h4>
                <span class="badge <?php echo $badge; ?>"><?php echo $stato; ?></span>
            </div>
            <div class="miegite-card-body">
                <div class="miegite-card-info">
                    <span><strong>Tipo:</strong> <?php echo $tipo; ?></span>
                    <span><strong>Mezzo:</strong> <?php echo $mezzo; ?></span>
                    <span><strong>Periodo:</strong> <?php echo $periodo; ?></span>
                    <span><strong><?php echo $dataLabel; ?>:</strong> <?php echo $data; ?></span>
                    <span><strong>Costo a persona:</strong> <?php echo $costo; ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ══════════════ SEZIONE 2: GITE IN ORGANIZZAZIONE / CONCLUSE ══════════════ -->
<div class="table-section" style="margin-top:3rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;">
        <h3 style="margin:0;color:var(--blue-700);">🗓️ Gite che sto organizzando</h3>
        <span style="font-size:0.85rem;color:var(--blue-400);"><?php echo count($organizzate); ?> gita<?php echo count($organizzate) != 1 ? 'e' : ''; ?></span>
    </div>

    <?php if (count($organizzate) === 0): ?>
        <p style="color:#64748b;font-style:italic;">Non stai organizzando nessuna gita al momento.</p>
    <?php else: ?>
    <div class="miegite-grid">
        <?php foreach ($organizzate as $row): ?>
        <?php
            $tipo    = $row['tipo'] === '1g' ? 'Gita 1 Giorno' : 'Gita Più Giorni';
            $dest    = htmlspecialchars($row['destinazione']);
            $mezzo   = htmlspecialchars($row['mezzo'] ?? '—');
            $periodo = htmlspecialchars($row['periodo'] ?? '—');
            $costo   = $row['costoAPersona'] !== null ? '€ ' . number_format($row['costoAPersona'], 2, ',', '.') : '—';
            $stato   = $row['Stato'];
            $badge   = badgeClass($stato);
            $numAl   = $row['numAlunni'] !== null ? $row['numAlunni'] : '—';

            if ($row['tipo'] === '1g') {
                $data      = $row['giorno'] ? date('d/m/Y', strtotime($row['giorno'])) : '—';
                $dataLabel = 'Giorno';
                $costoMezzo = $row['costoMezzo'] !== null ? '€ ' . number_format($row['costoMezzo'], 2, ',', '.') : '—';
                $costoAtt   = $row['costoAttivita'] !== null ? '€ ' . number_format($row['costoAttivita'], 2, ',', '.') : '—';
                $extraInfo  = "<span><strong>Costo mezzo:</strong> $costoMezzo</span><span><strong>Costo attività:</strong> $costoAtt</span>";
            } else {
                $ini = $row['giornoInizio'] ? date('d/m/Y', strtotime($row['giornoInizio'])) : '—';
                $fin = $row['giornoFine']   ? date('d/m/Y', strtotime($row['giornoFine']))   : '—';
                $data      = "$ini → $fin";
                $dataLabel = 'Date';
                $extraInfo = "";
            }
        ?>
        <div class="miegite-card">
            <div class="miegite-card-header">
                <h4 class="miegite-card-title"><?php echo $dest; ?></h4>
                <span class="badge <?php echo $badge; ?>"><?php echo $stato; ?></span>
            </div>
            <div class="miegite-card-body">
                <div class="miegite-card-info">
                    <span><strong>Tipo:</strong> <?php echo $tipo; ?></span>
                    <span><strong>Mezzo:</strong> <?php echo $mezzo; ?></span>
                    <span><strong>Periodo:</strong> <?php echo $periodo; ?></span>
                    <span><strong><?php echo $dataLabel; ?>:</strong> <?php echo $data; ?></span>
                    <span><strong>Costo a persona:</strong> <?php echo $costo; ?></span>
                    <span><strong>Num. alunni:</strong> <?php echo $numAl; ?></span>
                    <?php echo $extraInfo; ?>
                </div>
            </div>
            <div class="miegite-card-footer">
                <a href="partecipanti.php?id=<?php echo $row['idGita']; ?>&tipo=<?php echo $row['tipo']; ?>" class="button xs" style="text-decoration:none;">👥 Partecipanti</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

</main>

<footer><div class="footer-container"><div class="footer-left"><p><strong>Gestione Gite Scolastiche</strong></p></div></div></footer>
</div>
</body>
</html>
