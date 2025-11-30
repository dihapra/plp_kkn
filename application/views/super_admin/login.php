<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Super Admin Login</title>
	<link rel="icon" href="<?= base_url('assets/images/unimed.ico') ?>" type="image/x-icon">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		:root {
			color-scheme: light;
			font-family: 'Inter', system-ui, -apple-system, sans-serif;
		}

		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			min-height: 100vh;
			background: radial-gradient(circle at top left, #1d4ed8, transparent 45%),
				radial-gradient(circle at bottom right, #0f172a, transparent 55%),
				#020617;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 2rem;
			color: #0f172a;
		}

		.page {
			width: min(1100px, 100%);
			background: rgba(255, 255, 255, 0.08);
			border-radius: 2rem;
			padding: 2rem;
			box-shadow: 0 40px 80px rgba(15, 23, 42, 0.35);
			position: relative;
			overflow: hidden;
			backdrop-filter: blur(20px);
			border: 1px solid rgba(255, 255, 255, 0.1);
		}

		.page::before {
			content: '';
			position: absolute;
			inset: 0;
			background: radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.25), transparent 40%),
				radial-gradient(circle at 85% 0%, rgba(99, 102, 241, 0.3), transparent 40%);
			pointer-events: none;
		}

		.login-layout {
			position: relative;
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
			gap: 2rem;
			align-items: stretch;
		}

		.welcome-panel {
			background: linear-gradient(180deg, rgba(13, 110, 253, 0.9), rgba(15, 23, 42, 0.95));
			color: #fff;
			padding: 2.5rem;
			border-radius: 1.5rem;
			display: flex;
			flex-direction: column;
			gap: 1rem;
			box-shadow: 0 20px 45px rgba(15, 23, 42, 0.35);
		}

		.welcome-panel h1 {
			margin: 0;
			font-size: clamp(2rem, 3vw, 2.5rem);
			font-weight: 700;
		}

		.welcome-panel p {
			margin: 0;
			color: rgba(255, 255, 255, 0.86);
			line-height: 1.6;
		}

		.welcome-panel ul {
			padding-left: 1rem;
			margin: 0;
			display: grid;
			gap: 0.6rem;
		}

		.welcome-panel li {
			display: flex;
			align-items: center;
			gap: 0.5rem;
			font-size: 0.95rem;
		}

		.login-card {
			background: #fff;
			border-radius: 1.5rem;
			padding: 2.5rem;
			box-shadow: 0 25px 60px rgba(15, 23, 42, 0.15);
			border: 1px solid rgba(15, 23, 42, 0.1);
			position: relative;
			z-index: 1;
		}

		.login-card h3 {
			margin-bottom: 0.5rem;
			font-weight: 600;
		}

		.form-label {
			font-weight: 500;
		}

		.form-control {
			border-radius: 0.9rem;
			border-color: rgba(15, 23, 42, 0.15);
			padding: 0.9rem 1rem;
			font-size: 1rem;
		}

		.input-group .form-control {
			border-radius: 0.9rem 0 0 0.9rem;
		}

		.input-group .btn {
			border-radius: 0 0.9rem 0.9rem 0;
			border: 1px solid rgba(15, 23, 42, 0.15);
			background: #f1f5f9;
		}

		.btn-primary {
			background: linear-gradient(135deg, #0d6efd, #6610f2);
			border: none;
			box-shadow: 0 12px 30px rgba(13, 110, 253, 0.35);
		}

		.btn-primary:hover {
			transform: translateY(-2px);
		}

		.helper-text {
			font-size: 0.85rem;
			color: #6c757d;
			margin: 0.4rem 0 0;
		}

		@media (max-width: 660px) {
			.welcome-panel {
				padding: 1.8rem;
			}

			.login-card {
				padding: 2rem;
			}
		}
	</style>
</head>

<body>
	<div class="page">
		<div class="login-layout">
			<section class="welcome-panel">
				<p class="text-uppercase fw-bold" style="letter-spacing: 0.4rem; font-size: 0.75rem;">PLP Super Admin</p>
				<h1>Kelola data dengan tenang</h1>
				<p>Dashboard super admin memberi Anda kendali penuh terhadap user, admin, dan status PLP. Masuk untuk memantau statistik serta tangani kebutuhan operasional.</p>
				<ul>
					<li><i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i> Update pengguna real-time</li>
					<li><i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i> Kendali akses berbasis peran</li>
					<li><i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i> Laporan & notifikasi</li>
				</ul>
			</section>

			<section class="login-card">
				<h3 class="mb-4">Masuk ke super admin</h3>
				<p class="helper-text">Gunakan akun super admin terdaftar untuk melanjutkan.</p>

				<?php if (!empty($error)): ?>
					<div class="alert alert-danger small mb-3"><?= $error ?></div>
				<?php endif; ?>
				<?php if (!empty($success)): ?>
					<div class="alert alert-success small mb-3"><?= $success ?></div>
				<?php endif; ?>

				<form method="POST" action="<?= base_url('super-admin/login/authenticate') ?>" class="mt-3">
					<div class="mb-3">
						<label class="form-label">Email Address</label>
						<input type="email" name="identifier" class="form-control" required placeholder="name@plp2.test">
					</div>

					<div class="mb-4">
						<label class="form-label">Password</label>
						<div class="input-group">
							<input type="password" name="password" class="form-control" required placeholder="******">
							<button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Tampilkan password">
								<i class="bi bi-eye"></i>
							</button>
						</div>
					</div>

					<div class="d-grid">
						<button type="submit" class="btn btn-primary btn-lg">Masuk</button>
					</div>
				</form>
			</section>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		const toggleButton = document.getElementById('togglePassword');
		if (toggleButton) {
			toggleButton.addEventListener('click', function () {
				const input = document.querySelector('input[name="password"]');
				if (!input) return;
				const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
				input.setAttribute('type', type);
				this.querySelector('i').classList.toggle('bi-eye');
				this.querySelector('i').classList.toggle('bi-eye-slash');
			});
		}
	</script>
</body>

</html>


