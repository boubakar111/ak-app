<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\UsersModel;
use Database\DBConnection;

class AuthController extends Controller
{
    private $userModel;

    public function __construct(DBConnection $db)
    {
        parent::__construct($db);
        $this->userModel = new UsersModel($db::getInstance());
    }
    
    public function index()
    {
       
        return $this->view('login.index');
    }


    public function login()
    {   
        $response = []; // Initialisation du tableau de réponse
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
            $password = md5($password);
    
            if (!empty($username) && !empty($password)) {
                $user = $this->userModel->getByUsername($username, $password);
    
                if ($user && $user[0]->status != 0) {
                    
                    $this->startUserSession($user[0]);
                    $response['status'] = true;
                    $response['message'] = 'Connexion réussie';
                    $response['redirect'] = ADMIN_DASHBOARD_URL;
                    
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Email ou mot de passe incorrect.';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Veuillez entrer votre email et mot de passe.';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Accès non autorisé.';
        }
    
        // Envoi de la réponse JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function resetPassword()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $url = $_GET['url'];
            $tokenFromURL = $this->getTokenFromURL($url);

            if (!$tokenFromURL) {
                $this->redirectToLoginWithError('URL invalide, aucun token trouvé.');
            }

            $tokenFromDB = $this->userModel->getToken($tokenFromURL);

            if (!$tokenFromDB) {
                $this->redirectToLoginWithError('Token invalide.');
            }

            if ($this->isTokenExpired($tokenFromDB)) {
                $this->redirectToLoginWithError('Le délai de réinitialisation a expiré.');
            }

            $_SESSION['token'] = $tokenFromDB;
            return $this->viewAdmin('portail.reset_password');
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tokenDb = $_SESSION['token'];

            if (!$tokenDb || $this->isTokenExpired($tokenDb)) {
                $this->redirectToLoginWithError('Token invalide.');
            } else {
                $newPassword = $_POST['password'];
                $confirmPassword = $_POST['confirm_password'];

                if ($newPassword === $confirmPassword) {
                    $hashedPassword = md5($newPassword);
                    $res = $this->userModel->changePassword($hashedPassword, $tokenDb[0]->password_reset_token);
                   
                    if ($res) {
                        header('Location:' . ADMIN_LOGIN_URL);
                    } else {
                        $this->redirectToLoginWithError('Erreur lors de la réinitialisation du mot de passe.');
                    }
                } else {
                    $this->redirectToLoginWithError('Les mots de passe ne correspondent pas.');
                }
            }
        } else {
            header('Location:' . ADMIN_LOGIN_URL);
        }
    }

    public function logout()
    {
        $this->destroyUserSession();
        header('Location:' . ADMIN_LOGIN_URL);
        exit();
    }

    private function startUserSession($user)
    {   $this->destroyUserSession();
        session_start();
        $_SESSION['firstname'] = $user->firstname;
        $_SESSION['lastname'] = $user->lastname;
        $_SESSION['uType'] = $user->userType;
        
    }

    private function destroyUserSession()
    {
        session_start();
        $_SESSION = array();
        session_unset();
        session_destroy();
    }

}