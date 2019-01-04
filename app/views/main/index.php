<div class="row">
	<div class="col-4">
		<form class="border border-dark mt-2" action="import" method="post" enctype="multipart/form-data">
			<div class="form-group m-2">
				<label for="exampleFormControlFile1"><h4>Example file input</h4></label>
				<input name="films" type="file" class="form-control-file" id="exampleFormControlFile1">
				<button type="submit" class="btn btn-primary mt-2">Import file</button>
			</div>
		</form>
	</div>
	<div class="col-8"></div>
</div>

<div class="row">
	<?php foreach($vars as $film): ?>
		<div class="col-3">
			<div id="<?php echo $film['id']; ?>" class="card text-center">
				<div class="card-body">
					<h5 class="card-title"><?php echo $film['title']; ?></h5>
					<p class="card-text"><?php echo $film['format']; ?></p>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-<?php echo $film['id'] ?>">
						More
					</button>
				</div>
			</div>

			<div class="modal fade" id="modal-<?php echo $film['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal-<?php echo $film['id'] ?>Label" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modal-<?php echo $film['id'] ?>Label">Modal title</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							...
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						</div>
					</div>
				</div>
			</div>

		</div>

		

	<?php endforeach; ?>
</div>
