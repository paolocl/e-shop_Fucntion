//$(document).ready( function(event){
//        input = new Prix($('.quantiteProduit'));
//        input.changement();
//
//});




$(document).ready( function(event){
        $('.quantiteProduit').each(affichagePrix);
        $('.quantiteProduit').keyup(affichagePrix);
        $('.quantiteProduit').click(affichagePrix);



});



function affichagePrix(){
    var name = $(this).attr('data-id');
    var somme = $(this).val()*$('input[data-name="'+name+'"]').attr('data-prix');
    //SOMME PAR ELEMENT//
    $('input[data-name="'+name+'"]').val(somme);
    //*************** SOMME PAR ELEMENT DANS L'INPUT POUR PANIER TOTAL ***************/
    $('.quantiteePour'+name).val($(this).val());
    //**************** SOMME TOTAL ***************/
    $('#total').html(affichageTotal);

    if(parseFloat($(this).val()) > parseFloat($('.quantiteeDisponible'+name).val()))
    {
        $(this).parent().nextAll('input[type="submit"]').attr('disabled', 'disabled');
        $('#validerAll').attr('disabled', 'disabled');
    };
};



function affichageTotal()
{
    var total = 0;
    $('.quantiteProduit').each(function(){
        var name = $(this).attr('data-id');
        total += parseFloat($('input[data-name="'+name+'"]').val());
    });
    return total.toFixed(2);
}
