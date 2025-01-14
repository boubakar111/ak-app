<?php
session_start();
error_reporting(0);

if (isset($_SESSION['username']) && isset($_SESSION['uType'])) {
    header('location:' . ADMIN_LOGIN_URL);
    exit();
} else { ?>
    <!-- Top Bar Start -->
    <?php include_once(__DIR__ . '/../../../inc/topBarNav.php'); ?>
    <!-- ========== Left Sidebar Start ========== -->
    <?php include_once(__DIR__ . '/../../../inc/navigation.php'); ?>

    <style>
        /* Styles pour la div de message */
        #message-container {
            font-size: 16px;
            text-align: center;
            transition: opacity 0.5s ease-in-out;
        }

        #message-container.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        #message-container.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
    <?php 
          $product = $params['product']; 
          $categories = $params['category'];
    ?>
    <div class="card card-outline card-purple rounded-0 shadow">
        <div class="card-header">
            <h3 class="card-title"><?= isset($product) ? "Modifier un Produit" : "Ajouter un Produit"; ?></h3>
        </div>
        <div class="card-body">
            <div id="message-container" style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 5px;"></div>
            <div class="container-fluid">
                <form action="" method="POST" id="price-form">
                    <!-- Champ caché pour l'ID -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($product->id ?? '', ENT_QUOTES, 'UTF-8') ?>">

                    <div class="form-row">
                        <!-- Categorie -->
                        <div class="form-group col-md-3">
                            <label for="category_id" class="control-label">Categorie</label>
                            <select name="category_id" id="category_id" class="form-control form-control-border select2" required>
                                <option value="" disabled <?= !$product ? 'selected' : ''; ?>>Choisissez une catégorie</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category->id, ENT_QUOTES, 'UTF-8') ?>"
                                        <?= ($product && $product->category_id == $category->id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Nom du Produit -->
                        <div class="form-group col-md-6">
                            <label for="nom_Produit" class="control-label">Nom du Produit</label>
                            <input type="text" name="nom_produit" id="nom_Produit" class="form-control form-control-border"
                                placeholder="Nom du produit.."
                                value="<?= htmlspecialchars($product->nom_produit ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <!-- Référence -->
                        <div class="form-group col-md-3">
                            <label for="ref_produit" class="control-label">Référence</label>
                            <input type="number" name="ref_produit" id="ref_produit" class="form-control form-control-border"
                                placeholder="Référence produit.."
                                value="<?= htmlspecialchars($product->ref_produit ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>

                    <!-- Designation -->
                    <div class="form-group">
                        <label for="designiation" class="control-label">Designiation du produit</label>
                        <textarea name="designiation_produit" id="designiation" class="form-control form-control-border"
                            placeholder="Designiation du produit" required><?= htmlspecialchars($product->designiation_produit ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="row">
                        <!-- Unité de Mesure -->
                        <div class="form-group col-md-4">
                        <label for="unite" class="control-label">Unité de Mésure</label>
                        <select name="unite_mesure" id="mesure" class="form-control form-control-sm form-control-border" required>
                            <option value="">--- Sélectionnez l'unité de mésure</option>
                            <option value="1" <?= $product && $product->unite_mesure== 1 ? 'selected' : ''; ?>>Carton</option>
                            <option value="0" <?= $product && $product->unite_mesure == 0 ? 'selected' : ''; ?>>Unité</option>
                        </select>
                    </div>
                        <!-- Nombre de Cartons -->
                        <div class="form-group col-md-4"id="nbCarton">
                            <label for="nb_carton" class="control-label">Nombre de Cartons</label>
                            <input type="number" name="nb_carton"  class="form-control form-control-border"
                                value="<?= htmlspecialchars($product->nb_carton ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <!-- Nombre de Pièces par Carton -->
                        <div class="form-group col-md-4"id="nbPcsCarton" >
                            <label for="nb_pcs_carton" class="control-label">Nombre de Pièces par Carton</label>
                            <input type="number" name="nb_pcs_carton" class="form-control form-control-border"
                                value="<?= htmlspecialchars($product->nb_pcs_carton ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <div class="form-group col-md-4" id="nbPcs" style="display:none;">
                                <label for="nbPcs" class="control-label">Nombre de Piece</label>
                                <input type="text" name="nb_piece" id="nbPcs" class="form-control form-control-border" placeholder="" value="<?php echo isset($nb_piece) ? $nb_piece : 0 ?>" required>
                            </div>
                    </div>

                    <div class="row">
                        <!-- Prix d'Achat -->
                        <div class="form-group col-md-4">
                            <label for="prix_achat" class="control-label">Prix D'achat</label>
                            <input type="number" step="any" name="prix_achat" id="prix_achat" class="form-control form-control-border"
                                value="<?= htmlspecialchars($product->prix_achat ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <!-- Prix de Vente Détail -->
                        <div class="form-group col-md-4">
                            <label for="prix_vente_detail" class="control-label">Prix de Vente Détail</label>
                            <input type="number" step="any" name="prix_vente_detail" id="prix_vente_detail" class="form-control form-control-border"
                                value="<?= htmlspecialchars($product->prix_vente_detail ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <!-- Prix de Vente Gros -->
                        <div class="form-group col-md-4">
                            <label for="prix_vente_gros" class="control-label">Prix de Vente Gros</label>
                            <input type="number" step="any" name="prix_vente_gros" id="prix_vente_gros" class="form-control form-control-border"
                                value="<?= htmlspecialchars($product->prix_vente_gros ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Stock Initial -->
                        <div class="form-group col-md-4">
                            <label for="stock_initial" class="control-label">Stock Initial</label>
                            <input type="number" name="stock_initial" id="stock_initial" class="form-control form-control-border"
                                value="<?= htmlspecialchars($product->stock_initial ?? 0, ENT_QUOTES, 'UTF-8') ?>" disabled>
                        </div>
                        <!-- Stock de Sécurité -->
                        <div class="form-group col-md-4">
                            <label for="stock_securite" class="control-label">Stock de Sécurité</label>
                            <input type="number" name="stock_securite" id="stock_securite" class="form-control form-control-border"
                                value="<?= htmlspecialchars($product->stock_securite ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status" class="control-label">Status</label>
                        <select name="status" id="status" class="form-control form-control-border" required>
                            <option value="1" <?= ($product && $product->status == 1) ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= ($product && $product->status == 0) ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <!-- Bouton de soumission -->
                    <button type="submit"   class="btn btn-primary" id="submit-btn">
                        <?= isset($product->id) ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

<?php } ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageContainer = document.createElement('div');
        messageContainer.id = 'message-container';
        messageContainer.style.display = 'none';
        messageContainer.style.padding = '10px';
        messageContainer.style.marginBottom = '15px';
        messageContainer.style.borderRadius = '5px';
        messageContainer.style.textAlign = 'center';
        document.querySelector('.card-body').insertBefore(messageContainer, document.querySelector('.container-fluid'));

        document.getElementById('submit-btn').addEventListener('click', function(e) {
            e.preventDefault();
            var form = document.getElementById('price-form');
            var formData = new FormData(form);

            // Convertir FormData en objet JSON
            var formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });

            console.log('Form Data:', formObject);

            // Envoyer les données via fetch
            fetch(_base_url_ + 'admin/product/manage_price', {
                    method: 'POST',
                    body: JSON.stringify(formObject),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    // Vérification correcte du statut de la réponse
                    if (data.status === "success") {
                        showMessage(data.message, 'success'); // Affiche un message de succès
                    } else {
                        showMessage(data.message || "Une erreur est survenue.", 'error'); // Affiche un message d'erreur
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // Afficher un message d'erreur si la requête échoue
                    showMessage("Une erreur s'est produite lors de l'envoi des données.", 'error');
                });
        });

        /**
         * Fonction pour afficher un message dans la div
         * @param {string} message - Le message à afficher
         * @param {string} type - Le type de message ('success' ou 'error')
         */
        function showMessage(message, type) {
            messageContainer.textContent = message;
            messageContainer.className = type; // Ajouter une classe ('success' ou 'error')
            messageContainer.style.display = 'block';
            messageContainer.style.opacity = '1';

            // Appliquer des styles différents pour success et error
            if (type === 'success') {
                messageContainer.style.backgroundColor = '#d4edda';
                messageContainer.style.color = '#155724';
                messageContainer.style.border = '1px solid #c3e6cb';
            } else if (type === 'error') {
                messageContainer.style.backgroundColor = '#f8d7da';
                messageContainer.style.color = '#721c24';
                messageContainer.style.border = '1px solid #f5c6cb';
            }

            // Masquer le message après 5 secondes
            setTimeout(() => {
                messageContainer.style.opacity = '0'; // Début de la transition
                setTimeout(() => {
                    messageContainer.style.display = 'none'; // Cache complètement
                }, 500); // Attendre que l'opacité devienne 0
            }, 5000);
        }

        // Gérer l'affichage des champs en fonction du choix de l'utilisateur
        var selectOption = document.getElementById("mesure");
        var nbCarton = document.getElementById("nbCarton");
        var nbPcsCarton = document.getElementById("nbPcsCarton");
        var nbPcs = document.getElementById("nbPcs");

        // Fonction pour afficher la div correspondante en fonction de l'option sélectionnée
        selectOption.addEventListener("change", function() {
            var selectedOption = selectOption.value;

            // Afficher la div correspondante à l'option sélectionnée
            if (selectedOption === "1") {
                nbCarton.style.display = "block";
                nbPcsCarton.style.display = "block";
                nbPcs.style.display = "none";
            } else if (selectedOption === "0") {
                nbPcs.style.display = "block";
                nbCarton.style.display = "none";
                nbPcsCarton.style.display = "none";
            } else if (selectedOption === "") {
                nbPcs.style.display = "none";
                nbCarton.style.display = "none";
                nbPcsCarton.style.display = "none";
            }
        });
    });
</script>