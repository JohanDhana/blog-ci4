<div class="container-xl">
    <!-- category title -->
    <div class="category-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="category-title">
                    Category List
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
                                    <th>Slug</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category) : ?>

                                    <tr>
                                        <td data-label="Title">
                                            <div> <?= $category['name'] ?></div>
                                        </td>
                                        <td class="text-muted" data-label="Role">
                                            <?= $category['slug'] ?> </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="<?= base_url('categories/update/' . $category['id']) ?>" class="btn btn-white">
                                                    Edit
                                                </a>
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class=" dropdown-item" href="<?= base_url('categories/delete/' . $category['id']) ?>">
                                                            Delete
                                                        </a>
                                                        <a class="dropdown-item" target="_blank" href="<?= base_url('post/' . $category['slug']) ?>">
                                                            View on category
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
                    <?php if ($links) { ?>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">Showing <span>
                                    <?= (($category_nr - 1) * $limit) + 1  ?></span> to <span><?= (($category_nr  * $limit) < $total) ? $category_nr  * $limit : $total   ?></span> of <span><?= $total ?></span> entries</p>
                            <?= $links ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>