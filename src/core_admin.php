<?php
//controle des page accessible
$pages = [
    'commande_admin.phtml',
		'descriptif',
		'deconnexion',
		'produit_admin',
		'modif_admin',
];

if (isset($_GET['page']) && in_array($_GET['page'], $pages)) {
    ControllerAdmin::{$_GET['page']}();
} elseif (!isset($_GET['page'])) {
		ControllerAdmin::commande_admin();
} else {
    ControllerAdmin::erreur404();
}