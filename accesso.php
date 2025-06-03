<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi - CarPooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    require_once "./components/navbar.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        require_once('./db.php');
        $err = 'Credenziali sbagliate, riprova.';
        $utente = ottieniUtente($_POST['email']) ;
        if($utente == false) {
            echo $err; 
            return;
        }
         if (password_verify($_POST['password'], $utente['password'])) {
             echo "Reindirizzamento...";
             registraAccesso($utente['id']);
         } else {
             echo $err;
         }
    }
    ?>

    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">
                👋 Bentornato!
            </h1>
            <p class="text-xl text-white/90 font-light">
                Accedi al tuo account per continuare
            </p>
        </div>

        <div class="max-w-md mx-auto">
            <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mb-4">
                        <span class="text-2xl">🔐</span>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Accedi al tuo account</h2>
                    <p class="text-gray-600">Inserisci le tue credenziali per continuare</p>
                </div>

                <form method="POST" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">📧 Email</label>
                        <input type="email" name="email" id="email" 
                            class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 text-lg" 
                            placeholder="La tua email"
                            required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">🔒 Password</label>
                        <input type="password" name="password" id="password" 
                            class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 text-lg" 
                            placeholder="La tua password"
                            required>
                    </div>

                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-4 rounded-2xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-[1.02] shadow-lg text-lg font-semibold">
                        🚀 Accedi
                    </button>
                </form>

                <div class="my-8 flex items-center">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-gray-500 text-sm">oppure</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <div class="text-center">
                    <p class="text-gray-600 mb-4">Non hai ancora un account?</p>
                    <a href="registrazione.php" 
                        class="inline-block px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 transform hover:scale-105 shadow-lg font-medium">
                        ✨ Registrati ora
                    </a>
                </div>
            </div>

            <div class="mt-8 glass-effect rounded-3xl p-6 shadow-2xl">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">🌟 Perché scegliere CarPooling?</h3>
                    <div class="grid grid-cols-1 gap-3 text-sm text-gray-600">
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-green-500">💰</span>
                            <span>Risparmia sui costi di viaggio</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-blue-500">🤝</span>
                            <span>Incontra nuove persone</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-purple-500">🌱</span>
                            <span>Riduci l'impatto ambientale</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('transform', 'scale-[1.02]');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('transform', 'scale-[1.02]');
            });
        });


        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Per favore, compila tutti i campi.');
                return;
            }
        });
    </script>
</body>
</html>