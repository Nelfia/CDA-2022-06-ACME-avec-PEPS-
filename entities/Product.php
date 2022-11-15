<?php

declare(strict_types=1);

namespace entities;

use classes\ConnexionMySQL;
use classes\Entity;
use PDO;

class Product extends Entity {
    // Propriétés
    public ?int $idProduct = null;
    public ?int $idCategory = null;
    public ?string $name = null;
    public ?string $ref = null;
    public ?float $price = null;
    protected ?Category $category = null;

    // Messages d'erreurs(constantes)
    public final const ERROR_INVALID_CATEGORY = "La catégorie est absente ou invalide.";
    public final const ERROR_INVALID_NAME = "Le nom est absent ou invalide.";
    public final const ERROR_INVALID_REF = "La référence est absente ou invalide.";
    public final const ERROR_INVALID_DUPLICATE_REF = "La référence existe déjà.";
    public final const ERROR_INVALID_PRICE = "Le prix est absent ou invalide.";

    public function __construct(?int $idProduct = null) {
        $this->idProduct = $idProduct;
    }

    // Hydrate l'instance sur laquelle elle est appelée
    public function hydrate(): bool {
        $q = "SELECT * FROM product WHERE idProduct= :idProduct";
        $params = [':idProduct' => $this->idProduct];
        $stmt = ConnexionMySQL::getInstance()->getPDO()->prepare($q);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_INTO, $this);
        return (bool)$stmt->fetch();
    }

    // Insère ou MAJ les données de l'instance dans la base de données
    public function persist() : static {
        // Selon que l'idProduct est renseigné ou pas, faire un INSERT ou un UPDATE
        if($this->idProduct === null) {
            $q = "INSERT INTO product VALUES (DEFAULT, :idCategory, :name, :ref, :price)";
        } else {
            $q = "UPDATE product SET idCategory = :idCategory, name = :name, ref = :ref, price = :price WHERE idProduct = :idProduct";
        }
        $params = [
            ':idProduct' => $this->idProduct, 
            ':idCategory' => $this->idCategory, 
            ':name' => $this->name, 
            ':ref' => $this->ref, 
            ':price' => $this->price
        ];
        if($this->idProduct){
            $params[':idProduct'] = $this->idProduct;
        }
        $stmt = ConnexionMySQL::getInstance()->getPDO()->prepare($q);
        $stmt->execute($params);
        return $this;
    }

    // Supprime l'enregistrement correspondant à l'instance dans la base de données
    public function remove() : bool {
        $q = "DELETE FROM product WHERE idProduct = :idProduct";
        $params = [':idProduct' => $this->idProduct];
        $stmt = ConnexionMySQL::getInstance()->getPDO()->prepare(($q));
        $stmt->execute($params);
        return (bool)$stmt->rowCount();
    }
    
    // Chargement de la Category de l'instance en lazy loading
    public function getCategory(): ?Category {
        if ($this->category === null) {
            $this->category = new Category($this->idCategory);
            return $this->category->hydrate() ? $this->category : null;
        }
        return $this->category;
    }

}
