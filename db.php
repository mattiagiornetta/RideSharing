<?php

date_default_timezone_set('Europe/Rome');

function getDbConnection(): PDO
{
	require_once "./config.php";

	$config = getConfiguration("./config.txt");

	$servername = $config["dbservername"];
	$username = $config["dbuser"];
	$password = $config["dbpassword"];
	$dbname = $config["dbname"];

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	} catch (PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}

	return $conn;
}

function ottieniDateTime() {
	return (new \DateTime())->format( 'Y-m-d H:i:s' );
}
function registraUtente($nome, $cognome, $password, $username, $email, $data_nascita)
{
	$conn = getDbConnection();
	$query = "INSERT INTO Utente (password, nome, cognome, username, email, data_di_nascita) VALUES (?, ?, ?, ?, ?, ?);";
	$stmt = $conn->prepare($query);
	$passwordHash = password_hash($password, PASSWORD_DEFAULT);
	$stmt->execute([$passwordHash, $nome, $cognome, $username, $email, $data_nascita]);
	$id = $conn->lastInsertId();
	$conn = null;
	return registraAccesso($id);
}
function registraViaggio($id_organizzatore, $id_destinazione, $id_partenza, $posti, $prezzo_posto, $data_partenza)
{
	$conn = getDbConnection();
	$query = "INSERT INTO Viaggio (id_destinazione, id_partenza, posti, prezzo_posto, data_partenza, id_organizzatore) VALUES (?, ?, ?, ?, ?, ?);";
	$stmt = $conn->prepare($query);
	$stmt->execute([$id_destinazione, $id_partenza, $posti, $prezzo_posto, $data_partenza, $id_organizzatore]);
	$id = $conn->lastInsertId();
	$conn = null;
	return $id;
}
function ottieniSuggerimentoCitta($userQuery) {
	$conn = getDbConnection();
	$stmt = $conn->prepare('SELECT c.nome, c.id, p.sigla_automobilistica  FROM Comune c JOIN Provincia p on p.id  = c.id_provincia WHERE c.nome LIKE ? ORDER BY 
    CASE 
        WHEN c.nome = ? THEN 1  
        ELSE 2                   
    END, c.nome LIMIT 5');
	$stmt->execute(["{$userQuery}%",$userQuery]);
	return $stmt->fetchAll();
}

function cambiaPassword($id_utente, $nuova_password) {
	$conn = getDbConnection();
	
	$query = "UPDATE Utente SET password=? WHERE id=?;";

	$stmt = $conn->prepare(query: $query);
	$stmt->execute([password_hash($nuova_password, PASSWORD_DEFAULT), $id_utente]);
	$conn = null;
}

function ottieniViaggioDaId($id_viaggio) {
	$conn = getDbConnection();
	$stmt = $conn->prepare("SELECT * FROM Viaggio WHERE id=?");
	$stmt->execute([$id_viaggio]);
	$conn = null;
	return $stmt->fetch();
}
function registraAccesso($utente_id): void
{
    session_start();
    session_regenerate_id(true);
    
    $conn = getDbConnection();
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $query = "INSERT INTO Sessione (session_id, id_utente, ip_address, data) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $dateTime = ottieniDateTime();

    try {
        $session_id = session_id();
        $stmt->execute([$session_id, $utente_id, $ip_address, $dateTime]);

        $_SESSION['id'] = $utente_id;
    } catch (PDOException $e) {
        echo $e->getMessage(); 
        echo "Errore nella registrazione della sessione";
        die();
    }

    $conn = null;
    header("Location: index.php");
    exit();
}
function prenotaViaggio($utente_id, $viaggio_id): bool
{
    $conn = getDbConnection();
    $query = "INSERT INTO ViaggioUtente (id_utente, id_viaggio) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    try {
        $stmt->execute([$utente_id, $viaggio_id]);
		
    } catch (PDOException $e) {
		if($e->getCode() == '23000') {
			return false;	
		}
		echo $e->getMessage();
	}

    $conn = null;
	return true;
}


function invalidaAccesso(string $session_id, bool $logout)
{
	$conn = getDbConnection();
	if ($logout) {
		$stato = 0;
	} else {
		$stato = -1;
	}
	$query = "UPDATE Sessione SET stato=? WHERE session_id=?;";

	$stmt = $conn->prepare(query: $query);
	$stmt->execute([$stato, $session_id]);
	$conn = null;
}
function ottieniComune(string $comune_id) {
	$conn = getDbConnection();
	$stmt = $conn->prepare("SELECT * FROM Comune WHERE id=?");
	$stmt->execute([$comune_id]);
	$conn = null;
	return $stmt->fetch();
}
function ottieniSessioniAttive(string $utente_id) {
	$conn = getDbConnection();
	$stmt = $conn->prepare("SELECT * FROM Sessione WHERE LOWER(id_utente)=? AND stato = 1 ORDER BY data DESC");
	$stmt->execute([$utente_id]);
	$conn = null;
	return $stmt->fetchAll();
}

function ottieniViaggiUtente($id_utente) {
	$conn = getDbConnection();
	$stmt = $conn->prepare("SELECT * FROM Viaggio WHERE id_organizzatore=?");
	$stmt->execute([$id_utente]);
	$conn = null;
	return $stmt->fetchAll();	
}
function ottieniPrenotazioniUtente($id_utente) {
	$conn = getDbConnection();
	$stmt = $conn->prepare("SELECT * FROM ViaggioUtente WHERE id_utente=?");
	$stmt->execute([$id_utente]);
	$conn = null;
	return $stmt->fetchAll();	
}
function cercaViaggio($id_partenza, $id_destinazione) {
	$conn = getDbConnection();
	$stmt = $conn->prepare("SELECT * FROM Viaggio WHERE id_partenza=? AND id_destinazione=?");
	$stmt->execute([$id_partenza, $id_destinazione]);
	$conn = null;
	return $stmt->fetchAll();	
}

function ottieniPrenotazioniViaggio($id_viaggio) {
	$conn = getDbConnection();
	$stmt = $conn->prepare("SELECT * FROM ViaggioUtente WHERE id_viaggio=?");
	$stmt->execute([$id_viaggio]);
	$conn = null;
	return $stmt->fetchAll();	
}
function ottieniUtente($email)
{
	$conn = getDbConnection();
	$stmt = $conn->prepare("SELECT * FROM Utente WHERE LOWER(email)=?");
	$stmt->execute([$email]);
	$conn = null;
	return $stmt->fetch();
}
function ottieniUtenteDaId($id)
{
	$conn = getDbConnection();
	$stmt = $conn->prepare("SELECT * FROM Utente WHERE id=?");
	$stmt->execute([$id]);
	$conn = null;
	return $stmt->fetch();
}
function ottieniUtenteDaSessione(string $session_id) {
	$conn = getDbConnection();
	$stmt = $conn->prepare(query: "select u.* from Sessione s join Utente u on s.id_utente = u.id where s.session_id=?;");
	$stmt->execute(params: [$session_id]);
	$conn = null;
	return $stmt->fetch();
}

function esci(): void
{
	if(!isset($_SESSION)){
 		session_start();
	}
	$session_id = session_id();
	if ($session_id) {
		invalidaAccesso(session_id: $session_id, logout: true);
		session_regenerate_id(delete_old_session: true);
	}
	header(header: "Location: index.php");
}

function autentica(): bool {
	if(!isset($_SESSION)){
 		session_start();
	}
    $conn = getDbConnection();

    $stmt = $conn->prepare("SELECT stato FROM Sessione WHERE session_id = ?");
    
    $stmt->execute([session_id()]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null;

    $autenticato = ($res !== false && $res['stato'] == 1);
    return $autenticato;
}
