<?php

declare(strict_types=1);

namespace entities;

use classes\ConnexionMySQL;
use classes\Entity;
use PDO;

class Category extends Entity
{
    public ?int $idCategory = null;
    public ?string $name = null;
    protected ?array $products = null;

    public function __construct(?int $idCategory = null) {
        $this->idCategory = $idCategory;
    }

    public static function all(): array {
        $q = "SELECT * FROM category ORDER BY name";
        $stmt = ConnexionMySQL::getInstance()->getPDO()->query($q);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Category::class);
        return $stmt->fetchAll();
    }

    public function hydrate(): bool {
        $q = "SELECT * FROM category WHERE idCategory = :idCategory";
        $params = [':idCategory' => $this->idCategory];
        $stmt = ConnexionMySQL::getInstance()->getPDO()->prepare($q);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_INTO, $this);
        return (bool) $stmt->fetch();
    }

    // Chargement des produits de la catégorie en lazy loading
    public function getProducts(): array {
        if ($this->products === null) {
            $q = "SELECT * FROM product WHERE idCategory = :idCategory ORDER BY name";
            $params = [':idCategory' => $this->idCategory];
            $stmt = ConnexionMySQL::getInstance()->getPDO()->prepare($q);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Product::class);
            $this->products = $stmt->fetchAll();
        }
        return $this->products;
    }
}
