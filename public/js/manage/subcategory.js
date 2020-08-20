// ajouter sous-categorie
$(document).ready(function() {
    $("#savesubcategory").click(function() {
        if ($(".smart-form").valid()) {
            $.smallBox({
                title: "Validation",
                content: "<i class='fa fa-check'></i> <i> Sous-Catégorie bien ajouté</i>",
                color: "#659265",
                iconSmall: "fa fa-remove fa-2x fadeInRight animated",
                timeout: 5000
            });
        }
    });
});


//modifier sous-categorie
$(".editrow_sub_cat").each(function(elem) {
    var that = $(this);
    that.editable(function(value, settings) {
        var cellval = value;
        var attrcomp = $(this).attr('attrcomp');
        var origvalue = this.revert;
        var field = $(this).data('field');
        var id = $(this).data('id');
        var data = {};
        data[field] = value;
        $.ajax({
            type: 'POST',
            url: '/subcategory/' + id + '/edit',
            data: data,
            dataType: 'HTML',
            success: function(data) {
                if (origvalue != cellval) {
                    /*
                     * SmartAlerts success
                     */
                    $.smallBox({
                        title: "Validation",
                        content: "<i class='fa fa-check-circle'></i> <i> Modification (" + origvalue + ") à (" + cellval + " ) bien determiné</i>",
                        color: "#659265",
                        iconSmall: "fa fa-check fa-2x fadeInRight animated",
                        timeout: 5000
                    });
                }
            },
            error: function(error) {
                if (origvalue != cellval) {
                    /*
                     * SmartAlerts error
                     */
                    $.smallBox({
                        title: "Erreur",
                        content: "<i class='fa fa-close'></i> <i>Erreur de modification, form n'est pas valide </i>",
                        color: "#C46A69",
                        iconSmall: "fa fa-warning fa-2x fadeInRight animated",
                        timeout: 4000
                    });
                    return origvalue;

                }
            }
        });
        return value;
    }, {
        type: that.attr('attrcomp'),
        event: 'dblclick',
        width: '100%',
        tooltip: "Double click pour modifier la valeur de ce champs",
        onblur: 'submit',
        // submit    : 'OK',
        // cancel    : 'cancel',
    });
})

//suuprimer sous-categorie
$(".delete_sub_category").click(function() {
    var id = $(this).data('id');
    var tr = $(this).closest('tr');
    $.confirm({
        icon: 'fa fa-warning',
        title: 'Confirmer la supprission!',
        content: 'Are you sure delete this subcategory?',
        type: 'red',
        typeAnimated: true,
        onclick: false,
        buttons: {
            confirm: function() {
                $.ajax({
                    url: '/subcategory/' + id,
                    data: { "id": id },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        tr.remove();
                        $.smallBox({
                            title: "Validation supprission",
                            content: "<i class='fa fa-trash'></i> <i> Sous-Catégorie supprimé</i>",
                            color: "red",
                            iconSmall: "fa fa-remove fa-2x fadeInRight animated",
                            timeout: 5000
                        });
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            },
            cancel: function() {},
        }
    });

});