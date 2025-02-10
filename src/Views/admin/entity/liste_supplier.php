<?php
session_start();
error_reporting(0);
//var_dump($_SESSION ["uType"]);die;

if (isset($_SESSION['username']) && isset($_SESSION['uType'])) {
	header('location:' . ADMIN_LOGIN_URL);
	exit();
} else { ?>
	<!-- Top Bar Start -->
	<?php include_once(__DIR__ . '/../../../inc/topBarNav.php'); ?>
	<!-- ========== Left Sidebar Start ========== -->
	<?php include_once(__DIR__ . '/../../../inc/navigation.php'); ?>

	<style>
		.img-thumb-path {
			width: 100px;
			height: 80px;
			object-fit: scale-down;
			object-position: center center;
		}
	</style>
	<div class="card card-outline card-purple rounded-0 shadow">
		<div class="card-header">
			<h3 class="card-title">List des Entity</h3>
			<?php

			if ($_SESSION['uType'] == 1) : ?>
				<div class="card-tools">
				<a href="<?php echo base_url ?>admin/entity/manageSupplier" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span> Ajouter une Entitie</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<div class="container-fluid">
					<table class="table table-bordered table-hover table-striped">
						<colgroup>
							<col width="5%">
							<col width="10%">
							<col width="15%">
							<col width="30%">
							<col width="15%">
							<col width="15%">
							<col width="15%">
							<col width="15%">
						</colgroup>
						<thead>
							<tr class="bg-gradient-purple text-light">
								<th>#</th>
								<th>Date Created</th>
								<th>Nom entrempise</th>
								<th>Adresse</th>
								<th>Nom Gerant </th>
								<th>Tel Gerant </th>
								<th>Entitie </th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php

							// var_dump( $params);die;
							$cnt = 1;

							foreach ($params['suppliers'] as $supplier) : ?>
								<tr>
									<td class="text-center"><?= htmlentities($cnt++); ?></td>
									<td class=""><?= date("Y-m-d H:i", strtotime($supplier->date_creation)) ?></td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($supplier->nom_entreprise) ?></p>
									</td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($supplier->adresse."-".$supplier->ville ."-".$supplier->code_postal) ?></p>
									</td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($supplier->contact_nom) ?></p>
									</td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($supplier->contact_telephone) ?></p>
									</td>
									<td class="text-center">
										<?php
										switch ($supplier->designation) {
											case 1:
												echo '<span class="rounded-pill badge badge-info bg-gradient-info px-3">Client</span>';
												break;
											case 2:
												echo '<span class="rounded-pill badge badge-warning bg-gradient-warning px-3">Fournisseur</span>';
												break;
										}
										?>
									</td>
									<td class="text-center">
										<?php
										switch ($supplier->statut) {
											case 1:
												echo '<span class="rounded-pill badge badge-success bg-gradient-teal px-3">Active</span>';
												break;
											case 0:
												echo '<span class="rounded-pill badge badge-danger bg-gradient-danger px-3">Inactive</span>';
												break;
										}
										?>
									</td>
									<td align="center">
										<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
											Action
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu" role="menu">
											<a class="dropdown-item view_data" href="<?php echo base_url ?>admin/entity/view_supplier/<?= $supplier->id ?>" data-id="<?= $supplier->id ?>"><span class="fa fa-eye text-dark"></span> View</a>
											<?php if ($_SESSION['uType'] == 1) : ?>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item edit_data" href="<?php echo base_url ?>admin/entity/editSupplier/<?= $supplier->id ?>" data-id="<?= $supplier->id ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_data" href="<?php echo base_url ?>admin/entity/deletSupplier/<?= $supplier->id ?>" data-id="<?= $supplier->id ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
											<?php endif; ?>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<script>
$(document).ready(function() {

		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: 5
			}],
		});
})
</script>