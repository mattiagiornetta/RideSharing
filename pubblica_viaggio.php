<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pubblica Viaggio - CarPooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="">
    </script>
    <script src="scripts/functions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mapbox/polyline@1.2.1/src/polyline.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        #map { 
            height: 400px; 
            border-radius: 16px; 
            overflow: hidden; 
        }
        .glass-effect { 
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="min-h-screen">
    <?php 
    require_once "./db.php";
    require_once 'components/navbar.php';    
    if(!autentica()) {
        header("Location: accesso.php");
        die("403");
    }
?>
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">
                🚗 Pubblica il tuo Viaggio
            </h1>
            <p class="text-xl text-white/90 font-light">
                Condividi la strada, risparmia sui costi
            </p>
        </div>

        <?php
        if (!isset($_POST['stage'])) {
            $stage = 0;
        } else {
            $stage = $_POST['stage'];
        }
        switch ($stage) {
            case 0:
                echo '
                <div class="max-w-2xl mx-auto">
                    <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                        <div class="mb-8 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full mb-4">
                                <span class="text-2xl">📍</span>
                            </div>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Dove andrai?</h2>
                            <p class="text-gray-600">Inizia selezionando le tue destinazioni</p>
                        </div>

                        <form id="chooseCityId" action="pubblica_viaggio.php" method="POST" class="space-y-6">
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">🏠 Partenza da</label>
                                <textarea required type="text" rows="1" id="comunePartenza" class="resize-none w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-lg"
                                    placeholder="Da dove partirai?" autocomplete="off" onkeyup="getSuggestions(\'comunePartenza\',\'comunePartenzaId\')"></textarea>
                            </div>
                            
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">🎯 Destinazione</label>
                                <textarea required type="text" rows="1" id="comuneArrivo" class="resize-none w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-lg"
                                    placeholder="Dove arriverai?" autocomplete="off" onkeyup="getSuggestions(\'comuneArrivo\',\'comuneArrivoId\')"></textarea>
                            </div>
                            
                            <ul id="suggestions" class="mt-2 bg-white border-2 border-gray-200 rounded-2xl shadow-lg hidden max-h-48 overflow-y-auto"></ul>
                            <input type="hidden" id="comunePartenzaId" name="comunePartenzaId">
                            <input type="hidden" id="comuneArrivoId" name="comuneArrivoId">
                            <input type="hidden" id="stage" name="stage" value=1>

                            <input value="Continua" type="submit" class="mt-4 w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition duration-300">
                        </form>
                    </div>
                </div>';
                
                echo "
                <script>
                document.getElementById('chooseCityId').addEventListener('submit', function (e) {
                    const partenza = document.getElementById('comunePartenzaId').value
                    const arrivo = document.getElementById('comuneArrivoId').value;
                    if (!partenza || !arrivo) {
                        e.preventDefault();
                        alert('Per favore, seleziona una località valida per partenza e arrivo.');
                        return;
                    }
                    if (arrivo == partenza) {
                        e.preventDefault()
                        alert('I comuni di partenza e arrivo devono essere diversi.')
                        return;
                    }
                });
                </script>";
                break;
                
            case 1:
                if($_POST['comuneArrivoId'] == "" || $_POST['comunePartenzaId'] == "") {
                    die(503);
                }
                echo '
                <div class="max-w-4xl mx-auto space-y-8">
                    <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                        <div class="mb-6 text-center">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">🗺️ Il tuo percorso</h2>
                            <p class="text-gray-600">Visualizza il tragitto che percorrerai</p>
                        </div>
                        <div id="map" class="shadow-lg"></div>
                        <div id="distance-info" class="mt-4 p-4 text-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl text-center font-medium text-lg"></div>
                    </div>

                    <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                        <div class="mb-8 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full mb-4">
                                <span class="text-2xl">📅</span>
                            </div>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Quando partirai?</h2>
                            <p class="text-gray-600">Seleziona la datas del tuo viaggio</p>
                        </div>

                        <form id="chooseDateForm" action="pubblica_viaggio.php" method="POST" class="space-y-6">
                            <div class="gap-6">
                                <div>
                                    <label for="partenzaDate" class="block text-sm font-medium text-gray-700 mb-2">🚀 Data e Ora Partenza</label>
                                    <input required type="datetime-local" id="partenzaDate" name="partenzaDate" class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 text-lg">
                                </div>
                            </div>';
                
                echo '
                            <input type="hidden" id="comunePartenzaId" name="comunePartenzaId" value='.$_POST['comunePartenzaId'].'>
                            <input type="hidden" id="comuneArrivoId" name="comuneArrivoId" value='.$_POST['comuneArrivoId'].'>
                            <input type="hidden" id="stage" name="stage" value=2>';
                
                echo '
                            <input value="Avanti" type="submit" class="mt-4 w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition duration-300">
                        </form>
                    </div>
                </div>';
                echo "
                <script>
                document.getElementById('chooseDateForm').addEventListener('submit', function (e) {
                    const partenza = new Date(document.getElementById('partenzaDate').value);
                    const ora = new Date();
                    if(partenza < ora) {
                        e.preventDefault();
                        alert('La data di partenza non può essere passata.');
                    }   
             
                  
                });
                
                var endpoint = 'calcRoute.php?comunePartenzaId=" . $_POST['comunePartenzaId'] . "&comuneArrivoId=" . $_POST['comuneArrivoId'] . "';
                var map = L.map('map')

                fetch(endpoint)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data)
                        
                        const path = data.paths[0]
                        const distance = parseInt(path.distance/1000)
                        console.log(distance)
                        const time = path.time/60000
                        const hours = Math.floor(time / 60)
                        const minutes = parseInt(time % 60)
                        const timeString = hours+'h '+minutes+'m'
                        const distanceInfoDiv = document.getElementById('distance-info');
                        distanceInfoDiv.innerHTML = `
                            <div class=\"flex items-center justify-center space-x-6\">
                                <div class=\"flex items-center space-x-2\">
                                    <span class=\"text-blue-600\">📏</span>
                                    <span class=\"font-semibold\">Distanza: \${distance} km</span>
                                </div>
                                <div class=\"flex items-center space-x-2\">
                                    <span class=\"text-green-600\">⏱️</span>
                                    <span class=\"font-semibold\">Tempo stimato: \${timeString}</span>
                                </div>
                            </div>
                        `;

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href=\'https://www.openstreetmap.org/copyright\'>OpenStreetMap</a> contributors'
                        }).addTo(map);

                        const route = path.points

                        const latlngs = polyline.decode(route)

                        const pathLine = L.polyline(latlngs, { 
                            color: '#8b5cf6', 
                            weight: 5,
                            opacity: 0.8 
                        }).addTo(map)
                        map.fitBounds(pathLine.getBounds())
                    })
                    .catch(error => {
                        console.error('Error:', error)
                    });
                </script>";
                break;
                
            case 2: 
                if($_POST['partenzaDate'] == "" ||$_POST['comuneArrivoId'] == "" || $_POST['comunePartenzaId'] == "") {
                    die(503);
                }
                echo '
                <div class="max-w-2xl mx-auto">
                    <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                        <div class="mb-8 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full mb-4">
                                <span class="text-2xl">💰</span>
                            </div>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Dettagli del viaggio</h2>
                            <p class="text-gray-600">Imposta posti disponibili e prezzo</p>
                        </div>

                        <form action="pubblica_viaggio.php" method="POST" class="space-y-8">
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 rounded-2xl">
                                <label class="block text-lg font-medium text-gray-800 mb-4 text-center">👥 Quanti posti disponibili?</label>
                                <div class="flex items-center justify-center space-x-6">
                                    <button id="decrement" type="button" 
                                        class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-full hover:from-orange-600 hover:to-red-600 transition-all duration-300 transform hover:scale-110 shadow-lg text-xl font-bold">
                                        −
                                    </button>
                                    <input id="posti" name="posti" type="number" value="1" 
                                        class="w-20 h-12 text-center border-2 border-gray-300 rounded-xl text-2xl font-bold bg-white shadow-inner" readonly>
                                    <button id="increment" type="button" 
                                        class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-full hover:from-orange-600 hover:to-red-600 transition-all duration-300 transform hover:scale-110 shadow-lg text-xl font-bold">
                                        +
                                    </button>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl">
                                <label for="prezzo" class="block text-lg font-medium text-gray-800 mb-4 text-center">💵 Prezzo per posto</label>
                                <div class="flex items-center justify-center space-x-3">
                                    <input id="prezzo" name="prezzo" type="number" value="10" min="1" 
                                        class="w-24 h-12 text-center border-2 border-gray-300 rounded-xl text-2xl font-bold bg-white shadow-inner">
                                    <span class="text-2xl font-bold text-gray-700">€</span>
                                </div>
                            </div>';
                            
                echo '
                            <input type="hidden" id="partenzaDate" name="partenzaDate" value='.$_POST['partenzaDate'].'>
                            <input type="hidden" id="comunePartenzaId" name="comunePartenzaId" value='.$_POST['comunePartenzaId'].'>
                            <input type="hidden" id="comuneArrivoId" name="comuneArrivoId" value='.$_POST['comuneArrivoId'].'>
                            <input type="hidden" id="stage" name="stage" value=3>';
                            
                echo '
                            <input value="Avanti" type="submit" class="mt-4 w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition duration-300">
                        </form>
                    </div>
                </div>';
                
                echo "<script>
                const counterInput = document.getElementById('posti');

                function increment() {
                    if(counterInput.value > 5) return
                    counterInput.value = parseInt(counterInput.value) + 1;
                }

                function decrement() {
                    if(counterInput.value < 2) return
                    counterInput.value = parseInt(counterInput.value) - 1;
                }
                const decrementButton = document.getElementById('decrement');
                const incrementButton = document.getElementById('increment');
                decrementButton.addEventListener(\"click\", decrement)
                incrementButton.addEventListener(\"click\", increment)
                </script>";
                break;
                
            case 3:
                $utente = ottieniUtenteDaSessione(session_id());
                $id_viaggio = registraViaggio($utente['id'], $_POST['comuneArrivoId'],
                 $_POST['comunePartenzaId'], $_POST['posti'], $_POST['prezzo'], $_POST['partenzaDate']);
                header('Location: viaggio.php?id='.$id_viaggio);
                echo "<script>window.location = 'viaggio.php?id=".$id_viaggio."';</script>";
        }
        ?>
    </div>
</body>