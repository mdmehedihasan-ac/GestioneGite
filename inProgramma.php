<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Gite - In Programma</title>
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
            
            <div class="hero-section">
                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Gite in Programma</h2>
                <p>Elenco generale di tutte le gite organizzate e il loro stato di avanzamento. Monitora le date, i partecipanti e le approvazioni.</p>
            </div>

            <div class="table-section" style="margin-top: 2rem;">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Destinazione</th>
                                <th>Data Inizio</th>
                                <th>Data Fine</th>
                                <th>Alunni</th>
                                <th>Docenti</th>
                                <th>Costo Totale</th>
                                <th>Stato</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT g.*, p.Destinazione, s.Stato FROM gitaorganizzata g JOIN propostagita p ON g.IDProposta = p.IDProposta JOIN statogita s ON g.IDStato = s.IDStato ORDER BY g.DataInizio ASC";
                                $risultato = mysqli_query($conn, $query);

                                if (mysqli_num_rows($risultato) > 0) {
                                    while ($row = mysqli_fetch_assoc($risultato)) {
                                        $dataInizio = date('d/m/Y', strtotime($row['DataInizio']));
                                        $dataFine = date('d/m/Y', strtotime($row['DataFine']));

                                        $classeBadge = 'badge-secondary';
                                        if ($row['Stato'] == 'Approvata') $classeBadge = 'badge-success';
                                        if ($row['Stato'] == 'Inserita') $classeBadge = 'badge-warning';
                                        if ($row['Stato'] == 'Conclusa') $classeBadge = 'badge-primary';
                                        if ($row['Stato'] == 'NonApprovata') $classeBadge = 'badge-danger';

                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['Destinazione']) . "</td>";
                                        echo "<td>" . $dataInizio . "</td>";
                                        echo "<td>" . $dataFine . "</td>";
                                        echo "<td>" . $row['NumAlunni'] . "</td>";
                                        echo "<td>" . $row['NumDocentiAccompagnatori'] . "</td>";
                                        echo "<td>&euro; " . number_format($row['CostoTot'], 2, ',', '.') . "</td>";
                                        echo "<td><span class='badge $classeBadge'>" . htmlspecialchars($row['Stato']) . "</span></td>";
                                        echo "<td><button class='xs outline'>Dettagli</button></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' style='text-align:center;'>Nessuna gita in programma.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
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