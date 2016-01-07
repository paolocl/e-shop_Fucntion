<?php
session_start();
//controle des page accessible
$pages = [
    'index',
		'panier',
    'valider',
		'payment',
		'commande',
];

if (isset($_GET['page']) && in_array($_GET['page'], $pages)) {
    Controller::{$_GET['page']}();
} elseif (!isset($_GET['page'])) {
		Controller::index();
} else {
    Controller::erreur404();
}