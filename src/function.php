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



//****************** API CURL *******************/
function validationPayment($umeroCB,$crypto,$date,$prixTotalCommande,$commande_id/*,$name*/)
{
	$donneeAEnvoyer = ['number'=>$umeroCB, 'crypto'=>$crypto, 'endof'=>$date, 'amount'=>$prixTotalCommande, 'parameter'=>$commande_id/*,'name'=>$name*/];
	$donneeAEnvoyerURL = mettreGET($donneeAEnvoyer);

	$rsc = curl_init();
	curl_setopt($rsc, CURLOPT_URL, '192.168.1.91/dev/api/arnac-coeur-bank/'.$donneeAEnvoyerURL);
	curl_setopt($rsc, CURLOPT_RETURNTRANSFER, true);
	
	$retour = curl_exec($rsc);
	curl_close($rsc);
	
	return $retour;
}


//********** CONVERTION DU INPUT DATE ************/>
function InputDateEnDateCB($dateInput)
{
	$date = explode('-',$dateInput);
	array_splice($date,2,1);
	$date = implode($date);
	//var_dump($date);
	return $date;
}



//***************** CREATION PARAMETTRE GET POUR API ***********************/
function mettreGET($array)
{
	$arrayResult=[];
	$i = 0;
	foreach($array as $key => $value)
	{
		($i === 0) ? array_push($arrayResult, $key.'='.$value) : array_push($arrayResult, '&'.$key.'='.$value);
		$i++;
	}
	$getResult = '?'. implode('',$arrayResult);
	return $getResult;
	
}


//*********** CREATION TABLE POUR MODIF **************/

function getUpdateSQL(array $datas)
{
	$Resultat = [];
	foreach($datas as $key => $oneData)
	{
		if($key != 'supprimer' && $key != 'modifier' && $key != 'produit_id' && $key != 'pays')
		{
			if(is_numeric($oneData))
			{
				array_push($Resultat, $key .' = '.$oneData);
			}
			else
			{
				array_push($Resultat, $key .' = "'.$oneData.'"');
			}
			echo 'success';
		}
	}
	var_dump($Resultat);
	
	return implode(', ',$Resultat);
}


















