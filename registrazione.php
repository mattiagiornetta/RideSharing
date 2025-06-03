<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - CarPooling</title>
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
    require_once('./db.php');
    require_once('components/navbar.php');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        registraUtente(nome: $_POST['nome'], cognome: $_POST['cognome'], password: $_POST['password'], username: $_POST['username'], email: $_POST['email'], data_nascita: $_POST['data_nascita']);
    }
    ?>

    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">
                🎉 Unisciti a noi!
            </h1>
            <p class="text-xl text-white/90 font-light">
                Crea il tuo account e inizia a condividere i tuoi viaggi
            </p>
        </div>

        <div class="max-w-lg mx-auto">
            <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full mb-4">
                        <span class="text-2xl">✨</span>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Crea il tuo account</h2>
                    <p class="text-gray-600">Compila tutti i campi per registrarti</p>
                </div>

                <form method="POST" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">👤 Nome</label>
                            <input type="text" name="nome" id="nome"
                                class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 text-lg"
                                placeholder="Il tuo nome"
                                required>
                        </div>

                        <div>
                            <label for="cognome" class="block text-sm font-medium text-gray-700 mb-2">👥 Cognome</label>
                            <input type="text" name="cognome" id="cognome"
                                class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 text-lg"
                                placeholder="Il tuo cognome"
                                required>
                        </div>
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">🏷️ Username</label>
                        <input type="text" name="username" id="username"
                            class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 text-lg"
                            placeholder="Scegli un username unico"
                            required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">📧 Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 text-lg"
                            placeholder="La tua email"
                            required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">🔒 Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 text-lg"
                            placeholder="Crea una password sicura"
                            required>

                    </div>

                    <div>
                        <label for="data_nascita" class="block text-sm font-medium text-gray-700 mb-2">🎂 Data di Nascita</label>
                        <input type="date" name="data_nascita" id="data_nascita"
                            class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 text-lg"
                            required>
                    </div>

                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-4 rounded-2xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 transform hover:scale-[1.02] shadow-lg text-lg font-semibold">
                        🚀 Registrati ora
                    </button>
                </form>

                
                <div class="my-8 flex items-center">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-gray-500 text-sm">oppure</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <div class="text-center">
                    <p class="text-gray-600 mb-4">Hai già un account?</p>
                    <a href="accesso.php" 
                        class="inline-block px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg font-medium">
                        🔐 Accedi
                    </a>
                </div>
            </div>

            
        </div>
    </div>

    <script>

        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            const cognome = document.getElementById('cognome').value.trim();
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const dataNascita = document.getElementById('data_nascita').value;

            if (!nome || !cognome || !username || !email || !password || !dataNascita) {
                e.preventDefault();
                alert('Per favore, compila tutti i campi obbligatori.');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('La password deve essere di almeno 6 caratteri.');
                return;
            }

            const birthDate = new Date(dataNascita);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            if (age < 18) {
                e.preventDefault();
                alert('Devi avere almeno 18 anni per registrarti.');
                return;
            }
        });

        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('transform', 'scale-[1.02]');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('transform', 'scale-[1.02]');
            });
        });
    </script>
</body>
</html>