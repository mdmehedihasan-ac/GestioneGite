<?php<?php

include('nav.php');    session_start();

$messaggio = "";    include('config.php');

    

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_gita'])) {

    $idGita = intval($_POST['id_gita']);    $messaggio = "";

    $nuovoStato = ($_POST['azione'] == 'approva') ? 2 : 3;

    

    $queryAzione = "UPDATE gitaorganizzata SET IDStato = $nuovoStato WHERE IDGita = $idGita";    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_gita'])) {

    if (mysqli_query($conn, $queryAzione)) {        $idGita = (int)$_POST['id_gita'];

        $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Operazione completata con successo.</div>";        $nuovoStato = ($_POST['azione'] == 'approva') ? 2 : 3;

    } else {        

        $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante l'aggiornamento.</div>";        $queryAzione = "UPDATE gitaorganizzata SET IDStato = $nuovoStato WHERE IDGita = $idGita";

    }        if (mysqli_query($conn, $queryAzione)) {

}            $messaggio = "<div class='alert alert-success'>Operazione completata con successo.</div>";

?>        } else {

<!DOCTYPE html>            $messaggio = "<div class='alert alert-danger'>Errore durante l'aggiornamento.</div>";

<html lang="it">        }

<head>    }

    <meta charset="UTF-8">?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0"><?php include('nav.php'); ?>

    <title>Gestione Gite - Elenco Bozze</title><!DOCTYPE html>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"><html lang="it">

    <link rel="stylesheet" href="style.css"><head>

    <link rel="stylesheet" href="vetrina.css">    <meta charset="UTF-8">

    <link rel="stylesheet" href="style_custom.css">    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="vetrina.js"></script>    <title>Gestione Gite - Elenco Bozze</title>

</head>    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<body>    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="vetrina.css">

    <div class="container">    <link rel="stylesheet" href="style_custom.css">

        <main class="content bozze-padding">    <script src="vetrina.js"></script>

</head>

            <div class="hero-section" style="display:flex; justify-content:space-between; align-items:flex-end;"><body>

                <div>

                    <h2 style="margin-bottom:1rem; color:var(--blue-700);">Elenco Bozze</h2>    <div class="container">

                    <p>Gite inviate in attesa di approvazione. Approva o boccia ogni proposta per aggiornarne lo stato.</p>        <main class="content bozze-padding">

                </div>

                <div style="font-size:0.9rem; color:var(--my-gray);">            <div class="hero-section" style="display: flex; justify-content: space-between; align-items: flex-end;">

                    <?php                 <div>

                        $ricercaContatore = mysqli_query($conn, "SELECT COUNT(*) as totale FROM gitaorganizzata WHERE IDStato = 1");                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.4rem;">

                        $rigaContatore = mysqli_fetch_assoc($ricercaContatore);                        <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Elenco Bozze</h2>

                    ?>                    </div>

                    <strong><?php echo $rigaContatore['totale']; ?></strong> gite in attesa                    <p>Gite inviate in attesa di approvazione. Approva o boccia ogni proposta per aggiornarne lo stato.</p>

                </div>                </div>

            </div>                <div style="font-size: 0.9rem; color: var(--my-gray);">

                    <?php 

            <?php echo $messaggio; ?>                        $ricercaContatore = mysqli_query($conn, "SELECT COUNT(*) as totale FROM gitaorganizzata WHERE IDStato = 1");

                        $rigaContatore = mysqli_fetch_assoc($ricercaContatore);

            <div class="table-section" style="margin-top:1.5rem;">                    ?>

                <div class="table-container table-container-full">                    <strong id="contatore"><?php echo $rigaContatore['totale']; ?></strong> gite in attesa

                    <table>                </div>

                        <thead>            </div>

                            <tr>

                                <th>Destinazione / Descrizione</th>            <?php echo $messaggio; ?>

                                <th>Docente</th>

                                <th>Costo Stimato</th>            <div class="alert alert-info" style="margin-top: 1.5rem;">

                                <th>Azioni</th>                <span style="font-size: 1.2rem; font-weight: bold;">[i]</span>

                            </tr>                <p>Le azioni di approvazione e bocciatura aggiornano lo stato della gita in tutto il sistema.</p>

                        </thead>            </div>

                        <tbody>

                            <?php             <div class="table-section" style="margin-top: 1.5rem;">

                                $query = "SELECT g.*, u.Nome, u.Cognome FROM gitaorganizzata g JOIN utente u ON g.IDUtente = u.IDUtente WHERE g.IDStato = 1 ORDER BY g.IDGita DESC";                <div class="table-container table-container-full">

                                $risultatoBozze = mysqli_query($conn, $query);                    <table id="tabellaBozze">

                        <thead>

                                if (mysqli_num_rows($risultatoBozze) > 0) {                            <tr>

                                    while ($row = mysqli_fetch_assoc($risultatoBozze)) {                                <th>Destinazione</th>

                                        $idGita = $row['IDGita'];                                <th>Docente Referente</th>

                                        $dest = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');                                <th>Classe</th>

                                        $docente = htmlspecialchars($row['Nome'] . ' ' . $row['Cognome']);                                <th>Data Inizio</th>

                                        $costo = number_format($row['CostoTot'], 2, ',', '.');                                <th>Data Fine</th>

                                        $destSicura = addslashes($row['ClassiPartecipanti'] ?? 'N/D');                                <th>Alunni</th>

                                <th>Docenti</th>

                                        echo "<tr>";                                <th>Mezzo</th>

                                        echo "<td><strong>$dest</strong></td>";                                <th>Costo Totale</th>

                                        echo "<td>$docente</td>";                                <th>Inviata il</th>

                                        echo "<td>&euro; $costo</td>";                                <th>Azioni</th>

                                        echo "<td class='azioni-cell'>";                            </tr>

                                        echo "<button class='xs btn-approva' onclick=\"preparaAzione($idGita, '$destSicura', 'approva')\">Approva</button> ";                        </thead>

                                        echo "<button class='xs btn-boccia' onclick=\"preparaAzione($idGita, '$destSicura', 'boccia')\">Boccia</button>";                        <tbody>

                                        echo "</td>";                            <?php 

                                        echo "</tr>";                                $query = "SELECT g.*, p.Destinazione, p.MezzoDiTrasporto, u.Nome, u.Cognome FROM gitaorganizzata g JOIN propostagita p ON g.IDProposta = p.IDProposta JOIN utente u ON g.IDUtente = u.IDUtente WHERE g.IDStato = 1 ORDER BY g.IDGita DESC";

                                    }                                $risultatoBozze = mysqli_query($conn, $query);

                                } else {

                                    echo "<tr><td colspan='4' style='text-align:center;'>Nessuna gita in attesa di approvazione.</td></tr>";                                if (mysqli_num_rows($risultatoBozze) > 0) {

                                }                                    while ($row = mysqli_fetch_assoc($risultatoBozze)) {

                            ?>                                        $idGita = $row['IDGita'];

                        </tbody>                                        $destinazione = htmlspecialchars($row['Destinazione']);

                    </table>                                        $nomeCompleto = htmlspecialchars($row['Nome'] . ' ' . $row['Cognome']);

                </div>                                        $dataInizio = date('d/m/Y', strtotime($row['DataInizio']));

            </div>                                        $dataFine = date('d/m/Y', strtotime($row['DataFine']));

                                        $mezzo = htmlspecialchars($row['MezzoDiTrasporto']);

        </main>                                        $costo = number_format($row['CostoTot'], 2, ',', '.');

                                        $dataInvio = date('d/m/Y', strtotime($row['DataInizio'] . ' -7 days'));

        <div class="modal-overlay hidden" id="modalApprova">                                        $destinazioneSicura = addslashes($row['Destinazione']);

            <div class="modal" style="max-width:440px;">

                <div class="modal-header">                                        echo "<tr data-id='$idGita'>";

                    <h3 style="color:var(--my-green);">Conferma Approvazione</h3>                                        echo "<td><strong>$destinazione</strong></td>";

                    <button class="close-btn" onclick="closeModal('modalApprova')">&times;</button>                                        echo "<td>$nomeCompleto</td>";

                </div>                                        echo "<td>N/D</td>";

                <div class="modal-body" style="text-align:center;">                                        echo "<td>$dataInizio</td>";

                    <form method="POST" action="elencoBozze.php">                                        echo "<td>$dataFine</td>";

                        <input type="hidden" name="id_gita" id="approvaGitaId">                                        echo "<td>{$row['NumAlunni']}</td>";

                        <input type="hidden" name="azione" value="approva">                                        echo "<td>{$row['NumDocentiAccompagnatori']}</td>";

                        <p>Stai per <strong>approvare</strong> la proposta:</p>                                        echo "<td>$mezzo</td>";

                        <p style="margin-top:0.5rem;"><strong id="approvaDestLabel"></strong></p>                                        echo "<td>&euro; $costo</td>";

                        <p style="margin-top:0.5rem;font-size:0.85rem;color:var(--my-gray);">Lo stato passer&agrave; a <span class="badge badge-success">Approvata</span></p>                                        echo "<td>$dataInvio</td>";

                    </form>                                        echo "<td class='azioni-cell'>";

                </div>                                        echo "<button class='xs btn-approva' onclick=\"preparaAzione($idGita, '$destinazioneSicura', 'approva')\">Approva</button> ";

                <div class="modal-footer" style="justify-content:center;gap:1rem;">                                        echo "<button class='xs btn-boccia' onclick=\"preparaAzione($idGita, '$destinazioneSicura', 'boccia')\">Boccia</button>";

                    <button class="button outline" onclick="closeModal('modalApprova')">Annulla</button>                                        echo "</td>";

                    <button class="button" onclick="document.querySelector('#modalApprova form').submit()">Conferma Approvazione</button>                                        echo "</tr>";

                </div>                                    }

            </div>                                } else {

        </div>                                    echo "<tr><td colspan='11' style='text-align:center;'>Nessuna gita in attesa di approvazione.</td></tr>";

                                }

        <div class="modal-overlay hidden" id="modalBoccia">                            ?>

            <div class="modal" style="max-width:440px;">                        </tbody>

                <div class="modal-header">                    </table>

                    <h3 style="color:var(--hex-red);">Conferma Bocciatura</h3>                </div>

                    <button class="close-btn" onclick="closeModal('modalBoccia')">&times;</button>            </div>

                </div>

                <div class="modal-body" style="text-align:center;">            <div class="empty-state hidden" id="vuoto">

                    <form method="POST" action="elencoBozze.php">                <span style="font-size: 3rem; color: var(--blue-200);">OK</span>

                        <input type="hidden" name="id_gita" id="bocciaGitaId">                <h3>Nessuna gita in attesa</h3>

                        <input type="hidden" name="azione" value="boccia">                <p>Tutte le proposte sono state elaborate. Torna più tardi.</p>

                        <p>Stai per <strong>bocciare</strong> la proposta:</p>            </div>

                        <p style="margin-top:0.5rem;"><strong id="bocciaDestLabel"></strong></p>

                        <p style="margin-top:0.5rem;font-size:0.85rem;color:var(--my-gray);">Lo stato passer&agrave; a <span class="badge badge-danger">Bocciata</span></p>        </main>

                    </form>

                </div>        <div class="modal-overlay hidden" id="modalApprova">

                <div class="modal-footer" style="justify-content:center;gap:1rem;">            <div class="modal" style="max-width: 440px;">

                    <button class="button outline" onclick="closeModal('modalBoccia')">Annulla</button>                <div class="modal-header">

                    <button class="button cancel" onclick="document.querySelector('#modalBoccia form').submit()">Conferma Bocciatura</button>                    <h3 style="color: var(--my-green);">Conferma Approvazione</h3>

                </div>                    <button class="close-btn" onclick="closeModal('modalApprova')">&times;</button>

            </div>                </div>

        </div>                <div class="modal-body" style="text-align: center;">

                    <div style="margin-bottom: 1rem;">

        <footer>                        <span style="font-size: 2.5rem; color: var(--my-green);">OK</span>

            <div class="footer-container">                    </div>

                <div class="footer-left">                    <form method="POST" action="elencoBozze.php">

                    <p><strong>Gestione Gite Scolastiche</strong></p>                        <input type="hidden" name="id_gita" id="approvaGitaId">

                    <p class="footer-copyright">&copy; 2026 - Piattaforma Interna</p>                        <input type="hidden" name="azione" value="approva">

                </div>                        <p>Stai per <strong>approvare</strong> la gita</p>

            </div>                        <p style="margin-top: 0.5rem;"><strong id="approvaDestLabel"></strong></p>

        </footer>                        <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--my-gray);">Lo stato passerà a <span class="badge badge-success">Approvata</span></p>

    </div>                    </form>

                </div>

    <script>                <div class="modal-footer" style="justify-content: center; gap: 1rem;">

        function preparaAzione(id, dest, tipo) {                    <button class="button outline" onclick="closeModal('modalApprova')">Annulla</button>

            if (tipo === 'approva') {                    <button class="button" onclick="document.querySelector('#modalApprova form').submit()">Conferma Approvazione</button>

                document.getElementById('approvaGitaId').value = id;                </div>

                document.getElementById('approvaDestLabel').innerText = dest;            </div>

                openModal('modalApprova');        </div>

            } else {

                document.getElementById('bocciaGitaId').value = id;        <div class="modal-overlay hidden" id="modalBoccia">

                document.getElementById('bocciaDestLabel').innerText = dest;            <div class="modal" style="max-width: 440px;">

                openModal('modalBoccia');                <div class="modal-header">

            }                    <h3 style="color: var(--hex-red);">Conferma Bocciatura</h3>

        }                    <button class="close-btn" onclick="closeModal('modalBoccia')">&times;</button>

    </script>                </div>

                <div class="modal-body" style="text-align: center;">

</body>                    <div style="margin-bottom: 1rem;">

</html>                        <span style="font-size: 2.5rem; color: var(--hex-red);">X</span>

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
