<?php
include('components/navbar.php');
require('./db.php');

$conn = getDbConnection();
$query = "SELECT * FROM Utente WHERE LOWER(username)=?";
$stmt = $conn->prepare($query);
$stmt->execute([$_GET['username']]);
$res = $stmt->fetch();
$conn = null;


?>
<head>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .star-icon {
            transition: transform 0.2s;
        }
        .banner {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }
    </style>
</head>

<div class="mt-8 mx-auto max-w-4xl">
    <div class="banner rounded-lg p-8 shadow-xl text-white card">
        <div class="flex items-center space-x-6">
            <img src="profile-picture.jpg" alt="Foto Profilo" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
            
            <div>
                <h2 class="text-3xl font-bold"><?php echo $res['nome'].' '.$res['cognome'] ?></h2>
                <h3 class="text-xl font-bold text-blue-800"><?php echo '@'.$res['username'] ?></h3>

                <div class="flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star-fill star-icon" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star-fill star-icon" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star-fill star-icon" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star star-icon" viewBox="0 0 16 16">
                        <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star star-icon" viewBox="0 0 16 16">
                        <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6 mt-8 shadow-md card">
        <h3 class="text-2xl font-bold text-gray-700 mb-4">Biografia</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600">Membro dal: <span class="font-semibold"><?php echo $res['data_registrazione']?></span></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Data di nascita: <span class="font-semibold"><?php echo $res['data_di_nascita']?></span></p>
                <p class="text-sm text-gray-600 mt-2">Posizione: <span class="font-semibold">Milano</span></p>
            </div>
        </div>
        <div>
            <div>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi eum odit, delectus non, cumque, ex vitae sit inventore at corrupti est temporibus excepturi obcaecati repellendus similique labore in nemo blanditiis.</p>
            </div>
        </div>
</div>
