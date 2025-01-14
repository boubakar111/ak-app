<?php
namespace App\Models;

use PDO;

class TransactionModel extends Model{

    protected $tab  = 'transaction_list';


    public function getTransacPending()
    {
        try {
            $query = "SELECT COUNT(*) AS transactions  FROM transaction_list WHERE STATUS = 2";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {

            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

}