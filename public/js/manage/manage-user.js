$(document).ready(function() {
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.profile-pic').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".file-upload").on('change', function() {
        readURL(this);
    });
    $(".upload-button").on('click', function() {
        $(".file-upload").click();
    });

});

//ajouter user
$("#saveuser").click(function() {
    if ($(".smart-form").valid()) {
        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirmer!',
            content: 'Êtes-vous sûr de vouloir confirmer?',
            type: 'green',
            typeAnimated: true,
            onclick: false,
            buttons: {
                confirm: function() {
                    $.smallBox({
                        title: "Validation",
                        content: "<i class='fa fa-check-circle'></i> <i> Succés</i>",
                        color: "#659265",
                        iconSmall: "fa fa-check fa-2x fadeInRight animated",
                        timeout: 5000
                    });
                    $("#order-form").submit()
                },
                cancel: function() {},
            }
        });

    } else {
        onclick: false
    }
});
//supprimer user
$(".deleteuser").click(function() {
    var id = $(this).data('id');
    var tr = $(this).closest('tr');
    $.confirm({
        icon: 'fa fa-warning',
        title: 'Confirmer la supprission!',
        content: 'vous ete sur de supprimer cet user?',
        type: 'red',
        typeAnimated: true,
        onclick: false,
        buttons: {
            confirm: function() {
                $.ajax({
                    url: '/user/delete/' + id,
                    data: { "id": id },
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        tr.remove();
                        $.smallBox({
                            title: "Validation supprission",
                            content: "<i class='fa fa-trash'></i> <i> Utilisateur supprimé</i>",
                            color: "red",
                            iconSmall: "fa fa-remove fa-2x fadeInRight animated",
                            timeout: 5000
                        });
                    },
                    error: function(e) {
                        // console.log(e.responseText);
                    }
                });
            },
            cancel: function() {},

        }
    });
});




//
// //bloquer user
// $(".status_user").click(function() {
//     var id = $(this).data('id');
//     $(".valid_status_user").click(function () {
//         var field = $(this).data('field');
//         var value = $(this).data('id');
//         var data = {};
//         data[field] = value;
//         $.ajax({
//             type: 'POST',
//             url: '/user/' + id + '/statususer',
//             dataType: 'HTML',
//             data: data,
//             success: function (response) {
//                 $("#modal_valid_user").modal("hide");
//                 var reslt = $.parseJSON(response);
//                 if (value == 1) {
//                     $.smallBox({
//                         title: "Validation",
//                         content: "<i class='fa fa-check'></i> <i> User bloqué</i>",
//                         color: "#659265",
//                         iconSmall: "fa fa-remove fa-2x fadeInRight animated",
//                         timeout: 5000
//                     });
//                     $('#active' + id + '').removeClass("badge bg-color-greenLight inbox-badge status_user").addClass("badge bg-color-redLight inbox-badge").html('' + '<i class="fa fa-check-circle"> &nbsp;' + 'Bloqué' + '</i>');
//                 }
//         }
//         })
// })
// });