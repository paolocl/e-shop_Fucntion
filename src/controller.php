<?php

class Controller
{

    public static function index()
    {
        $listProduit = null;
        $listProduit = getListProduits();
        require BASE_DIR . '/views/index.phtml';
    }
    public static function panier()
    {

        //$_SESSION['cadie'] =[];
        $produitResultat = '';
        $trop = 'positif';

        $cadie = new Panier;

        if(isset($_POST['ajouterAuPanier']) && !empty($_POST['quantitee']) && is_int(intval($_POST['id']))):
            $id = $_POST['id'];
            $quantitee = $_POST['quantitee'];
            $ajout = AjoutProduit($id, $quantitee, $cadie);
            $produitResultat = $ajout[0];
            $StockDisponible = $ajout[1];

        elseif(isset($_POST['validerAll'])):
            foreach($_POST['produit_id'] as $uneId):
                if($_POST['quantitee_produit_'.$uneId] != 0 && !empty($_POST['quantitee_produit_'.$uneId])):
                    $id = $uneId;
                    $quantitee = $_POST['quantitee_produit_'.$uneId];
                    $ajout = AjoutProduit($id, $quantitee, $cadie);
                    $produitResultat = $ajout[0];
                    $StockDisponible = $ajout[1];
                endif;
            endforeach;
        endif; //***** FIN AJOUT DE PRODUIT ************/



        if(isset($_POST['suppDuPanier'])):
            array_splice($_SESSION['cadie'], intval($_POST['eleSupp']), 1);
        endif;
        if(isset($_POST['suppAll'])):
            $_SESSION['cadie'] =[];
        endif;
        //********* AJOUTER AU CADIE ***************/
        require BASE_DIR . '/views/panier.phtml';
    }
    public static function valider()
    {
        if(isset($_POST['valider']) && !empty($_POST['valider']))
        {
            //var_dump($_SESSION['cadie']);
            $commande_id = addCommande('paolo', $_SESSION['cadie']);
        }
        elseif(isset($_POST['envoiPayment']))
        {
            $commande_id = $_POST['numeroDeCommande'];
            //Requet pour recup le prix;
            //SELECT sum(quantitee*(price/100)) FROM produits_commander natural join produits where commande_id=15
        }
        require BASE_DIR . '/views/valider.phtml';
    }

}