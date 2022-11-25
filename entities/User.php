<?php

declare(strict_types=1);

namespace entities;

use peps\core\Entity;

class User extends Entity {
    /**
     * PK.
     *
     * @var integer|null
     */
    public ?int $idUser = null;
    /**
     * Identifiant.
     *
     * @var string|null
     */
    public ?string $log = null;
    /**
     * Mot de passe TOUJOURS chiffré.
     *
     * @var string|null
     */
    public ?string $pwd = null;
    /**
     * Nom de famille.
     *
     * @var string|null
     */
    public ?string $lastName = null;
    /**
     * Prénom.
     *
     * @var string|null
     */
    public ?string $firstName = null;
    /**
     * Email.
     *
     * @var string|null
     */
    public ?string $email = null;
    /**
     * Instance du User logué.
     * Lazy loading.
     *
     * @var self|null
     */
    private static ?self $loggedUser = null;

    /**
     * Constructeur.
     *
     * @param integer|null $idUser PK.
     */
    public function __construct(?int $idUser = null) {
        $this->idUser = $idUser;
    }

    /**
     * Tente de loguer $this en session. 
     * Retourne true ou false selon que le login a réussi ou pas.
     * 
     * @param string $pwd Mot de passe clair.
     * @return boolean Echec ou réussite.
     */
    public function login(string $pwd) : bool {
        // Par sécurité, liquider l'éventuel ancien idUser
        if(isset($_SESSION['idUser'])) unset($_SESSION['idUser']);
        // Retrouver le user d'après son log - Requête SELECT préparée
        $user = User::findOneBy(['log' => $this->log]);
        if(!$user) return false;
        // Si log/pwd corrects, hydrater $this, le placer en session et retrouver true.
        if (password_verify($pwd, $user->pwd)) {
            // Définir l'idUser et $this syr l'idUser de $user.
            $this->idUser = $user->idUser;
            // Hydrater $this.
            $this->hydrate();
            return (bool)$_SESSION['idUser'] = $this->idUser;
        }
        // Sinon retourner false.
        return false;             
    }

    // Retourne le user logué ou null si absent
    public static function getLoggedUser() : ?self {
        if(!self::$loggedUser && isset($_SESSION['idUser'])) {
            self::$loggedUser = new User($_SESSION['idUser']);
            self::$loggedUser->hydrate();
        }
        return self::$loggedUser ?: null;
    }
}