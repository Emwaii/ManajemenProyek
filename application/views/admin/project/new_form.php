<!DOCTYPE html>
<html lang="en">

<head>
	<?php $this->load->view("admin/_partials/head.php") ?>
</head>

<body id="page-top">

	<?php $this->load->view("admin/_partials/navbar.php") ?>
	<div id="wrapper">

		<?php $this->load->view("admin/_partials/sidebar.php") ?>

		<div id="content-wrapper">

			<div class="container-fluid">

				<?php $this->load->view("admin/_partials/breadcrumb.php") ?>

				<?php if ($this->session->flashdata('success')): ?>
				<div class="alert alert-success" role="alert">
					<?php echo $this->session->flashdata('success'); ?>
				</div>
				<?php endif; ?>

				<div class="card mb-3">
					<div class="card-header">
						<a href="<?php echo site_url('admin/projects/') ?>"><i class="fas fa-arrow-left"></i> Back</a>
					</div>
					<div class="card-body">

						<form action="<?php base_url('admin/project/add') ?>" method="post" enctype="multipart/form-data" >
							<div class="form-group">
								<label for="name">Name*</label>
								<input class="form-control <?php echo form_error('name') ? 'is-invalid':'' ?>"
								 type="text" name="name" placeholder="Project name" />
								<div class="invalid-feedback">
									<?php echo form_error('name') ?>
								</div>
							</div>

							<div class="form-group">
								<label for="price">Price*</label>
								<input class="form-control <?php echo form_error('price') ? 'is-invalid':'' ?>"
								 type="number" name="price" min="0" placeholder="Project price" />
								<div class="invalid-feedback">
									<?php echo form_error('price') ?>
								</div>
							</div>


							<!-- <div class="form-group">
								<label for="name">Photo</label>
								<input class="form-control-file <?php echo form_error('price') ? 'is-invalid':'' ?>"
								 type="file" name="image" />
								<div class="invalid-feedback">
									<?php echo form_error('image') ?>
								</div>
							</div> -->

							<div class="form-group">
								<label for="name">Description*</label>
								<textarea class="form-control <?php echo form_error('description') ? 'is-invalid':'' ?>"
								 name="description" placeholder="Project description..."></textarea>
								<div class="invalid-feedback">
									<?php echo form_error('description') ?>
								</div>
							</div>

							<div class="form-group">
  								<label for="name">Date Start*</label>
								<input class="form-control <?php echo form_error('project_started') ? 'is-invalid':'' ?>" 
								type="date" name="project_started" placeholder="Project Started">
								<div class="invalid-feedback">
									<?php echo form_error('project_started') ?>
								</div>
							</div>

							<div class="form-group">
  								<label for="name">Date Ended*</label>
								<input class="form-control <?php echo form_error('project_ended') ? 'is-invalid':'' ?>" 
								type="date" name="project_ended" placeholder="Project Ended">
								<div class="invalid-feedback">
									<?php echo form_error('project_ended') ?>
								</div>
							</div>

							<div>
								<label for="name">Pilih Client*</label>
							</div>
							<div class="form-group input-group">
								<input type="hidden" name="client_id" id="client_id">
								<input type="text" name="client_name" id="client_name" class="form-control <?php echo form_error('client_id') ? 'is-invalid':'' ?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-item">
										<i class="fa fa-search"></i>
									</button>
								</span>
								<div class="invalid-feedback">
									<?php echo form_error('client_id') ?>
								</div>
							</div>

							<div class="form-group">
								<label>Status</label>
								<select name="proj_status_id" class="form-control">
									<?php foreach ($projects_status as $project_status) {
										echo '<option value="'.$project_status->proj_status_id.'">'.$project_status->status.'</option>';
									}?>
								</select>
							</div>

							<input class="btn btn-success" type="submit" name="btn" value="Save"/>
						</form>

					</div>

					<div class="card-footer small text-muted">
						* required fields
					</div>


				</div>
				<!-- /.container-fluid -->

				<!-- Sticky Footer -->
				<?php $this->load->view("admin/_partials/footer.php") ?>

			</div>
			<!-- /.content-wrapper -->

		</div>
		<!-- /#wrapper -->


		<?php $this->load->view("admin/_partials/scrolltop.php") ?>

		<?php $this->load->view("admin/_partials/js.php") ?>

</body>

</html>

<!-- Select Client-->
<div class="modal fade bd-example-modal-lg" id="modal-item">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	  	<h4 class="modal-title">Select Client</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body table-responsive">
        <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>Name</th>
					<th>Address</th>
					<th>Industry</th>
					<th>Email</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($clients as $client): ?>
				<tr>
					<td><?php echo $client->name ?></td>
					<td><?php echo $client->address ?></td>
					<td><?php echo $client->industry ?></td>
					<td><?php echo $client->email ?></td>
					<td>
						<button class="btn btn-xs btn-info" id="select" data-id="<?php echo $client->client_id ?>"
						data-name="<?php echo $client->name ?>">
							<i class="fa fa-check"></i> Select
						</button>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
	$(document).on('click','#select', function() {
		var client_id = $(this).data('id');
		var client_name = $(this).data('name');
		$('#client_id').val(client_id);
		$('#client_name').val(client_name);
		$('#modal-item').modal('hide');
	})
})
</script>