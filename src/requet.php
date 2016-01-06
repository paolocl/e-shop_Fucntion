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
        $query->fetchAll(PDO::FETCH_ASSOC);

    endforeach;
    return $commande_id;
}

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

        $query->fetchAll(PDO::FETCH_ASSOC);
        return $pdo->lastInsertId();
};