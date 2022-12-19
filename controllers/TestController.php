<?php

declare(strict_types=1);

namespace controllers;

use entities\Category;
use entities\Product;
use peps\core\Router;

/**
 * Classe 100% statique de test.
 */
final class TestController {
    /**
     * Constructeur privé
     */
    private function __construct() {}

    /**
     * Méthode de test.
     * 
     * GET/test/{id}
     *
     * @param array $assocParams Tableau associatif des éventuels paramètres
     * @return void
     */
    public static function test() : void {
        $category = new Category(2);
        $category->hydrate();
        // var_dump($category->persist());
    }
}


// $obj = new stdClass();
// $dbal = DBAL::get();
// $q = 'SELECT * FROM product WHERE idProduct = 2';
// var_dump($obj);
// var_dump($dbal->xeq($q)->into($obj), $obj);

// $dbal = DBAL::get();
// $q = "INSERT INTO product VALUES (DEFAULT, :idCategory, :name, :ref, :price)";
// $params = [':idCategory' => 2, ':name' => 'test', ':ref'=> '1542345', ':price' => 6.10];
// $dbal->xeq($q, $params);
// var_dump($dbal->pk());


// Test du Rollback complet
// $q = "INSERT INTO category VALUES (DEFAULT, :name)";
// $params1 = [':name' => 'Test 1'];
// $params2 = [':name' => 'Test 2'];
// DBAL::get()
//     ->start()
//     ->xeq($q, $params1)
//     ->xeq($q, $params2)
//     ->rollback();

// $q = "INSERT INTO category VALUES (DEFAULT, :name)";
// $params1 = [':name' => 'Test 1'];
// $params2 = [':name' => 'Test 2'];
// DBAL::get()
//     ->start()
//     ->xeq($q, $params1)
//     ->savepoint('TEST')
//     ->xeq($q, $params2)
//     ->rollback('TEST')
//     ->commit();
// }

// // Afficher le tableau des catégories triées sur leur nom dans l'ordre ASC. (findAllBy())
// var_dump(Category::findAllBy([], ['name' => 'ASC']));
// // Afficher le tableau des produits triés sur leur prix dans l'ordre descendant. (findAllBy())
// var_dump(Product::findAllBy([], ['price' => 'DESC']));
// // Afficher le produit 5 (findOneBy())
// var_dump(Product::findOneBy(['idProduct' => 5]));
// // Créer la catégorie 3, l'hydrater, l'afficher puis charger ses produits et l'afficher à nouveau. (lazy loading de "products").
// $category = new Category(3);
// $category->hydrate();
// var_dump($category);
// $category->products;
// var_dump($category);
// // Créer un nouveau produit et lui donner l'idProduct 3, l'hydrater, l'afficher puis charger sa catégorie et l'afficher à nouveau (lazy loading de catégorie).
// $product = new Product(99);
// $product->hydrate();
// var_dump($product);
// $product->category;
// var_dump($product);


// $product = new Product();
// $product->idCategory = 3;
// $product->name = 'testPeps';
// $product->ref = 'TEST PEPS';
// $product->price = 3.15;
// $product->persist();
// var_dump($product);
// var_dump($product->idProduct);
// $product->getCategory();
// var_dump($product);

// $product = new Product(3);
// $product->hydrate();
// $product->category;
// Router::render('error404.php');