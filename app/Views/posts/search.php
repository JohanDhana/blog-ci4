<div class="row">
    <?php foreach ($posts_view as $post) : ?>
        <div class="col-lg-6 col-md-7 py-3">
            <a href="<?= base_url('post/' . $post['slug']) ?>" class="post-link text-decoration-none">
                <div class="card border-0 shadow-sm h-100">
                    <?php if ($post['post_image']) : ?>
                        <img src="<?= base_url('assets/images/posts/' . $post['post_image']) ?>" class="card-img-top w-100" alt="<?= $post['title'] ?>">
                    <?php endif ?>
                    <div class="card-body">
                        <h3 class="card-title"><?= $post['title'] ?></h3>
                        <p><?= substr(strip_tags($post['body']), 0, 70)  ?></p>
                        <p class="card-text"><small class="text-muted"><?= strftime("%a %d %b %Y", strtotime($post['created_at'])) ?></small></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach ?>
</div>

<div class="row">
    <div class="col-md-12 d-flex justify-content-center py-4">
        <?= $links ?>
    </div>
</div>