<?php
// Registration handler for Cooficongo
// Expects POST from /reg.html with fields: fullname, role, phone, email, password, confirm_password

session_start();
require_once __DIR__ . '/connection.php';

header('Content-Type: text/html; charset=utf-8');

// Utility: generate a UUID v4 (RFC 4122)
function uuidv4(): string {
	$data = random_bytes(16);
	// Set version to 0100
	$data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
	// Set bits 6-7 to 10
	$data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
	return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// Ensure table exists (safe no-op if already there)
$conn->query("CREATE TABLE IF NOT EXISTS `users` (
  `uuid` VARCHAR(36) NOT NULL,
  `username` VARCHAR(150) NOT NULL,
  `role` VARCHAR(50) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uuid`),
  UNIQUE KEY `uniq_users_email` (`email`),
  UNIQUE KEY `uniq_users_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'POST') {
	http_response_code(405);
	echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Method Not Allowed</title></head><body>
	<p>Méthode non autorisée.</p>
	<p><a href="../reg.html">Retour à l\'inscription</a></p>
	</body></html>';
	exit;
}

// Collect and sanitize inputs
$fullname = trim($_POST['fullname'] ?? '');
$role = trim($_POST['role'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Server-side validation
$errors = [];
if ($fullname === '') { $errors[] = "Le nom complet est requis."; }
if ($role === '') { $errors[] = "Le rôle est requis."; }
if ($phone === '') { $errors[] = "Le numéro de téléphone est requis."; }
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Adresse e-mail invalide."; }
if (strlen($password) < 6) { $errors[] = "Le mot de passe doit contenir au moins 6 caractères."; }
if ($password !== $confirm_password) { $errors[] = "Les mots de passe ne correspondent pas."; }

// Optional: normalize phone (basic trim of spaces)
$phone = preg_replace('/\s+/', ' ', $phone);

if ($errors) {
	http_response_code(422);
	echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Erreur d\'inscription</title>
	<link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="../assets/css/main.css" rel="stylesheet">
	</head><body class="auth-page">
	<main class="main"><div class="container py-5" style="max-width:860px;">
	<div class="alert alert-danger"><strong>Impossible de créer le compte :</strong><ul class="mb-0">';
	foreach ($errors as $e) {
		echo '<li>' . htmlspecialchars($e) . '</li>';
	}
	echo '</ul></div>
	<a class="btn btn-secondary" href="../reg.html">Revenir au formulaire</a>
	</div></main></body></html>';
	exit;
}

// Check for duplicates (email or phone)
$stmt = $conn->prepare("SELECT uuid FROM users WHERE email = ? OR phone = ? LIMIT 1");
if (!$stmt) {
	http_response_code(500);
	echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Erreur serveur</title></head><body>
	<p>Erreur du serveur (préparation de requête).</p><p><a href="../reg.html">Retour</a></p></body></html>';
	exit;
}
$stmt->bind_param('ss', $email, $phone);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
	$stmt->close();
	http_response_code(409);
	echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Compte existe déjà</title>
	<link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="../assets/css/main.css" rel="stylesheet">
	</head><body class="auth-page"><main class="main"><div class="container py-5" style="max-width:860px;">
	<div class="alert alert-warning">Un compte avec cet e-mail ou ce téléphone existe déjà. Veuillez vous connecter.</div>
	<a class="btn btn-primary me-2" href="../Login.html">Aller à la connexion</a>
	<a class="btn btn-outline-secondary" href="../reg.html">Retour</a>
	</div></main></body></html>';
	exit;
}
$stmt->close();

// Insert user
$uuid = uuidv4();
$username = $fullname; // map fullname -> username
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (uuid, username, role, phone, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
if (!$stmt) {
	http_response_code(500);
	echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Erreur serveur</title></head><body>
	<p>Erreur du serveur (préparation d\'insertion).</p><p><a href="../reg.html">Retour</a></p></body></html>';
	exit;
}
$stmt->bind_param('ssssss', $uuid, $username, $role, $phone, $email, $password_hash);

if ($stmt->execute()) {
	// Success: redirect to Login page with success flag
	$stmt->close();
	// Optionally set a flash session message
	$_SESSION['flash_success'] = 'Compte créé avec succès. Vous pouvez vous connecter.';
	header('Location: ../Login.html?registered=1');
	echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0;url=../Login.html?registered=1"><script>window.location.href="../Login.html?registered=1";</script></head><body>Compte créé. <a href="../Login.html?registered=1">Aller à la connexion</a>.</body></html>';
	exit;
} else {
	$err = htmlspecialchars($conn->error);
	http_response_code(500);
	echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Erreur</title>
	<link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="../assets/css/main.css" rel="stylesheet">
	</head><body class="auth-page"><main class="main"><div class="container py-5" style="max-width:860px;">
	<div class="alert alert-danger">Une erreur est survenue lors de la création du compte. '.$err.'</div>
	<a class="btn btn-outline-secondary" href="../reg.html">Réessayer</a>
	</div></main></body></html>';
	exit;
}

?>
