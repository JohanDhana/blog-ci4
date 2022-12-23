<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Page</h3>
                    </div>
                    <div class="card-body">
                        <?php echo form_open_multipart('posts/update/' . $page['id']); ?>
                        <div class="form-group mb-3 ">
                            <label class="form-label">Page title</label>
                            <div>
                                <input type="text" class="form-control" name="title" value="<?= $page['title'] ?>">
                            </div>
                        </div>
                        <div class="form-group mb-3 ">
                            <label class="form-label">Icon</label>
                            <div>
                                <input type="file" class="form-control" accept="image/png, image/svg, image/jpeg" name="post_icon" id="post_icon" placeholder="Enter title">
                            </div>
                            <?php if ($page['post_icon']) {
                                echo '<img src="' . base_url('assets/images/posts/' . $page['post_icon']) . '" width="75px" class="pt-3">';
                            } ?>
                        </div>

                        <div class="form-group mb-3 ">
                            <label class="form-label">Post Image</label>
                            <div>
                                <input type="file" class="form-control" accept="image/png, image/svg, image/jpeg" name="post_image" id="post_image" placeholder="Enter title">
                            </div>
                            <?php if ($page['post_image']) {
                                echo '<img src="' . base_url('assets/images/posts/' . $page['post_image']) . '" width="75px" class="pt-3">';
                            } ?>
                        </div>

                        <?php if (!$page['is_parent']) { ?>
                            <div class="form-group mb-3 ">
                                <label class="form-label">Parent Post</label>
                                <div>
                                    <select class="form-control" name="parent_id" id="post_icon" placeholder="Enter title">
                                        <option value="">No parent</option>

                                        <?php foreach ($posts as $post) : ?>
                                            <option <?php if ($page['parent'] === $post['id']) echo 'selected'  ?> value="<?= $post['id'] ?>"><?= $post['title'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="form-group mb-3 ">
                            <label class="form-label">Tags</label>
                            <div>
                                <input type="text" class="form-control" name="tags" placeholder="Enter tags " value="<?= $page['tags'] ?>">
                            </div>
                        </div>
                        <div class="form-group mb-3 ">
                            <label class="form-label">Post Categories</label>
                            <div>
                                <select class="form-control" name="categories[]" id="categories" multiple="multiple" placeholder="Enter title">
                                    <option value="">No category</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option <?= $category['selected'] ? 'selected' : '' ?> value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-3 ">
                            <label class="form-label">SEO title</label>
                            <div>
                                <input type="text" class="form-control" name="seo_title" placeholder="Enter seo title" value="<?= $page['seo_title'] ?>">
                            </div>
                        </div>
                        <div class="form-group mb-3 ">
                            <label class="form-label">SEO description</label>
                            <div>
                                <textarea type="text" class="form-control" name="seo_description" placeholder="Enter seo description"><?= $page['seo_description'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-3 ">
                            <label class="form-label">SEO Schema</label>
                            <div>
                                <textarea rows="5" type="text" class="form-control" name="seo_schema" id="schema" placeholder="Enter seo Schema"><?= $page['seo_schema'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-3 ">
                            <label class="form-label">Page content</label>
                            <textarea id="summernote" name="body"><?= html_escape($page['body'])  ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-check form-switch">
                                <input class="form-check-input" name="on_homepage" type="checkbox" <?= $page['on_homepage']  ? 'checked' : '' ?>>
                                <span class="form-check-label">Show on homepage</span>
                            </label>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>