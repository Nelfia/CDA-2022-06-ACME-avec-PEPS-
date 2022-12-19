<?php

declare(strict_types=1);

namespace controllers;

use entities\User;
use peps\core\Router;

/**
 * Classe 100% statique de gestion des utilisateurs.
 */
final class UserController {
    /**
     * Constructeur privé
     */
    private function __construct() {}

    /**
     * Affiche la vue de saisie des identifiants de connexion.
     * 
     * GET/user/signin
     *
     * @return void
     */
    public static function signin() : void {
        // Rendre la vue.
        Router::render('signin.php');
    }

    /**
     * Tente de loguer un utilisateur en session.
     * 
     * POST /user/login
     *
     * @return void
     */
    public static function login() : void {
        // Créer un user
        $user = new User();
        // Initialiser le tableau des erreurs
        $errors = [];
        // Récupérer les données et tenter le login
        $user->log = filter_input(INPUT_POST, 'log', FILTER_SANITIZE_SPECIAL_CHARS);
        $pwd = filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_SPECIAL_CHARS);
        // Si login OK, rediriger.
        if ($user->login($pwd)) {
            Router::redirect('/');
        }
        // Sinon, rendre à nouveau le formulaire avec erreur.
        $errors[] = UserControllerException::LOGIN_FAILED;
        Router::render('signin.php', ['user' => $user, 'errors' => $errors]);
    }

    /**
     * Délogue l'utilisateur en session.
     * 
     * POST /user/logout
     *
     * @return void
     */
    public static function logout() : void {
        // Détruire les variables de session.
        session_destroy();
        // Rediriger
        Router::redirect('/user/signin');
    }
}
