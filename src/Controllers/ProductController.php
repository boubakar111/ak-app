<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\SupplierModel;
use App\Models\SystemInfoModel;
use Database\DBConnection;

class ProductController extends Controller
{

    private $productModel;
    private $systemInfoModel;
    private $categoryModel;
    private $supplierModel;

    public function __construct(DBConnection $db)
    {
        parent::__construct($db);
        $this->systemInfoModel = new SystemInfoModel($db);
        $this->productModel = new ProductModel($db);
        $this->categoryModel = new CategoryModel($db);
        $this->supplierModel = new SupplierModel($db);
    }

    public function listProduct()
    {

        $info = $this->systemInfoModel->load_system_info();
        $product = $this->productModel->getAllProduct();
        return $this->view('admin.product.list_produit', compact('product', 'info'));
    }


    public function managePrice()
    {
        // Vérifiez si une requête POST est envoyée
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérez les données JSON envoyées par le client
            $data = json_decode(file_get_contents('php://input'), true);

            // Vérifiez que les données ont été correctement décodées
            if ($data) {
                try {
                    
                    $this->productModel->saveProduct($data);

                    // Retournez une réponse JSON en cas de succès
                    echo json_encode(['status' => 'success', 'message' => 'Produit ajouté avec succès.']);
                } catch (\Exception $e) {
                    // Gérez les erreurs d'insertion
                    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout du produit : ' . $e->getMessage()]);
                }
            } else {
                // Si le JSON est invalide
                echo json_encode(['status' => 'error', 'message' => 'Données JSON invalides.']);
            }
            exit(); // Terminez l'exécution pour éviter de retourner la vue après un POST
        }

        // Pour les requêtes GET, affichez la page HTML avec le formulaire
        $category = $this->categoryModel->getActiveCategories();
        $supplier = $this->supplierModel->allSupplier();

        return $this->view('admin.product.manage_price', compact('category', 'supplier'));
    }


    public function editProduct($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Gestion de la requête GET pour afficher le formulaire
        $url = $_SERVER['REQUEST_URI'];
        $pattern = '/\/editProduct\/(\d+)/';

        // Vérifier si l'ID est présent dans l'URL
        if (preg_match($pattern, $url, $matches)) {
            // Si une correspondance est trouvée, l'ID sera dans $matches[1]
            $id = $matches[1];
           
            $product = $this->productModel->getProductById($id);
            $category = $this->categoryModel->getActiveCategories(); // Récupérer toutes les catégories
            $info = $this->systemInfoModel->load_system_info();

            // Charger la vue avec les données
            return $this->view('admin.product.manage_price', compact('product', 'category', 'info'));
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Gestion de la requête POST pour mettre à jour le produit
        $data = [
            'id' => $id,
            'category_id' => $_POST['category_id'],
            'nom_produit' => $_POST['nom_produit'],
            'ref_produit' => $_POST['ref_produit'],
            'designiation_produit' => $_POST['designiation_produit'],
            'unite_mesure' => $_POST['unite_mesure'],
            'nb_carton' => $_POST['nb_carton'],
            'nb_pcs_carton' => $_POST['nb_pcs_carton'],
            'prix_achat' => $_POST['prix_achat'],
            'prix_vente_detail' => $_POST['prix_vente_detail'],
            'prix_vente_gros' => $_POST['prix_vente_gros'],
            'stock_securite' => $_POST['stock_securite'],
            'status' => $_POST['status'],
        ];

        // Validation des données
        if (empty($data['category_id']) || empty($data['nom_produit']) || empty($data['ref_produit'])) {
            // Gérer les erreurs de validation
            $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Mise à jour du produit
        $result = $this->productModel->updateProduct($data);
        if ($result) {
            $_SESSION['success'] = "Produit mis à jour avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du produit.";
        }

        // Redirection après la mise à jour
        header('Location: /admin/products');
        exit();
    }
}

    // public function editProduct($id)
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         $url = $_SERVER['REQUEST_URI'];
    //         $pattern = '/\/editProduct\/(\d+)/';

    //         // Vérifier si l'ID est présent dans l'URL
    //         if (preg_match($pattern, $url, $matches)) {
    //             // Si une correspondance est trouvée, l'ID sera dans $matches[1]
    //             $id = $matches[1];
               
    //             $product = $this->productModel->getProductById($id);
    //             $category =$this->categoryModel->getActiveCategories();
    //             $info = $this->systemInfoModel->load_system_info();
    //             return $this->view('admin.product.manage_price', compact('product','category','info'));
    //         }
    //     }
    // }
}
