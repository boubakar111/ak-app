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

        .form-divider {
            border: none;
            border-top: 2px solid #6c757d;
            /* Couleur grise */
            margin: 20px 0;
        }
    </style>
    <?php
    $entity = $params['supplier'];

    //var_dump( $entity);die();
    ?>
    <div class="card card-outline card-purple rounded-0 shadow">
        <div class="card-header">
            <h3 class="card-title"><?= isset($product) ? "Modifier une Entitie" : "Ajouter une Entitie"; ?></h3>
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
            <div id="message-container" style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 5px;"></div>
            <div class="container-fluid">
                <form method="POST" action="<?php echo base_url ?>admin/entity/editSupplier">
                    <!-- Champ caché pour l'ID -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($entity->id ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <fieldset class="border p-3">
                        <legend class="w-auto"> Information Entreprise</legend>
                        <div class="form-row">
                            <!-- Entitie de lentreprise -->
                            <div class="form-group col-md-2">
                                <label for="entity_type" class="control-label">Désignation</label>
                                <select name="entity_type" id="entity_type" class="form-control form-control-border select2" required>
                                    <option value="" disabled <?= !$product ? 'selected' : ''; ?>>Choisissez une option</option>
                                    <option value="1" <?= ($entity && $entity->designation == 1) ? 'selected' : '' ?>>Client</option>
                                    <option value="2" <?= ($entity && $entity->designation == 2) ? 'selected' : '' ?>>Fournisseur</option>
                                </select>
                            </div>
                            <!-- Nom de l'entreprise -->
                            <div class="form-group col-md-4">
                                <label for="nom_entreprise" class="control-label">Nom de l'entreprise</label>
                                <input type="text" name="nom_entreprise" id="nom_entreprise" class="form-control form-control-border"
                                    placeholder=" nom entreprise ..."
                                    value="<?= htmlspecialchars($entity->nom_entreprise ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <!-- adresse -->
                            <div class="form-group col-md-6">
                                <label for="adresse" class="control-label">Adresse</label>
                                <input type="text" name="adresse" id="adresse" class="form-control form-control-border"
                                    placeholder="Adresse.."
                                    value="<?= htmlspecialchars($entity->adresse ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <!-- Nom  de la ville  -->
                            <div class="form-group col-md-4">
                                <label for="ville" class="control-label">Nom de la ville</label>
                                <input type="text" name="ville" id="ville" class="form-control form-control-border"
                                    placeholder=" nom  fr la ville ..."
                                    value="<?= htmlspecialchars($entity->ville ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <!-- code postal  -->
                            <div class="form-group col-md-4">
                                <label for="code_postal" class="control-label">Code postal</label>
                                <input type="numeric" name="code_postal" id="code_postal" class="form-control form-control-border"
                                    placeholder="le code postal ..."
                                    value="<?= htmlspecialchars($entity->code_postal ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <!-- pays -->
                            <div class="form-group col-md-4">
                                <label for="pays" class="control-label">Pays</label>
                                <input type="text" name="pays" id="pays" class="form-control form-control-border"
                                    placeholder="Pays.."
                                    value="<?= htmlspecialchars($entity->pays ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <!-- telephone de l'entreprise  -->
                            <div class="form-group col-md-4">
                                <label for="telephone" class="control-label">Telephone de l'entreprise</label>
                                <input type="text" name="telephone" id="telephone" class="form-control form-control-border"
                                    placeholder=" telephone de l'entreprise ..."
                                    value="<?= htmlspecialchars($entity->telephone ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <!-- Ne_mail de l'entreprise -->
                            <div class="form-group col-md-4">
                                <label for="email" class="control-label">Email de l'entreprise</label>
                                <input type="email" name="email" id="email" class="form-control form-control-border"
                                    placeholder="le code postal ..."
                                    value="<?= htmlspecialchars($entity->email ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <!-- adresse -->
                        </div>

                    </fieldset>
                    <fieldset class="border p-3">
                        <legend class="w-auto"> Information Contact</legend>
                        <div class="form-row">
                            <!-- Nom  du contact  -->
                            <div class="form-group col-md-4">
                                <label for="contact_nom" class="control-label">Nom du contact</label>
                                <input type="text" name="contact_nom" id="contact_nom" class="form-control form-control-border"
                                    placeholder=" Nom du contact  ..."
                                    value="<?= htmlspecialchars($entity->contact_nom ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <!-- telephone du contact  -->
                            <div class="form-group col-md-4">
                                <label for="contact_telephone" class="control-label">Telephone du contact </label>
                                <input type="numeric" name="contact_telephone" id="contact_telephone" class="form-control form-control-border"
                                    placeholder=" telephone du contact  ..."
                                    value="<?= htmlspecialchars($entity->contact_telephone ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <!-- email du contact  -->
                            <div class="form-group col-md-4">
                                <label for="contact_email" class="control-label">Email du contact</label>
                                <input type="text" name="contact_email" id="contact_email" class="form-control form-control-border"
                                    placeholder="Email du contact .."
                                    value="<?= htmlspecialchars($entity->contact_email ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                        </div>

                    </fieldset>
                    <!-- Status -->
                    <fieldset class="border p-3">
                        <legend class="w-auto"> Information Statut</legend>
                        <div class="form-group">
                            <label for="status" class="control-label">Status</label>
                            <select name="statut" id="status" class="form-control form-control-border" required>
                                <option value="1" <?= ($entity && $entity->statut == 1) ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= ($entity && $entity->statut == 0) ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </fieldset>
                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <?= isset($entity->id) ? 'Modifier' : 'Ajouter'; ?>
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

        // document.getElementById('submit-btn').addEventListener('click', function(e) {
        //     e.preventDefault();
        //     var form = document.getElementById('price-form');
        //     var formData = new FormData(form);

        //     // Convertir FormData en objet JSON
        //     var formObject = {};
        //     formData.forEach((value, key) => {
        //         formObject[key] = value;
        //     });

        //     console.log('Form Data:', formObject);

        //     // Envoyer les données via fetch
        //     fetch(_base_url_ + 'admin/product/manage_price', {
        //             method: 'POST',
        //             body: JSON.stringify(formObject),
        //             headers: {
        //                 'Content-Type': 'application/json'
        //             }
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             console.log('Success:', data);
        //             // Vérification correcte du statut de la réponse
        //             if (data.status === "success") {
        //                 showMessage(data.message, 'success'); // Affiche un message de succès
        //             } else {
        //                 showMessage(data.message || "Une erreur est survenue.", 'error'); // Affiche un message d'erreur
        //             }
        //         })
        //         .catch((error) => {
        //             console.error('Error:', error);
        //             // Afficher un message d'erreur si la requête échoue
        //             showMessage("Une erreur s'est produite lors de l'envoi des données.", 'error');
        //         });
        // });

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
    });
</script>