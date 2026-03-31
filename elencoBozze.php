<?php
    session_start();
    include('config.php');
    

    $messaggio = "";

    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_gita'])) {
        $idGita = (int)$_POST['id_gita'];
        $nuovoStato = ($_POST['azione'] == 'approva') ? 2 : 3;
        
        $queryAzione = "UPDATE gitaorganizzata SET IDStato = $nuovoStato WHERE IDGita = $idGita";
        if (mysqli_query($conn, $queryAzione)) {
            $messaggio = "<div class='alert alert-success'>Operazione completata con successo.</div>";
        } else {
            $messaggio = "<div class='alert alert-danger'>Errore durante l'aggiornamento.</div>";
        }
    }
?>
<?php include('nav.php'); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Gite - Elenco Bozze</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js"></script>
</head>
<body>

    <div class="container">
        <main class="content bozze-padding">

            <div class="hero-section" style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.4rem;">
                        <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Elenco Bozze</h2>
                    </div>
                    <p>Gite inviate in attesa di approvazione. Approva o boccia ogni proposta per aggiornarne lo stato.</p>
                </div>
                <div style="font-size: 0.9rem; color: var(--my-gray);">
                    <?php 
                        $ricercaContatore = mysqli_query($conn, "SELECT COUNT(*) as totale FROM gitaorganizzata WHERE IDStato = 1");
                        $rigaContatore = mysqli_fetch_assoc($ricercaContatore);
                    ?>
                    <strong id="contatore"><?php echo $rigaContatore['totale']; ?></strong> gite in attesa
                </div>
            </div>

            <?php echo $messaggio; ?>

            <div class="alert alert-info" style="margin-top: 1.5rem;">
                <span style="font-size: 1.2rem; font-weight: bold;">[i]</span>
                <p>Le azioni di approvazione e bocciatura aggiornano lo stato della gita in tutto il sistema.</p>
            </div>

            <div class="table-section" style="margin-top: 1.5rem;">
                <div class="table-container table-container-full">
                    <table id="tabellaBozze">
                        <thead>
                            <tr>
                                <th>Destinazione</th>
                                <th>Docente Referente</th>
                                <th>Classe</th>
                                <th>Data Inizio</th>
                                <th>Data Fine</th>
                                <th>Alunni</th>
                                <th>Docenti</th>
                                <th>Mezzo</th>
                                <th>Costo Totale</th>
                                <th>Inviata il</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT g.*, p.Destinazione, p.MezzoDiTrasporto, u.Nome, u.Cognome FROM gitaorganizzata g JOIN propostagita p ON g.IDProposta = p.IDProposta JOIN utente u ON g.IDUtente = u.IDUtente WHERE g.IDStato = 1 ORDER BY g.IDGita DESC";
                                $risultatoBozze = mysqli_query($conn, $query);

                                if (mysqli_num_rows($risultatoBozze) > 0) {
                                    while ($row = mysqli_fetch_assoc($risultatoBozze)) {
                                        $idGita = $row['IDGita'];
                                        $destinazione = htmlspecialchars($row['Destinazione']);
                                        $nomeCompleto = htmlspecialchars($row['Nome'] . ' ' . $row['Cognome']);
                                        $dataInizio = date('d/m/Y', strtotime($row['DataInizio']));
                                        $dataFine = date('d/m/Y', strtotime($row['DataFine']));
                                        $mezzo = htmlspecialchars($row['MezzoDiTrasporto']);
                                        $costo = number_format($row['CostoTot'], 2, ',', '.');
                                        $dataInvio = date('d/m/Y', strtotime($row['DataInizio'] . ' -7 days'));
                                        $destinazioneSicura = addslashes($row['Destinazione']);

                                        echo "<tr data-id='$idGita'>";
                                        echo "<td><strong>$destinazione</strong></td>";
                                        echo "<td>$nomeCompleto</td>";
                                        echo "<td>N/D</td>";
                                        echo "<td>$dataInizio</td>";
                                        echo "<td>$dataFine</td>";
                                        echo "<td>{$row['NumAlunni']}</td>";
                                        echo "<td>{$row['NumDocentiAccompagnatori']}</td>";
                                        echo "<td>$mezzo</td>";
                                        echo "<td>&euro; $costo</td>";
                                        echo "<td>$dataInvio</td>";
                                        echo "<td class='azioni-cell'>";
                                        echo "<button class='xs btn-approva' onclick=\"preparaAzione($idGita, '$destinazioneSicura', 'approva')\">Approva</button> ";
                                        echo "<button class='xs btn-boccia' onclick=\"preparaAzione($idGita, '$destinazioneSicura', 'boccia')\">Boccia</button>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='11' style='text-align:center;'>Nessuna gita in attesa di approvazione.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="empty-state hidden" id="vuoto">
                <span style="font-size: 3rem; color: var(--blue-200);">OK</span>
                <h3>Nessuna gita in attesa</h3>
                <p>Tutte le proposte sono state elaborate. Torna più tardi.</p>
            </div>

        </main>

        <div class="modal-overlay hidden" id="modalApprova">
            <div class="modal" style="max-width: 440px;">
                <div class="modal-header">
                    <h3 style="color: var(--my-green);">Conferma Approvazione</h3>
                    <button class="close-btn" onclick="closeModal('modalApprova')">&times;</button>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <div style="margin-bottom: 1rem;">
                        <span style="font-size: 2.5rem; color: var(--my-green);">OK</span>
                    </div>
                    <form method="POST" action="elencoBozze.php">
                        <input type="hidden" name="id_gita" id="approvaGitaId">
                        <input type="hidden" name="azione" value="approva">
                        <p>Stai per <strong>approvare</strong> la gita</p>
                        <p style="margin-top: 0.5rem;"><strong id="approvaDestLabel"></strong></p>
                        <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--my-gray);">Lo stato passerà a <span class="badge badge-success">Approvata</span></p>
                    </form>
                </div>
                <div class="modal-footer" style="justify-content: center; gap: 1rem;">
                    <button class="button outline" onclick="closeModal('modalApprova')">Annulla</button>
                    <button class="button" onclick="document.querySelector('#modalApprova form').submit()">Conferma Approvazione</button>
                </div>
            </div>
        </div>

        <div class="modal-overlay hidden" id="modalBoccia">
            <div class="modal" style="max-width: 440px;">
                <div class="modal-header">
                    <h3 style="color: var(--hex-red);">Conferma Bocciatura</h3>
                    <button class="close-btn" onclick="closeModal('modalBoccia')">&times;</button>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <div style="margin-bottom: 1rem;">
                        <span style="font-size: 2.5rem; color: var(--hex-red);">X</span>
                    </div>
                    <form method="POST" action="elencoBozze.php">
                        <input type="hidden" name="id_gita" id="bocciaGitaId">
                        <input type="hidden" name="azione" value="boccia">
                        <p>Stai per <strong>bocciare</strong> la gita</p>
                        <p style="margin-top: 0.5rem;"><strong id="bocciaDestLabel"></strong></p>
                        <div class="form-group" style="margin-top: 1rem; text-align: left;">
                            <label for="motivazione">Motivazione (opzionale)</label>
                            <textarea id="motivazione" name="motivazione" rows="3" placeholder="Inserisci una motivazione per il docente..." style="width:100%; border-radius: var(--radius); border: 1px solid var(--my-gray); padding: 0.75rem; font-family: inherit; font-size: 0.9rem; resize: vertical; box-sizing: border-box;"></textarea>
                        </div>
                        <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--my-gray);">Lo stato verrà rimosso dall'elenco bozze.</p>
                    </form>
                </div>
                <div class="modal-footer" style="justify-content: center; gap: 1rem;">
                    <button class="button outline" onclick="closeModal('modalBoccia')">Annulla</button>
                    <button class="button cancel" onclick="document.querySelector('#modalBoccia form').submit()">Conferma Bocciatura</button>
                </div>
            </div>
        </div>

        <footer>
            <div class="footer-container">
                <div class="footer-left">
                    <p><strong>Gestione Gite Scolastiche</strong></p>
                    <p class="footer-copyright">© 2026 - Piattaforma Interna</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function preparaAzione(id, dest, tipo) {
            if (tipo === 'approva') {
                document.getElementById('approvaGitaId').value = id;
                document.getElementById('approvaDestLabel').innerText = dest;
                openModal('modalApprova');
            } else {
                document.getElementById('bocciaGitaId').value = id;
                document.getElementById('bocciaDestLabel').innerText = dest;
                document.getElementById('motivazione').value = '';
                openModal('modalBoccia');
            }
        }
    </script>

</body>
</html>
