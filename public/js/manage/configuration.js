$(document).ready(function () {
   

});


$("#saverole").click(function () {
    if($(".smart-form").valid()) {
        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirmer!',
            content: 'Êtes-vous sûr de vouloir ajouter cet role?',
            type: 'green',
            typeAnimated: true,
            onclick: false,
            buttons: {
                confirm: function () {
                    $.smallBox({
                        title: "Validation",
                        content: "<i class='fa fa-check-circle'></i> <i> Role bien ajouté</i>",
                        color: "#659265",
                        iconSmall: "fa fa-check fa-2x fadeInRight animated",
                        timeout: 5000
                    });
                    $("#order-form").submit()
                },
                cancel: function () {
                },
            }
        });

    } else {
        onclick: false
    }
});



$(".deleterole").click(function () {
    var id = $(this).data('id');
    var tr = $(this).closest('tr');
    $.confirm({
        icon: 'fa fa-warning',
        title: 'Confirmer la supprission!',
        content: 'Are you sure delete this role?',
        type: 'red',
        typeAnimated: true,
        onclick: false,
        buttons: {
            confirm: function () {
                $.ajax({
                    url: '/role/' + id,
                    data: {"id": id},
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        tr.remove();
                        $.smallBox({
                            title: "Validation supprission",
                            content: "<i class='fa fa-trash'></i> <i> role supprimer</i>",
                            color: "red",
                            iconSmall: "fa fa-remove fa-2x fadeInRight animated",
                            timeout: 5000
                        });
                    },
                    error: function (e) {
                        console.log(e.responseText);
                    }
                });
            },
            cancel: function () {
            },

        }
    });
});