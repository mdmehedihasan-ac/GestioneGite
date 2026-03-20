<?php 
    include('nav.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Gite - Catalogo Proposte</title>
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
                    <h1>Catalogo Proposte</h1>
                    <p>Elenco delle mete approvate dalla Commissione. Scegli una proposta per organizzare la gita.</p>
                </div>
                <div>
                    <button class="button" id="btnNuova">Nuova Proposta</button>
                </div>
            </div>

            <div class="table-section" style="margin-top: 2rem;">
                <div class="table-container table-catalogo">
                    <table>
                        <thead>
                            <tr>
                                <th>Destinazione</th>
                                <th>Mezzo di Trasporto</th>
                                <th>Periodo</th>
                                <th>Min Part.</th>
                                <th>Max Part.</th>
                                <th>Costo Stimato</th>
                                <th>Modifica</th>
                                <th>Elimina</th>
                                <th>Azione</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Roma - Musei Vaticani</td>
                                <td>Treno Alta Velocità</td>
                                <td>Marzo - Aprile</td>
                                <td>20</td>
                                <td>50</td>
                                <td>€ 180,00</td>
                                <td><button class="xs outline btn-modifica">Modifica</button></td>
                                <td><button class="xs cancel">Elimina</button></td>
                                <td><button class="xs">Crea Gita</button></td>
                            </tr>
                            <tr>
                                <td>Napoli e Pompei</td>
                                <td>Pullman GT</td>
                                <td>Maggio</td>
                                <td>40</td>
                                <td>100</td>
                                <td>€ 120,00</td>
                                <td><button class="xs outline btn-modifica">Modifica</button></td>
                                <td><button class="xs cancel">Elimina</button></td>
                                <td><button class="xs">Crea Gita</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <div class="modal-overlay hidden" id="modalOverlay">
            <div class="modal wide-modal">
                <div class="modal-header">
                    <h3 id="modalTitle">Nuova Proposta di Gita</h3>
                    <button class="close-btn" id="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formNuovaProposta" class="form-grid">
                        
                        <div class="form-group">
                            <label for="destinazione">Destinazione</label>
                            <input type="text" id="destinazione" placeholder="es. Parigi">
                        </div>
                        
                        <div class="form-group">
                            <label for="mezzo">Mezzo di Trasporto</label>
                            <select id="mezzo">
                                <option value="Autobus">Autobus GT</option>
                                <option value="Treno">Treno Alta Velocità</option>
                                <option value="Aereo">Aereo</option>
                                <option value="Nave">Nave / Traghetto</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="periodo">Periodo Ideale</label>
                            <input type="text" id="periodo" placeholder="es. Aprile 2026">
                        </div>

                        <div class="form-group">
                            <label for="costo">Costo Stimato (€)</label>
                            <input type="number" id="costo" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for="minPart">Minimo Partecipanti</label>
                            <input type="number" id="minPart" placeholder="es. 15">
                        </div>

                        <div class="form-group">
                            <label for="maxPart">Massimo Partecipanti</label>
                            <input type="number" id="maxPart" placeholder="es. 30">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="button cancel" id="cancelModal">Annulla</button>
                    <button class="button" type="submit" form="formNuovaProposta" id="submitModalBtn">Registra Proposta</button>
                </div>
            </div>
        </div>

        <footer>
            <div class="footer-container">
                <div class="footer-left">
                    <p><strong>Gestione Gite Scolastiche</strong></p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        var modale = document.getElementById('modalOverlay');
        var btnApri = document.getElementById('btnNuova');
        var btnChiudi = document.getElementById('closeModal');
        var btnAnnulla = document.getElementById('cancelModal');
        var modulo = document.getElementById('formNuovaProposta');
        var titolo = document.getElementById('modalTitle');
        var btnInvia = document.getElementById('submitModalBtn');

        btnApri.addEventListener('click', function() {
            modulo.reset();
            titolo.innerText = "Nuova Proposta di Gita";
            btnInvia.innerText = "Registra Proposta";
            modale.classList.remove('hidden');
        });

        var listaModifica = document.querySelectorAll('.btn-modifica');
        for (var i = 0; i < listaModifica.length; i++) {
            listaModifica[i].addEventListener('click', function() {
                var riga = this.closest('tr');
                var celle = riga.querySelectorAll('td');

                document.getElementById('destinazione').value = celle[0].innerText;
                document.getElementById('periodo').value = celle[2].innerText;
                document.getElementById('minPart').value = celle[3].innerText;
                document.getElementById('maxPart').value = celle[4].innerText;

                var costoTesto = celle[5].innerText.replace('€', '').trim().replace(',', '.');
                document.getElementById('costo').value = Number(costoTesto);

                var selectMezzo = document.getElementById('mezzo');
                var valMezzo = celle[1].innerText;
                for (var j = 0; j < selectMezzo.options.length; j++) {
                    if (selectMezzo.options[j].text.indexOf(valMezzo) >= 0 || selectMezzo.options[j].value === valMezzo) {
                        selectMezzo.selectedIndex = j;
                        break;
                    }
                }

                titolo.innerText = "Modifica Proposta di Gita";
                btnInvia.innerText = "Salva Modifiche";
                modale.classList.remove('hidden');
            });
        }

        function chiudiModale() {
            modale.classList.add('hidden');
        }

        btnChiudi.addEventListener('click', chiudiModale);
        btnAnnulla.addEventListener('click', chiudiModale);

        window.addEventListener('click', function(e) {
            if (e.target === modale) chiudiModale();
        });
    </script>
</body>
</html>