<?php

namespace App\Models;

use PDO;

class UsersModel extends Model
{
    protected $tab = 'users';

    public function getUsers()
    {
        try {
            $query = "SELECT username, password  FROM {$this->tab} where username = ? and password = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {

            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    public function getByUsername(string $username, string $password)
    {
        try {

            
            $pw = $password;
            $query = "SELECT firstname, lastname, username, password ,userType, status FROM {$this->tab} WHERE username = :username AND password = :password";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $pw, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    public function getToken(string $oken)
    {
    }
    public function checkUsername($name)
    {
    }

    public function getUserById($id)
    {
    }

    public function updatUser($id, $email)
    {
    }

    public function insertAdmin($username, $email, $password, $usertype, $isActive)
    {
    }

    public function deleteUser($id)
    {
    }

    public function getUserByEmail($email)
    {
    }

    public function createToken($pwd_reset_token, $pwd_reset_expiration, $id)
    {
    }


    public function changePassword($newPwd, $token)
    {
    }
}
