<?php 
    include('nav.php');
?>
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
<body>


    <div class="container">
        <main class="content home-padding">
            
            <section class="hero-section">
                <h1>Gite in Programma</h1>
                <p>Elenco generale di tutte le gite organizzate e il loro stato di avanzamento. Monitora le date, i partecipanti e le approvazioni.</p>
            </section>

            <section class="table-section" style="margin-top: 2rem;">
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
                            <tr>
                                <td>Roma - Musei Vaticani</td>
                                <td>15/04/2026</td>
                                <td>18/04/2026</td>
                                <td>45</td>
                                <td>4</td>
                                <td>€ 8.100,00</td>
                                <td><span class="badge badge-success">Approvata</span></td>
                                <td><button class="xs outline">Dettagli</button></td>
                            </tr>
                            
                            <tr>
                                <td>Napoli e Pompei</td>
                                <td>10/05/2026</td>
                                <td>12/05/2026</td>
                                <td>40</td>
                                <td>3</td>
                                <td>€ 4.800,00</td>
                                <td><span class="badge badge-warning">Inserita</span></td>
                                <td><button class="xs outline">Dettagli</button></td>
                            </tr>
                            
                            <tr>
                                <td>CERN di Ginevra</td>
                                <td>20/02/2026</td>
                                <td>23/02/2026</td>
                                <td>20</td>
                                <td>2</td>
                                <td>€ 7.000,00</td>
                                <td><span class="badge badge-secondary">Bozza</span></td>
                                <td><button class="xs outline">Dettagli</button></td>
                            </tr>

                            <tr>
                                <td>Firenze Rinascimentale</td>
                                <td>10/10/2025</td>
                                <td>12/10/2025</td>
                                <td>24</td>
                                <td>2</td>
                                <td>€ 3.500,00</td>
                                <td><span class="badge badge-primary">Conclusa</span></td>
                                <td><button class="xs outline">Dettagli</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>

        <footer>
            <div class="footer-container">
                <div class="footer-left">
                    <p><strong>Gestione Gite Scolastiche</strong></p>
                    <p class="footer-copyright">© 2026 - Piattaforma Interna</p>
                </div>
            </div>
        </footer>
    </div>

</body>
</html>