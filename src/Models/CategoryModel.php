<?php

namespace App\Models;

use PDO;

class CategoryModel extends Model
{

    protected $tab  = 'category_list';


    public function getCatRow()
    {
        try {
            $query = "SELECT COUNT(*) AS total_categories FROM `{$this->tab}`";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {

            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
    public function getAllCat()
    {
        try {
            $query = "SELECT id, name, description ,date_created ,status  FROM `{$this->tab}` WHERE delete_flag = 0";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {

            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
    public function getCatById($id)
    {
        try {
            $query = "SELECT id, name, description ,status  FROM `{$this->tab}` WHERE id= :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {

            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    public function getActiveCategories($category_id = NULL)
    {
        try {

            $query = "SELECT id, name, description, status, delete_flag FROM `{$this->tab}` WHERE `status` = 1 AND `delete_flag` = 0";

            if (isset($category_id)) {
                $query .= " AND id = :category_id";
            }
            $query .= " ORDER BY name ASC";

            $stmt = $this->db->prepare($query);
            if (isset($category_id)) {
                $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    public function manageCategory($id, $name, $description, $status)
{
    try {
        // Valider les paramètres
        if (empty($name) || empty($description) || ($status !== 0 && $status !== 1)) {
            throw new \Exception("Paramètres invalides.");
        }

        // Déterminer si c'est une mise à jour ou une insertion
        if (!empty($id) && is_int($id) && $id > 0) {
            // Préparer la requête UPDATE
            $query = "UPDATE `{$this->tab}` SET name = :name, description = :description, status = :status WHERE id = :id";
        } else {
            // Préparer la requête INSERT
            $query = "INSERT INTO `{$this->tab}` (name, description, status) VALUES (:name, :description, :status)";
        }

        // Préparer la requête SQL
        $stmt = $this->db->prepare($query);

        // Lier les paramètres communs
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);

        // Lier l'ID uniquement pour l'UPDATE
        if (!empty($id) && is_int($id) && $id > 0) {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        }

        // Exécuter la requête
        $stmt->execute();

        // Retourner un résultat (rowCount() pour UPDATE ou lastInsertId() pour INSERT)
        if (!empty($id) && is_int($id) && $id > 0) {
            // Mise à jour
            return $stmt->rowCount() > 0;
        } else {
            // Insertion
            return $this->db->lastInsertId();
        }
    } catch (\PDOException $e) {
        // Gérer les erreurs SQL
        echo "Erreur SQL : " . $e->getMessage() . "<br>";
        return false;
    } catch (\Exception $e) {
        // Gérer les erreurs générales
        echo "Erreur : " . $e->getMessage() . "<br>";
        return false;
    }
}

    // public function manageCategory($id, $name, $description, $status)
    // {

    //     try {
    //         // Valider les paramètres
    //         if (!is_int($id) || $id <= 0) {
    //             throw new \Exception("ID invalide.");
    //         }
    //         if (empty($name) || empty($description) || ($status !== 0 && $status !== 1)) {
    //             throw new \Exception("Paramètres invalides.");
    //         }

    //         // Préparer la requête UPDATE
    //         $query = "UPDATE `{$this->tab}` SET name = :name, description = :description, status = :status WHERE id = :id";
    //         $stmt = $this->db->prepare($query);

    //         // Lier les paramètres
    //         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    //         $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    //         $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    //         $stmt->bindParam(':status', $status, PDO::PARAM_INT);

    //         // Exécuter la requête
    //         $stmt->execute();

    //         // Vérifier si la mise à jour a réussi
    //         return $stmt->rowCount() > 0;
    //     } catch (\PDOException $e) {
    //         // Gérer les erreurs SQL
    //         echo "Erreur SQL : " . $e->getMessage() . "<br>";
    //         echo "Code SQL : " . $e->getCode() . "<br>";
    //         echo "Requête SQL : " . $query . "<br>";
    //         return false;
    //     } catch (\Exception $e) {
    //         // Gérer les erreurs générales
    //         echo "Erreur : " . $e->getMessage() . "<br>";
    //         return false;
    //     }
    // }
}
