<?php build('content')?>
<?php Flash::show()?>
<div class="row">
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-6 grid-margin">
				<div class="card">
					<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Total Catalogs</h6>
					</div>
					<div class="row">
						<div class="col-6 col-md-12 col-xl-5">
							<h3 class="mb-2"><?php echo $totalCatalog?></h3>
							<div class="d-flex align-items-baseline"></div>
						</div>
					</div>
					</div>
				</div>
			</div>

			<div class="col-md-6 grid-margin">
				<div class="card">
					<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Total Users</h6>
					</div>
					<div class="row">
						<div class="col-6 col-md-12 col-xl-5">
							<h3 class="mb-2"><?php echo $totalUser?></h3>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
		<?php echo divider(40)?>
	</div>

	<div class="col-md-4 grid-margin">
		<div class="card">
			<div class="card-body">
			<div class="d-flex justify-content-between align-items-baseline">
				<h6 class="card-title mb-0">Trends</h6>
			</div>
			<?php if($trends) :?>
				<ol>
					<?php foreach($trends as $key => $row) :?>
						<li><?php echo wCatalogToStringToLink($row->value, _route('item:index'), $row->category)?></li>
					<?php endforeach?>
				</ol>
			</div>
			<?php else:?>
				<p>No Trends</p>
			<?php endif?>
		</div>
	</div>
</div>
<?php endbuild()?>
<?php loadTo()?>