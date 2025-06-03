<?php 
    require_once "./db.php";
    $auth = autentica();
?>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
        }
        .glass-nav { 
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<nav class="glass-nav sticky top-0 z-50">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center">
            <a href="index.php" class="group flex items-center space-x-3">
                <div class="w-12 h-12 flex items-center justify-center">
                    <span class="text-2xl">🚗</span>
                </div>
                <span class="text-white text-2xl font-bold">
                    Ride-Sharing
                </span>
            </a>
            
            <ul class="flex items-center space-x-2">
                <?php 
                    if($auth) {
                        echo '<li>
                                <a href="viaggi_prenotati.php" class="group relative px-6 py-3 text-white font-medium rounded-2xl transition-all flex items-center space-x-2">
                                    <span class="text-lg">🎫</span>
                                    <span>I miei viaggi</span>
                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-500/20 to-cyan-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </a>
                              </li>';
                    
                    
                        echo '<li>
                                <a href="pubblica_viaggio.php" class="group relative px-6 py-3 text-white font-medium rounded-2xl transition-all flex items-center space-x-2">
                                    <span class="text-lg">📝</span>
                                    <span>Pubblica un viaggio</span>
                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-green-500/20 to-emerald-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </a>
                              </li>';
                    
                    }
                    if(!$auth) {
                        echo '<li>
                                <a href="accesso.php" class="group relative px-6 py-3 text-white font-medium rounded-2xl transition-all flex items-center space-x-2">
                                    <span class="text-lg">🔑</span>
                                    <span>Accesso</span>
                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-500/20 to-indigo-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </a>
                              </li>
                              <li>
                                <a href="registrazione.php" class="group relative px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-semibold rounded-2xl hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center space-x-2">
                                    <span class="text-lg">✨</span>
                                    <span>Registrazione</span>
                                </a>
                              </li>';
                    } else {
                        echo '<li>
                                <a href="impostazioni.php" class="group relative px-6 py-3 text-white font-medium rounded-2xl transition-all flex items-center space-x-2">
                                    <span class="text-lg">⚙️</span>
                                    <span>Impostazioni</span>
                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-gray-500/20 to-slate-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </a>
                              </li>
                              <li>
                                <a href="esci.php" class="group relative px-6 py-3 text-white font-medium rounded-2xl hover:bg-red-500/20 transition-all flex items-center space-x-2">
                                    <span class="text-lg">🚪</span>
                                    <span>Esci</span>
                                </a>
                              </li>';
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>