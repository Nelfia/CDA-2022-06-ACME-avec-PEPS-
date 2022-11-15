<?php

declare(strict_types=1);

namespace entities;
use classes\ConnexionMySQL;
use classes\Entity;
use PDO;

class User extends Entity {
    public ?int $idUser = null;
    public ?string $log = null;
    public ?string $pwd = null;
    public ?string $lastName = null;
    public ?string $firstName = null;
    public ?string $email = null;

    private static ?self $loggedUser = null;

    public final const ERROR_LOGIN_FAILED = "Login ou mot de passe invalide.";

    public function __construct(?int $idUser = null) {
        $this->idUser = $idUser;
    }

    public function hydrate(): bool {
        $q = "SELECT * FROM user WHERE idUser= :idUser";
        $params = [':idUser' => $this->idUser];
        $stmt = ConnexionMySQL::getInstance()->getPDO()->prepare($q);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_INTO, $this);
        return (bool)$stmt->fetch();
    }

    // Tente de loguer $this en session. 
    // Retourne true ou false selon que le login a réussi ou pas.
    public function login(string $pwd) : bool {
        // Par sécurité, liquider l'éventuel ancien idUser
        if(isset($_SESSION['idUser'])) unset($_SESSION['idUser']);
        // Retrouver le user d'après son log - Requête SELECT préparée
        $q = "SELECT * FROM user WHERE log = :log";
        $params = [ ':log' => $this->log];
        $stmt = ConnexionMySQL::getInstance()->getPDO()->prepare($q);
        $stmt->execute($params);
        // Récupération de l'enregistrement encapsulé ds mon obget PDOStatement $stmt (Hydrater $this)
        $stmt->setFetchMode(PDO::FETCH_INTO, $this);
        // Si user retrouvé et log/pwd corrects, le placer en session et retrouver true
        if ($stmt->fetch() && password_verify($pwd, $this->pwd)) {
            return (bool)$_SESSION['idUser'] = $this->idUser;
        }
        // Sinon retourner false
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