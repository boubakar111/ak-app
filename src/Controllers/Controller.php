<?php

namespace App\Controllers;

use Database\DBConnection;
use PHPMailer\PHPMailer\PHPMailer;

class Controller
{
    protected $db;
    public function __construct(DBConnection $db)
    {
        $this->db = $db ;
    }
    protected function validateSession()
    {
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['uType'] !== 1) {
            header('Location: ' . ADMIN_LOGIN_URL);
            exit();
        }
    }

    // protected function sendEmail($subject, $to, $message)
    // {
    //     $mail = new PHPMailer(true);
    //     try {
    //         // Paramètres du serveur SMTP
    //         $mail->isSMTP();
    //         $mail->Host = 'smtp.free.fr'; // Adresse du serveur SMTP
    //         //SMTPAuth: Cette option est définie sur true, ce qui signifie que vous souhaitez utiliser l'authentification SMTP. Vous devez fournir un nom d'utilisateur (Username) et un mot de passe (Password) pour vous connecter au serveur SMTP.
    //         //Username: C'est le nom d'utilisateur que vous utilisez pour vous connecter au serveur SMTP. Vous devez remplacer 'your_username' par votre propre nom d'utilisateur.
    //         //Password: C'est le mot de passe associé à votre nom d'utilisateur pour l'authentification
    //         $mail->SMTPAuth = false;
    //         $mail->Username = '';
    //         $mail->Password = '';
    //         $mail->SMTPSecure = 'tls'; // TLS ou SSL, selon la configuration du serveur
    //         $mail->Port = 587; // Port SMTP (587 pour TLS, 465 pour SSL)

    //         // Destinataire, expéditeur et objet
    //         $mail->setFrom('m&melec@gmail.com', 'Administrateur');
    //         $mail->addAddress($to);
    //         $mail->Subject = $subject;
    //         // Corps de l'e-mail
    //         $mail->Body = $message;
    //         // Envoyer l'e-mail
    //         $mail->send();
    //         // L'e-mail a été envoyé avec succès
    //         return true;
    //     } catch (\Exception $e) {
    //         echo 'Erreur lors de l\'envoi de l\'e-mail : ' . $mail->ErrorInfo;
    //         return false;
    //     }
    // }



   protected function redirectToLoginWithError($error)
    {
        header('Location:' . ADMIN_LOGIN_URL . '?error=' . urlencode($error));
        exit();
    }


    protected  function getTokenFromURL($url)
    {
        $urlParts = parse_url($url);
        if (isset($urlParts['path']) && str_contains($url, '/token/')) {
            $parts = explode('/', $urlParts['path']);
            $tokenUrl = end($parts);
            return ltrim($tokenUrl, ':');
        }
        return false;
    }

    protected function isTokenExpired($token)
    {
        $currentTimestamp = time();
        $expirationFromDB = strtotime($token[0]->password_reset_expiration);
        $validTimestamp = strtotime('-4 hours', $currentTimestamp);

        return empty($token) || $currentTimestamp > $expirationFromDB || $validTimestamp > $expirationFromDB;
    }


    protected function view(string $path, array $params = null)
    {
        ob_start();
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);

        require VIEWS . $path . '.php';
        $content = ob_get_clean();
        require VIEWS . 'layout.php';
    }


    // les views  pour l'admin
    protected function viewAdmin(string $path, array $params = null)
    {
        ob_start();
        
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        $temp_admin = explode("/", $path);
        
        require VIEWS_ADMIN. $path . '.php';
        $content = ob_get_clean();
        require VIEWS_ADMIN. $path . '.php';

    }
}
