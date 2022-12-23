<html>

<head>
    <title>EasySchema Dashboard</title>
    <!-- CSS files -->
    <link href="<?php echo base_url(); ?>assets/css/tabler.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/tabler-flags.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/tabler-payments.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/tabler-vendors.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/demo.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="icon" href="https://easyschema.com/Components/Assets/images/logo_ico.jpg" sizes="32x32" type="image/png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar navbar-light">
                <div class="container-xl">
                    <ul class="navbar-nav">
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <polyline points="5 12 3 12 12 3 21 12 19 12"></polyline>
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Home
                                </span>
                            </a>
                        </li> -->
                        <li class="nav-item dropdown <?= (current_url() === base_url('posts/list')) ? 'active' : ''  ?>">
                            <a class="nav-link" href="<?= base_url('posts/list') ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"></polyline>
                                        <line x1="12" y1="12" x2="20" y2="7.5"></line>
                                        <line x1="12" y1="12" x2="12" y2="21"></line>
                                        <line x1="12" y1="12" x2="4" y2="7.5"></line>
                                        <line x1="16" y1="5.25" x2="8" y2="9.75"></line>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Pages
                                </span>
                            </a>
                        </li>
                        <li class="nav-item dropdown <?= (current_url() === base_url('posts/create')) ? 'active' : ''  ?>">
                            <a class="nav-link" href="<?= base_url('posts/create') ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <polyline points="9 11 12 14 20 6"></polyline>
                                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Create Post
                                </span>
                            </a>
                        </li>
                        <li class="nav-item dropdown <?= (current_url() === base_url('categories/list')) ? 'active' : ''  ?>">
                            <a class="nav-link" href="<?= base_url('categories/list') ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"></polyline>
                                        <line x1="12" y1="12" x2="20" y2="7.5"></line>
                                        <line x1="12" y1="12" x2="12" y2="21"></line>
                                        <line x1="12" y1="12" x2="4" y2="7.5"></line>
                                        <line x1="16" y1="5.25" x2="8" y2="9.75"></line>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Categories
                                </span>
                            </a>
                        </li>
                        <li class="nav-item dropdown <?= (current_url() === base_url('categories/create')) ? 'active' : ''  ?>">
                            <a class="nav-link" href="<?= base_url('categories/create') ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <polyline points="9 11 12 14 20 6"></polyline>
                                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Create Categories
                                </span>
                            </a>
                        </li>

                    </ul>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu" aria-expanded="false">
                            <span class="avatar avatar-sm" style="background-color: #fff;"> <img src="<?= base_url('assets/images/user-astronaut-solid.svg') ?>" alt="" srcset=""> </span>
                            <div class="d-none d-xl-block ps-2">
                                <div><?= $this->session->userdata('username'); ?></div>
                                <div class="mt-1 small text-muted">Admin</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="<?= base_url('users/reset-password') ?>" class="dropdown-item">Reset Password</a>
                            <a href="<?= base_url('users/logout') ?>" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-title">Error</h4>
                <div class="text-muted"><?= validation_errors() ?></div>
            </div>
        <?php endif; ?>
        <!-- Flash messages -->
    </div>

    <div class="page-wrapper">