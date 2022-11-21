<?php

declare(strict_types=1);

namespace controllers;

use entities\Truc;
use peps\core\DBAL;
use stdClass;

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
        var_dump(Truc::describe());
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