//feedback
function saveForm(e) {

    let form_id = $(e).data('form-id');
    $('#' + form_id).submit(function (event) {

        event.preventDefault();
    });

    let form = $('#' + form_id);
    // var serializedForm = $(form).find("select, textarea, input").serializeArray();

    let serializedForm = new FormData(form[0]);

    if (!$(form)) {
        return;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: $(form).attr('action'),
        data: serializedForm,
        enctype: 'multipart/form-data',
        processData: false,  // Important!
        contentType: false,
        cache: false,
        success: function (response) {

            //Remove error message
            form.find('label.error').remove();

            if (response.status == true) {

                //Recaptcha reset
                //getRecaptcha('/contacts', 'recaptcha-contacts-form');

                $('.error-input').removeClass('error-input');

                Notiflix.Notify.Success(response.message, {
                        position: 'center-top',
                        timeout: 4000
                    }
                );

                setTimeout(function () {
                    if (response.redirect != null) {
                        window.location.href = response.redirect;
                    }
                }, 4000);

                //remove inputs values after send message
                form.find('input[type=text],input[type=email], input[type=password], input[type=date], textarea').val('');
                $('#agree').prop('checked', false);
            } else {
                if (response.messages != null) {

                    $.each(response.messages, function (ObjNames, ObjValues) {
                        if (ObjNames == 'agree')
                            form.find("[name='" + ObjNames + "']").parent().find('.aggreement-checkbox').addClass('error-input');
                        else {
                            form.find("[name='" + ObjNames + "']").addClass('error-input');
                            form.find("[name='" + ObjNames + "']").after('<label class="error ' + ObjNames + '" for="' + ObjNames + '">' + '<strong>' + ObjValues + '</strong>' + '</label>');
                        }
                    });

                    setTimeout(function () {
                        $(".error-input").removeClass('error-input');
                        //Remove error message
                        form.find('label.error').remove();
                    }, 5000);
                } else {
                    Notiflix.Notify.Failure(response.message, {
                            position: 'center-top',
                            timeout: 4000
                        }
                    );
                }
            }
        }
    })
}






//Add goods to cart
$(document).ready(function () {
    $(document).on('click', '.add-to-basket, .product-end-add-to-basket', function (e) {

        e.preventDefault();
        let lang = $('html').attr('lang');
        let url = '/' + lang + '/cartElements/goods';
        let goods_id = $(this).data('id');
        //count value (+-) from main-page
        let goods_count = $(this).parent().find('input').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: goods_id,
                number: goods_count
                //page: page
            },
            success: function (response) {
                if (response.status == true) {
                    $('.num-cart').html(response.basket_count);

                    Notiflix.Notify.Success(response.message, {
                            position: 'center-top',
                            timeout: 4000
                        }
                    );
                }
            }
        })
    });
});


// Change goods count(+,-) on cart page
$(document).on('click', '.count-minus, .count-plus', function (e) {
    e.preventDefault();
    let _this = $(this).parent().parent().find('input');
    diffSumCart(_this);
});

$('.basket-item-quantity').on('change', function (e) {
    e.preventDefault();
    let _this = $(this);
    diffSumCart(_this);
});

//Count items(-+) in item page and cart
function diffSumCart(_this) {

    let lang = $('html').attr('lang');
    let url = '/' + lang + '/diffSumItems/goods';
    let goods_id = _this.attr('data-id');
    let page = _this.attr('data-page');
    let number = _this.val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: url,
        data: {
            id: goods_id,
            page: page,
            number: number
        },
        success: function (response) {
            if (response.status == true) {

                if (response.page == 'main-page') {
                    //$('#add_to_cart_form input[name=number]').val(response.number);
                }
                else {
                    //For cart page
                    _this.parent().parent().parent().find('.price-f-row span').html(parseInt(response.total_item_price));
                    $('.final-price span').html(parseInt(response.total_price));
                }
            }
        }
    })
}

//Delete Item from basket
$('.basket-remove').on('click', function (e) {
    e.preventDefault();

    let _this = $(this);

    let lang = $('html').attr('lang');
    let url = '/' + lang + '/destroyCartElements/goods';
    let goods_id = $(this).attr('data-id');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: url,
        data: {
            id: goods_id
        },
        success: function (response) {
            if (response.status == true) {
                if (response.basket_count > 0) {
                    _this.parent().parent().parent().remove();
                    $('.num-cart , .final-items-col span').html(response.basket_count);
                    $('.final-price span').html(parseInt(response.total_price));

                    Notiflix.Notify.Failure(response.message, {
                            position: 'center-top',
                            timeout: 4000
                        }
                    );
                } else {
                    $('.cart-btn').removeClass('full');

                    Notiflix.Notify.Failure(response.message, {
                            position: 'center-top',
                            timeout: 4000
                        }
                    );

                    setTimeout(function () {
                        location.reload();
                    }, 4000);
                }
            }
        }
    });
});
$(".send-bth").on('click', function(){
    event.preventDefault();
    newOrder($(this).data('form-id'));
})
//Create new order
function newOrder(parentThat) {

    let lang = $('html').attr('lang');
    let url = '/' + lang;

    let form_id = $(this).data('form-id');

    $('#'+form_id).submit(function (event) {
        event.preventDefault();
    });

    let form = $('#' + parentThat);

   let order_form = form.find("select, textarea, input, radio").serializeArray();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: $(form).attr('action'),
        data: order_form,
        enctype: 'multipart/form-data',
        success: function (response) {
            //Remove error message
            form.find('label.error').remove();
            if (response.status == true) {

                //Recaptcha reset
                getRecaptcha('/cart', 'recaptcha-form-new-order');

                Notiflix.Notify.Success(response.message, {
                        position: 'center-top',
                        timeout: 5000
                    }
                );

                setTimeout(function () {
                    if (response.redirect != null) {
                        window.location.href = response.redirect;
                    }
                }, 5000);

                /*if (response.redirect != null)
                    window.location.href = response.redirect;*/

            } else {
                if (response.messages != null) {
                    $.each(response.messages, function (ObjNames, ObjValues) {
                        if (ObjNames == 'agree') {
                            form.find("[name='" + ObjNames + "']").parent().find('.aggreement-checkbox').addClass('error-input');
                        } else {
                            form.find("[name='" + ObjNames + "']").addClass('error-input');
                            form.find("[name='" + ObjNames + "']").after('<label class="error ' + ObjNames + '" for="' + ObjNames + '">' + '<strong>' + ObjValues + '</strong>' + '</label>');
                        }
                    });

                    setTimeout(function () {
                        $(".error-input").removeClass('error-input');
                        //Remove error message
                        form.find('label.error').fadeOut();
                    }, 6000);
                } else {

                }
            }
        }
    });
}

///for filter
$('.filter input, .filter select').on('change', function () {
    let my_form_id = $(this).parents('#filter-data').get(0);
    filterForm(my_form_id);
});
$('.slider-price').on('change', function () {
    let my_form_id = $(this).parents('#filter-data').get(0);
    filterForm(my_form_id);
});

///Filter
function filterForm(parentThat) {

    let form_id = $(parentThat).data('form-id');
    $('#' + form_id).submit(function (event) {
        event.preventDefault();
    });

    $('[data-type="ckeditor"]').each(function (index, el) {
        $(this).val(CKEDITOR.instances.body.getData())
    });

    let form = $('#' + $(parentThat).data('form-id'));
//	var search_form = $('#search-form').find('input[name=s]');
    let serializedForm = $(form).find("select, textarea, input").serializeArray();

    serializedForm.push({name: 'data-parent', value: form.attr('data-parent')});
//	serializedForm.push({name: 's', value: search_form.val()});

    if (!$(form)) {
        return;
    }
    $.ajax({
        method: "POST",
        url: $(form).attr('action'),
        beforeSend: function () {
            $('.render-block').hide();
            $('.loader').fadeIn(500);
        },
        /*beforeSend: function () {
            $('.filter-page__list').fadeOut(100);
        },*/
        data: serializedForm,
        success: function (response) {
            if (response.status == true) {
                //$('span.total-count').html(response.total_elements);

                let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + response.messages;
                window.history.pushState({path: newUrl}, '', newUrl);
                $('.render-block').html(response.view);
                setTimeout(function () {
                    $('.loader').hide();
                    $('.render-block').fadeIn(500);
                }, 500);

//					if(response.total_elements == 0) {
//						$('.product').html('<div class="empty-list"><span> No items</span></div>');
//					}
            }
            else {
                let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.pushState({path: newUrl}, '', newUrl);
                //location.reload();
            }
        },
        error: function () {
            $('.sk-double-bounce').fadeOut(500);
        }
    })
}

$('.add-to-fav-it, .product-end-add-to-wish').on('click', function () {

    let id = $(this).attr('data-id');
    //let wish = $(this).data('wish');
    let lang = $('html').attr('lang');
    let url = '/' + lang + '/favAdd';
    let _this = $(this);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: url,
        data: {
            'id': id,
        },
        success: function (response) {
            if (response.status == true) {
                $('.header-wish-count').html(response.wish_item);

                /* if(response.wish_item == 1){
                     $('.wish-'+goods_id).addClass('active');
                 }*/

                if (response.wish_item == 0) {
                    //_this.removeClass('active');
                    //_this.attr('data-wish', 0);
                    $('.wish-' + goods_id).removeClass('active');
                    $('.wish-' + goods_id).attr('data-wish', 0);
                }
            }
        }
    });
});

$('.remove-wish-item').on('click', function () {

    let goods_id = $(this).attr('data-id');
    let lang = $('html').attr('lang');
    let url = '/' + lang + '/destroyItemWish';
    let _this = $(this);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: url,
        data: {
            'goods_id': goods_id,
        },
        success: function (response) {
            if (response.status == true) {
                $(_this).closest('.special-item').remove();
                $('.main-ttl-on-page span').html(response.wish_count);
                if (response.wish_count == 0) {
                    document.location.reload();

                }
            }
        }
    });
});

$(document).on('click', '.add-to-compare, .product-end-add-to-compare', function () {

    let goods_id = $(this).attr('data-goods-id');
    let goods_subject_id = $(this).attr('data-goods-subject-id');
    let lang = $('html').attr('lang');
    let url = '/' + lang + '/compareElements/goods';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: url,
        data: {
            'goods_id': goods_id,
            'goods_subject_id': goods_subject_id,
        },
        success: function (response) {
            if (response.status == true) {
                $('.header-compare-count').html(response.compare_count);
                $('.header-compare-items').html(response.header_compare_items_view);


                if (response.compare_item == 0) {

                    Notiflix.Notify.Success(response.message, {
                            position: 'center-top',
                            timeout: 2000
                        }
                    );

                    $('.compare-' + goods_id).removeClass('bg-gray-700 text-white');
                    $('.compare-' + goods_id).addClass('bg-gray-100 hover:bg-gray-700 hover:text-white');
                    $('.compare-' + goods_id).attr('data-compare', 0);
                } else {

                    Notiflix.Notify.Success(response.message, {
                            position: 'center-top',
                            timeout: 2000
                        }
                    );

                    $('.compare-' + goods_id).addClass('bg-gray-700 text-white');
                    $('.compare-' + goods_id).removeClass('bg-gray-100 hover:bg-gray-700 hover:text-white');
                    $('.compare-' + goods_id).attr('data-compare', 1);
                }
            }
        }
    });
});

//Remove item from compare list
$(document).on('click', '.remove-compare-item', function () {

    let goods_id = $(this).attr('data-goods-id');
    let lang = $('html').attr('lang');
    let url = '/' + lang + '/destroyCompareElements/goods';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: url,
        data: {
            'goods_id': goods_id
        },
        success: function (response) {
            if (response.status == true) {
                if (response.compare_count > 0) {
                    $('.header-compare-count').html(response.compare_count);
                    $('.header-compare-items').html(response.header_compare_items_view);

                    Notiflix.Notify.Success(response.message, {
                            position: 'center-top',
                            timeout: 2000
                        }
                    );

                    setTimeout(function () {
                        location.reload();
                    }, 2000);

                } else {
                    $('.header-compare-count').html(response.compare_count);
                    $('.header-compare-items').html(response.header_compare_items_view);

                    Notiflix.Notify.Success(response.message, {
                            position: 'center-top',
                            timeout: 2000
                        }
                    );

                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            }
        }
    });
});

//Delete all items from compare
$(document).on('click', '.remove_all_compare_items', function (e) {
    e.preventDefault();

    let lang = $('html').attr('lang');
    let url = '/' + lang + '/destroyAllCompareItems/goods';
    let segment_2 = '';
    let link = window.location.pathname.split('/');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: url,
        success: function (response) {
            $('.header-compare-items').html(response.header_empty_compare_view);
            $('.header-compare-count').html(response.compare_count);

            if (link.length > 2) {
                segment_2 = link[2];

                if (segment_2 === 'compare-list') {
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            }
        }
    });
});

$('.block-with-av-sort a').on('click', function (e) {
    e.preventDefault();
    changeSort($(this).data('type'),$(this).data('filt-value'));
});

function changeSort(type, val) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: '/ajaxSortPage',
        beforeSend: function () {
            $('.sk-cube-grid').fadeIn();
        },
        data: {
            type: type,
            sorting: val
        },
        success: function (response) {
            $('.sk-cube-grid').fadeOut();

            if (response.status == true) {
                location.reload();
            }
        },
        error: function () {
            location.reload();
        }
    })
}

$(document).ready(function () {
    $(document).on('click', '.createPdf', function (e) {

        e.preventDefault();
        let lang = $('html').attr('lang');
        let url = '/' + lang + '/createPdf';
        let id = $(this).data('id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id:id,
                //page: page
            },
            success: function (response) {
                if (response.status == true) {
                    $('.num-cart').html(response.basket_count);

                    Notiflix.Notify.Success(response.message, {
                            position: 'center-top',
                            timeout: 4000
                        }
                    );
                }
            }
        })
    });
});


//feedback
function calcThis(e) {

    let form_id = $(e).data('form-id');
    $('#' + form_id).submit(function (event) {

        event.preventDefault();
    });

    let form = $('#' + form_id);
    // var serializedForm = $(form).find("select, textarea, input").serializeArray();

    let serializedForm = new FormData(form[0]);

    if (!$(form)) {
        return;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: $(form).attr('action'),
        data: serializedForm,
        enctype: 'multipart/form-data',
        processData: false,  // Important!
        contentType: false,
        cache: false,
        success: function (response) {

            //Remove error message
            form.find('label.error').remove();

            if (response.status == true) {

                //Recaptcha reset
                //getRecaptcha('/contacts', 'recaptcha-contacts-form');

                $('.error-input').removeClass('error-input');

                $('.result').html(response.result);
                $('.result').addClass('active');

                $([document.documentElement, document.body]).animate({
                    scrollTop: $(".anchor").offset().top
                }, 500);

                setTimeout(function () {
                    $('.result').removeClass('active');
                },3000)

                // Notiflix.Notify.Success(response.message, {
                //         position: 'center-top',
                //         timeout: 4000
                //     }
                // );

                // setTimeout(function () {
                //     if (response.redirect != null) {
                //         window.location.href = response.redirect;
                //     }
                // }, 4000);

                //remove inputs values after send message
                // form.find('input[type=text],input[type=email], input[type=password], input[type=date], textarea').val('');
                // $('#agree').prop('checked', false);
            } else {
                if (response.messages != null) {

                //     $.each(response.messages, function (ObjNames, ObjValues) {
                //         if (ObjNames == 'agree')
                //             form.find("[name='" + ObjNames + "']").parent().find('.aggreement-checkbox').addClass('error-input');
                //         else {
                //             form.find("[name='" + ObjNames + "']").addClass('error-input');
                //             form.find("[name='" + ObjNames + "']").after('<label class="error ' + ObjNames + '" for="' + ObjNames + '">' + '<strong>' + ObjValues + '</strong>' + '</label>');
                //         }
                //     });
                //
                //     setTimeout(function () {
                //         $(".error-input").removeClass('error-input');
                //         //Remove error message
                //         form.find('label.error').remove();
                //     }, 5000);
                // } else {
                //     Notiflix.Notify.Failure(response.message, {
                //             position: 'center-top',
                //             timeout: 4000
                //         }
                //     );
                }
            }
        },
        error: function (error) {
            $('.info-alert').fadeIn();
            $('.info-alert').addClass('active');
            setTimeout(function () {
                $('.info-alert').fadeOut();
                $('.info-alert').removeClass('active');
            },4000)
        }

    })
}


let search_time = 0;

$('.search-form input').on('keyup',function(){
    clearTimeout(search_time);
    let value = $(this).val();
    search_time = setTimeout(function() {
        if (value.length > 0) {
            $('.smart-search').fadeIn();

            let lang = $('html').attr('lang');
            let url = '/' + lang + '/smartSearchFun';
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
              }
            });
            $.ajax({
              type: "POST",
              url: url,
              data: {
                'value': value,
              },
              success: function(response) {
                if (response.status == true) {
                  if (response.view) {
                    $('.search-result').html(response.view);
                      $('.search-result').fadeIn();
                      $('.loading-search').fadeOut();
                  }
                }
              }
            });
        } else {
            $('.loading-search').fadeIn();
            $('.search-result').fadeOut();
            // $('.smart-curient').fadeOut();
            // $('.smart-default').fadeIn();
        }
    }, 300)
})
