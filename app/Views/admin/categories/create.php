<div class="page-body">
	<div class="container-xl">
		<div class="row row-cards">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Create Category</h3>
					</div>
					<div class="card-body">
						<?php echo form_open_multipart('categories/create'); ?>
						<div class="form-group mb-3 ">
							<label class="form-label">Category title</label>
							<div>
								<input type="text" class="form-control" name="name" placeholder="Enter title">
							</div>
						</div>
						<div class="form-group mb-3 ">
							<label class="form-label">Icon</label>
							<div>
								<input type="file" class="form-control" accept="image/png, image/svg, image/jpeg" name="post_icon" id="post_icon" placeholder="Enter title">
							</div>
						</div>
						<div class="form-group mb-3 ">
							<label class="form-label">Post Image</label>
							<div>
								<input type="file" class="form-control" accept="image/png, image/svg, image/jpeg" name="post_image" id="post_image" placeholder="Enter title">
							</div>
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