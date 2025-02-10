<?php

namespace App\Models;

use App\Models\Model;
use PDO;
use stdClass;

class SupplierModel extends Model
{

    protected $tab = 'fournisseurs';


    public function getAllSuppliers()
    {
        try {
            $query = "SELECT id, nom_entreprise, adresse, ville, email, contact_nom, contact_telephone, contact_email ,designation,statut FROM {$this->tab} ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {

            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    public function getSupplierById($id)
    {

        try {
            $query = "SELECT * FROM `{$this->tab}` WHERE id= :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {

            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    public function manageSupplier($data)
    {

        
        try {
            
           
            $id = $data["id"];
            if ($id) { 
                // Mise à jour du fournisseur existant
                $query = "UPDATE `{$this->tab}` 
                      SET 
                          nom_entreprise = :nom_entreprise,
                          adresse = :adresse,
                          ville = :ville,
                          code_postal = :code_postal,
                          pays = :pays,
                          telephone = :telephone,
                          email = :email,
                          contact_nom = :contact_nom,
                          contact_telephone = :contact_telephone,
                          contact_email = :contact_email,
                          statut = :statut,
                          designation = :designation
                      WHERE id = :id";
            } else {
                // Insertion d'un nouveau fournisseur
                $query = "INSERT INTO `{$this->tab}` 
                      (nom_entreprise, adresse, ville, code_postal, pays, telephone, email, contact_nom, contact_telephone, contact_email, statut, designation) 
                      VALUES 
                      (:nom_entreprise, :adresse, :ville, :code_postal, :pays, :telephone, :email, :contact_nom, :contact_telephone, :contact_email, :statut, :designation)";
            }

            // Préparer la requête
            $stmt = $this->db->prepare($query);

            // Lier les paramètres
            if ($id) {
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
            $stmt->bindParam(':nom_entreprise', $data['nom_entreprise'], PDO::PARAM_STR);
            $stmt->bindParam(':adresse', $data['adresse'], PDO::PARAM_STR);
            $stmt->bindParam(':ville', $data['ville'], PDO::PARAM_STR);
            $stmt->bindParam(':code_postal', $data['code_postal'], PDO::PARAM_STR);
            $stmt->bindParam(':pays', $data['pays'], PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $data['telephone'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':contact_nom', $data['contact_nom'], PDO::PARAM_STR);
            $stmt->bindParam(':contact_telephone', $data['contact_telephone'], PDO::PARAM_STR);
            $stmt->bindParam(':contact_email', $data['contact_email'], PDO::PARAM_STR);
            $stmt->bindParam(':statut', $data['statut']);
            $stmt->bindParam(':designation', $data['designation'], PDO::PARAM_INT);

            // Exécuter la requête
            $stmt->execute();
             
            // Si l'opération est une insertion, récupérer le dernier ID inséré
            if ($id) {
               return $id;
            }else{
                 return $this->db->lastInsertId();
            }

            return true;
        } catch (\PDOException $e) {
            // Gérer les erreurs
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

}
