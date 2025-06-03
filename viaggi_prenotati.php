<?php
require_once 'db.php';
require_once 'components/navbar.php';

if(!autentica()) {
    header("Location: accesso.php");
    die("403");
}
$utente = ottieniUtenteDaSessione(session_id());

$prenotazioni = ottieniPrenotazioniUtente($utente['id']);
$viaggi_pubblicati = ottieniViaggiUtente($utente['id']);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>I Miei Viaggi - CarPooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .trip-card {
            transition: all 0.3s ease;
        }

        .trip-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .time-badge {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .tab-button {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }

        .tab-button:not(.active) {
            background: rgba(255, 255, 255, 0.7);
            color: #374151;
        }

        .tab-button:not(.active):hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-1px);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">
                🎫 I Miei Viaggi
            </h1>
            <p class="text-xl text-white/90 font-light">
                Gestisci le tue prenotazioni e pubblicazioni
            </p>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="glass-effect rounded-3xl p-8 shadow-2xl mb-8">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="switchTab('prenotati')" id="tab-prenotati" class="tab-button active px-8 py-4 rounded-2xl font-bold text-lg flex items-center justify-center space-x-3 transition-all duration-300">
                        <span>🎫</span>
                        <span>Viaggi Prenotati</span>
                    </button>
                    <button onclick="switchTab('pubblicati')" id="tab-pubblicati" class="tab-button px-8 py-4 rounded-2xl font-bold text-lg flex items-center justify-center space-x-3 transition-all duration-300">
                        <span>🚗</span>
                        <span>Viaggi Pubblicati</span>
                    </button>
                </div>
            </div>

            <div id="content-prenotati" class="tab-content active space-y-6">
                <?php
                if (!empty($prenotazioni)) {
                    foreach ($prenotazioni as $prenotazione) {
                        $viaggio = ottieniViaggioDaId($prenotazione['id_viaggio']);
                        $comuneArrivo = ottieniComune($viaggio['id_destinazione'])['nome'];
                        $comunePartenza = ottieniComune($viaggio['id_partenza'])['nome'];
                        $dataOggetto = date_create($viaggio['data_partenza']);
                        $data = date_format($dataOggetto, "d/m/Y");
                        $ora = date_format($dataOggetto, "H:i");
                        $autista = ottieniUtenteDaId($viaggio['id_organizzatore']);
                        
                        $ora_viaggio = strtotime($viaggio['data_partenza']);
                        $ora_attuale = time();
                        ?>

                        <div class="glass-effect rounded-3xl p-8 shadow-2xl trip-card">
                            <div class="grid grid-cols-1 lg:grid-cols-10 gap-6 items-center">
                                
                                <div class="lg:col-span-2 flex justify-center lg:justify-start">
                                    <div class="time-badge text-white px-4 py-3 rounded-2xl text-center shadow-lg">
                                        <div class="text-2xl font-bold">⏰ <?php echo $ora; ?></div>
                                        <div class="text-sm opacity-90"><?php echo $data; ?></div>
                                    </div>
                                </div>

                                <div class="lg:col-span-4">
                                    <div class="flex items-center justify-center space-x-3 mb-4">
                                        <div class="text-center flex-1">
                                            <div class="text-sm text-gray-600 font-medium">🏠 Partenza</div>
                                            <div class="text-lg font-bold text-gray-800"><?php echo $comunePartenza; ?></div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-1 px-2">
                                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                            <div class="w-6 h-0.5 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-606" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                            <div class="w-6 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                                            <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                        </div>
                                        
                                        <div class="text-center flex-1">
                                            <div class="text-sm text-gray-600 font-medium">🎯 Destinazione</div>
                                            <div class="text-lg font-bold text-gray-800"><?php echo $comuneArrivo; ?></div>
                                        </div>
                                    </div>

                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-3 rounded-2xl">
                                        <div class="flex items-center justify-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm">👤</span>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xs text-gray-600 font-medium">Autista</div>
                                                <div class="text-base font-semibold text-gray-800"><?php echo $autista['nome'] . ' ' . $autista['cognome']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="lg:col-span-2 flex justify-center">
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-4 py-3 rounded-2xl text-center shadow-lg">
                                        <div class="text-2xl font-bold">💰 €<?php echo $viaggio['prezzo_posto']; ?></div>
                                        <div class="text-xs opacity-90">pagato</div>
                                    </div>
                                </div>

                                <div class="lg:col-span-2 flex justify-center lg:justify-end space-x-2">
                        
                                    
                                    <a href="viaggio.php?id=<?php echo $viaggio['id']; ?>" 
                                       class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 text-sm">
                                        Dettagli
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php }
                } else { ?>
                    <div class="glass-effect rounded-3xl p-12 shadow-2xl text-center">
                        <div class="mb-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full mb-4">
                                <span class="text-3xl">🎫</span>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Nessun viaggio prenotato</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Non hai ancora prenotato nessun viaggio. Inizia a esplorare le opzioni disponibili!
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="index.php" 
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-2xl transition-all duration-300 font-semibold flex items-center space-x-2">
                                <span>🔍</span>
                                <span>Cerca viaggi</span>
                            </a>
                            <a href="pubblica_viaggio.php" 
                                class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-6 py-3 rounded-2xl transition-all duration-300 font-semibold flex items-center space-x-2">
                                <span>➕</span>
                                <span>Pubblica viaggio</span>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div id="content-pubblicati" class="tab-content space-y-6">
                <?php
                if (!empty($viaggi_pubblicati)) {
                    foreach ($viaggi_pubblicati as $viaggio) {
                        $comuneArrivo = ottieniComune($viaggio['id_destinazione'])['nome'];
                        $comunePartenza = ottieniComune($viaggio['id_partenza'])['nome'];
                        $dataOggetto = date_create($viaggio['data_partenza']);
                        $data = date_format($dataOggetto, "d/m/Y");
                        $ora = date_format($dataOggetto, "H:i");
                        $prenotazioni_viaggio = ottieniPrenotazioniViaggio($viaggio['id']);
                        $posti_prenotati = count($prenotazioni_viaggio);
                        $posti_disponibili = $viaggio['posti'] - $posti_prenotati;
                        
                        $ora_viaggio = strtotime($viaggio['data_partenza']);
                        $ora_attuale = time();
                        ?>

                        <div class="glass-effect rounded-3xl p-8 shadow-2xl trip-card">
                            <div class="grid grid-cols-1 lg:grid-cols-10 gap-6 items-center">
                                
                                <div class="lg:col-span-2 flex justify-center lg:justify-start">
                                    <div class="time-badge text-white px-4 py-3 rounded-2xl text-center shadow-lg">
                                        <div class="text-2xl font-bold">⏰ <?php echo $ora; ?></div>
                                        <div class="text-sm opacity-90"><?php echo $data; ?></div>
                                    </div>
                                </div>

                                <div class="lg:col-span-4">
                                    <div class="flex items-center justify-center space-x-3 mb-4">
                                        <div class="text-center flex-1">
                                            <div class="text-sm text-gray-600 font-medium">🏠 Partenza</div>
                                            <div class="text-lg font-bold text-gray-800"><?php echo $comunePartenza; ?></div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-1 px-2">
                                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                            <div class="w-6 h-0.5 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-606" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                            <div class="w-6 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                                            <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                        </div>
                                        
                                        <div class="text-center flex-1">
                                            <div class="text-sm text-gray-600 font-medium">🎯 Destinazione</div>
                                            <div class="text-lg font-bold text-gray-800"><?php echo $comuneArrivo; ?></div>
                                        </div>
                                    </div>

                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-3 rounded-2xl">
                                        <div class="flex items-center justify-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm">👥</span>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xs text-gray-600 font-medium">Posti</div>
                                                <div class="text-base font-semibold text-gray-800"><?php echo $posti_prenotati; ?>/<?php echo $viaggio['posti']; ?> occupati</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="lg:col-span-2 flex justify-center">
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-4 py-3 rounded-2xl text-center shadow-lg">
                                        <div class="text-2xl font-bold">💰 €<?php echo $viaggio['prezzo_posto']; ?></div>
                                        <div class="text-xs opacity-90">a posto</div>
                                    </div>
                                </div>

                                <div class="lg:col-span-2 flex justify-center lg:justify-end space-x-2">
                    
                                    
                                    <a href="viaggio.php?id=<?php echo $viaggio['id']; ?>" 
                                       class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 text-sm">
                                        Dettagli
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php }
                } else { ?>
                    <div class="glass-effect rounded-3xl p-12 shadow-2xl text-center">
                        <div class="mb-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full mb-4">
                                <span class="text-3xl">🚗</span>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Nessun viaggio pubblicato</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Non hai ancora pubblicato nessun viaggio. Inizia a offrire i tuoi passaggi!
                        </p>
                        <div class="flex justify-center">
                            <a href="pubblica_viaggio.php" 
                                class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-6 py-3 rounded-2xl transition-all duration-300 font-semibold flex items-center space-x-2">
                                <span>➕</span>
                                <span>Pubblica viaggio</span>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            document.getElementById('tab-' + tabName).classList.add('active');
            document.getElementById('content-' + tabName).classList.add('active');
        }
    </script>
</body>

</html>