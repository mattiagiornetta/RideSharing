<?php
require_once "./db.php";
if(!autentica()) {
    header("Location: accesso.php");
    die("403");
}
$utente =  ottieniUtenteDaSessione(session_id());
$sessioni = ottieniSessioniAttive($utente['id']);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni Account - CarPooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        .message-success {
            backdrop-filter: blur(16px);
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.9), rgba(22, 163, 74, 0.9));
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
        }
        .message-error {
            backdrop-filter: blur(16px);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9), rgba(220, 38, 38, 0.9));
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

    </style>
</head>

<body class="min-h-screen">
    <?php 
        require_once 'components/navbar.php';    
    ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">
                ⚙️ Impostazioni Account
            </h1>
            <p class="text-xl text-white/90 font-light">
                Gestisci il tuo profilo e la sicurezza
            </p>
        </div>
        
        <?php 
        if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
            $messageType = $_SESSION['message_type'];
            $icon = '';
            $cssClass = '';
            
            if ($messageType === 'success') {
                $icon = '✅';
                $cssClass = 'message-success';
            } elseif ($messageType === 'error') {
                $icon = '❌';
                $cssClass = 'message-error';
            }
            
            if (!empty($cssClass)) {
                echo "<div class='max-w-4xl mx-auto mb-8'>
                        <div class='{$cssClass} rounded-2xl p-6 shadow-xl transform hover:scale-[1.02] transition-all duration-300'>
                            <div class='flex items-center space-x-3'>
                                <span class='text-2xl'>{$icon}</span>
                                <p class='text-lg font-medium'>{$_SESSION['message']}</p>
                            </div>
                        </div>
                      </div>";
            }
            
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>
        
        <div class="max-w-4xl mx-auto space-y-8">
            <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full mb-4">
                        <span class="text-2xl">👤</span>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Modifica Profilo</h2>
                    <p class="text-gray-600">Cambia password</p>
                </div>

                <form method="POST" action="cambia_password.php" class="space-y-6">
                    <div class="relative">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">👨‍💼 Nome Utente</label>
                        <input type="text" name="username" id="username" 
                            class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-lg" 
                            placeholder="Il tuo nome utente" required>
                    </div>
                    <div class="relative">
                        <label for="password-attuale" class="block text-sm font-medium text-gray-700 mb-2">✅ Conferma Password</label>
                        <input type="password" name="password-attuale" id="password-attuale" 
                            class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-lg" 
                            placeholder="Ripeti la password" required>
                    </div>
                    <div class="relative">
                        <label for="nuova-password" class="block text-sm font-medium text-gray-700 mb-2">🔐 Nuova Password</label>
                        <input type="password" name="nuova-password" id="nuova-password" 
                            class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-lg" 
                            placeholder="Inserisci una nuova password" required>
                    </div>

                    <div class="pt-4">
                        <input type="submit" value="💾 Salva Modifiche" 
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-4 rounded-2xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 text-lg font-medium shadow-lg transform hover:scale-[1.02]">
                    </div>
                </form>
            </div>

            <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full mb-4">
                        <span class="text-2xl">🔍</span>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Accessi Recenti</h2>
                    <p class="text-gray-600">Monitora l'attività del tuo account</p>
                </div>

                <div class="overflow-hidden rounded-2xl border-2 border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                        📅 Data e Ora
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                        🌐 Indirizzo IP
                                    </th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                        🚪 Azioni
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php 
                                foreach($sessioni as $s) {
                                    $data = date_format(date_create($s['data']), "d/m/Y H:i");
                                    echo '<tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-gray-600">⏰</span>
                                                <span class="text-sm font-medium text-gray-900">'.$data.'</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-gray-600">🖥️</span>
                                                <span class="text-sm text-gray-700 font-mono bg-gray-100 px-2 py-1 rounded-lg">'.$s['ip_address'].'</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <a href="termina_sessione.php?id='.$s['session_id'].'" 
                                                class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-110 shadow-lg"
                                                title="Termina sessione">
                                                <i class="fas fa-times text-sm"></i>
                                            </a>
                                        </td>
                                    </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if (empty($sessioni)): ?>
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <span class="text-2xl text-gray-400">📋</span>
                    </div>
                    <p class="text-gray-500 text-lg">Nessun accesso recente trovato</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('nuova-password').value;
         
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('La password deve essere di almeno 6 caratteri.');
                return false;
            }
        });

        document.querySelectorAll('a[href*="termina_sessione.php"]').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Sei sicuro di voler terminare questa sessione?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>