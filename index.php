<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerca Viaggio - CarPooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="scripts/functions.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    </style>
</head>

<body class="min-h-screen">
    <?php
    require('./db.php');
        require_once 'components/navbar.php';

    ?>

    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">
                🔍 Trova il tuo Viaggio
            </h1>
            <p class="text-xl text-white/90 font-light">
                Scopri i viaggi disponibili nella tua zona
            </p>
        </div>

        <div class="max-w-2xl mx-auto">
            <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-4">
                        <span class="text-2xl">🚗</span>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Dove vuoi andare?</h2>
                    <p class="text-gray-600">Inserisci le tue destinazioni per trovare un passaggio</p>
                </div>

                <form action="cerca_viaggio.php" method="POST" class="space-y-6">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">🏠 Partenza da</label>
                        <textarea type="text" rows="1" id="comunePartenza" 
                            class="resize-none w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-lg"
                            placeholder="Da quale città parti?" 
                            autocomplete="off" 
                            onkeyup="getSuggestions('comunePartenza','comunePartenzaId')"></textarea>
                    </div>
                    
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">🎯 Destinazione</label>
                        <textarea type="text" rows="1" id="comuneArrivo" 
                            class="resize-none w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-lg"
                            placeholder="Dove vuoi arrivare?" 
                            autocomplete="off" 
                            onkeyup="getSuggestions('comuneArrivo','comuneArrivoId')"></textarea>
                    </div>
                    
                    <ul id="suggestions" class="mt-2 bg-white border-2 border-gray-200 rounded-2xl shadow-lg hidden max-h-48 overflow-y-auto"></ul>
                    
                    <input type="hidden" id="comunePartenzaId" name="comunePartenzaId">
                    <input type="hidden" id="comuneArrivoId" name="comuneArrivoId">

                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 rounded-2xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-[1.02] shadow-lg text-lg font-semibold">
                        🔍 Cerca Viaggio
                    </button>
                </form>
            </div>

            <div class="mt-8 glass-effect rounded-3xl p-6 shadow-2xl">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">💡 Suggerimenti per la ricerca</h3>
                    <div class="grid md:grid-cols-3 gap-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-2">
                            <span class="text-green-500">✓</span>
                            <span>Risparmia sui costi</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-blue-500">✓</span>
                            <span>Viaggia in compagnia</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-purple-500">✓</span>
                            <span>Riduci l'impatto ambientale</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const partenza = document.getElementById('comunePartenzaId').value;
            const arrivo = document.getElementById('comuneArrivoId').value;
            
            if (!partenza || !arrivo) {
                e.preventDefault();
                alert('Per favore, seleziona una località valida per partenza e arrivo.');
                return;
            }
            
            if (arrivo === partenza) {
                e.preventDefault();
                alert('I comuni di partenza e arrivo devono essere diversi.');
                return;
            }
        });
    </script>
</body>
</html>