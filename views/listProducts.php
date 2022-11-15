<?php

declare(strict_types=1);

use classes\Autoload;
use entities\Category;
use entities\User;

require './classes/Autoload.php';
// Initialisation de l'autoload
Autoload::init();

// Ouverture de la session utilisateur
session_start();

$categories = Category::all();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<title>ACME</title>
	<link rel="stylesheet" href="/assets/css/acme.css" />
</head>

<body>
	<?php require "inc/header.php" ?>
	<main>
		<?php 
		foreach ($categories as $category) { 
		?>
			<div class="category">
				<?php
				if(User::getLoggedUser()) {
				?>
					<a href="./editProduct.php?idCategory=<?= $category->idCategory ?>">
						<img class="ico" src="./assets/img/icons/create.svg" alt="Ajouter un produit dans cette catégorie" />
					</a>
				<?php
				}
				?>
				<?= $category->name ?>
			</div>
			<?php $category->products;
			foreach ($category->products as $product) { ?>
				<div class="blockProduct">
					<a href="/showProduct.php?idProduct=<?= $product->idProduct ?>">
						<img class="thumbnail" src=<?= "./assets/img/products/{$product->idCategory}_" . ($product->idProduct % 10) . ".png"; ?> alt=<?= $product->name ?> />
						<div class="name"><?= $product->name ?></div>
					</a>
				<?php
				if(User::getLoggedUser()) {
				?>					
					<a class="ico update" href="./editProduct.php?idProduct=<?= $product->idProduct?>">
						<img src="./assets/img/icons/update.svg" alt="Modifier un produit" />
					</a>
					<a class="ico delete" href="/removeProduct.php?idProduct=<?=$product->idProduct ?>">
						<img src="./assets/img/icons/delete.svg" alt="Supprimer le produit">
					</a>
				<?php
				}
				?>
				</div>
			<?php 
		}
	} ?>
	</main>
	<footer></footer>
</body>

</html>