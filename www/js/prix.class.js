//***** NE FONCTIONNE PAS ***********/

function Prix (input) {
        this.input = input;
}

function affichagePrix() {
    var name = $(this).attr('data-id');
    return $('input[data-name="'+name+'"]').val($(this).val()*$('input[data-name="'+name+'"]').attr('data-prix'));
};

Prix.prototype.selection = function(){
    return this.input;
};

Prix.prototype.changement = function(){
    this.input.each(affichagePrix());
    this.input.keyup(affichagePrix());
    this.input.click(affichagePrix());
};

