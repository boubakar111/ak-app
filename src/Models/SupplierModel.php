<?php

namespace App\Models;

use App\Models\Model;
use PDO;
use stdClass;

class SupplierModel extends Model {
	
    protected $tab = 'fournisseurs';


    public function allSupplier()
    {
        try {
            $query = "SELECT id, nom_entreprise, adresse, ville, email, contact_nom, contact_telephone, contact_email  FROM {$this->tab}";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {

            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

}