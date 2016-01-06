<?php
//************ AJOUT DE PRODUIT *************/
function AjoutProduit($id, $quantitee, $cadie)
{
    $listProduit = getListProduits();
    $produitResultat = '';
    $trop = 'positif';
    foreach($listProduit as $unProduit):
        if($unProduit['produit_id'] === $id):
            $StockDisponible = $unProduit['stock'];


            //********************* SI CA EXISTE DEJA ****************************//

            $retourExist = $cadie->has($unProduit['produit_id'],$quantitee, $StockDisponible);
            if($retourExist != 'negatif' && $retourExist != null):
                $quantitee = floatval($retourExist);
            elseif($retourExist === 'negatif'):
                $trop = 'negatif';
                return ['ko',$unProduit['stock']];
            endif;

            //*********** AJOUT DE L'ELEMENT **************/
            if($quantitee<= $unProduit['stock'] && $trop === 'positif'):
                $arrProduit = [
                    'produit_id' =>$unProduit['produit_id'],
                    'name' => $unProduit['produit'],
                    'description' => $unProduit['description'],
                    'pays_id' => $unProduit['pays_id'],
                    'price' => $unProduit['price'],
                    'quantitee' => $quantitee,
                    'image' => $unProduit['image'],
                    'pays' => $unProduit['pays']
                ];
                $cadie->add($arrProduit);
                return ['ok',$unProduit['stock']];
            endif;
        endif;
    endforeach;
}
