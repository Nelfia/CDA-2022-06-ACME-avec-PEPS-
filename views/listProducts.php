<?php

declare(strict_types=1);

namespace views;

use entities\User;
use peps\core\Cfg;

$categories = $categories ?? [];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<title><?= Cfg::get('appTitle') ?></title>
	<link rel="stylesheet" href="/assets/css/acme.css" />
</head>

<body>
	<?php require 'inc/header.php' ?> 
	<main>
		<?php 
		foreach ($categories as $category) { 
		?>
			<div class="category">
				<?php 
				if(User::getLoggedUser()){
				?>
				<a href="/product/create/<?= $category->idCategory ?>">
					<img class="ico" src="/assets/img/icons/create.svg" alt="Ajouter un produit dans cette catégorie" />
				</a>
				<?php
				} 
				?>
				<?= $category->name ?>
			</div>
			<?php $category->products;
			foreach ($category->products as $product) { 
				$imagePath = "/assets/img/products/{$product->idCategory}_" . ($product->idProduct % 10) . ".png";?>
				<div class="blockProduct">
					<a href="/product/show/<?= $product->idProduct ?>">
						<img class="thumbnail" src=<?= "{$imagePath}"; ?> alt=<?= $product->name ?> />
						<div class="name"><?= $product->name ?></div>
					</a>
					<?php 
					if(User::getLoggedUser()){
					?>
					<a class="ico update" href="/product/update/<?= $product->idProduct?>">
						<img src="/assets/img/icons/update.svg" alt="Modifier un produit" />
					</a>
					<a class="ico delete" href="/product/remove/<?=$product->idProduct ?>">
						<img src="/assets/img/icons/delete.svg" alt="Supprimer le produit">
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