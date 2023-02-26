<?php

namespace inc;

use entities\User;

?>

<header>
	<div class="user">
		<?php
		if(User::getLoggedUser()) {
			?>
			<?= User::getLoggedUser()?->lastName; ?> <?= User::getLoggedUser()->firstName; ?>
			<a href="/user/logout">Déconnexion</a>
		<?php
		} else {
		?>
			<a href="/user/signin">Connexion</a>
		<?php
		}
		?>
	</div>
</header>