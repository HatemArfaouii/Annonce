//apii conf
$(document).ready(function() {
    $("input:file").on('change', function() {
        var fd = new FormData();
        var files = $(this)[0].files[0];
        fd.append('file', files);
        $.ajax({
            url: '/annonce/testphoto',
            data: {},
            data: fd,
            type: 'POST',
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.nudity.raw > 0.50) {
                    $.confirm({
                        icon: 'fa fa-warning',
                        title: 'Image error!',
                        content: 'Vous étes ajout mauvaise image',
                        type: 'red',
                        typeAnimated: true,
                        onclick: false,
                        buttons: {
                            cancel: function() {},
                        }
                    });
                    $("input:file").val('');
                }
            },
            error: function(e) {
                console.log(e.responseText);
            }
        });
    });
});




$(document).ready(function() {
    $('#resltat_search').hide();
    var readURL2 = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.profile-pic2').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".file-upload2").on('change', function() {
        readURL2(this);
    });
    $(".upload-button2").on('click', function() {
        $(".file-upload2").click();
    });
});


$("#saveannonce").click(function() {
    if ($(".smart-form").valid()) {
        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirmer!',
            content: 'Êtes-vous sûr de vouloir ajouter cet annonce?',
            type: 'green',
            typeAnimated: true,
            onclick: false,
            buttons: {
                confirm: function() {
                    $.smallBox({
                        title: "Validation",
                        content: "<i class='fa fa-check-circle'></i> <i> Annonce ajoutée</i>",
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


//delete annonce front
$(".delete_annonce_front").click(function() {
    var id = $(this).data('id');
    var tr = $(this).closest('tr');
    $(".valid_delete_annonce").click(function() {
        $.ajax({
            url: '/front/customer/' + id,
            data: { "id": id },
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                $("#modal_delete_annonce").modal("hide");
                tr.remove();
            },
            error: function(e) {
                console.log(e.responseText);
            }
        });

    })
});

//delete annonce back
$(".deleteannonce").click(function() {
    var id = $(this).data('id');
    var tr = $(this).closest('tr');
    $.confirm({
        icon: 'fa fa-warning',
        title: 'Confirmer la supprission!',
        content: 'Are you sure delete this annonce?',
        type: 'red',
        typeAnimated: true,
        onclick: false,
        buttons: {
            confirm: function() {
                $.ajax({
                    url: '/annonce/' + id,
                    data: { "id": id },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        tr.remove();
                        $.smallBox({
                            title: "Validation supprission",
                            content: "<i class='fa fa-trash'></i> <i> Annonce supprimée</i>",
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

//validate annonce

$(".status_annonce").click(function() {
    var id = $(this).data('id');
    $(".valid_status_annonce").click(function() {
        var field = $(this).data('field');
        var value = $(this).data('id');
        var data = {};
        data[field] = value;
        $.ajax({
            type: 'POST',
            url: '/annonce/' + id + '/statusannonce',
            dataType: 'HTML',
            data: data,
            success: function(response) {
                $("#modal_valid_annonce").modal("hide");
                var reslt = $.parseJSON(response);
                $(".count_valide").html('<i class="glyphicon glyphicon-check"></i> ' + reslt.count_valide);
                $(".count_attent").html('<i class="glyphicon glyphicon-time"></i> ' + reslt.count_attende);
                $(".count_refuse").html('<i class="glyphicon glyphicon-remove-circle"></i> ' + reslt.count_refuse);
                if (value == 1) {
                    $.smallBox({
                        title: "Validation",
                        content: "<i class='fa fa-check'></i> <i> Annonce validé</i>",
                        color: "#659265",
                        iconSmall: "fa fa-remove fa-2x fadeInRight animated",
                        timeout: 5000
                    });
                    $('#attend' + id + '').removeClass("badge bg-color-yellow inbox-badge status_annonce").addClass("badge bg-color-greenLight inbox-badge").html('' + '<i class="fa fa-check-circle"> &nbsp;' + 'Validé' + '</i>');
                }
                if (value == 2) {
                    $.smallBox({
                        title: "Validation",
                        content: "<i class='fa fa-check'></i> <i> Annonce réfuse</i>",
                        color: "red",
                        iconSmall: "fa fa-remove fa-2x fadeInRight animated",
                        timeout: 5000
                    });
                    $('#attend' + id + '').removeClass("badge bg-color-yellow inbox-badge status_annonce").addClass("badge bg-color-redLight inbox-badge").html('' + '<i class="fa  fa-times-circle"> &nbsp;' + 'Réfusé' + '</i>');
                }
            }

        });
    })


});

//edit annonce

$(".edit_annonce_sub").click(function() {
    var id = $(this).data('id');
    $("#editannonce" + id).click(function(value) {
        var data = {
            "id": id,
            "subcategory": $("#subcategory" + id).val(),
            "libelle": $("#libelle" + id).val(),
            "description": $("#description" + id).val(),
            "prix": $("#prix" + id).val(),
            "image": $("#image" + id).val(),
        }
        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirmer la modification!',
            content: 'Are you sure to update?',
            type: 'green',
            typeAnimated: true,
            onclick: false,
            buttons: {
                confirm: function() {
                    $.ajax({
                        type: 'POST',
                        url: '/annonce/' + id + '/edit',
                        data: data,
                        dataType: 'HTML',
                        success: function() {
                            $("#modal_edit_annonce" + id).modal("hide");

                            //$("#idannonce" + id).val(data.data.id);
                            console.log(annonce.libelle);
                            $(".libelle" + id).val(annonce.libelle);
                            $(".description" + id).val(annonce.description);
                            $(".prix" + id).val(annonce.prix);


                            $.smallBox({
                                title: "Validation modification",
                                content: "<i class='fa fa-trash'></i> <i> Annonce modifiée</i>",
                                color: "green",
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
});

$('.regiontn ').click(function() {
    $('#detaill_annonce').html('');

    var region = $(this).attr("value");
    $.ajax({
        type: 'POST',
        url: '/front/searchbyregion',
        data: { "region": region },
        dataType: 'json',
        success: function(data) {
            $('#resltat_search').show();
            $('#list-annonces').hide();
            $('#categories').hide();
            $('#vous-comment').hide();
            $('#statistique').hide();
            var res_count_search = data.count_annonce_by_region;
            if (res_count_search == 0) {
                $('.res_search').html('Aucune résultat trouvé (0)');
            }
            $('.res_search').html('Résultat de recherche pour cette région (' + res_count_search + ')');
            var object = data
            for (var value in object.list_annonce_by_regions) {
                $('#detaill_annonce').append(
                    '<div class="col-xs-6 col-sm-6 col-md-12 col-lg-3">' +
                    '<div class="featured-box" >' +
                    '<figure>' +
                    '<div class="icon-widh">' +
                    '<span class="price">' + object.list_annonce_by_regions[value].prix + ' Dt</span>' +
                    '</div>' +
                    '<a href="/front/' + object.list_annonce_by_regions[value].id + '/details"><img alt="" class="img-prod" src="../images/users/' + object.list_annonce_by_regions[value].filename + '"></a>' +
                    '</figure>' +
                    '<div class="feature-content">' +
                    '<div class="product">' +
                    '<a href="#">' +
                    '<i class="fa fa-folder"></i> ' +
                    '</a>' +
                    '</div>' +
                    '<h4>' +
                    '<a href=href="/front/' + object.list_annonce_by_regions[value].id + '/details">' + object.list_annonce_by_regions[value].libelle + '' +
                    '</a>' +
                    '</h4>' +
                    '<span>Dernier mise a jour : 1 heure</span>' +
                    '<ul>' +
                    '<li>' +
                    '<i class="fa fa-map-marker"></i> ' + object.list_annonce_by_regions[value].ville +
                    '</li>' +
                    '<li>' +
                    '<i class="fa fa-calendar"></i> ' + object.list_annonce_by_regions[value].dateadd +
                    '</li>' +
                    '<li>' +
                    '<i class="fa fa-gift"></i> ' + object.list_annonce_by_regions[value].type +
                    '</li>' +
                    '<li>' +
                    '</li>' +
                    '</ul>' +
                    '<div class="listing-bottom">' +
                    '<a class="btn-verified float-right" href="/front/' + object.list_annonce_by_regions[value].id + '/details">' +
                    ' <i class="fa fa-eye"></i> Voir' +
                    '</a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
            }
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });

});

// search by category

$('#category_selected ').change(function() {
    $('#detaill_annonce').html('');
    var id = $(this).val();
    $.ajax({
        type: 'POST',
        url: '/front/searchbycategory',
        data: { "id": id },
        dataType: 'json',
        success: function(data) {
            $('#resltat_search').show();
            $('#list-annonces').hide();
            $('#categories').hide();
            $('#vous-comment').hide();
            $('#statistique').hide();
            var res_count_search_by_cat = data.count_annonce_by_cat;
            if (res_count_search_by_cat == 0) {
                $('.res_search').html('Aucune résultat trouvé (0)');
            }
            $('.res_search').html('Résultat de recherche pour cette catégorie (' + res_count_search_by_cat + ')');
            var object = data
            for (var value in object.annonces_list_by_cat) {
                $('#detaill_annonce').append(
                    '<div class="col-xs-6 col-sm-6 col-md-12 col-lg-3">' +
                    '<div class="featured-box" >'

                    +
                    '<figure>' +
                    '<div class="icon-widh">' +
                    '<span class="price">' + object.annonces_list_by_cat[value].prix + ' Dt</span>' +
                    '</div>' +
                    '<a href="/front/' + object.annonces_list_by_cat[value].id + '/details"><img alt="" class="img-prod" src="../images/users/' + object.annonces_list_by_cat[value].filename + '"></a>' +
                    '</figure>' +
                    '<div class="feature-content">' +
                    '<div class="product">' +
                    '<a href="#">' +
                    '<i class="fa fa-folder"></i> ' +
                    '</a>' +
                    '</div>' +
                    '<h4>' +
                    '<a href=href="/front/' + object.annonces_list_by_cat[value].id + '/details">' + object.annonces_list_by_cat[value].libelle + '' +
                    '</a>' +
                    '</h4>' +
                    '<span>Dernier mise a jour : 1 heure</span>' +
                    '<ul>' +
                    '<li>' +
                    '<i class="fa fa-map-marker"></i> ' + object.annonces_list_by_cat[value].ville +
                    '</li>' +
                    '<li>' +
                    '<i class="fa fa-calendar"></i> ' + object.annonces_list_by_cat[value].dateadd +
                    '</li>' +
                    '<li>' +
                    '<i class="fa fa-gift"></i> ' + object.annonces_list_by_cat[value].type +
                    '</li>' +
                    '<li>' +
                    '</li>' +
                    '</ul>' +
                    '<div class="listing-bottom">' +
                    '<a class="btn-verified float-right" href="/front/' + object.annonces_list_by_cat[value].id + '/details">' +
                    ' <i class="fa fa-eye"></i> Voir' +
                    '</a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
            }
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });

});

$(document).ready(function() {
    $('.comment_annonce ').click(function() {
        var comment = $('#comment_ann').val();
        var id_annonce = $(this).val();
        $.ajax({
            type: 'POST',
            url: '/front/comment',
            data: { 'id': id_annonce, "comment": comment },
            dataType: 'json',
            success: function(data) {
                $('#comment_ann').val('');
                var object = data;

            },
            error: function(e) {
                console.log(e.responseText);
            }
        });
    });
});


$('.add_comment ').click(function() {
    $('.all_comment').html('');
    var id_annonce = $('.annonceid').val();
    $.ajax({
        type: 'POST',
        url: '/front/addcomment',
        data: { 'id': id_annonce },
        dataType: 'json',
        success: function(data) {
            $('#comment_ann').val('');
            $('.all_comment').html('');
            var object = data;
            for (var value in object.comments) {

                console.log(object);
                $('.all_comment').append(
                    '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                    '<div class="item">' +
                    '<div class="testimonial-item">' +
                    '<div class="img-thumb">' +
                    '<img src="../images/users/' + object.comments[value].usercommet.filename + '" id="bb" alt="">' +
                    '</div>' +
                    '<div class="content">' +
                    '<h2><a href="#">' + object.comments[value].usercommet.full_name + '</a></h2>' +
                    '<p class="description">' + object.comments[value].comment + '</p>' +
                    '</h3>' +
                    // '<font style="vertical-align: inherit;"><font style="vertical-align: inherit;">' + object.comments[value].dateadd + '</font></font>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
            }

        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
});

//send message
$('#btn-chat').click(function () {
    var message = $('.mesage_content').val();
    var id_annonce = $('.annonceid').val();
    $.ajax({
        type: 'POST',
        url: '/front/sendmessage',
        data: {'id': id_annonce, "message": message},
        dataType: 'json',
        success: function (data) {
            $('.mesage_content').val('');
            var object = data;
        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
});


//get message
$('.get_message ').click(function () {
    $('.all_message').html('');
    var id_annonce = $('.annonceid').val();
    $.ajax({
        type: 'POST',
        url: '/front/getmessage',
        data: {'id': id_annonce},
        dataType: 'json',
        success: function (data) {
            $('.all_message').html('');
            var object = data;
            for (var value in object.messages) {
                $('.all_message').append(
                    '<ul class="posts-list">'
                    +'<li>'
                    +'<div class="widget-thumb">'
                    +'<a href="#"><img src="../images/users/' + object.messages[value].userid.filename + '" style="width: 30px; height: 30px" alt=""></a>'
                    +'</div>'
                    +'<div class="widget-content" style="background-color: #007bb5; border-radius:10px ">'
                    +'<a style="color: #fff">'+ object.messages[value].content + '</a>'
                    +'<span style="color: #fff; font-size: 9px; text-align: right"><i class="icon-calendar"></i>' + object.messages[value].dateadd + '</span>'
                    +'</div>'
                    +'<div class="clearfix"></div>'
                    +'</li>'
                    + '</ul>'
                );
            }

        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
});

var searchRequest = null;
$("#transcript").keyup(function() {
    var minlength = 1;
    var that = this;
    var value = $(this).val();

    //alert(value);
    // var entitySelector = $("#entitiesNav").html('');
    if (value.length >= minlength) {
        if (searchRequest != null)
            searchRequest.abort();
        $.ajax({
            type: "GET",
            url: '/front/searchautocomplate',
            data: {
                'q': value
            },
            dataType: "text",
            success: function(msg) {
                if (value == $(that).val()) {
                    var result = JSON.parse(msg);
                    console.log(result);
                    $.each(result, function(key, arr) {
                        $.each(arr, function(id, value) {
                            if (key == 'entities') {
                                if (id != 'error') {
                                    console.log(value);
                                    // entitySelector.append('<li><a href="/daten/'+id+'">'+value+'</a></li>');
                                } else {
                                    alert(value);
                                    //                 entitySelector.append('<li class="errorLi">'+value+'</li>');
                                }
                            }
                        });
                    });
                }
            }
        });
    }
});