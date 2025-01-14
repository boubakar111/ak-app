<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\SystemInfoModel;
use Database\DBConnection;

class CategoryController extends Controller
{

    private $systemInfoModel;
    private $categoryModel;
    private $message = [];

    public function __construct(DBConnection $db)
    {
        parent::__construct($db);
        $this->categoryModel = new CategoryModel($db);
        $this->systemInfoModel = new SystemInfoModel($db);
    }

    public function listCategory()
    {

        $info = $this->systemInfoModel->load_system_info();
        $category = $this->categoryModel->getAllCat();
        return $this->view('admin.category.list_category', compact('category', 'info'));
    }

    public function editCategory($id = null)
    {

        $info = $this->systemInfoModel->load_system_info();
        $category = $this->categoryModel->getCatById($id);

        return $this->view('admin.category.edit_category', compact('category', 'info'));
    }
    public function manageCategory($id = null)
    {
        $info = $this->systemInfoModel->load_system_info();
        $message = null;

        // Si le formulaire est soumis en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données
            $id = isset($_POST['id']) && !empty($_POST['id']) ? filter_var($_POST['id'], FILTER_VALIDATE_INT) : null;
            $nameCategory = filter_var($_POST['nomCategory'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nameCategory = html_entity_decode($nameCategory, ENT_QUOTES, 'UTF-8');
            $descCategory = filter_var($_POST['descCategory'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $descCategory = html_entity_decode($descCategory, ENT_QUOTES, 'UTF-8');
            $status = filter_var($_POST['status'], FILTER_VALIDATE_INT);

            // Valider les données
            if ($nameCategory && $descCategory && $status !== false) {
                // Appeler la fonction du modèle pour gérer l'insertion ou la mise à jour
                $result = $this->categoryModel->manageCategory($id, $nameCategory, $descCategory, $status);

                // Gérer le résultat
                if ($result) {
                    $message = [
                        'type' => 'success',
                        'text' => $id ? 'Les modifications ont été bien prises en compte.' : 'La nouvelle catégorie a été ajoutée avec succès.',
                    ];
                } else {
                    $message = [
                        'type' => 'error',
                        'text' => "Une erreur s'est produite. Veuillez réessayer.",
                    ];
                }

                // Récupérer les données pour le formulaire après mise à jour ou insertion
                $category = $id ? $this->categoryModel->getCatById($id) : null;

                return $this->view('admin.category.edit_category', compact('category', 'info', 'message'));
            } else {
                // Erreur de validation
                $message = [
                    'type' => 'error',
                    'text' => 'Veuillez remplir correctement tous les champs.',
                ];
            }
        }

        // Si l'ID est fourni en paramètre ou pour un nouveau formulaire
        $category = $id ? $this->categoryModel->getCatById($id) : null;

        return $this->view('admin.category.edit_category', compact('category', 'info', 'message'));
    }
}
