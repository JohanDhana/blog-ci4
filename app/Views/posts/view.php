<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
	<h1 class="h2 text-capitalize"><?= $post['title'] ?></h1>
</div>

<?= $post['body'] ?>

<script type="application/ld+json">
	<?= $post['seo_schema'] ?>
</script>