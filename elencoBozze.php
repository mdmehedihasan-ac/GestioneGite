<?php
    include('nav.php');
?>
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
                        <h1>Elenco Bozze</h1>
                    </div>
                    <p>Gite inviate in attesa di approvazione. Approva o boccia ogni proposta per aggiornarne lo stato.</p>
                </div>
                <div style="font-size: 0.9rem; color: var(--my-gray);">
                    <strong id="contatore">4</strong> gite in attesa
                </div>
            </div>

            <div class="alert alert-info" style="margin-top: 1.5rem;">
                <span style="font-size: 1.2rem;">ℹ️</span>
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
                            <tr data-id="1">
                                <td><strong>CERN di Ginevra</strong></td>
                                <td>Prof. Bianchi</td>
                                <td>5CL</td>
                                <td>20/02/2026</td>
                                <td>23/02/2026</td>
                                <td>20</td>
                                <td>2</td>
                                <td>Aereo</td>
                                <td>€ 7.000,00</td>
                                <td>05/01/2026</td>
                                <td class="azioni-cell">
                                    <button class="xs btn-approva" title="Approva">✔ Approva</button>
                                    <button class="xs btn-boccia" title="Boccia">✘ Boccia</button>
                                </td>
                            </tr>
                            <tr data-id="2">
                                <td><strong>Barcellona - Architettura Gaudì</strong></td>
                                <td>Prof.ssa Verdi</td>
                                <td>4AEA</td>
                                <td>12/03/2026</td>
                                <td>16/03/2026</td>
                                <td>32</td>
                                <td>3</td>
                                <td>Aereo</td>
                                <td>€ 12.500,00</td>
                                <td>08/01/2026</td>
                                <td class="azioni-cell">
                                    <button class="xs btn-approva" title="Approva">✔ Approva</button>
                                    <button class="xs btn-boccia" title="Boccia">✘ Boccia</button>
                                </td>
                            </tr>
                            <tr data-id="3">
                                <td><strong>Berlino - Storia del Novecento</strong></td>
                                <td>Prof. Neri</td>
                                <td>5BL</td>
                                <td>05/04/2026</td>
                                <td>09/04/2026</td>
                                <td>28</td>
                                <td>3</td>
                                <td>Aereo</td>
                                <td>€ 9.800,00</td>
                                <td>10/01/2026</td>
                                <td class="azioni-cell">
                                    <button class="xs btn-approva" title="Approva">✔ Approva</button>
                                    <button class="xs btn-boccia" title="Boccia">✘ Boccia</button>
                                </td>
                            </tr>
                            <tr data-id="4">
                                <td><strong>Londra - Scienza e Tecnologia</strong></td>
                                <td>Prof.ssa Russo</td>
                                <td>5AII</td>
                                <td>18/05/2026</td>
                                <td>22/05/2026</td>
                                <td>25</td>
                                <td>2</td>
                                <td>Aereo</td>
                                <td>€ 11.200,00</td>
                                <td>12/01/2026</td>
                                <td class="azioni-cell">
                                    <button class="xs btn-approva" title="Approva">✔ Approva</button>
                                    <button class="xs btn-boccia" title="Boccia">✘ Boccia</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="empty-state hidden" id="vuoto">
                <span style="font-size: 3rem; color: var(--blue-200);">✔</span>
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
                        <span style="font-size: 2.5rem; color: var(--my-green);">✔</span>
                    </div>
                    <p>Stai per <strong>approvare</strong> la gita</p>
                    <p style="margin-top: 0.5rem;"><strong id="approvaDestLabel"></strong></p>
                    <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--my-gray);">Lo stato passerà a <span class="badge badge-success">Approvata</span></p>
                </div>
                <div class="modal-footer" style="justify-content: center; gap: 1rem;">
                    <button class="button outline" onclick="closeModal('modalApprova')">Annulla</button>
                    <button class="button btn-conferma-approva">Conferma Approvazione</button>
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
                        <span style="font-size: 2.5rem; color: var(--hex-red);">✘</span>
                    </div>
                    <p>Stai per <strong>bocciare</strong> la gita</p>
                    <p style="margin-top: 0.5rem;"><strong id="bocciaDestLabel"></strong></p>
                    <div class="form-group" style="margin-top: 1rem; text-align: left;">
                        <label for="motivazione">Motivazione (opzionale)</label>
                        <textarea id="motivazione" rows="3" placeholder="Inserisci una motivazione per il docente..." style="width:100%; border-radius: var(--radius); border: 1px solid var(--my-gray); padding: 0.75rem; font-family: inherit; font-size: 0.9rem; resize: vertical; box-sizing: border-box;"></textarea>
                    </div>
                    <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--my-gray);">Lo stato verrà rimosso dall'elenco bozze.</p>
                </div>
                <div class="modal-footer" style="justify-content: center; gap: 1rem;">
                    <button class="button outline" onclick="closeModal('modalBoccia')">Annulla</button>
                    <button class="button cancel btn-conferma-boccia">Conferma Bocciatura</button>
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
        var rigaScelta = null;

        var bottoniApprova = document.querySelectorAll('.btn-approva');
        for (var i = 0; i < bottoniApprova.length; i++) {
            bottoniApprova[i].addEventListener('click', function() {
                rigaScelta = this.closest('tr');
                var dest = rigaScelta.querySelector('td strong').innerText;
                document.getElementById('approvaDestLabel').innerText = dest;
                openModal('modalApprova');
            });
        }

        document.querySelector('.btn-conferma-approva').addEventListener('click', function() {
            if (!rigaScelta) return;
            var riga = rigaScelta;
            riga.style.transition = 'opacity 0.4s';
            riga.style.opacity = '0';
            setTimeout(function() {
                riga.remove();
                aggiornaContatore();
                controllaVuoto();
            }, 400);
            closeModal('modalApprova');
            rigaScelta = null;
        });

        var bottoniBoccia = document.querySelectorAll('.btn-boccia');
        for (var i = 0; i < bottoniBoccia.length; i++) {
            bottoniBoccia[i].addEventListener('click', function() {
                rigaScelta = this.closest('tr');
                var dest = rigaScelta.querySelector('td strong').innerText;
                document.getElementById('bocciaDestLabel').innerText = dest;
                document.getElementById('motivazione').value = '';
                openModal('modalBoccia');
            });
        }

        document.querySelector('.btn-conferma-boccia').addEventListener('click', function() {
            if (!rigaScelta) return;
            var riga = rigaScelta;
            riga.style.transition = 'opacity 0.4s';
            riga.style.opacity = '0';
            setTimeout(function() {
                riga.remove();
                aggiornaContatore();
                controllaVuoto();
            }, 400);
            closeModal('modalBoccia');
            rigaScelta = null;
        });

        function aggiornaContatore() {
            var righe = document.querySelectorAll('#tabellaBozze tbody tr');
            document.getElementById('contatore').innerText = righe.length;
        }

        function controllaVuoto() {
            var righe = document.querySelectorAll('#tabellaBozze tbody tr');
            if (righe.length === 0) {
                document.querySelector('.table-section').classList.add('hidden');
                document.getElementById('vuoto').classList.remove('hidden');
            }
        }
    </script>

</body>
</html>
