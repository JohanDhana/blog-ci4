<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset</title>
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
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success" role="alert">
                <h4 class="alert-title">Wow! Everything worked!</h4>
                <div class="text-muted"><?= $this->session->flashdata('success') ?></div>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('bad_request')) : ?>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-title">Error</h4>
                <div class="text-muted"><?= $this->session->flashdata('bad_request') ?></div>
            </div>
        <?php endif; ?>
        <!-- Flash messages -->
    </div>

    <div class="page page-center">
        <div class="container-tight py-4">
            <form class="card card-md" action="<?= base_url('users/reset-password') ?>" method="POST">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Reset Password</h2>
                    <div class="mb-3">
                        <label class="form-label">Old Password</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password" class="form-control" placeholder="Enter current password">
                            <span class="input-group-text">
                                <a id="passwordToggle" class="link-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                    </svg>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            New Password
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="new_password" class="form-control" placeholder="New Password">
                            <span class="input-group-text">
                                <a id="newPasswordToggle" class="link-secondary">
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
                        <button type="submit" class="btn btn-primary w-100">Change Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="<?php echo base_url(); ?>assets/js/tabler.min.js"></script>
    <script>
        var oldPassword = document.getElementsByName('password')[0]
        var newPassword = document.getElementsByName('new_password')[0]
        document.getElementById('passwordToggle').addEventListener('click', (evt) => {
            if (oldPassword.type === 'text') {
                oldPassword.type = 'password'
            } else {
                oldPassword.type = 'text'
            }
        })
        document.getElementById('newPasswordToggle').addEventListener('click', (evt) => {
            if (newPassword.type === 'text') {
                newPassword.type = 'password'
            } else {
                newPassword.type = 'text'
            }
        })
    </script>
</body>

</html>