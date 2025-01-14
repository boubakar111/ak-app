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



	<div class="card card-outline card-purple rounded-0 shadow">
	<div class="card-header">
    <h3 class="card-title <?= isset($params['category']) && $params['category'] !== false ? 'text-primary' : 'text-danger'; ?>">
        <?= isset($params['category']) && $params['category'] !== false ? 'Modifier la catégorie' : 'Ajouter une catégorie'; ?>
    </h3>
</div>
		<?php
		$message = $params['message'];
		if (isset($message) && !empty($message)): ?>
			<div
				class="alert <?= $message['type'] === 'success' ? 'alert-success' : 'alert-danger'; ?>"
				role="alert"
				id="temporaryMessage">
				<?= htmlentities($message['text']); ?>
			</div>
		<?php endif; ?>
		<div class="card-body">
			<div class="container-fluid">
				<div class="container-fluid">
					<form method="POST" action="<?php echo base_url ?>admin/category/editCategory">
						<!-- Champ caché pour l'ID si modification -->
						<?php
						$category = $params['category'];
						if (isset($category)): ?>
							<input type="hidden" name="id" value=<?= htmlentities($category->id) ?>>
						<?php endif; ?>

						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="nomCategory">Nom de la Catégorie</label>
								<input
									type="text"
									class="form-control"
									id="nomCategory"
									name="nomCategory"
									value="<?= isset($category) ? htmlentities($category->name) : '' ?>"
									required>
							</div>
						</div>

						<div class="form-group">
							<label for="descCategory">Description de la Catégorie</label>
							<textarea
								rows="10"
								cols="50"
								class="form-control"
								id="descCategory"
								name="descCategory"
								required><?= isset($category) ? htmlentities($category->description) : '' ?></textarea>
						</div>

						<div class="form-row">
							<div class="form-group col-md-4">
								<label for="inputState">Statut</label>
								<select id="inputState" name="status" class="form-control" required>
									<option value="" selected disabled>Sélectionner une valeur</option>
									<option value="1" <?= isset($category) && $category->status == 1 ? 'selected' : '' ?>>Active</option>
									<option value="0" <?= isset($category) && $category->status == 0 ? 'selected' : '' ?>>Non active</option>
								</select>
							</div>
						</div>

						<button type="submit" class="btn btn-primary"><?= isset($category) ? 'Modifier' : 'Ajouter' ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<script>
	document.querySelector('form').addEventListener('submit', function(e) {
		const state = document.getElementById('inputState').value;
		if (state === "") {
			e.preventDefault();
			alert("Veuillez sélectionner une valeur avant de soumettre.");
		}
	});

	// Attendre que le DOM soit chargé
	document.addEventListener('DOMContentLoaded', function() {
		// Cible l'élément avec l'ID "temporaryMessage"
		var messageElement = document.getElementById('temporaryMessage');
		if (messageElement) {
			// Masquer l'alerte après 5 secondes
			setTimeout(function() {
				messageElement.style.display = 'none';
			}, 5000); // 5000 ms = 5 secondes
		}
	});
</script>