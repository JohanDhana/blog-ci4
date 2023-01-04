<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $seo_title ?> </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
  <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" />
  <meta name="theme-color" content="#7952b3" />
  <meta name="description" content="<?= $seo_desc ?>">
  <?php foreach ($tags as $tag) : ?>
    <meta property="article:tag" content="<?= $tag ?>" />
  <?php endforeach ?>

  <!-- Favicons -->

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
</head>

<body>
  <header class="navbar navbar-light sticky-top bg-white flex-md-nowrap pt-4 pb-0">
    <div class="container p-0">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?php echo base_url(); ?>">
      </a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <form method="get" action="<?= base_url('posts/search') ?>">
        <input class="form-control form-control-dark w-100 w-md-50 me-auto border-bottom" type="text" placeholder="Search" name="search_query" aria-label="Search" />
      </form>
      <!-- <div class="btn-group" role="group">
          <button type="button" class="btn btn-warning text-white rounded-pill px-4 me-3">Left</button>
          <button type="button" class="btn btn-warning text-white rounded-pill px-4">Middle</button>
        </div> -->
    </div>
  </header>

  <div class="container">
    <div class="row">
      <nav id="sidebarMenu" class="
            col-md-3 col-lg-2
            d-md-block
            bg-white
            sidebar
            collapse
            position-sticky
          ">
        <div class="position-sticky sticky-top pt-3" style="top: 92px">
          <ul class="nav flex-column">
            <li class=" nav-item">
              <div class="btn-group w-100">
                <a aria-current="page" href="<?= base_url('posts/') ?>" class="nav-link <?php if (current_url() == base_url('posts')) echo 'active'; ?>"> Posts</a>
              </div>
            </li>
            <li class=" nav-item">
              <div class="btn-group w-100">
                <a aria-current="page" href="<?= base_url('categories/') ?>" class="nav-link <?php if (current_url() == base_url('categories')) echo 'active'; ?>"> Categories</a>
              </div>
            </li>
            <?php foreach ($posts as $post) : ?>
              <li class=" <?php if (!$post->sub) echo 'nav-item'  ?>">
                <div class="btn-group w-100">
                  <a aria-current="page" href="<?= base_url('post/' . $post->slug) ?>" class="nav-link  <?php if (current_url() == base_url('post/' . $post->slug)) echo 'active'; ?>">
                    <?php if ($post->post_icon) { ?> <img src="<?= base_url('assets/images/posts/' . $post->post_icon) ?>" alt="" width="20px" class="me-1" srcset=""> <?php } ?>
                    <?= $post->title ?>
                  </a>
                  <?php if ($post->sub) { ?>
                    <a data-bs-toggle="collapse" href="#collapseExample<?= $post->id ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?= $post->id ?>" class="nav-link ms-auto">
                      <i class="fas fa-chevron-down"></i>
                    </a>
                  <?php } ?>
                </div>
                <?php if ($post->sub) { ?>
                  <div class="collapse <?php if (in_array(current_url(true)->getSegment('2'), array_column($post->sub, 'slug'))) echo 'show'; ?>" id="collapseExample<?= $post->id ?>">
                    <div class="py-2 ps-3">
                      <ul class="list-unstyled fw-normal">
                        <?php foreach ($post->sub as $sub_post) : ?>
                          <li>
                            <a href="<?= base_url('post/' . $sub_post->slug) ?>" class="nav-link <?php if (current_url() == base_url('post/' . $sub_post->slug)) echo 'active'; ?>"><?= $sub_post->title ?></a>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  </div>
                <?php  } ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">