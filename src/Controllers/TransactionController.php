<?php 
namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\SupplierModel;
use App\Models\SystemInfoModel;
use Database\DBConnection;

class TransactionController extends Controller
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

    public function manageTransaction()
    {
        return $this->view('admin.transaction.manage_transaction');
    }
}