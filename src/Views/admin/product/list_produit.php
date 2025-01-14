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
			<h3 class="card-title">List des Produits</h3>
			<?php

			if ($_SESSION['uType'] == 1) : ?>
				<div class="card-tools">
				<a href="<?php echo base_url ?>admin/product/manage_price" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span> Ajouter un produit</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<div class="container-fluid">
					<table class="table table-bordered table-hover table-striped">
						<colgroup>
							<col width="5%">
							<col width="15%">
							<col width="20%">
							<col width="15%">
							<col width="15%">
							<col width="15%">
							<col width="15%">
						</colgroup>
						<thead>
							<tr class="bg-gradient-purple text-light">
								<th>#</th>
								<th>Date Created</th>
								<th>Categorie</th>
								<th>Nom produit</th>
								<th>Ref </th>
								<th>Nb Cartons </th>
								<th>Nb Pcs Carton </th>
								<th>Prix d'achat </th>
								<th>Px vente détail </th>
								<th>Px vente Gros </th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php

							// var_dump( $params);die;
							$cnt = 1;

							foreach ($params['product'] as $product) : ?>
								<tr>
									<td class="text-center"><?= htmlentities($cnt++); ?></td>
									<td class=""><?= date("Y-m-d H:i", strtotime($product->date_created)) ?></td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($product->category) ?></p>
									</td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($product->designiation_produit) ?></p>
									</td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($product->ref_produit) ?></p>
									</td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($product->nb_carton) ?></p>
									</td>
									<td class="">
										<p class="m-0 truncate-1"><?= $product->nb_pcs_carton ?></p>
									</td>
									<td class="text-right"><?= number_format($product->prix_achat, 2) ?></td>
									<td class="text-right"><?= number_format($product->prix_vente_detail, 2) ?></td>
									<td class="text-right"><?= number_format($product->prix_vente_gros, 2) ?></td>
									<td class="text-center">
										<?php
										switch ($product->status) {
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
											<a class="dropdown-item view_data" href="<?php echo base_url ?>admin/product/view_product/<?= $product->id ?>" data-id="<?= $product->id ?>"><span class="fa fa-eye text-dark"></span> View</a>
											<?php if ($_SESSION['uType'] == 1) : ?>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item edit_data" href="<?php echo base_url ?>admin/product/editProduct/<?= $product->id ?>" data-id="<?= $product->id ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_data" href="<?php echo base_url ?>admin/product/delet_product/<?= $product->id ?>" data-id="<?= $product->id ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
	
		$('#create_new').click(function() {
			console.log('111')
			uni_modal("Ajouter un nouveau produit", "manage_price.php")
		})
		$('.view_data').click(function() {
			uni_modal("Détails du produit", "prices/view_price.php?id=" + $(this).attr('data-id'))
		})
		$('.edit_data').click(function() {
			uni_modal("Mettre à jour les détails du produit", "prices/manage_price.php?id=" + $(this).attr('data-id'))
		})
		$('.delete_data').click(function() {
			_conf("Etes-vous sûr de supprimer ce prix définitivement?", "delete_price", [$(this).attr('data-id')])
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: 5
			}],
		});
	})
	function delete_price($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_price",
			method: "POST",
			data: {
				id: $id
			},
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("An error occured.", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("An error occured.", 'error');
					end_loader();
				}
			}
		})
	}
</script>
<!-- Include the modal structure if not already included -->
<div class="modal fade" id="uniModal" tabindex="-1" aria-labelledby="uniModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uniModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modalForm">
                        <div class="form-group">
                            <label for="productName">Nom du produit</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="form-group">
                            <label for="productPrice">Prix du produit</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                        </div>
                        <div class="form-group">
                            <label for="productDescription">Description du produit</label>
                            <textarea class="form-control" id="productDescription" name="productDescription" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
