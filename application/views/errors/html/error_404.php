<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>404 Halaman Tidak Ditemukan</title>
	<style>
		body {
			margin: 0;
			min-height: 100vh;
			font-family: 'Segoe UI', Tahoma, sans-serif;
			background: radial-gradient(circle at 20% 20%, #fefefe, #e3ebff 50%, #ccd7ff 100%);
			color: #0f172a;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 2rem;
		}

		.card {
			width: min(540px, 100%);
			background: #ffffff;
			border-radius: 1.25rem;
			padding: 2.5rem;
			text-align: center;
			box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
			border: 1px solid rgba(13, 110, 253, 0.18);
		}

		.error-code {
			font-size: clamp(96px, 12vw, 140px);
			font-weight: 700;
			margin: 0;
			color: #0d6efd;
		}

		.error-title {
			margin: 0.5rem 0 0.25rem;
			font-size: clamp(1.9rem, 4vw, 2.4rem);
			font-weight: 600;
		}

		.error-message {
			margin: 0 0 1.5rem;
			color: #475467;
			font-size: 1rem;
			line-height: 1.6;
		}

		.actions {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			gap: 0.75rem;
		}

		.actions a,
		.actions button {
			border-radius: 999px;
			padding: 0.9rem 1.5rem;
			font-size: 0.95rem;
			font-weight: 600;
			text-decoration: none;
			border: 1px solid transparent;
			cursor: pointer;
			transition: transform 0.2s ease, box-shadow 0.2s ease;
		}

		.actions a {
			background: linear-gradient(135deg, #0d6efd, #6610f2);
			color: #fff;
			box-shadow: 0 12px 30px rgba(13, 110, 253, 0.3);
		}

		.actions a:hover {
			transform: translateY(-2px);
		}

		.actions button {
			background: transparent;
			border-color: rgba(15, 23, 42, 0.2);
			color: #0f172a;
		}

		@media (max-width: 480px) {
			.card {
				padding: 2rem;
			}

			.error-code {
				font-size: clamp(72px, 20vw, 100px);
			}

			.actions {
				flex-direction: column;
			}

			.actions a,
			.actions button {
				width: 100%;
			}
		}
	</style>
</head>
<body>
	<div class="card">
		<p class="error-code">404</p>
		<h1 class="error-title"><?php echo htmlspecialchars($heading, ENT_QUOTES, 'UTF-8'); ?></h1>
		<p class="error-message"><?php echo $message; ?></p>
		<div class="actions">
			<a href="/">Kembali ke Beranda</a>
			<button type="button" onclick="window.history.back()">Kembali</button>
		</div>
	</div>
</body>
</html>
