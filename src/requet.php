<?php


//*********** LISTES PRODUITS **************/
function getListProduits()
{
    require 'connect.php';
    $query = $pdo->prepare('
        SELECT produits.name AS produit, pays.name AS pays, description, price, stock, image, produit_id, produits.pays_id as pays_id FROM produits INNER JOIN pays ON produits.pays_id = pays.pays_id
    ');

    $executer = $query->execute();

    //var_dump($executer);

    $listProduit = $query->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($listProduit);
    return $listProduit;
}
//************** AJOUT COMMANDE ******************/
function addCommande($client, $session)
{
    $commande_id = creationCommande($client);
    require 'connect.php';
    //var_dump($commande_id);
    //var_dump($session);
    foreach($session as $unProduit):
        //var_dump($unProduit);
        $produit_id = $unProduit['produit_id'];
        $quantitee = $unProduit['quantitee'];
				var_dump(verifEnStock($produit_id, $quantitee));
				if(verifEnStock($produit_id, $quantitee))
				{
					echo 'ok';
					$query = $pdo->prepare("
							INSERT INTO produits_commander
							(commande_id, produit_id, quantitee)
							VALUES (:commande_id,:produit_id,:quantitee);
					");

					$query->bindValue(':commande_id',$commande_id, PDO::PARAM_INT);
					$query->bindValue(':produit_id',$produit_id, PDO::PARAM_INT);
					$query->bindValue(':quantitee',$quantitee, PDO::PARAM_INT);

					$i = $query->execute();
					//var_dump($i);
				}
				else
				{
					echo 'la';
					return [false,$produit_id];
					break;
					
				};
	
    endforeach;
    return $commande_id;
}
//********** GET CLIENT ID ***************/
function getClientId($client){

    require 'connect.php';
    $query = $pdo->prepare("
        SELECT client_id FROM clients WHERE name = :client;
    ");
    $query->bindValue(':client', $client, PDO::PARAM_STR);

    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function creationCommande($client){

        $client_id = getClientId($client)[0];
        require 'connect.php';
        $query = $pdo->prepare("
            INSERT INTO commandes
            (client_id, heure)
            VALUES (:client_id,NOW());
        ");

        $query->bindValue(':client_id',$client_id['client_id'],PDO::PARAM_INT);

        $query->execute();
	
        return $pdo->lastInsertId();
};

///*******obtenir prix commande **************/
function getCommandePrice($commande_id)
{
	require 'connect.php';
	$query = $pdo->prepare("
		SELECT sum(quantitee*(price)) AS prix_total_commande FROM produits_commander NATURAL JOIN produits WHERE commande_id=:commande_id
	");
	
	$query->bindValue(':commande_id',$commande_id,PDO::PARAM_INT);
	
	$query->execute();
	
	return $query->fetch(PDO::FETCH_ASSOC);
	
}
///******* VERIF COMMANDE DANS BDD ********/
function verifCommandeDBB($commande_id)
{
	require 'connect.php';
	$query= $pdo->prepare("
		SELECT commande_id, status FROM commandes WHERE commande_id = :commande_id
	");
	
	$query->bindValue(':commande_id', $commande_id, PDO::PARAM_INT);
	
	$query->execute();
	
	return $query->fetch(PDO::FETCH_ASSOC);
}

//****** MISE A JOUR COMMANDE **************/
function changementStatus($status, $commande_id)
{
	require 'connect.php';
	//********** CHANGEMENT DE STATUS DE COMMANDE *******************/
	$query= $pdo->prepare("
		UPDATE commandes SET status = :status WHERE commande_id = :commande_id
	");
	$query->bindValue(':status',$status,PDO::PARAM_INT);
	$query->bindValue(':commande_id', $commande_id,PDO::PARAM_INT);
	
	$query->execute();
		
	//****************** GET PRODUIT DE LA COMMANDE AVEC LEURS QUANTITY *****/
	$query= $pdo->prepare("
		SELECT quantitee, produit_id, stock FROM produits_commander NATURAL JOIN produits WHERE commande_id = :commande_id
	");
	
	$query->bindValue(':commande_id', $commande_id,PDO::PARAM_INT);
	
	$query->execute();
	
	$quantiteeCommandee = $query->fetchAll(PDO::FETCH_ASSOC);
	
	//************** METTRE A JOUR LE STOCK *************/
	foreach($quantiteeCommandee as $unProduit)
	{
		$produit_id = $unProduit['produit_id'];
		$stock =  $unProduit['stock'] - $unProduit['quantitee'];
		
	
	$query = $pdo->prepare("
		UPDATE produits SET stock = :stock WHERE produit_id = :produit_id
	");
	
	$query->bindValue(':produit_id', $produit_id,PDO::PARAM_INT);
	$query->bindValue(':stock', $stock,PDO::PARAM_INT);
	
	$query->execute();
		
	}
		
}
//RECUP UNE COMMANDE//
function getCommande($commande_id)
{
	
	require 'connect.php';
	
	$query = $pdo->prepare("
	SELECT image, produits.name, description, pays.name, quantitee*price as prix_commande, status, quantitee  FROM `produits` INNER JOIN pays ON produits.pays_id = pays.pays_id NATURAL JOIN commandes NATURAL JOIN produits_commander WHERE commande_id = :commande_id
	");
	$query->bindValue(':commande_id', $commande_id, PDO::PARAM_INT);
	
	$query->execute();
	
	return $query->fetchAll(PDO::FETCH_ASSOC);
}
//******** RECUP TOUTE Les COMMANDE du user *****/
function getAllCommandeUser($client)
{
	$client_id = getClientId($client);
	require 'connect.php';
	$query = $pdo->prepare("
	SELECT client_id, commande_id, status  FROM commandes NATURAL JOIN clients WHERE client_id = :client_id
	");
	$query->bindValue(':client_id', $client_id, PDO::PARAM_INT);
	
	$query->execute();
	
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

//******* VERIF PRODUIT EN STOCK *********/
function verifEnStock($produit_id, $quantitee)
{
	require 'connect.php';
    $query = $pdo->prepare('
        SELECT produit_id, stock FROM produits WHERE produit_id = :produit_id
    ');
		$query->bindValue(':produit_id', $produit_id, PDO::PARAM_INT);

    $executer = $query->execute();

    //var_dump($executer);

    $listProduit = $query->fetch(PDO::FETCH_ASSOC);
    //var_dump($listProduit);
    if($listProduit['stock'] >= $quantitee && $quantitee > 0)
		{
			return true;
		}
		else
		{
			return false;
		};
		
}


















