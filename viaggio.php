<?php
require_once 'db.php';
require_once 'components/navbar.php';

$auth = autentica();
if (!$auth) {
    header('Location: accesso.php');
    exit();
}
$utente = ottieniUtenteDaSessione(session_id());

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id_viaggio = $_GET['id'];
$viaggio = ottieniViaggioDaId($id_viaggio);

if (!$viaggio) {
    header('Location: index.php');
    exit();
}

$comunePartenza = ottieniComune($viaggio['id_partenza']);
$comuneDestinazione = ottieniComune($viaggio['id_destinazione']);
$organizzatore = ottieniUtenteDaId($viaggio['id_organizzatore']);
$prenotazioni = ottieniPrenotazioniViaggio($id_viaggio);
$posti_prenotati = count($prenotazioni);
$posti_disponibili = $viaggio['posti'] - $posti_prenotati;

$utente_ha_prenotato = false;
foreach ($prenotazioni as $prenotazione) {
    if ($prenotazione['id_utente'] == $utente['id']) {
        $utente_ha_prenotato = true;
        break;
    }
}

$e_organizzatore = ($viaggio['id_organizzatore'] == $utente['id']);

$dataOggetto = date_create($viaggio['data_partenza']);
$data_formattata = date_format($dataOggetto, "d/m/Y");
$ora_formattata = date_format($dataOggetto, "H:i");
$giorno_settimana = date_format($dataOggetto, "l");

$giorni_ita = [
    'Monday' => 'Lunedì',
    'Tuesday' => 'Martedì', 
    'Wednesday' => 'Mercoledì',
    'Thursday' => 'Giovedì',
    'Friday' => 'Venerdì',
    'Saturday' => 'Sabato',
    'Sunday' => 'Domenica'
];
$giorno_ita = $giorni_ita[$giorno_settimana];

$ora_viaggio = strtotime($viaggio['data_partenza']);
$ora_attuale = time();
$viaggio_passato = $ora_viaggio < $ora_attuale;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['prenota'])) {
    if (!$utente_ha_prenotato && !$e_organizzatore && $posti_disponibili > 0 && !$viaggio_passato) {
        $risultato = prenotaViaggio($utente['id'], $id_viaggio);
        if ($risultato) {
            header('Location: viaggio.php?id=' . $id_viaggio . '&success=prenotato');
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Viaggio: <?php echo $comunePartenza['nome'] . ' → ' . $comuneDestinazione['nome']; ?> - CarPooling</title>
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

        .info-card {
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.1);
        }

        .route-line {
            background: linear-gradient(90deg, #8b5cf6 0%, #3b82f6 50%, #06b6d4 100%);
            height: 4px;
            border-radius: 2px;
            position: relative;
            overflow: hidden;
        }

        .route-line::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .passenger-card {
            transition: all 0.3s ease;
        }

        .passenger-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #065f46 100%);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert-success text-white p-4 rounded-2xl mb-6 text-center shadow-lg">
                <div class="flex items-center justify-center space-x-2">
                    <span class="text-2xl">✅</span>
                    <span class="font-semibold text-lg">
                        <?php echo $_GET['success'] == 'prenotato' ? 'Prenotazione effettuata con successo!' : 'Prenotazione cancellata con successo!'; ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <div class="glass-effect rounded-3xl p-8 shadow-2xl mb-8">
            <div class="flex items-center justify-between mb-6">
                <a href="javascript:history.back()" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-4 py-2 rounded-xl transition-all duration-300 font-semibold flex items-center space-x-2">
                    <span>←</span>
                    <span>Indietro</span>
                </a>
          
            </div>

            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    🚗 <?php echo $comunePartenza['nome']; ?> → <?php echo $comuneDestinazione['nome']; ?>
                </h1>
                <div class="flex items-center justify-center space-x-4 text-lg text-gray-600">
                    <span class="bg-purple-100 px-4 py-2 rounded-xl font-semibold">
                        📅 <?php echo $giorno_ita . ', ' . $data_formattata; ?>
                    </span>
                    <span class="bg-blue-100 px-4 py-2 rounded-xl font-semibold">
                        ⏰ <?php echo $ora_formattata; ?>
                    </span>
                </div>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-2 shadow-lg">
                            🏠
                        </div>
                        <div class="text-sm text-gray-600 font-medium">Partenza</div>
                        <div class="text-xl font-bold text-gray-800"><?php echo $comunePartenza['nome']; ?></div>
                    </div>
                    
                    <div class="flex-1 mx-8">
                        <div class="route-line"></div>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-2 shadow-lg">
                            🎯
                        </div>
                        <div class="text-sm text-gray-600 font-medium">Destinazione</div>
                        <div class="text-xl font-bold text-gray-800"><?php echo $comuneDestinazione['nome']; ?></div>
                    </div>
                </div>
            </div>
        </div>

            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="glass-effect rounded-3xl p-6 shadow-2xl info-card">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center space-x-2">
                        <span>👤</span>
                        <span>Organizzatore</span>
                    </h3>
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                <?php echo strtoupper(substr($organizzatore['nome'], 0, 1) . substr($organizzatore['cognome'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-gray-800">
                                    <?php echo $organizzatore['nome'] . ' ' . $organizzatore['cognome']; ?>
                                </div>
                                <div class="text-gray-600">📧 <?php echo $organizzatore['email']; ?></div>
                                <?php if (!empty($organizzatore['telefono'])): ?>
                                    <div class="text-gray-600">📱 <?php echo $organizzatore['telefono']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($viaggio['note'])): ?>
                <div class="glass-effect rounded-3xl p-6 shadow-2xl info-card">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center space-x-2">
                        <span>📝</span>
                        <span>Note del viaggio</span>
                    </h3>
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-2xl">
                        <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($viaggio['note'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="glass-effect rounded-3xl p-6 shadow-2xl info-card">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center space-x-2">
                        <span>👥</span>
                        <span>Passeggeri (<?php echo $posti_prenotati; ?>/<?php echo $viaggio['posti']; ?>)</span>
                    </h3>
                    
                    <?php if (!empty($prenotazioni)): ?>
                        <div class="space-y-3">
                            <?php foreach ($prenotazioni as $prenotazione): 
                                $passeggero = ottieniUtenteDaId($prenotazione['id_utente']);
                            ?>
                                <div class="passenger-card bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-2xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                            <?php echo strtoupper(substr($passeggero['nome'], 0, 1) . substr($passeggero['cognome'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">
                                                <?php echo $passeggero['nome'] . ' ' . $passeggero['cognome']; ?>
                                            </div>
                       
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="text-6xl mb-4">🪑</div>
                            <p class="text-lg text-gray-600">Nessun passeggero prenotato ancora</p>
                        </div>
                    <?php endif; ?>

                    <?php if ($posti_disponibili > 0): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-center text-gray-600">
                                <span class="font-semibold text-green-600"><?php echo $posti_disponibili; ?></span> 
                                post<?php echo $posti_disponibili > 1 ? 'i' : 'o'; ?> ancora disponibil<?php echo $posti_disponibili > 1 ? 'i' : 'e'; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>        
        </div>

</body>

</html>