$('#add-image').click(function(){
    // Je récupère le numéro des futurs champs que je vais créer
    const index = +$('#widgets-counter').val(); //Le '+' transforme la valeur récupérée en nombre

    // Je récupère le prototype des entrées
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index); //g = je veux remplacer __name__ par index PLUSIEURS FOIS

    // J'injecte ce code au sein de la div
    $('#annonce_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    //Je gère le bouton supprimer
    handleDeleteButtons();
});

//Gère les bouttons de suppression
function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target; //je veux acceder à l'attribut target de this

        $(target).remove();
    });
}

function updateCounter(){
    const count = +$('#annonce_images div.form-group').length;

    $('#widgets-counter').val(count);
    console.log(count);
}

updateCounter();
handleDeleteButtons();