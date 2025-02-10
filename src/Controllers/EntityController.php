<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\SupplierModel;
use App\Models\SystemInfoModel;
use Database\DBConnection;

class EntityController extends Controller
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

    public function listSupplier()
    {
        $info = $this->systemInfoModel->load_system_info();
        $suppliers = $this->supplierModel->getAllSuppliers();
        return $this->view('admin.entity.liste_supplier', compact('suppliers', 'info'));
    }

    public function manageEntity()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $info = $this->systemInfoModel->load_system_info();

            return $this->view('admin.entity.edit_supplier', compact('info'));
        }
    }

    public function editSupplier($id = null)
    {

        $info = $this->systemInfoModel->load_system_info();
        $message = null;


        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $supplier = $this->supplierModel->getSupplierById($id);
            return $this->view('admin.entity.edit_supplier', compact('supplier', 'info'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            $data = [
                'id' => filter_var($_POST['id'], FILTER_VALIDATE_INT),
                'designation' => filter_var($_POST['entity_type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'nom_entreprise' => filter_var($_POST['nom_entreprise'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'adresse' => filter_var($_POST['adresse'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'ville' => filter_var($_POST['ville'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'code_postal' => filter_var($_POST['code_postal'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'pays' => filter_var($_POST['pays'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'telephone' => filter_var($_POST['telephone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'email' => filter_var($_POST['email'], FILTER_VALIDATE_EMAIL),
                'contact_nom' => filter_var($_POST['contact_nom'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'contact_telephone' => filter_var($_POST['contact_telephone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'contact_email' => filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL),
                'statut' => isset($_POST['statut']) ? (int)$_POST['statut'] : 0,
            ];
            
                // ✅ Mise à jour du fournisseur
                $result = $this->supplierModel->manageSupplier($data);
                if ($result) {
                    $message = [
                        'type' => 'success',
                        'text' => 'Les modifications ont été bien prises en compte.',
                    ];
                } else {
                    $message = [
                        'type' => 'error',
                        'text' => "Une erreur s'est produite lors de la mise à jour. Veuillez réessayer.",
                    ];
                }
            

            // Récupération des données mises à jour
            $supplier = $this->supplierModel->getSupplierById($result?? null);
            return $this->view('admin.entity.edit_supplier', compact('supplier', 'info', 'message'));
        }
    }


}
