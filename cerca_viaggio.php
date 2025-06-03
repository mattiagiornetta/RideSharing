<?php
require_once 'db.php';
require_once 'components/navbar.php';

$viaggio = cercaViaggio($_POST['comunePartenzaId'], $_POST['comuneArrivoId']);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Risultati Viaggio - CarPooling</title>
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

        .price-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .time-badge {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }
    </style>
</head>

<body class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">
                🚗 Viaggi Disponibili
            </h1>
            <p class="text-xl text-white/90 font-light">
                Trova il viaggio perfetto per te
            </p>
        </div>

        <div class="max-w-7xl mx-auto space-y-6">
            <?php
            $utente = ottieniUtenteDaSessione(session_id());
            foreach ($viaggio as $v) {
                if(isset($utente) && $utente != false) {
                    if($v['id_organizzatore'] == $utente['id']) continue;
                }
                $comuneArrivo = ottieniComune($v['id_destinazione'])['nome'];
                $comunePartenza = ottieniComune($v['id_partenza'])['nome'];
                $dataOggetto = date_create($v['data_partenza']);
                $data = date_format($dataOggetto, "d/m/Y");
                $ora = date_format($dataOggetto, "H:i");
                $prenotazioni = ottieniPrenotazioniViaggio($v['id']);
                $posti_occupati = count($prenotazioni);
                $posti_disponibili = $v['posti'] - $posti_occupati;
                if ($posti_disponibili < 1) {
                    continue;
                }
                $utente = ottieniUtenteDaId($v['id_organizzatore']);
                ?>

                <div class="glass-effect rounded-3xl p-8 shadow-2xl trip-card">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                        
                        <div class="lg:col-span-2 flex justify-center lg:justify-start">
                            <div class="time-badge text-white px-4 py-3 rounded-2xl text-center shadow-lg">
                                <div class="text-2xl font-bold">⏰ <?php echo $ora; ?></div>
                                <div class="text-sm opacity-90"><?php echo $data; ?></div>
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="flex items-center justify-center space-x-3 mb-4">
                                <div class="text-center flex-1">
                                    <div class="text-sm text-gray-600 font-medium">🏠 Partenza</div>
                                    <div class="text-lg font-bold text-gray-800"><?php echo $comunePartenza; ?></div>
                                </div>
                                
                                <div class="flex items-center space-x-1 px-2">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                    <div class="w-6 h-0.5 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                        <div class="text-base font-semibold text-gray-800"><?php echo $utente['nome'] . ' ' . $utente['cognome']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2 flex justify-center">
                            <div class="price-badge text-white px-4 py-3 rounded-2xl text-center shadow-lg">
                                <div class="text-2xl font-bold">💰 €<?php echo $v['prezzo_posto']; ?></div>
                                <div class="text-xs opacity-90">per posto</div>
                            </div>
                        </div>
                        
                        <div class="lg:col-span-2 flex justify-center">
                            <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-4 py-3 rounded-2xl text-center">
                                <div class="flex flex-col items-center space-y-1">
                                    <span class="text-green-600 text-xl">👥</span>
                                    <div class="text-green-800 font-semibold text-sm"><?php echo $posti_disponibili; ?></div>
                                    <div class="text-green-700 text-xs">disponibili</div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 flex justify-center lg:justify-end">
                            <form action="prenota.php" method="POST">
                                <input type="hidden" name="id_viaggio" value="<?php echo $v['id']; ?>">
                                <input type="hidden" name="id_utente" value="<?php echo $v['id_organizzatore']; ?>">
                                <input type="submit" value="Prenota" 
                                    class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-6 py-3 rounded-2xl transition-all duration-300 cursor-pointer font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 whitespace-nowrap" />
                            </form>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <?php if (empty($viaggio)) : ?>
                <div class="glass-effect rounded-3xl p-12 shadow-2xl text-center">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full mb-4">
                            <span class="text-3xl">😔</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Nessun viaggio trovato</h3>
                    <p class="text-lg text-gray-600 mb-6">
                        Non ci sono viaggi disponibili per i criteri selezionati.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="index.php" 
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-2xl transition-all duration-300 font-semibold">
                            🔍 Nuova ricerca
                        </a>
                        <a href="pubblica_viaggio.php" 
                            class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-6 py-3 rounded-2xl transition-all duration-300 font-semibold">
                            ➕ Pubblica viaggio
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>