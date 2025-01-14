<?php

namespace App\Models;

use PDO;

class ProductModel extends Model
{


    private $tab = "produits";


    public function getAllProduct()
    {

        try {
            $query = "SELECT p.*,c.name as category from {$this->tab} p 
            inner join category_list c 
            on p.category_id = c.id 
            where p.delete_flag = 0 
            order by c.`name` 
            asc, p.designiation_produit asc";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {

            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    function saveProduct($data)
    {
        if (empty($data)) {
            return json_encode(['status' => 'failed', 'msg' => 'No data received.']);
        }
        //var_dump($data);die();
        $dataString = ""; // Initialiser la chaîne pour construire la requête SQL
        $params = []; // Tableau pour les paramètres liés à la requête

        foreach ($data as $k => $v) {
            if (!in_array($k, array('id'))) { // Exclure le champ 'id'
                // On ajoute un paramètre à l'array
                $params[":$k"] = $v; // Utilisation de paramètre lié avec PDO
                if (!empty($dataString)) {
                    $dataString .= ",";
                }
                $dataString .= " `{$k}` = :$k "; // Constructeur de la liste des colonnes et valeurs
            }
        }

        // Vérifiez si une mise à jour ou un ajout est requis
        if (empty($data['id'])) {
            // Pour l'insertion, ne pas inclure `id` car la base de données pourrait générer cet ID automatiquement
            $sql = "INSERT INTO {$this->tab} SET {$dataString}";
        } else {
            $id = $data['id']; // Utilisation de l'id dans le tableau
            $sql = "UPDATE {$this->tab}  SET {$dataString} WHERE id = :id"; // Ajoutez `:id` pour les requêtes préparées
            $params[':id'] = $id; // Lier l'id avec le paramètre PDO
        }

        //         // Pour le débogage : afficher la requête SQL avec les paramètres
        // $sqlDebug = $sql;
        // foreach ($params as $key => $value) {
        //     $sqlDebug = str_replace($key, "'$value'", $sqlDebug); // Remplacer les placeholders par les valeurs
        // }

        // // Affichage de la requête SQL pour vérification
        // var_dump($sqlDebug);
        // die();
        try {
            // Utilisez la connexion PDO pour exécuter la requête préparée
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params); // Exécuter la requête avec les paramètres liés
            $resp = [
                'status' => 'success',
                'id' => !empty($data['id']) ? $data['id'] : $this->db->lastInsertId(),
                'msg' => empty($data['id']) ? 'Produit ajouté avec succès.' : 'Produit mis à jour avec succès.'
            ];
        } catch (\Exception $e) {
            // Gérer les erreurs de manière appropriée
            $resp = [
                'status' => 'failed',
                'msg' => 'Une erreur s’est produite.',
                'error' => $e->getMessage(),
            ];
        }

        // Retournez la réponse sous forme de JSON
        return json_encode($resp);
    }

    public function getProductById($id)
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

    public function updateProduct($data)
    {
        try {
            $query = "UPDATE products 
                  SET 
                      nom_produit = :nom_produit, 
                      ref_produit = :ref_produit, 
                      designiation_produit = :designiation_produit, 
                      category_id = :category_id, 
                      unite_mesure = :unite_mesure, 
                      nb_carton = :nb_carton, 
                      nb_pcs_carton = :nb_pcs_carton, 
                      prix_achat = :prix_achat, 
                      prix_vente_detail = :prix_vente_detail, 
                      prix_vente_gros = :prix_vente_gros, 
                      stock_securite = :stock_securite, 
                      status = :status, 
                      date_updated = NOW() 
                  WHERE id = :id";

            $stmt = $this->db->prepare($query);

            // Associer les valeurs
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(':nom_produit', $data['nom_produit']);
            $stmt->bindParam(':ref_produit', $data['ref_produit']);
            $stmt->bindParam(':designiation_produit', $data['designiation_produit']);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':unite_mesure', $data['unite_mesure'], PDO::PARAM_INT);
            $stmt->bindParam(':nb_carton', $data['nb_carton'], PDO::PARAM_INT);
            $stmt->bindParam(':nb_pcs_carton', $data['nb_pcs_carton'], PDO::PARAM_INT);
            $stmt->bindParam(':prix_achat', $data['prix_achat']);
            $stmt->bindParam(':prix_vente_detail', $data['prix_vente_detail']);
            $stmt->bindParam(':prix_vente_gros', $data['prix_vente_gros']);
            $stmt->bindParam(':stock_securite', $data['stock_securite'], PDO::PARAM_INT);
            $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);

            // Exécution
            return $stmt->execute();
        } catch (\PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }
}
