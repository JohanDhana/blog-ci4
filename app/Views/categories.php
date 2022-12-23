<div class="container">
    <div class="row text-center">
        <?php foreach ($categories as $category) : ?>
            <div class="col-md-3"><a href="<?= base_url('categories/' . $category['slug'] . '/posts') ?>"> <span class="text-capitalize"><?= $category['name'] ?></span></a></div>
        <?php endforeach ?>
    </div>
</div>