<html lang="en">
<?php $session = \Config\Services::session(); ?>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Sign in</title>
	<!-- CSS files -->
	<link href="<?= base_url(); ?>/assets/css/tabler.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/assets/css/tabler-flags.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/assets/css/tabler-payments.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/assets/css/tabler-vendors.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/assets/css/demo.min.css" rel="stylesheet">
</head>

<body class="antialiased border-top-wide border-primary d-flex flex-column">

	<div class="container-xl pt-2">
		<!-- Flash messages -->
		<?php if ($session->getFlashdata('success')) : ?>
			<div class="alert alert-success" role="alert">
				<h4 class="alert-title">Wow! Everything worked!</h4>
				<div class="text-muted"><?= $session->getFlashdata('success') ?></div>
			</div>
		<?php endif; ?>

		<?php if ($session->getFlashdata('bad_request')) : ?>
			<div class="alert alert-danger" role="alert">
				<h4 class="alert-title">Error</h4>
				<div class="text-muted"><?= $session->getFlashdata('bad_request') ?></div>
			</div>
		<?php endif; ?>

		<!-- Flash messages -->
	</div>

	<div class="page page-center">
		<div class="container-tight py-4">
			<div class="text-center mb-4">
				<a href="."><img src="./static/logo.svg" height="36" alt=""></a>
			</div>
			<form class="card card-md" action="<?= base_url('login') ?>" method="POST">
				<div class="card-body">
					<h2 class="card-title text-center mb-4">Login to your account</h2>
					<div class="mb-3">
						<label class="form-label">Username</label>
						<input type="text" name="username" class="form-control" placeholder="Enter username">
					</div>
					<div class="mb-2">
						<label class="form-label">
							Password
						</label>
						<div class="input-group input-group-flat">
							<input type="password" name="password" class="form-control" placeholder="Password">
							<span class="input-group-text">
								<a id="passwordToggle" class="link-secondary" title="" data-bs-toggle="tooltip" data-bs-original-title="Show password">
									<!-- Download SVG icon from http://tabler-icons.io/i/eye -->
									<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
										<circle cx="12" cy="12" r="2"></circle>
										<path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
									</svg>
								</a>
							</span>
						</div>
					</div>
					<div class="form-footer">
						<button type="submit" class="btn btn-primary w-100">Sign in</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- Libs JS -->
	<!-- Tabler Core -->
	<script src="<?= base_url(); ?>/assets/js/tabler.min.js"></script>
	<script>
		var password = document.getElementsByName('password')[0]
		document.getElementById('passwordToggle').addEventListener('click', (evt) => {
			if (password.type === 'text') {
				password.type = 'password'
			} else {
				password.type = 'text'
			}
		})
	</script>
</body>

</html>