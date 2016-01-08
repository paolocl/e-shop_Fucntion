<?php

class ControllerAdmin
{
    public static function commande_admin()
    {
				require 'connect.php';
				
				$query = $pdo->prepare("
					SELECT client_id, commande_id, 
					status , COUNT( produit_id ) AS nombre_element, SUM( quantitee * price ) AS prix_commande
					FROM commandes
					NATURAL JOIN produits
					NATURAL JOIN produits_commander
					GROUP BY commande_id
					ORDER BY status, commande_id
				");	

				$query->execute();

				$listCommande = $query->fetchAll(PDO::FETCH_ASSOC);
				//var_dump($listCommande);
        require BASE_DIR . '/views/commande_admin.phtml';
    }
	public static function descriptif()
	{
		//var_dump($_GET['commande']);
				
		$commande = getCommande($_GET['commande']);
		//var_dump($commande);
		require BASE_DIR . '/views/descriptif_admin.phtml';
	}
	public static function produit_admin()
	{
		$listProduit = getListProduits();
		$listPays = getPays();
		require BASE_DIR . '/views/produit_admin.phtml';
	}
	public static function modif_admin()
	{
		if(isset($_POST['pays']))
		{
			$pays_id = getPaysByName($_POST['pays']);
			var_dump($pays_id);
		};
		
		if(isset($_POST['supprimer']) && !empty($_POST['supprimer']))
		{
		var_dump($_POST);
		deletProduit($_POST['produit_id']);	
		}
		elseif(isset($_POST['modifier']) && !empty($_POST['modifier']))
		{
			$_POST['pays_id'] = $pays_id['pays_id'];
		var_dump($_POST);
		$_POST['price'] = $_POST['price']*100;
		$datas = getUpdateSQL($_POST);
			var_dump($datas);
		modifProduit($datas, $_POST['produit_id']);
		}
		require BASE_DIR . '/views/modif_admin.phtml';
	}
	public static function deconnexion()
	{
		$_SESSION = [];
		require BASE_DIR . '/www/connection_admin.php';
	}
}