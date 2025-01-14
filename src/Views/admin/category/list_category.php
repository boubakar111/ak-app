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
			<h3 class="card-title">List des Category</h3>
			<?php

			if ($_SESSION['uType'] == 1) : ?>
				<div class="card-tools">
				<a href="<?php echo base_url ?>admin/category/editCategory" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span> Ajouter une category</a>
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
					<col width="25%">
					<col width="25%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-purple text-light">
						<th>#</th>
						<th>Date Created</th>
						<th>Name</th>
						<th>Description</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
						<tbody>
							<?php

						// var_dump( $params['category']);die;
							$cnt = 1;

							foreach ($params['category'] as $category) : ?>
								<tr>
									<td class="text-center"><?= htmlentities($cnt++); ?></td>
									<td class=""><?= date("Y-m-d H:i", strtotime($category->date_created)) ?></td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($category->name) ?></p>
									</td>
									<td class="">
										<p class="m-0 truncate-1"><?= htmlentities($category->description) ?></p>
									</td>
									<td class="text-center">
										<?php
										switch ($category->status) {
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
											<a class="dropdown-item view_data" href="<?php echo base_url ?>admin/category/view_category/<?= $category->id ?>" data-id=""><span class="fa fa-eye text-dark"></span> View</a>
											<?php if ($_SESSION['uType'] == 1) : ?>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item edit_data" href="<?php echo base_url ?>admin/category/editCategory/<?= $category->id ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?= $category->id ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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

	function delete_category($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_category",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>