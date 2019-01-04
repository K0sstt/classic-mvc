<div class="row">
	<div class="col-4"></div>
	<div class="col-4">
		<a class="btn btn-lg btn-block btn-outline-secondary mt-3 text-uppercase font-weight-bold" href="/">Back</a>
		<ul class="list-group m-3">
			<?php foreach ($vars as $film): ?>
				<li class="list-group-item"><i><?php echo $film['title']; ?></i></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="col-4"></div>
</div>