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



        if(isset($_POST['suppDuPanier'])): //********** SUP UN ELEMENT
            array_splice($_SESSION['cadie'], intval($_POST['eleSupp']), 1);
        endif;
        if(isset($_POST['suppAll'])): //*** SUP TOUS ELEMENTS
            $_SESSION['cadie'] =[];
        endif;
        //********* AJOUTER AU CADIE ***************/
        require BASE_DIR . '/views/panier.phtml';
    }
    public static function valider()
    {
        if(isset($_POST['valider']) && !empty($_POST['valider'])) //**** ADD A LA BDD
        {
            //var_dump($_SESSION['cadie']);
					if(isset($_SESSION['cadie']) && !empty($_SESSION['cadie']))
					{
            $commande_id = addCommande('paolo'/* $_SESSION['nomClient'] */, $_SESSION['cadie']); //////LE CLIENT EST MOI!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! A CHANGER
						echo 'ici';
						var_dump($commande_id);
						if(!$commande_id[0])
						{
							header('Location: index.php?page=panier&produit='.$commande_id[1]);
						};
						$_SESSION['cadie'] =[];
						
					}
        }
        elseif(isset($_POST['envoiPayment']))// POUR PAYER PLUS TARD AVEC SON NUMERO DE COMMANDE 
        {
					//********** VERIFICATION DU STATUS BDD LE STATUS DE LA COMMANDE ***********//

					$verif = verifCommandeDBB($_POST['numeroDeCommande'])['status'];
					if($verif === '0')
					{
            $commande_id = $_POST['numeroDeCommande'];  
					}
					elseif($verif === '2')
					{
						$commande_id = 'Probleme dans le payment, contacter votre banque';
					}
					elseif($verif === '4')
					{
						$commande_id = 'Déjà payé';
					};
        };
				if(isset($commande_id)) //**** RECUP PRIX TOTAL DEPUIS BDD
				{
					//var_dump(getCommandePrice($commande_id));
					$prixTotalCommande = getCommandePrice($commande_id)['prix_total_commande'];
					var_dump($prixTotalCommande);
				}
        require BASE_DIR . '/views/valider.phtml';
    }
		public static function payment()
		{
			
			
			// A TESTER
			$toujoursEnStock = true;
			$listDesCommandes = getCommande($_POST['commande_id']);
			
			foreach($listDesCommandes as $uneCommande)
			{
				$produit_id = $uneCommande['produit_id'];
				$quantitee = $uneCommande['quantitee'];
				
				if(!verifEnStock($produit_id, $quantitee))
				{
					var_dump($toujoursEnStock);
					$toujoursEnStock = false;
					break;
				};
			}
			
			//AJOUT DES FUNCTIONS DE VERIF DES COMMANDE AU MOMENT DU PAYMENT MAIS PAS TESTEE
			// $toujoursEnStock A VERIF
			
			
			
			
			
			// REJEX CB *** var_dump(strlen(preg_replace("/[\-]/", "", $_POST['numeroCB'])));
			if(isset($_POST['envoiPayment']) && intval($_POST['numeroCB']) != 0 && strlen(preg_replace("/[\s\-]/", "", $_POST['numeroCB'])) === 16 && intval($_POST['commande_id']) != 0  && intval($_POST['crypto']) != 0 && strlen(preg_replace("/[\s\-]/", "", $_POST['crypto'])) === 3 && !$toujoursEnStock)
			{
				echo 'hello';
				$date = InputDateEnDateCB($_POST['date']);
				$prixTotalCommande = getCommandePrice($_POST['commande_id'])['prix_total_commande'];
				$retour = validationPayment($_POST['numeroCB'],$_POST['crypto'],$date,$prixTotalCommande,$_POST['commande_id']/*,$name)*/);
				$retour = explode('-',$retour);
				if($retour[0] === 'ok')
				{
					$status = 4; //PAYEMENT OK
				}
				else
				{
				 	$status = 2;	//PROBLEME DE PAYEMENT
				}
				changementStatus($status,$retour[1]);
			}
			else
			{
				header ('Location:index.php?page=valider&status=2');	
			}
			//var_dump($retour);
			require BASE_DIR . '/views/payment.phtml';
			//header('Location:'.BASE_DIR.'/views/commande.phtml?status='.$status);
		}
		public static function commande()
		{
			$AllOrderCostumer = getAllCommandeUser(/*$_SESSION['nomClient']*/'paolo');
			//On fera une page detaillent la commande $uneCommande avec getCommande($commande_id)
			require BASE_DIR . '/views/commande.phtml';
		}
		public static function une_commande()
		{
			if(isset($_POST['afficheUncommande']))
			{
				$uneCommande = getCommande($_POST['commande_id']);
			}
			require BASE_DIR . '/views/une_commande.phtml';
		}

}