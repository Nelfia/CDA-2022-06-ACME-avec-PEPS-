<?php

declare(strict_types=1);

use classes\Autoload;
use classes\Cfg;
use entities\Product;

require './classes/Autoload.php';
// Initialisation de l'autoload
Autoload::init();

// Ouverture de la session utilisateur
session_start();

$idProduct = filter_input(INPUT_GET, 'idProduct', FILTER_VALIDATE_INT);
if(!$idProduct || $idProduct <= 0) {
    header('Location:error404.php');
    exit('Redirection: idProduct invalide !');
};
$product = new Product($idProduct);
if(!$product->hydrate()){
	header('Location:error404.php');
    exit('Redirection: idProduct inexistant!');
};
$imgPath = "./assets/img/products/{$product->idCategory}_" . ($product->idProduct % 10) . ".png";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<title>ACME</title>
	<link rel="stylesheet" href="/assets/css/acme.css" />
</head>

<body>
	<?php require 'inc/header.php' ?>
	<main>
		<div class="category">
			<a href="/listProducts.php">Produits</a> &gt; <?= $product->name ?>
		</div>
		<div id="detailProduct">
			<img src=<?= $imgPath ?> alt="<?= $product->name;?>" />
			<div>
				<div class="price"><?= Cfg::format2DecFr($product->price) ?> €</div>
				<div class="category">catégorie<br />
					<?= $product->category?->name; ?></div>
				<div class="ref">référence<br />
					<?= $product->ref ?></div>
			</div>
		</div>
	</main>
	<footer></footer>
</body>

</html>