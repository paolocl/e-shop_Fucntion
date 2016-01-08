$(document).ready(function() {
	$('form.addCart').submit(function(event) {
		event.preventDefault(); //desactive comportement evenement
		var form = $(this);
		console.log(form);
		$.ajax({
			type: form.attr('method'),
			url: form.attr('action'),
			data: form.serialize() + '&ajouterAuPanier=', //isset(ajouterAuPanier)
		});
		affichagePanier().bind($('.addElemPanier'));
	});
	
	$('form.addAllToCard').submit(function(event) {
		event.preventDefault(); //desactive comportement evenement
		var form = $(this);
		console.log(form);
		$.ajax({
			type: form.attr('method'),
			url: form.attr('action'),
			data: form.serialize() + '&validerAll=',
		});
		affichagePanier().bind($('.addElemPanier'));
	});
	
		$('.clickPanier').click(affichagePanier.bind($('.clickPanier')));
});

/*************** FUNCTION *************************/

/*********** AFFICHAGE PANIER *****************/

var click = true;
function affichagePanier(event){
	console.log($(this));
	if($(this).hasClass('addElemPanier'))
	{
		if($('.interactivePanier').length === 1){
			$('.interactivePanier').remove();
		};
		click = true;
		console.log(click);
	};


	if(click)
	{
		console.log('hello');
		click = false;
		$.ajax({
			type: 'POST',
			url: 'index.php?page=panier',
			data : 'index.php?page=panier',
			success : function(data) {
				$('.monPanier').append('<div class=\'interactivePanier\'>'+data+'</div>');
			},
			error: function(){
				$('.monPanier').append('<p>ratter</p>');
			}
		});	
	}
	else if(!click)
	{
		click = true;
		$('.interactivePanier').remove();
	};
};