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
        <style>
        .marge-beneficiaire {
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body class="container mt-4">

    <h2 class="mb-3">Gestion des Transactions</h2>

    <!-- Sélection du Client -->
    <div class="card p-3 mb-3">
        <h5>Liste des Clients</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody id="clients-list">
                <tr onclick="selectClient(1, 'Jean Dupont', '10 Rue de Paris', '0654321098')">
                    <td>1</td>
                    <td>Jean Dupont</td>
                    <td>10 Rue de Paris</td>
                    <td>0654321098</td>
                </tr>
                <tr onclick="selectClient(2, 'Marie Curie', '5 Place Voltaire', '0787654321')">
                    <td>2</td>
                    <td>Marie Curie</td>
                    <td>5 Place Voltaire</td>
                    <td>0787654321</td>
                </tr>
            </tbody>
        </table>
        <div>
            <strong>Client sélectionné :</strong> <span id="selected-client">Aucun</span>
        </div>
    </div>

    <!-- Sélection et ajout des Produits -->
    <div class="card p-3 mb-3">
        <h5>Ajouter un Produit</h5>
        <div class="row">
            <div class="col-md-4">
                <select id="product-select" class="form-control">
                    <option value="1" data-ref="P001" data-prix-gros="80" data-prix-detail="100">Produit A</option>
                    <option value="2" data-ref="P002" data-prix-gros="150" data-prix-detail="200">Produit B</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" id="product-quantity" class="form-control" placeholder="Quantité" min="1" value="1">
            </div>
            <div class="col-md-2">
                <select id="price-type" class="form-control">
                    <option value="detail">Prix de détail</option>
                    <option value="gros">Prix de gros</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" onclick="addProduct()">Ajouter le produit</button>
            </div>
        </div>
    </div>

    <!-- Liste des Produits ajoutés -->
    <div class="card p-3 mb-3">
        <h5>Produits Ajoutés</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Désignation</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Prix Total</th>
                    <th>Marge</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="product-list">
                <!-- Les produits ajoutés apparaîtront ici -->
            </tbody>
        </table>
    </div>

    <!-- Calcul des Totaux -->
    <div class="card p-3 mb-3">
        <h5>Total & Paiement</h5>
        <div class="row">
            <div class="col-md-4">
                <strong>Montant Total :</strong> <span id="total-amount">0</span> €
            </div>
            <div class="col-md-4">
                <label>Montant Réglé :</label>
                <input type="number" id="paid-amount" class="form-control" min="0" value="0" oninput="updateRemainingAmount()">
            </div>
            <div class="col-md-4">
                <strong>Montant Restant :</strong> <span id="remaining-amount">0</span> €
            </div>
        </div>
    </div>

    <!-- Boutons -->
    <div class="d-flex justify-content-between">
        <button class="btn btn-danger" onclick="resetForm()">Annuler</button>
        <button class="btn btn-success" onclick="saveTransaction()">Enregistrer la transaction</button>
    </div>
<?php } ?>
    <script>
        let totalAmount = 0;

        function selectClient(id, name, address, phone) {
            document.getElementById("selected-client").innerText = `${name} (${phone})`;
        }

        function addProduct() {
            let select = document.getElementById("product-select");
            let quantity = parseInt(document.getElementById("product-quantity").value);
            let priceType = document.getElementById("price-type").value;
            let selectedOption = select.options[select.selectedIndex];

            let productName = selectedOption.text;
            let productRef = selectedOption.getAttribute("data-ref");
            let unitPrice = priceType === "gros" ? parseFloat(selectedOption.getAttribute("data-prix-gros")) : parseFloat(selectedOption.getAttribute("data-prix-detail"));
            let totalPrice = unitPrice * quantity;
            let margin = priceType === "gros" ? "Faible" : "Élevée";

            totalAmount += totalPrice;
            document.getElementById("total-amount").innerText = totalAmount;
            updateRemainingAmount();

            let row = `<tr>
                <td>${productRef}</td>
                <td>${productName}</td>
                <td>${quantity}</td>
                <td>${unitPrice} €</td>
                <td>${totalPrice} €</td>
                <td class="marge-beneficiaire">${margin}</td>
                <td><button class="btn btn-danger btn-sm" onclick="removeProduct(this, ${totalPrice})">Supprimer</button></td>
            </tr>`;
            document.getElementById("product-list").innerHTML += row;
        }

        function removeProduct(button, price) {
            totalAmount -= price;
            document.getElementById("total-amount").innerText = totalAmount;
            updateRemainingAmount();
            button.closest("tr").remove();
        }

        function updateRemainingAmount() {
            let paid = parseFloat(document.getElementById("paid-amount").value) || 0;
            document.getElementById("remaining-amount").innerText = (totalAmount - paid).toFixed(2);
        }
    </script>

</body>
</html>