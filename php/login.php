<?php
session_start();

// DB connection
require_once __DIR__ . '/connection.php';

$message = null; // ['type' => 'success'|'error', 'text' => '...']
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Surface any flash success from registration, logout, or query param
if (isset($_SESSION['flash_success'])) {
	$message = ['type' => 'success', 'text' => $_SESSION['flash_success']];
	unset($_SESSION['flash_success']);
} elseif (isset($_GET['registered'])) {
	$message = ['type' => 'success', 'text' => 'Compte créé avec succès. Vous pouvez vous connecter.'];
} elseif (isset($_GET['logout']) && $_GET['logout'] === 'success') {
	$message = ['type' => 'success', 'text' => 'Vous avez été déconnecté avec succès.'];
}
	// accept field named "identifier" (can be email or phone) for better UX
	$identifier = trim($_POST['identifier'] ?? $_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';

	if ($identifier === '' || $password === '') {
		$message = ['type' => 'error', 'text' => 'Please provide email/phone and password.'];
	} else {
		// NOTE: assumption: users are stored in a table named `users` with columns
		// uuid, username, phone, email, password, created_at, updated_at
		// If your users table has a different name, update the query below.

		$stmt = $conn->prepare("SELECT uuid, username, email, phone, password, role FROM users WHERE email = ? OR phone = ? LIMIT 1");
		if ($stmt) {
			$stmt->bind_param('ss', $identifier, $identifier);
			$stmt->execute();
			$res = $stmt->get_result();
			if ($row = $res->fetch_assoc()) {
				$hash = $row['password'];

				// Prefer password_verify for hashed passwords. As a tolerant fallback
				// (in case the DB contains plain text, which is not recommended),
				// we also allow direct comparison. Remove the fallback if all
				// passwords are hashed with password_hash().
				$password_ok = false;
				if (!empty($hash) && password_verify($password, $hash)) {
					$password_ok = true;
				} elseif ($password === $hash) {
					$password_ok = true; // fallback -- consider removing
				}

				if ($password_ok) {
					// successful login -> set session and redirect immediately to dashboard
					session_regenerate_id(true);
					$_SESSION['user_uuid'] = $row['uuid'];
					$_SESSION['username'] = $row['username'];
					$_SESSION['role'] = $row['role'] ?? 'User'; // Default to 'User' if role is not set

					// Redirect to dashboard (relative path from php/)
					header('Location: ../dashboard.php?login=success');
					echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0;url=../dashboard.php?login=success"><script>window.location.href="../dashboard.php?login=success";</script></head><body>If you are not redirected, <a href="../dashboard.php?login=success">click here</a>.</body></html>';
					exit();
				} else {
					$message = ['type' => 'error', 'text' => 'Email, phone number or password incorrect.'];
				}
			} else {
				$message = ['type' => 'error', 'text' => 'Email, phone number or password incorrect.'];
			}
			$stmt->close();
		} else {
			$message = ['type' => 'error', 'text' => 'Server error. Please try again later.'];
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - Cooficongo</title>
	<link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="../assets/css/main.css" rel="stylesheet">
	<style>
		/* flash message at top-right */
		.flash {
			position: fixed;
			top: 16px;
			right: 16px;
			z-index: 9999;
			padding: 12px 18px;
			border-radius: 8px;
			box-shadow: 0 6px 18px rgba(0,0,0,0.12);
			color: #fff;
		}
		.flash.success { background: #28a745; }
		.flash.error { background: #e74c3c; }
		/* ensure form card sits below the flash on small screens */
		main { padding-top: 48px; }
	</style>
</head>
<body class="auth-page">

<?php if ($message): ?>
	<div class="flash <?php echo $message['type'] === 'success' ? 'success' : 'error'; ?>" role="alert">
		<?php echo htmlspecialchars($message['text']); ?>
	</div>
<?php endif; ?>

<!-- Minimal reproduction of the Login page UI so the user sees the form and messages -->
<main class="main">
	<div class="page-title light-background" style="background-image: url(../assets/img/page-title-bg.webp);">
		<div class="container position-relative">
			<h1>Login</h1>
			<p>Connectez-vous pour accéder à votre espace</p>
		</div>
	</div>

	<section id="login" class="section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12 col-md-10 col-lg-8">
					<div class="login-card card shadow-sm">
						<div class="card-body p-5 text-center">
							<img src="../assets/img/logo.png" alt="logo" class="login-logo" style="max-height:64px;margin-bottom:18px;">
							<h2 class="mb-3">Se connecter</h2>
							<p class="form-note mb-4">Entrez votre adresse e-mail et mot de passe pour continuer.</p>

							<form method="post" action="" class="php-email-form needs-validation" novalidate>
								<div class="mb-3 text-start">
									<label for="identifier" class="form-label">E-mail ou Téléphone</label>
									<input type="text" class="form-control" id="identifier" name="identifier" placeholder="E-mail ou numéro de téléphone" required value="<?php echo htmlspecialchars($identifier); ?>">
									<div class="invalid-feedback">Veuillez entrer une adresse e-mail ou un numéro de téléphone valide.</div>
								</div>

								<div class="mb-3 text-start">
									<label for="password" class="form-label">Mot de passe</label>
									<input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
									<div class="invalid-feedback">Le mot de passe est requis.</div>
								</div>

								<div class="d-flex justify-content-between align-items-center mb-4 text-start">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
										<label class="form-check-label" for="remember">Se souvenir de moi</label>
									</div>
									<div><a href="../contact.html">Mot de passe oublié?</a></div>
								</div>

								<div class="d-grid">
									<button type="submit" class="btn btn-get-started"><i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Se connecter</button>
								</div>

								<div class="mt-4">
									<p class="mb-0">Pas encore de compte? <a href="../contact.html">Contactez-nous</a> pour créer un compte.</p>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</main>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
	// client-side validation UI (same as original)
	(function () {
		'use strict'
		var forms = document.querySelectorAll('.needs-validation')
		Array.prototype.slice.call(forms).forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}
				form.classList.add('was-validated')
			}, false)
		})
	})()
</script>

</body>
</html>
