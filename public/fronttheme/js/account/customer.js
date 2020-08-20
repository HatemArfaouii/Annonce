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

$('#summernote').summernote({
    height: 250, // set editor height
    minHeight: null, // set minimum height of editor
    maxHeight: null, // set maximum height of editor
    focus: false // set focus to editable area after initializing summernote
});


//Save customer
$("#savecustomer").click(function() {
    if ($(".smart-form").valid()) {
        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirmer!',
            content: 'Êtes-vous sûr de vouloir ajouter cet utilisateur?',
            type: 'green',
            typeAnimated: true,
            onclick: false,
            buttons: {
                confirm: function() {
                    $.smallBox({
                        title: "Validation",
                        content: "<i class='fa fa-check-circle'></i> <i> Utilisateur bien ajouté</i>",
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