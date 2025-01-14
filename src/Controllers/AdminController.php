<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Categories;
use App\Models\CategoryModel;
use Database\DBConnection;
use App\Models\SystemInfoModel;
use App\Models\TransactionModel;

class AdminController extends Controller
{
    private $systemInfoModel;
    private $categoriesModel;
    private $transactionModel;

    public function __construct(DBConnection $db)
    {
        parent::__construct($db);

        $this->systemInfoModel = new SystemInfoModel($db);
        $this->categoriesModel = new CategoryModel($db);
        $this->transactionModel = new TransactionModel($db);
    }

    public function dashboard(){
        
        $info = $this->systemInfoModel->load_system_info();
        $totalCat = $this->categoriesModel->getCatRow();
        $trans = $this->transactionModel->getTransacPending();
        return $this->view('admin.dashboard',compact('info','totalCat','trans'));
    }
}
