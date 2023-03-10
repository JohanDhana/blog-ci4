<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Post List
                </h2>
            </div>
        </div>
    </div>
</div>


<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter table-mobile-md card-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pages as $page) : ?>

                                    <tr>
                                        <td data-label="Title">
                                            <div> <?= $page['title'] ?></div>
                                        </td>
                                        <td class="text-muted" data-label="Role">
                                            <?= substr(strip_tags($page['body']), 0, 15) ?> </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="<?= base_url('posts/update/' . $page['id']) ?>" class="btn btn-white">
                                                    Edit
                                                </a>
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class=" dropdown-item" href="<?= base_url('posts/delete/' . $page['id']) ?>">
                                                            Delete
                                                        </a>
                                                        <a class="dropdown-item" target="_blank" href="<?= base_url('post/' . $page['slug']) ?>">
                                                            View on page
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                    <?php if ($pager) { ?>
                        <div class="card-footer d-flex align-items-center">
                            <?= $pager->links() ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>