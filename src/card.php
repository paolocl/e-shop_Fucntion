<?php
/**
 * Created by PhpStorm.
 * User: wap26
 * Date: 05/01/16
 * Time: 15:06
 */

class Panier {
    private $products;

    public function __construct()
    {
        if (isset($_SESSION['cadie'])) {
            $this->products = $_SESSION['cadie'];
        } else {
            $this->products = [];
        }
    }


    //*************** AJOUTER ***************//
    public function add(array $product)
    {
        array_push($this->products, $product);
        $this->transfereASession();
    }



    public function has($id, $quantitee, $quantiteeTotal)
    {
        foreach($this->products as $key => $oneProduct)
        {
            if($oneProduct['produit_id'] === $id)
            {
                $nouvelleQuantitee = $quantitee + $oneProduct['quantitee'];
                if($nouvelleQuantitee <= $quantiteeTotal)
                {
                    array_splice($this->products, $key, 1);
                    $this->transfereASession();
                    return $nouvelleQuantitee;
                }
                else
                {
                    return 'negatif';
                }
            }
        }
        if(empty($this->products))
        {
            return $quantitee;
        }
    }

    public function transfereASession()
    {
        $_SESSION['cadie'] = $this->products;
    }
    public function getPanier(){
        return $this->products;
    }



//    public static function ajoutItemPanier($unProduit, $quantitee){
//        $arrProduit = [
//            'produit_id' =>$unProduit['produit_id'],
//            'name' => $unProduit['produit'],
//            'description' => $unProduit['description'],
//            'pays_id' => $unProduit['pays_id'],
//            'price' => $unProduit['price'],
//            'quantitee' => $quantitee,
//            'image' => $unProduit['image'],
//            'pays' => $unProduit['pays']
//        ];
//        array_push($_SESSION['cadie'],$arrProduit );
//
//    }
//
//    public static function dejaExist($session, $post, $unProduit){
//        foreach($session as $key => $unElemCadie):
//            var_dump($unElemCadie);
//            var_dump($post);
//            //var_dump($session);
//            if($unElemCadie['produit_id'] === $post['id']):
//                $nouvelleQuantitee = $post['quantitee'] + $unElemCadie['quantitee'];
//                //var_dump($nouvelleQuantitee);
//                if($nouvelleQuantitee <= $unProduit['stock']):
//                    return $nouvelleQuantitee;
//                    array_splice($_SESSION['cadie'], $key, 1);
//                else:
//                    return $trop = 'negatif';
//                endif;
//            endif;
//        endforeach;
//    }

}