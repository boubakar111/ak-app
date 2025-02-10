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
    $suppliers = $params['supplier'];
    //var_dump(  $product);die;
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
                    <fieldset class="border p-3">
                        <legend class="w-auto">Information Fournisseur</legend>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <select name="fournisseur_id" id="fournisseur_id" class="form-control form-control-border select2">
                                    <option value="" <?= (!$product || !$product->fournisseur_id) ? 'selected' : '' ?>>Choisissez un fournisseur</option>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?= htmlspecialchars($supplier->id, ENT_QUOTES, 'UTF-8') ?>"
                                            <?= ($product && $product->fournisseur_id == $supplier->id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($supplier->nom_entreprise, ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border p-2">
                        <legend class="w-auto"> Information Produit</legend>
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
                                <?php if (empty($product->ref_produit)): ?>
                                    <input type="checkbox" aria-label="Checkbox for following text input">
                                <?php endif; ?>
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
                    </fieldset>

                    <fieldset class="border p-3">
                        <legend class="w-auto"> Information Quantite</legend>
                        <div class="row">
                            <!-- Unité de Mesure -->
                            <div class="form-group col-md-4">
                                <label for="unite" class="control-label">Unité de Mésure</label>
                                <select name="unite_mesure" id="mesure" class="form-control form-control-sm form-control-border" required>
                                    <option value="">--- Sélectionnez l'unité de mésure</option>
                                    <option value="1" <?= $product && $product->unite_mesure == 1 ? 'selected' : ''; ?>>Carton</option>
                                    <option value="0" <?= $product && $product->unite_mesure == 0 ? 'selected' : ''; ?>>Unité</option>
                                </select>
                            </div>
                            <!-- Nombre de Cartons -->
                            <div class="form-group col-md-2" id="nbCarton">
                                <label for="nb_carton" class="control-label">Nombre de Cartons</label>
                                <input type="number" name="nb_carton" class="form-control form-control-border"
                                    value="<?= htmlspecialchars($product->nb_carton ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <!-- Nombre de Pièces par Carton -->
                            <div class="form-group col-md-2" id="nbPcsCarton">
                                <label for="nb_pcs_carton" class="control-label">Nombre de Pièces par Carton</label>
                                <input type="number" name="nb_pcs_carton" class="form-control form-control-border"
                                    value="<?= htmlspecialchars($product->nb_pcs_carton ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="form-group col-md-2" id="nbPcs" style="display:none;">
                                <label for="nbPcs" class="control-label">total Piece</label>
                                <input type="text" name="nb_piece" id="nbPcs" class="form-control form-control-border" placeholder="" value="<?php echo isset($nb_piece) ? $nb_piece : 0 ?>" required>
                            </div>
                            <div class="form-group col-md-3" id="nbtotalPcs">
                                <label for="nb_total_piece" class="control-label">Total Pieces</label>
                                <input type="number" name="nb_total_piece" id="nb_total_piece" class="form-control form-control-border" placeholder="" value="<?php echo isset($product->nb_total_piece) ? $product->nb_total_piece : '' ?>" disabled>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border p-3">
                        <legend class="w-auto"> Information Prix d'achat</legend>
                        <div class="row">
                            <!-- Prix d'Achat -->
                            <div class="form-group col-md-4">
                                <label for="prix_achat_ht" class="control-label">Prix D'achat HT </label>
                                <input type="number" step="any" name="prix_achat_ht" id="prix_achat_ht" class="form-control form-control-border"
                                    value="<?= htmlspecialchars($product->prix_achat_ht ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <!-- Unité de Mesure -->
                            <div class="form-group col-md-4">
                                <label for="unite" class="control-label">Mantant TVA </label>
                                <select name="tva" id="tva" class="form-control form-control-sm form-control-border" required>
                                    <option value="">--- Sélectionnez la tva</option>
                                    <option value="1" <?= $product && $product->unite_mesure == 1 ? 'selected' : ''; ?>>19%</option>
                                    <option value="2" <?= $product && $product->unite_mesure == 0 ? 'selected' : ''; ?>>9%</option>
                                </select>
                            </div>
                            <!-- Prix TTC -->
                            <div class="form-group col-md-4">
                                <label for="prix_achat_ttc" class="control-label">Prix TTC</label>
                                <input type="number" step="any" name="prix_achat_ttc" id="prix_achat_ttc" class="form-control form-control-border"
                                    value="<?= htmlspecialchars($product->prix_achat_ttc ?? 0, ENT_QUOTES, 'UTF-8') ?>" disabled>
                            </div>
                            
                        </div>
                    </fieldset>
                    <fieldset class="border p-3">
                        <legend class="w-auto"> Information Prix de vente</legend>
                        <div class="row">
                            <!-- Prix TTC -->
                            <div class="form-group col-md-4">
                                <label for="prix_vent_ttc" class="control-label">Prix de Vente Détail</label>
                                <input type="number" step="any" name="prix_vente_ttc" id="prix_vente_ttc" class="form-control form-control-border"
                                    value="<?= htmlspecialchars($product->prix_achat_ttc ?? 0, ENT_QUOTES, 'UTF-8') ?>" disabled>
                            </div>
                            <!-- Prix de Vente Détail -->
                            <div class="form-group col-md-3">
                                <label for="marge_benefice" class="control-label">Marge de bénéfice </label>
                                <input type="number" step="any" name="marge_benefice" id="marge_benefice" class="form-control form-control-border"
                                    value="<?= htmlspecialchars($product->marge_benefice ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <!-- Prix de Vente Gros -->
                            <div class="form-group col-md-3">
                                <label for="prix_vente_unite" class="control-label">Prix de Vente /unite</label>
                                <input type="number" step="any" name="prix_vente_unite" id="prix_vente_unite" class="form-control form-control-border"
                                    value="<?= htmlspecialchars($product->prix_vente_unite ?? 0, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                        </div>
                    </fieldset>
                    <!-- stock -->
                    <fieldset class="border p-3">
                        <legend class="w-auto"> Stock</legend>
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
                    </fieldset>

                    <!-- Status -->
                    <fieldset class="border p-3">
                        <legend class="w-auto"> Statut</legend>
                        <div class="form-group">
                            <label for="status" class="control-label">Status</label>
                            <select name="status" id="status" class="form-control form-control-border" required>
                                <option value="1" <?= ($product && $product->status == 1) ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= ($product && $product->status == 0) ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </fieldset>
                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <?= isset($product->id) ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

<?php } ?>



<script>
document.addEventListener("DOMContentLoaded", function () {
    // Sélection des éléments du DOM
    const messageContainer = document.createElement("div");
    const form = document.getElementById("price-form");
    const submitBtn = document.getElementById("submit-btn");

    // Sélection des éléments liés aux prix et TVA
    const prixAchatHT = document.getElementById("prix_achat_ht");
    const tvaSelect = document.getElementById("tva");
    const prixAchatTTC = document.getElementById("prix_achat_ttc");
    const prixVenteTTC = document.getElementById("prix_vente_ttc");
    const margeBeneficeInput = document.getElementById("marge_benefice");
    const prixVenteUnite = document.getElementById("prix_vente_unite");

    // Sélection des éléments pour la gestion des unités et cartons
    const selectOption = document.getElementById("mesure");
    const nbCarton = document.getElementById("nbCarton");
    const nbPcsCarton = document.getElementById("nbPcsCarton");
    const nbTotalPcsDiv = document.getElementById("nbtotalPcs");
    const nbPcs = document.getElementById("nbPcs");
    const nbCartonInput = document.querySelector("input[name='nb_carton']");
    const nbPcsCartonInput = document.querySelector("input[name='nb_pcs_carton']");
    const nbTotalPcsInput = document.querySelector("input[name='nb_total_piece']");

    // Initialisation du container pour les messages de notification
    setupMessageContainer();

    // Event listener pour le formulaire
    submitBtn.addEventListener("click", handleSubmitForm);

    // Gestion de l'affichage des champs selon le type de mesure
    selectOption.addEventListener("change", handleMesureChange);
    nbCartonInput.addEventListener("input", updateTotalPieces);
    nbPcsCartonInput.addEventListener("input", updateTotalPieces);
    prixAchatHT.addEventListener("input", calculerPrixTTC);
    tvaSelect.addEventListener("change", calculerPrixTTC);
    margeBeneficeInput.addEventListener("input", calculerPrixVenteUnite);

    // Initialisation de l'affichage des champs
    selectOption.dispatchEvent(new Event("change"));

    // Fonction pour configurer le conteneur du message
    function setupMessageContainer() {
        messageContainer.id = "message-container";
        messageContainer.style.display = "none";
        messageContainer.style.padding = "10px";
        messageContainer.style.marginBottom = "15px";
        messageContainer.style.borderRadius = "5px";
        messageContainer.style.textAlign = "center";
        document.querySelector(".card-body").insertBefore(messageContainer, document.querySelector(".container-fluid"));
    }
    // Fonction pour afficher les messages de notification
    function showMessage(message, type) {
        messageContainer.textContent = message;
        messageContainer.className = type; // 'success' ou 'error'
        messageContainer.style.display = "block";
        messageContainer.style.opacity = "1";

        // Style différent selon le type
        if (type === "success") {
            messageContainer.style.backgroundColor = "#d4edda";
            messageContainer.style.color = "#155724";
            messageContainer.style.border = "1px solid #c3e6cb";
        } else if (type === "error") {
            messageContainer.style.backgroundColor = "#f8d7da";
            messageContainer.style.color = "#721c24";
            messageContainer.style.border = "1px solid #f5c6cb";
        }

        // Masquer le message après 5 secondes
        setTimeout(() => {
            messageContainer.style.opacity = "0";
            setTimeout(() => {
                messageContainer.style.display = "none";
            }, 500);
        }, 5000);
    }

    // Fonction pour gérer l'envoi du formulaire via AJAX
    function handleSubmitForm(e) {
        e.preventDefault();

        // Activer les champs désactivés avant de soumettre le formulaire
        nbTotalPcsInput.disabled = false;
        prixAchatTTC.disabled = false;

        // Récupérer les données du formulaire
        const formData = new FormData(form);
        const formObject = Object.fromEntries(formData.entries());

        console.log("Form Data:", formObject);

        // Envoyer les données via fetch
        fetch(_base_url_ + "admin/product/manage_price", {
            method: "POST",
            body: JSON.stringify(formObject),
            headers: { "Content-Type": "application/json" },
        })
        .then((response) => response.json())
        .then((data) => {
            console.log("Success:", data);
            showMessage(data.message, data.status === "success" ? "success" : "error");
        })
        .catch((error) => {
            console.error("Error:", error);
            showMessage("Une erreur s'est produite lors de l'envoi des données.", "error");
        })
        .finally(() => {
            // Désactiver à nouveau les champs après la soumission
            nbTotalPcsInput.disabled = true;
            prixAchatTTC.disabled = true;
        });
    }

    // Fonction pour gérer l'affichage des champs en fonction de la mesure choisie
    function handleMesureChange() {
        const selectedOption = selectOption.value;

        if (selectedOption === "1") {
            // Mesure en Cartons
            nbCarton.style.display = "block";
            nbPcsCarton.style.display = "block";
            nbTotalPcsDiv.style.display = "block";
            nbPcs.style.display = "none";
        } else if (selectedOption === "0") {
            // Mesure en Unités
            nbCarton.style.display = "none";
            nbPcsCarton.style.display = "none";
            nbTotalPcsDiv.style.display = "none";
            nbPcs.style.display = "block";
        } else {
            // Aucun choix
            nbCarton.style.display = "none";
            nbPcsCarton.style.display = "none";
            nbTotalPcsDiv.style.display = "none";
            nbPcs.style.display = "none";
        }
    }

    // Fonction pour mettre à jour le nombre total de pièces
    function updateTotalPieces() {
        let nbCartonVal = parseInt(nbCartonInput.value) || 0;
        let nbPcsCartonVal = parseInt(nbPcsCartonInput.value) || 0;
        let totalPieces = nbCartonVal * nbPcsCartonVal;
        nbTotalPcsInput.value = totalPieces;
    }

    // Fonction pour calculer le prix TTC en fonction du prix HT et de la TVA
    function calculerPrixTTC() {
        const prixHT = parseFloat(prixAchatHT.value) || 0;
        const tva = parseFloat(tvaSelect.value);

        const tauxTVA = tva === 1 ? 0.19 : tva === 2 ? 0.09 : 0;
        const prixTTC = prixHT * (1 + tauxTVA);

        prixAchatTTC.value = prixTTC.toFixed(2);
        prixVenteTTC.value = prixTTC.toFixed(2);

        calculerPrixVenteUnite(); // Mise à jour du prix de vente unitaire
    }

    // Fonction pour calculer le prix de vente unitaire à partir du prix TTC et de la marge bénéficiaire
    function calculerPrixVenteUnite() {
        const prixTTC = parseFloat(prixVenteTTC.value) || 0;
        const marge = parseFloat(margeBeneficeInput.value) || 0;

        const prixVenteUniteValue = prixTTC * (1 + marge / 100);
        prixVenteUnite.value = prixVenteUniteValue.toFixed(2);
    }
});

</script>