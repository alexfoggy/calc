(function ($) {
    $(window).on("load", function () {

        $(".sidebar").mCustomScrollbar({
            axis: "y",
            scrollbarPosition: "inside",
            theme: "light"
        });

        //equalHeight
        $('.content .main-block').matchHeight();
    });

    $(document).ready(function () {
        // Global variables

        var global_lang = $('html').attr('lang');
        var iteration = 0;

        // Global variables

        // Ajax setup for forms

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            }
        });

        // Ajax setup for forms

        // Auto collspan for table

        var count_th = $('.table').find('th').length;
        $('.table').children('tfoot').find('td').prop('colspan', count_th);

        // Auto collspan for table

        // Open upload img with keyboard

        $(document).on('keyup', function (e) {
            var evtobj = window.event ? event : e;

            if (evtobj.keyCode == 107 && evtobj.altKey)
                $('.file-div').find('button').click();
        });

        // Open upload img with keyboard

        // Make active first input
        $('.form').find('input').filter(':visible:first').focus();
        // Make active first input

        // Styled checkbox and radio

        $('input[type=checkbox], input[type=radio]').not(".remove-all-elements").not(".restore-all-elements").styler();

        // Styled checkbox and radio

        //Select with search

        $('.select2').select2();

        //End select with search

        // Change language

        $('#lang').select2().on('change', function () {
            var my_link = $(this).val();
            var new_location = window.location.href.slice(0, -1) + my_link;
            if ($(this).parents('#edit-form').length > 0)
                window.location.href = new_location;
        });

        // Change language

        //DateTime picker

        $.datetimepicker.setLocale(global_lang);

        $('.datetimepicker').datetimepicker({
            timepicker: false,
            format: 'd-m-Y',
            mask: true,
            scrollInput: false
        });

        //DateTime picker

        //Initialize all CkEditors

        $.each($('.form-page .form').find('textarea[data-type=ckeditor]'), function (e, v) {
            CKEDITOR.replace($(v).attr('id'), {
                language: global_lang
            });
        });

        //Initialize all CkEditors

        $('.hide-menu-btn').on('click', function () {
            $(this).toggleClass('close');
            $(this).parents('.sidebar-wrap').toggleClass('sidebar-hidden');
            $('.header').toggleClass('with-sidebar');
            $('.content').toggleClass('with-sidebar');
            $('.footer').toggleClass('with-sidebar');

            var url = window.location.origin + '/' + global_lang + '/back/hide-menu';

            if ($('.sidebar-wrap').hasClass('sidebar-hidden'))
                var is_visible = 0;
            else
                is_visible = 1;

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    is_visible: is_visible
                }
            });

        });

        $('.sidebar-wrap .menu-item.has-child > a').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('.hidden-menu-items').stop().slideToggle();
            $(this).parents('.menu-item.has-child').toggleClass('open');
        });

        //Run modals

        $('a.getModal').on('click', function () {
            var target_modal = $(this).attr('href');
            $(target_modal).arcticmodal();
        });

        //Run modals

        //Go top

        $('.go-top').click(function () {
            $('html, body').stop().animate({scrollTop: 0}, 300);
        });
        $(window).scroll(function () {
            if ($(window).scrollTop() > 150) {
                $('.go-top').fadeIn();
            } else {
                $('.go-top').fadeOut();
            }
        });

        //Go top

        //Sitemap generator

        $('#sitemap').on('click', function (e) {
            e.preventDefault();
            var url = window.location.origin + '/' + global_lang + '/back/sitemap';

            var wait_sitemap = $('#wait-sitemap').html();
            $.arcticmodal('close');
            toastr.warning(wait_sitemap);

            var wait_msg = setInterval(function () {
                toastr.warning(wait_sitemap);
            }, 10000);

            $.ajax({
                type: "POST",
                url: url,
                success: function (data) {
                    clearInterval(wait_msg);

                    toastr.clear();

                    if (data.status == true)
                        toastr[data.type](data.messages)
                },
                error: function () {
                    clearInterval(wait_msg);
                }
            });
        });

        //Sitemap generator

        //Table sorter

        $('#tablelistsorter').tableDnD({
            onDrop: function (table, row) {

                var action = $('#tablelistsorter').attr('action');
                if (action != undefined) {
                    var url = $('#tablelistsorter').attr('url') + '/ajaxRequest/changePosition'
                } else {
                    url = window.location.pathname + '/ajaxRequest/changePosition'
                }
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        neworder: $.tableDnD.serialize(),
                        action: action
                    },
                    success: function (data) {
                    }
                });

            },
            dragHandle: "dragHandle"
        });

        $("#tablelistsorter tr").hover(function () {
            $(this.cells[4]).addClass('showDragHandle');
        }, function () {
            $(this.cells[4]).removeClass('showDragHandle');
        });

        $("#lma").css("cursor", "pointer").click(function () {

            if ($("#lma-img").attr("src") == "/img/back/lma-right.gif") {
                $(".leftmenu").hide();
                $("#lma-img").attr("src", "/img/back/lma-left.gif");
                $(".lma").css("left", "0");
                $(".main-div").css("margin-left", "18px");
                $.post("/back/e.php", {action: "leftmenustatus", leftmenustatus: "closed"}, function (data) {
                    if (data != 0) {

                    }
                });
            } else {
                $(".leftmenu").show();
                $("#lma-img").attr("src", "/img/back/lma-right.gif");
                $(".lma").css("left", "184px");
                $(".main-div").css("margin-left", "0");
                $.post("/back/e.php", {action: "leftmenustatus", leftmenustatus: "opened"}, function (data) {
                    if (data != 0) {

                    }
                });
            }
        });

        //Table sorter

        //Generate alias

        if ($('#alias').val() === '') {
            $('#name').on('keyup contextmenu input', function (e) {
                var evtobj = window.event ? event : e;

                if (evtobj.keyCode !== 67 && !evtobj.ctrlKey)
                    $('#alias').val(translit($(this).val()));
            });
        }

        //Generate alias

        //Alert block

        setTimeout(function () {
            $('.alert.alert-info').fadeOut('slow');
        }, 3000);

        $('.alert.alert-info').on('click', function () {
            $(this).fadeOut('slow');
        });

        setTimeout(function () {
            $('.alert-notification').fadeOut('slow');
        }, 15000);

        $('.alert-notification').on('click', function () {
            $(this).fadeOut('slow');
        });

        $('.alert.json-info').on('click', function () {
            $(this).fadeOut('slow');
        });

        $('.alert.json-error').on('click', function () {
            $(this).fadeOut('slow');
        });

        //End alert block

        //Error alert block

        setTimeout(function () {
            $('.error-alert.alert-info').fadeOut('slow');
        }, 3000)

        $('.error-alert.alert-info').on('click', function () {
            $('.error-alert.alert-info').fadeOut('slow');
        });

        //End error alert block

        // Change active element

        $(document).on("click", '.change-active', function (e) {
            e.preventDefault();

            var active = $(this).data('active');
            var action = $(this).attr('action');
            var element_id = $(this).attr('element-id');

            if (active === 1) {
                $(this).data('active', 0);
                $(this).removeClass('active');
            } else {
                $(this).data('active', 1);
                $(this).addClass('active');
            }

            if (action !== undefined) {
                var url = $(this).attr('url') + '/ajaxRequest/changeActive';
            } else {
                url = window.location.pathname + '/ajaxRequest/changeActive';
            }
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    action: action,
                    active: active,
                    id: element_id
                },
                success: function (response) {
                    if (response.status === true) {
                        toastr[response.type](response.messages);
                    } else {
                        toastr[response.type](response.messages);
                    }
                },
                error: function (response) {
                    toastr.error('Ups, something went wrong! Please contact administrator!');
                }
            });
        });

        //End change active element

        // Change leader element

        $(document).on("click", '.change-leader', function (e) {
            e.preventDefault();

            var active = $(this).data('active');
            var action = $(this).attr('action');
            var element_id = $(this).attr('element-id');

            if (active === 1) {
                $(this).data('active', 0);
                $(this).removeClass('active');
            } else {
                $(this).data('active', 1);
                $(this).addClass('active');
            }

            if (action !== undefined) {
                var url = $(this).attr('url') + '/ajaxRequest/changeLeader';
            } else {
                url = window.location.pathname + '/ajaxRequest/changeLeader';
            }
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    action: action,
                    active: active,
                    id: element_id
                },
                success: function (response) {
                    if (response.status === true) {
                        toastr[response.type](response.messages);
                    } else {
                        toastr[response.type](response.messages);
                    }
                },
                error: function (response) {
                    toastr.error('Ups, something went wrong! Please contact administrator!');
                }
            });
        });

        // Change new element
        $(document).on("click", '.change-new', function (e) {
            e.preventDefault();

            var active = $(this).data('active');
            var action = $(this).attr('action');
            var element_id = $(this).attr('element-id');

            if (active === 1) {
                $(this).data('active', 0);
                $(this).removeClass('active');
            } else {
                $(this).data('active', 1);
                $(this).addClass('active');
            }

            if (action !== undefined) {
                var url = $(this).attr('url') + '/ajaxRequest/changeNew';
            } else {
                url = window.location.pathname + '/ajaxRequest/changeNew';
            }
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    action: action,
                    active: active,
                    id: element_id
                },
                success: function (response) {
                    if (response.status === true) {
                        toastr[response.type](response.messages);
                    } else {
                        toastr[response.type](response.messages);
                    }
                },
                error: function (response) {
                    toastr.error('Ups, something went wrong! Please contact administrator!');
                }
            });
        });

        // Change top menu element

        $('.change-top-menu').on("click", function (e) {
            e.preventDefault();

            var active = $(this).data('active');
            var action = $(this).attr('action');
            var element_id = $(this).attr('element-id');

            if (active === 1) {
                $(this).data('active', 0);
                $(this).removeClass('active');
            } else {
                $(this).data('active', 1);
                $(this).addClass('active');
            }

            if (action !== undefined) {
                var url = $(this).attr('url') + '/ajaxRequest/changeTopMenu';
            } else {
                url = window.location.pathname + '/ajaxRequest/changeTopMenu';
            }
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    action: action,
                    active: active,
                    id: element_id
                },
                success: function (response) {
                    if (response.status === true) {
                        toastr[response.type](response.messages);
                    } else {
                        toastr[response.type](response.messages);
                    }
                },
                error: function (response) {
                    toastr.error('Ups, something went wrong! Please contact administrator!');
                }
            });
        });

        $('.change-partner').on("click", function (e) {
            e.preventDefault();

            var active = $(this).data('active');
            var action = $(this).attr('action');
            var element_id = $(this).attr('element-id');

            if (active === 1) {
                $(this).data('active', 0);
                $(this).removeClass('active');
            } else {
                $(this).data('active', 1);
                $(this).addClass('active');
            }

            if (action !== undefined) {
                var url = $(this).attr('url') + '/ajaxRequest/changePartners';
            } else {
                url = window.location.pathname + '/ajaxRequest/changePartners';
            }
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    action: action,
                    active: active,
                    id: element_id
                },
                success: function (response) {
                    if (response.status === true) {
                        toastr[response.type](response.messages);
                    } else {
                        toastr[response.type](response.messages);
                    }
                },
                error: function (response) {
                    toastr.error('Ups, something went wrong! Please contact administrator!');
                }
            });
        });

        //End change top menu element

        // Change footer menu element

        $('.change-footer-menu').on("click", function (e) {
            e.preventDefault();

            var active = $(this).data('active');
            var action = $(this).attr('action');
            var element_id = $(this).attr('element-id');

            if (active === 1) {
                $(this).data('active', 0);
                $(this).removeClass('active');
            } else {
                $(this).data('active', 1);
                $(this).addClass('active');
            }

            if (action !== undefined) {
                var url = $(this).attr('url') + '/ajaxRequest/changeFooterMenu';
            } else {
                url = window.location.pathname + '/ajaxRequest/changeFooterMenu';
            }
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    action: action,
                    active: active,
                    id: element_id
                },
                success: function (response) {
                    if (response.status === true) {
                        toastr[response.type](response.messages);
                    } else {
                        toastr[response.type](response.messages);
                    }
                },
                error: function (response) {
                    toastr.error('Ups, something went wrong! Please contact administrator!');
                }
            });
        });

        //End change footer menu element

        //Remove all elements

        var remove_many_objects = $('.remove-many-object');
        $(remove_many_objects).find('span').html($(remove_many_objects).find('span').attr('data-msg'));
        var send_right = $(remove_many_objects).outerWidth() + 55 + 'px';
        $(remove_many_objects).css('right', '-' + send_right);

        $(document).on('click', '.check-destroy-element', function (e) {

            var checkbox = $(this).find('input[type="checkbox"]');
            // $(checkbox).trigger('refresh');

            if (!$(e.target).is('input')) {
                $(checkbox).prop('checked', function (i, value) {
                    return !value;
                });
            }

            var count = 0;
            var data_id = '';
            $.each($('.check-destroy-element').find('input[type="checkbox"]:checked'), function (key, value) {
                data_id += $(value).val() + ',';

                if ($(value).length > 0)
                    count++;
                else
                    count--;
            });

            data_id = data_id.slice(0, -1);

            $(remove_many_objects).find('span').html($(remove_many_objects).find('span').attr('data-msg') + ' ' + count);
            $(remove_many_objects).find('span').attr('data-id', '[' + data_id + ']');
            $(remove_many_objects).find('span').attr('action', $(checkbox).attr('action'));
            $(remove_many_objects).find('span').attr('url', $(checkbox).attr('url'));

            if (count > 0) {
                $(remove_many_objects).stop().animate({
                    right: '0'
                }, 'slow');
            } else {
                $(remove_many_objects).stop().animate({
                    right: '-' + send_right
                }, 'slow');
            }

        });

        $(document).on('click', '.remove-many-object', function (e) {
            var data_id = $(this).find('span').attr('data-id');
            var url = $(this).find('span').attr('url');

            if (data_id == undefined)
                data_id = '';

            var conf = confirm("Do you want delete this element?");
            if (conf != true)
                e.preventDefault();
            else {

                var loader_list = $('#loader-gif');

                $.ajax({
                    type: "POST",
                    url: url,
                    beforeSend: function () {
                        loader_list.fadeIn()
                    },
                    data: {
                        id: data_id
                    },
                    success: function (response) {
                        loader_list.fadeOut();

                        $(remove_many_objects).animate({
                            right: '-' + send_right
                        }, 'slow');

                        $(restore_many_objects).animate({
                            right: '-' + send_right
                        }, 'slow');

                        if (response.status == true) {

                            if (response.deleted_elements != '') {
                                $.each(response.deleted_elements, function (key, value) {
                                    $('.table').find('tr[id="' + value + '"]').remove();
                                });
                            }

                            if ($('.table').children('tbody').find('tr').length < 1) {
                                $('.list-table').html('<div class="empty-response">' + $('.table').attr('empty-response') + '</div>');

                                $('.table').remove();
                            }


                            $('.json-info').fadeIn('slow');

                            if (response.del_messages != '' && response.del_messages != undefined) {
                                $('.json-info').html(response.del_messages)
                            } else if (response.cart_messages != '' && response.cart_messages != undefined) {
                                $('.json-info').html(response.cart_messages)
                            }

                            setTimeout(function () {
                                $('.alert.json-info').fadeOut('slow');
                            }, 3000)
                        } else {

                            $('.json-error').fadeIn('slow');

                            if (response.messages != '') {
                                $('.json-error').html(response.messages);
                            }

                            setTimeout(function () {
                                $('.alert.json-error').fadeOut('slow');
                            }, 3000)
                        }
                    },
                    error: function () {
                        loader_list.fadeOut();
                        toastr.error('Ups, something went wrong!');
                    }
                });
            }
        });

        $(document).on('click', '.remove-all', function () {
            $(this).toggleClass('all-checked');
            var all_elements = $(this).parents('table').find('.check-destroy-element').children('input[type=checkbox]');

            if ($(this).hasClass('all-checked')) {
                all_elements.prop('checked', true);

                var count = 0;
                var data_id = '';
                $.each(all_elements, function (key, value) {
                    data_id += $(value).val() + ',';

                    if ($(value).length > 0)
                        count++;
                    else
                        count--;
                });

                data_id = data_id.slice(0, -1);

                $(remove_many_objects).find('span').html($(remove_many_objects).find('span').attr('data-msg') + ' ' + count);
                $(remove_many_objects).find('span').attr('data-id', '[' + data_id + ']');
                $(remove_many_objects).find('span').attr('action', $(all_elements.get(0)).attr('action'));
                $(remove_many_objects).find('span').attr('url', $(all_elements.get(0)).attr('url'));

                if (count > 0) {
                    $(remove_many_objects).stop().animate({
                        right: '0'
                    }, 'slow');
                } else {
                    $(remove_many_objects).stop().animate({
                        right: '-' + send_right
                    }, 'slow');
                }
            } else {
                all_elements.prop('checked', false);
                $(remove_many_objects).stop().animate({
                    right: '-' + send_right
                }, 'slow');
            }
        });

        //Remove all elements

        // Restore all elements

        var restore_many_objects = $('.restore-many-object');
        $(restore_many_objects).find('span').html($(restore_many_objects).find('span').attr('data-msg'));
        send_right = $(restore_many_objects).outerWidth() + 55 + 'px';
        $(restore_many_objects).css('right', '-' + send_right);

        $(document).on('click', '.check-restore-element', function (e) {

            var checkbox = $(this).find('input[type="checkbox"]');

            if (!$(e.target).is('input')) {

                $(checkbox).prop('checked', function (i, value) {
                    return !value;
                });
            }

            var count = 0;
            var data_id = '';
            $.each($('.check-restore-element').find('input[type="checkbox"]:checked'), function (key, value) {
                data_id += $(value).val() + ',';

                if ($(value).length > 0)
                    count++;
                else
                    count--;
            });

            data_id = data_id.slice(0, -1);

            $(restore_many_objects).find('span').html($(restore_many_objects).find('span').attr('data-msg') + ' ' + count);
            $(restore_many_objects).find('span').attr('data-id', '[' + data_id + ']');
            $(restore_many_objects).find('span').attr('action', $(checkbox).attr('action'));
            $(restore_many_objects).find('span').attr('url', $(checkbox).attr('url'));

            if (count > 0) {
                $(restore_many_objects).stop().animate({
                    right: '0'
                }, 'slow');
            } else {
                $(restore_many_objects).stop().animate({
                    right: '-' + send_right
                }, 'slow');
            }

        });

        $(document).on('click', '.restore-many-object', function (e) {
            var data_id = $(this).find('span').attr('data-id');
            var url = $(this).find('span').attr('url');

            if (data_id == undefined)
                data_id = '';

            var loader_list = $('#loader-gif');

            $.ajax({
                type: "POST",
                url: url,
                beforeSend: function () {
                    loader_list.fadeIn()
                },
                data: {
                    id: data_id
                },
                success: function (response) {
                    loader_list.fadeOut();

                    $(remove_many_objects).animate({
                        right: '-' + send_right
                    }, 'slow');

                    $(restore_many_objects).animate({
                        right: '-' + send_right
                    }, 'slow');

                    if (response.status == true) {

                        if (response.restored_elements != '') {
                            $.each(response.restored_elements, function (key, value) {
                                $('.table').find('tr[id="' + value + '"]').remove();
                            });
                        }

                        if ($('.table').children('tbody').find('tr').length < 1) {
                            $('.list-table').html('<div class="empty-response">' + $('.table').attr('empty-response') + '</div>');

                            $('.table').remove();
                        }


                        $('.json-info').fadeIn('slow');

                        if (response.cart_messages != '' && response.cart_messages != undefined) {
                            $('.json-info').html(response.cart_messages)
                        }

                        setTimeout(function () {
                            $('.alert.json-info').fadeOut('slow');
                        }, 3000)
                    } else {

                        $('.json-error').fadeIn('slow');

                        if (response.messages != '') {
                            $('.json-error').html(response.messages);
                        }

                        setTimeout(function () {
                            $('.alert.json-error').fadeOut('slow');
                        }, 3000)
                    }
                },
                error: function () {
                    loader_list.fadeOut();
                    toastr.error('Ups, something went wrong!');
                }
            });

        });

        $(document).on('click', '.restore-all', function () {
            $(this).toggleClass('all-checked');
            var all_elements = $(this).parents('table').find('.check-restore-element').children('input[type=checkbox]');

            if ($(this).hasClass('all-checked')) {
                all_elements.prop('checked', true);

                var count = 0;
                var data_id = '';
                $.each(all_elements, function (key, value) {
                    data_id += $(value).val() + ',';

                    if ($(value).length > 0)
                        count++;
                    else
                        count--;
                });

                data_id = data_id.slice(0, -1);

                $(restore_many_objects).find('span').html($(restore_many_objects).find('span').attr('data-msg') + ' ' + count);
                $(restore_many_objects).find('span').attr('data-id', '[' + data_id + ']');
                $(restore_many_objects).find('span').attr('action', $(all_elements.get(0)).attr('action'));
                $(restore_many_objects).find('span').attr('url', $(all_elements.get(0)).attr('url'));

                if (count > 0) {
                    $(restore_many_objects).animate({
                        right: '0'
                    }, 'slow');
                } else {
                    $(restore_many_objects).animate({
                        right: '-' + send_right
                    }, 'slow');
                }
            } else {
                all_elements.prop('checked', false);
                $(restore_many_objects).animate({
                    right: '-' + send_right
                }, 'slow');
            }
        });

        // Restore all elements

        // Upload more img

        var inputFilesFormat = ".file-div input[type='hidden']";
        $(inputFilesFormat).each(function (iterator, parentThat) {
            if (iterator === 0) {
                var htmlFormat = "" +
                    "<div class='hidden'>" +
                    "<form action='" + $(parentThat).data('url') + "' enctype='multipart/form-data' class='upload-form' upload='" + $(parentThat).attr('path') + "' >" +
                    "<input type='file' name='" + $(parentThat).attr('name') + "' id='" + $(parentThat).attr('class') + "' multiple/>" +
                    "</form>" +
                    "</div>";
                $('body').append(htmlFormat);
            }
        });
        $(document).on('click', '.file-div button', function (parentThat) {
            parentThat.preventDefault();

            var inputClass = $(this).closest('div').find('input').attr('class');
            var clonedFileInput = $('input[id=' + inputClass + '][type="file"]');

            $(clonedFileInput).trigger('click');

        });

        $(document).on('change', 'input[type=file]', function () {
            var parentThat = $(this);
            if ($(this).attr('id') != 'image_upload') {
                setTimeout(function () {
                    $(parentThat).closest('form').submit();
                }, 500);
            }
        });

        $(document).on('submit', '.upload-form', function (e) {
            e.preventDefault();

            var inputName = $(this).find('input').attr('id');

            var clonedTextHidenInput = $('input[name=' + inputName + '\\[\\]][type="hidden"]');

            var formData = new FormData($(this)[0]);
            var url = $(this).attr('action');
            var uploadPath = $(this).attr('upload');

            formData.append('uploadPath', uploadPath);

            var form = $('.file-div button');

            $.ajax({
                url: url,
                beforeSend: function () {
                    form.find('.loader-list').fadeIn();
                },
                type: 'POST',
                data: formData,
                async: true,
                success: function (data) {
                    form.find('.loader-list').fadeOut();

                    if (data.status === true) {
                        for (var i in data[0].fileName) {
                            var newClonedTextHidenInput = $(clonedTextHidenInput).last().clone().insertAfter($(clonedTextHidenInput).last());
                            setTimeout(function () {
                                if ($(clonedTextHidenInput).val() == '')
                                    $(clonedTextHidenInput).remove();
                            }, 100);
                            $(newClonedTextHidenInput).val(data[0].url[i]);

                            if (data.fileType = 'img') {
                                $(clonedTextHidenInput).closest('div').append("<div class='img-block' style='background: url(" + data[0].url[i] + ") center; background-size: cover;'>" +
                                    "<span class='hidden-text'>Dynamic items</span>" +
                                    "<span class='remove-upload-img' data-file-name='" + data[0].fileName[i] + "'>x</span>" +
                                    "</div>");
                                $(clonedTextHidenInput).closest('div').find('span').removeClass('glyphicon-refresh');
                            }
                        }

                        $('.img-block').arrangeable();

                    } else {
                        if (data.messages !== undefined)
                            toastr.error(data.messages);
                    }
                },
                error: function () {
                    form.find('.loader-list').fadeOut();
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });

        $(document).on('click', '.remove-upload-img', function (e) {
            e.preventDefault();
            var _this = $(this);
            var curr_img = _this.attr('data-file-name');
            var url = _this.parents('.img-block').siblings('input[type="hidden"]').attr('data-destroy-url');
            var uploadPath = _this.parents('.img-block').siblings('input[type="hidden"]').attr('path');
            var curr_id = _this.parents('.img-block').data('id');

            var form = $('.file-div button');

            $.ajax({
                url: url,
                beforeSend: function () {
                    form.find('.loader-list').fadeIn();
                },
                type: 'POST',
                data: {
                    'curr_img': curr_img,
                    'uploadPath': uploadPath,
                    'curr_id': curr_id
                },
                success: function (data) {
                    form.find('.loader-list').fadeOut();

                    if (data.status === true) {

                        if (_this.parents('.img-block').siblings('input[type="hidden"]').length > 1)
                            _this.parents('.img-block').siblings('input[type="hidden"][value="' + window.location.origin + '/upfiles/' + uploadPath + '/' + curr_img + '"]').remove();
                        else
                            _this.parents('.img-block').siblings('input[type="hidden"][value="' + window.location.origin + '/upfiles/' + uploadPath + '/' + curr_img + '"]').attr('value', '');

                        _this.parents('.img-block').fadeOut(function () {
                            _this.parents('.img-block').remove();
                        });

                        if (data.messages !== undefined)
                            toastr.info(data.messages);

                    } else {
                        if (data.messages !== undefined)
                            toastr.error(data.messages);
                    }
                },
                error: function () {
                    form.find('.loader-list').fadeOut();
                }
            });
            return false;
        });

        $(document).on('click', '.active-upload-img', function (e) {
            e.preventDefault();
            var _this = $(this);
            var url = _this.parents('.img-block').siblings('input[type="hidden"]').attr('data-activate-url');
            var uploadPath = _this.parents('.img-block').siblings('input[type="hidden"]').attr('path');
            var active_elem = _this.data('active');

            var curr_id = _this.parents('.img-block').data('id');

            if (_this.hasClass('active')) {
                _this.data('active', 0);
                _this.removeClass('active');
                _this.text('-');
            } else {
                _this.data('active', 1);
                _this.addClass('active');
                _this.text('+');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    'curr_id': curr_id,
                    'uploadPath': uploadPath,
                    'active': active_elem
                },
                success: function (data) {

                    if (data.status === true) {
                        if (data.messages !== undefined)
                            toastr.info(data.messages);
                    } else {
                        if (data.messages !== undefined)
                            toastr.error(data.messages);
                    }
                },
                error: function () {
                    toastr.error('Ups, something went wrong! Please contact administrator!');
                }
            });
            return false;
        });

        // End upload img

        //Drag images

        if ($(document).find('.file-div').length > 0) {
            var url = $('.form').data('parent-url') + '/ajaxRequest/changeImgPosition';

            $('.img-block').arrangeable({
                'ajaxOption': {
                    'method': 'post',
                    'url': url
                }
            });
        }

        //Drag images

        // Change select input, textarea, ckeditor

        var set_type = $('#set_type').val();
        if (set_type === 'textarea') {
            $('.input').hide().find('input').val('');
            $('.ckeditor').hide();
            $('.textarea').show();
            CKEDITOR.instances.body.setData('');
        }

        if (set_type === 'ckeditor') {
            $('.input').hide().find('input').val('');
            $('.textarea').hide().find('textarea').val('');
            $('.ckeditor').show();
        }

        if (set_type === 'input') {
            $('.textarea').hide().find('textarea').val('');
            $('.ckeditor').hide();
            $('.input').show();
            CKEDITOR.instances.body.setData('');
        }

        if (set_type === 'page') {
            $('.link').hide().find('input').val('');
            $('.ckeditor').show();
        }

        if (set_type === 'link') {
            $('.ckeditor').hide();
            $('.link').show();
            CKEDITOR.instances.body.setData('');
        }

        $('#set_type').on('change', function () {
            var set_type = $(this).val();

            if (set_type === 'textarea') {
                $('.input').hide().find('input').val('');
                $('.ckeditor').hide();
                $('.textarea').show();
                CKEDITOR.instances.body.setData('');

                $('.input').addClass('hidden').val('');
                $('.ckeditor').hide();
                $('.textarea').show();
                CKEDITOR.instances.body.setData('');
            }

            if (set_type === 'ckeditor') {
                $('.input').hide().find('input').val('');
                $('.textarea').hide().find('textarea').val('');
                $('.ckeditor').show()
            }

            if (set_type === 'input') {
                $('.textarea').hide().find('textarea').val('');
                $('.ckeditor').hide();
                $('.input').show();
                CKEDITOR.instances.body.setData('');
            }

            if (set_type === 'page') {
                $('.controller').hide().find('input').val('');
                $('.link').hide();
                $('.ckeditor').show();
            }

            if (set_type === 'link') {
                $('.controller').hide().find('input').val('');
                $('.ckeditor').hide();
                $('.link').show();
                CKEDITOR.instances.body.setData('');
            }
        });

        //End change select input, textarea, ckeditor

        // Youtube id

        $("#youtube_link").change(function () {
            var code = $(this).val();
            $.ajax({
                type: "POST",
                url: $(this).attr('url') + '/ajaxRequest/youtubeId',
                data: {code: code},
                success: function (data) {
                    if (data != '') {
                        $(".youtube_id").html('<div><input type="hidden" name="youtube_id" value="' + data + '">' + '<iframe width="100%" height="200" src="https://www.youtube.com/embed/' + data + '" frameborder="0" allowfullscreen=""></iframe></div>');
                    } else {
                        $(".youtube_id").html('<input type="hidden" name="youtube_id" value="">');
                    }
                }
            });
        });

        // Youtube id

        // Add Parameter

        if ($('#add-form').attr('page') === 'create-parameter') {

            var val = $('#measure_type').val();
            if (val === "no_measure") {
                $(".goods_measure_list").hide();
                $(".goods_measure_id").hide();
                $('.new-select').parent('div').remove();
            }
            if (val === "with_measure") {
                $(".goods_measure_id").show();
                $(".goods_measure_list").hide();
                $('.new-select').parent('div').remove();
            }
            if (val === "measure_list") {
                $(".goods_measure_id").hide();
                $(".goods_measure_list").show();
            }

            val = $('#parametr_type').val();
            if (val === "input" || val === "textarea") {
                $("#parametr_value").hide();
                $('.inputs').children('input').val('');
                $('.new-inputs').remove();
            } else {
                $("#parametr_value").show();
            }
            if (val === "input") {
                $("#tr_measure_type").show();
                $(".goods_measure_id").show();
            } else {
                $("#tr_measure_type").hide();
                $(".goods_measure_id").hide();
                $(".goods_measure_list").hide();
                $('.new-select').parent('div').remove();
            }

            // $('.new-inputs').append('<img src="/admin-assets/img/del.png" alt="" align="absmiddle" class="delparametrtypevalues" style="cursor:pointer" hspace="3" />');
            // $('.select-measure .new-select').append('<img src="/admin-assets/img/del.png" alt="" align="absmiddle" class="delmeasure" style="cursor: pointer; display: inline-block;" hspace="3">');

            $("#measure_type").on('change', function () {
                val = $(this).val();
                if (val === "no_measure") {
                    $(".goods_measure_list").hide();
                    $(".goods_measure_id").hide();
                    $('.new-select').parent('div').remove();
                }
                if (val === "with_measure") {
                    $(".goods_measure_id").show();
                    $(".goods_measure_list").hide();
                    $('.new-select').parent('div').remove();
                }
                if (val === "measure_list") {
                    $(".goods_measure_id").hide();
                    $(".goods_measure_list").show();
                }
            });

            $('#moreterm').on('click', function () {
                $('.select-measure:last-child').clone().appendTo('.goods_measure_list_cont');
                var last_element_id = $('.select-measure:first-child').find('select').attr('id');
                $('.select-measure:last-child').find('select').attr('id', '' + last_element_id + '_' + iteration++ + '');

                $('.select-measure:last-child').find('.select2').siblings('span').remove();
                $('.select-measure:last-child').find('.select2').select2();

                $.each($('.select-measure'), function (key, value) {
                    $('.select-measure:last-child').eq(key).find('select').addClass('new-select');
                    $('.select-measure:last-child').eq(key).find('select').before('<span class="delmeasure"></span>');
                    console.log($('.select-measure:last-child').eq(key).find('select'));
                })
            });

            $("#parametr_type").on("change", function () {
                val = $(this).val();
                if (val === "input" || val === "textarea") {
                    $("#parametr_value").hide();
                    $('.inputs').children('input').val('');
                    $('.new-inputs').remove();
                } else {
                    $("#parametr_value").show();
                }
                if (val === "input") {
                    $("#tr_measure_type").show();
                    $(".goods_measure_id").show();
                } else {
                    $("#tr_measure_type").hide();
                    $(".goods_measure_id").hide();
                    $(".goods_measure_list").hide();
                    $('.new-select').parent('div').remove();
                }
            });

            $('#morevalues').on('click', function () {
                $('#tablelistsorter_parametrvalue').append('<div class="new-inputs"><input name="parametr_type_value[]"><span class="delparametrtypevalues"></span></div>');
            });

            $(document).on('click', '.delmeasure', function () {
                $(this).parent('.select-measure').remove();
            });

            $(document).on('click', '.delparametrtypevalues', function () {
                $(this).parent('.new-inputs').remove();
            });
        }

        // Add Parameter

        //Edit Parameter
        if ($('#edit-form').attr('page') === 'edit-parameter') {

            val = $('#measure_type').val();
            if (val === "no_measure") {
                $(".goods_measure_list").hide();
                $(".goods_measure_id").hide();
                $('.new-select').parent('div').remove();
            }
            if (val === "with_measure") {
                $(".goods_measure_id").show();
                $(".goods_measure_list").hide();
                $('.new-select').parent('div').remove();
            }
            if (val === "measure_list") {
                $(".goods_measure_id").hide();
                $(".goods_measure_list").show();
            }

            $("#measure_type").change(function () {
                val = $(this).val();
                if (val === "no_measure") {
                    $(".goods_measure_list").hide();
                    $(".goods_measure_id").hide();
                }
                if (val === "with_measure") {
                    $(".goods_measure_id").show();
                    $(".goods_measure_list").hide();
                    $('.new-select').parent('div').remove();
                }
                if (val === "measure_list") {
                    $(".goods_measure_id").hide();
                    $(".goods_measure_list").show();
                    $('.new-select').parent('div').remove();
                }
            });

            var measure_index = 0;
            $('#moreterm').on('click', function () {
                measure_index = measure_index - 1;
                var first_td = $('tr:first-child .goods_measure_list option');

                var options_arr_name = [];
                var options_arr_val = [];
                for (i = 0; i <= first_td.length - 1; i++) {
                    options_arr_name[i] = first_td.eq(i).text();
                    options_arr_val[i] = first_td.eq(i).val();
                }

                var selectList = document.createElement("select");
                selectList.className = "select2 goods_measure_list new-measure-select";
                selectList.name = "goods_measure_list[" + measure_index + "]";

                for (var i = 0; i < options_arr_val.length; i++) {
                    var option = document.createElement("option");
                    option.value = options_arr_val[i];
                    option.text = options_arr_name[i];
                    selectList.appendChild(option);
                }

                $('#tablelistsorter_measure tbody').append('<tr><td class="dragHandle"></td>' +
                    '<td class="my-nowrap">' +
                    '<span class="delmeasure_new"></span>' +
                    '</td>' +
                    '</tr>');

                $('.my-nowrap').html(selectList);
                $('.my-nowrap').find('.select2').select2();

            });

            var parametr_value_index = 0;
            $('#morevalues').on('click', function () {
                parametr_value_index = parametr_value_index - 1;
                $('#tablelistsorter_parametrvalue tbody').append('<tr><td class="dragHandle"></td>' +
                    '<td>' +
                    '<input name="parametr_type_value[' + parametr_value_index + ']" class="parameter-input" style="background: rgb(255, 255, 255);">' +
                    '<span class="delparametrtypevalues_new"></span>' +
                    '</td>' +
                    '</tr>')
            });

            $(".delmeasure").on('click', function () {
                if ($("#tablelistsorter_measure tr").length > 2) {
                    $(this).parents('td').parents('tr').remove();
                    var goods_measure_list_id_for_del_id = $(this).parents('td').parents('tr').attr("id");
                    var goods_parametr_id = $(this).parents('td').parents('tr').attr("param-id");
                    var url = $('#tablelistsorter_measure').attr('url') + '/ajaxRequest/removeMeasureList';
                    if (goods_measure_list_id_for_del_id > 0) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                action: goods_measure_list_id_for_del_id,
                                param_id: goods_parametr_id
                            },
                            success: function (response) {
                                if (response.status === true) {
                                    if (response.messages !== '' && response.messages !== undefined)
                                        toastr.success(response.messages);
                                } else {
                                    if (response.messages !== '' && response.messages !== undefined)
                                        toastr.error(response.messages);
                                }
                            }
                        });
                    }

                } else {
                    toastr.error("Select must have min two value");
                }
            });

            $(".delparametrtypevalues").on('click', function () {
                if ($("#tablelistsorter_parametrvalue tr").length > 2) {
                    $(this).parents('td').parents('tr').remove();
                    var goods_parametr_value_id = $(this).parents('td').parents('tr').attr("id");
                    var goods_parametr_id = $(this).parents('td').parents('tr').attr("param-id");
                    var url = $('#tablelistsorter_parametrvalue').attr('url') + '/ajaxRequest/removeParameter';
                    if (goods_parametr_value_id > 0) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                action: goods_parametr_value_id,
                                param_id: goods_parametr_id
                            },
                            success: function (response) {
                                if (response.status === true) {
                                    if (response.messages !== '' && response.messages !== undefined)
                                        toastr.success(response.messages);
                                } else {
                                    if (response.messages !== '' && response.messages !== undefined)
                                        toastr.error(response.messages);
                                }
                            }
                        });
                    }
                } else {
                    toastr.error("Select must have min one value!");
                }
            });

            $(document).on('click', '.delparametrtypevalues_new', function () {
                $(this).parents('td').parents('tr').remove();
            });

            $(document).on('click', '.delmeasure_new', function () {
                $(this).parents('td').parents('tr').remove();
            });

            $('#tablelistsorter_measure, #tablelistsorterac_parametrvalue').tableDnD({
                dragHandle: "dragHandle"
            });


            $('#tablelistsorter_parametrvalue').tableDnD({
                onDrop: function (table, row) {
                    var url = $('#tablelistsorter_parametrvalue').attr('url') + '/ajaxRequest/changeParameterValues';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            neworder: $.tableDnD.serialize()
                        },
                        success: function (data) {
                        }
                    });
                },
                dragHandle: "dragHandle"
            });

            $('#tablelistsorter_measure').tableDnD({
                onDrop: function (table, row) {
                    var url = $('#tablelistsorter_measure').attr('url') + '/ajaxRequest/changeMasuresList';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            neworder: $.tableDnD.serialize()
                        },
                        success: function (data) {
                        }
                    });
                },
                dragHandle: "dragHandle"
            });


            $("#tablelistsorter_measure tr, .tablelistsorter_parametrvalue tr").hover(function () {
                $(this.cells[4]).addClass('showDragHandle');
            }, function () {
                $(this.cells[4]).removeClass('showDragHandle');
            });
        }

        // Edit Parameter

        // Search ico

        $('.search-btn').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $(this).parent('.list-head-tabs').toggleClass('active');
            $('.search-block').stop().slideToggle();
        });

        // Search ico

        // Add new object (new element and sale element)

        $('#new_element').on('change', function () {
            var className = $(this).attr('id');
            if ($('div.' + className).is(':visible')) {
                $('div.' + className).fadeOut();
                $('div.' + className).find('input[type=text]').val('');
            } else
                $('div.' + className).fadeIn();

        });

        // Add new object (new element and sale element)

        // Create more modules

        if ($('#add-form').attr('page') === 'add-item') {
            $('.new_module').on('click', function (e) {
                e.preventDefault();

                var count = $('.module').length + 1;

                $('.module').last().after(
                    '<div class="module">' +
                    '<div class="label-wrap">' +
                    '<label for="title_' + count + '">' + $(this).attr("data-title") + '</label>' +
                    '<span class="close-module"></span>' +
                    '</div>' +
                    '<div class="input-wrap">' +
                    '<input name="module_title[]" id="title_' + count + '">' +
                    '</div>' +
                    '<div class="label-wrap">' +
                    '<label for="body' + count + '">' + $(this).attr("data-description") + '</label>' +
                    '</div>' +
                    '<div class="input-wrap">' +
                    '<textarea name="content[]" id="body' + count + '" data-type="ckeditor"></textarea>' +
                    '</div>' +
                    '</div>');

                CKEDITOR.replace("body" + count, {
                    language: global_lang
                });
            });
        }

        $(document).on('click', '.close-module', function () {
            var curr_module = $(this);
            var module_id = $(this).attr('data-modules-id');
            var url = $(this).attr('data-url') + '/ajaxRequest/removeModule';

            if (module_id === '' || module_id === undefined) {
                curr_module.parents('.module').remove();
            } else {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        module_id: module_id
                    },
                    success: function (response) {
                        if (response.status === true) {
                            for (var messageIterator in response.messages) {

                                toastr.success(response.messages[messageIterator]);
                            }

                            curr_module.parents('.module').remove();
                        } else {
                            for (messageIterator in response.messages) {

                                toastr.error(response.messages[messageIterator]);
                            }
                        }
                    }
                });
            }
        });

        // Create more modules

        // Edit modules

        if ($('#edit-form').attr('page') == 'edit-item') {
            var module_count_id = 0;
            $('.new_module').on('click', function (e) {
                e.preventDefault();
                var count = $('.module').length + 1;
                module_count_id = module_count_id - 1;

                $('.module').last().after(
                    '<div class="module">' +
                    '<div class="label-wrap">' +
                    '<label for="title_' + count + '">' + $(this).attr("data-title") + '</label>' +
                    '<span class="close-module"></span>' +
                    '</div>' +
                    '<div class="input-wrap">' +
                    '<input name="module_title[' + module_count_id + ']" id="title_' + count + '">' +
                    '</div>' +
                    '<div class="label-wrap">' +
                    '<label for="body' + count + '">' + $(this).attr("data-description") + '</label>' +
                    '</div>' +
                    '<div class="input-wrap">' +
                    '<textarea name="content[' + module_count_id + ']" id="body' + count + '" data-type="ckeditor"></textarea>' +
                    '</div>' +
                    '</div>');

                CKEDITOR.replace("body" + count, {
                    language: global_lang
                });

            });
        }

        // Edit modules

        //  Edit gallery video item

        $('.edit-gallery-item a').on('click', function (e) {
            e.preventDefault();

            var _this = $(this);
            var video_form = $('#add-video-form');
            var curr_item_id = _this.data('item-id');
            var curr_lang_edit = _this.data('lang-id');
            var url = _this.data('url') + '/ajaxRequest/ajaxVideoContent';

            $('html, body').animate({
                scrollTop: (video_form.offset().top - 60)
            }, 300);

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: curr_item_id,
                    lang_id: curr_lang_edit
                },
                success: function (response) {

                    if (response.status === true) {
                        video_form.addClass('edit-video-form');

                        var _this_name = response.name;
                        var _this_body = response.body;
                        var _this_link = response.link;
                        var _this_youtube_id = response.youtube_id;

                        video_form.find('div').first().before('<input type="hidden" name="current_item" value="' + curr_item_id + '">');

                        $.each(video_form.find('#lang').children('option'), function (k, v) {
                            if ($(v).val() === curr_lang_edit)
                                $(v).prop('selected', true);
                        });

                        video_form.find('#name').val(_this_name);
                        CKEDITOR.instances['body'].setData(_this_body);
                        video_form.find('#youtube_link').val(_this_link);
                        $(".youtube_id").html('<input type="hidden" name="youtube_id" value="' + _this_youtube_id + '">' + '<iframe width="100%" height="200" src="https://www.youtube.com/embed/' + _this_youtube_id + '" frameborder="0" allowfullscreen=""></iframe>');

                    } else {
                        toastr.error(response.messages);
                    }
                }
            });

        });

        $(document).on('change', '.edit-video-form #lang', function () {

            var video_form = $('#add-video-form');
            var curr_lang_edit = $(this).val();
            var url = $('.edit-gallery-item').find('a').data('url') + '/ajaxRequest/ajaxVideoContent';
            var curr_item_id = video_form.find('input[name="current_item"]').val();

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: curr_item_id,
                    lang_id: curr_lang_edit
                },
                success: function (response) {
                    if (response.status === true) {
                        video_form.addClass('edit-video-form');

                        var _this_name = response.name;
                        var _this_body = response.body;
                        var _this_link = response.link;

                        $.each(video_form.find('#lang').children('option'), function (k, v) {
                            if ($(v).val() === curr_lang_edit)
                                $(v).prop('selected', true);
                        });

                        video_form.find('#name').val(_this_name);
                        CKEDITOR.instances['body'].setData(_this_body);
                        video_form.find('#youtube_link').val(_this_link);

                    } else {
                        toastr.error(response.messages);
                    }
                }
            });
        });

        //  Edit gallery video item

        //  Edit gallery photo item

        $('.edit-gallery-photo a').on('click', function (e) {
            e.preventDefault();

            var _this = $(this);
            var curr_item_id = _this.data('item-id');
            var curr_lang_edit = _this.data('lang-id');
            var url = _this.data('url') + '/ajaxRequest/ajaxAudioContent';

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: curr_item_id,
                    lang_id: curr_lang_edit
                },
                success: function (response) {

                    if (response.status === true) {
                        _this.parents('.edit-gallery-photo').siblings('.photo-name').html('<input name="material-name" class="material-name-input" value="' + response.name + '" lang-id="' + curr_lang_edit + '" url="' + _this.data('url') + '" element-id="' + curr_item_id + '" >');
                        _this.parents('.edit-gallery-photo').siblings('.photo-descr').html('<textarea name="material-body" class="material-name-input" lang-id="' + curr_lang_edit + '" url="' + _this.data('url') + '" element-id="' + curr_item_id + '" >' + response.body + '</textarea>');
                    } else {
                        toastr.error(response.messages);
                    }
                }
            });

        });

        $(document).on('change', '.material-name-input', function () {

            var current_element = $(this);
            var url = $(this).attr('url') + '/ajaxRequest/changeItemName';
            var element_id = $(this).attr('element-id');
            var lang_id = $(this).attr('lang-id');
            var element_name = $(this).val();
            var element_attr_name = $(this).attr('name');
            if (element_attr_name === 'material-name')
                var edited_row = 'name';
            else
                edited_row = 'body';

            if (element_id === undefined)
                element_id = '';

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: element_id,
                    name: element_name,
                    lang_id: lang_id,
                    edited_row: edited_row
                },
                success: function (response) {

                    if (response.status === true) {

                        $(current_element.parents('.photo-name').siblings('.edit-gallery-photo').find('a[data-lang-id="' + lang_id + '"]')).addClass('active');
                        current_element.parents('.photo-name').html('<span>' + response.new_name + '</span>');
                        current_element.parents('.photo-descr').html('<span>' + response.new_body + '</span>');


                        toastr.success(response.messages);
                    } else {
                        toastr.error(response.messages);
                    }
                }
            });

        });

        //  Edit gallery photo item

        // Universal clone

        $('.button-new-input button').click(function (e) {
            e.preventDefault();

            var _this = $(this);
            var clone_div = _this.parent('.button-new-input').siblings('.clone_div');

            cloneInput($('form').attr('id'), clone_div, iteration++, 'add');
        });

        $(document).on('click', '.destroy_cloned_input', function () {
            cloneInput($('form').attr('id'), $(this), null, 'destroy');
        });

        $(document).on('click', '.new_destroy_cloned_input', function () {
            cloneInput($('form').attr('id'), $(this), null, 'destroy');
        });

        // Universal clone

        // Copy alias to clipboard

        $(document).on("dblclick", ".alias-block", function () {
            copyToClipboard($(this).find('span').get(0));
        });

        // Copy alias to clipboard

        //    Change user relations

        $('.modules-id').on('change', function () {
            var _this = $(this);
            var module_id = _this.data('module-id');
            ChangeActionDisplay(module_id);
        });

        //    Change user relations

        //   Count new feedback

        var static_lang = $('html').attr('lang');
        var static_url = window.location.origin + '/' + static_lang + '/back/new-feedback';
        var audio_alert = new Audio('/admin-assets/js/audio/alert-sound.mp3');

        var check_news = setInterval(function () {
            $.ajax({
                type: "POST",
                url: static_url,
                success: function (response) {

                    if (response.status == true) {

                        if (response.feedform.count > 0) {

                            if ($('#' + response.feedform.alias).find('sup').html() == ' ') {
                                audio_alert.play();

                                $('.alert-parent').find('#alert-' + response.feedform.alias).children('a').html(response.feedform.messages);
                                $('.alert-parent').find('#alert-' + response.feedform.alias).fadeIn(function () {
                                    $('.alert-parent').find('#alert-' + response.feedform.alias).removeClass('hidden');
                                });

                                setTimeout(function () {
                                    $('.alert-parent').find('#alert-' + response.feedform.alias).fadeOut('slow');
                                }, 15000);
                            } else if (response.feedform.count > parseInt($('#' + response.feedform.alias).find('sup').data('count'))) {
                                audio_alert.play();
                                $('.alert-parent').find('#alert-' + response.feedform.alias).children('a').html(response.feedform.messages);
                                $('.alert-parent').find('#alert-' + response.feedform.alias).fadeIn('slow');

                                setTimeout(function () {
                                    $('.alert-parent').find('#alert-' + response.feedform.alias).fadeOut('slow');
                                }, 15000);
                            }

                            $('.sidebar').find('#' + response.feedform.alias).find('sup').html(response.feedform.count > 99 ? '99+' : response.feedform.count);
                        }

                        // if (response.comments.count > 0) {
                        //
                        //     if ($('#' + response.comments.alias).find('sup').html() == ' ') {
                        //         audio_alert.play();
                        //         $('.alert-parent').find('#alert-' + response.comments.alias).children('a').html(response.comments.messages);
                        //         $('.alert-parent').find('#alert-' + response.comments.alias).fadeIn(function () {
                        //             $('.alert-parent').find('#alert-' + response.comments.alias).removeClass('hidden');
                        //         });
                        //
                        //         setTimeout(function () {
                        //             $('.alert-parent').find('#alert-' + response.comments.alias).fadeOut('slow');
                        //         }, 15000);
                        //     }
                        //     else if (response.comments.count > parseInt($('#' + response.comments.alias).find('sup').data('count'))) {
                        //         audio_alert.play();
                        //         $('.alert-parent').find('#alert-' + response.comments.alias).children('a').html(response.comments.messages);
                        //         $('.alert-parent').find('#alert-' + response.comments.alias).fadeIn('slow');
                        //
                        //         setTimeout(function () {
                        //             $('.alert-parent').find('#alert-' + response.comments.alias).fadeOut('slow');
                        //         }, 15000);
                        //     }
                        //
                        //     $('.sidebar').find('#' + response.comments.alias).find('sup').html(response.comments.count > 99 ? '99+' : response.comments.count);
                        // }

                    }
                },
                error: function (event) {
                    switch (event.status) {
                        case 401:
                            clearInterval(check_news);
                            break;
                    }
                }
            });
        }, 15000);
//    Count new feedback


        /*new upload photos*/
        $("#image_upload").change(function (e) {
            e.stopPropagation();
            e.preventDefault();
            showUploadPhotos(this);
        });
        $(document).on('click', '.upload_photos .delete_photo', function () {
            $(this).parents('.one_photo').remove();
        });

        /*delete current images*/
        $(document).on('click', '.upload_photos .remove_uploaded_img', function (e) {
            e.preventDefault();

            var _this = $(this);
            var curr_img = _this.attr('data-file-name');
            var url = _this.data('destroy-url');
            var uploadPath = _this.parents('.one_photo').data('path');
            var curr_id = _this.parents('.one_photo').data('id');

//			var form = $('.file-div button');

            $.ajax({
                url: url,
                beforeSend: function () {
//					form.find('.loader-list').fadeIn();
                },
                type: 'POST',
                data: {
                    'curr_img': curr_img,
                    'uploadPath': uploadPath,
                    'curr_id': curr_id
                },
                success: function (data) {
//					form.find('.loader-list').fadeOut();

                    if (data.status === true) {
                        _this.parents('.one_photo').fadeOut(function () {
                            _this.parents('.one_photo').remove();
                        });
                        if (data.messages !== undefined)
                            toastr.info(data.messages);
                    } else {
                        if (data.messages !== undefined)
                            toastr.error(data.messages);
                    }
                },
                error: function () {
//					form.find('.loader-list').fadeOut();
                }
            });
            return false;
        });


    });

    /*new upload photos*/
    function showUploadPhotos(input) {
        console.log('aa');
        $('.images_list').html('');
        if (input.files && input.files[0]) {
            $.each(input.files, function (index, value) {
                // if(value.type.search("png"))
                var reader = new FileReader();
                reader.onload = function (e) {

                    var image = '<div class="one_photo">\n' +
                        '<div class="ann_photo_bg" style="background-image: url(' + e.target.result + ');"></div>\n' +
                        '<div class="ann_controls">\n' +
                        '<div class="ann_control delete_photo">x</div>\n' +
                        '</div>\n' +
                        '<input type="hidden" name="uploaded_images[]" value="' + index + '">\n' +
                        '</div>\n';

                    $('.images_list').append(image);
                };
                reader.readAsDataURL(value);

            });
        }
    }

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "3000",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    function translit(s) {
        var t = "îişsăaâaţtаaбbвvгgдdеeёjoжzhзzиiйjjкkлlмmнnоoпpрrсsтtуuфfхkhцcчchшshщshhъ''ыyь'эehюjuяjaĂAÂAÎIŞSŢTАAБBВVГGДDЕEЁJoЖZhЗZИIЙJjКKЛLМMНNОOПPРRСSТTУUФFХKhЦCЧChШShЩShhЪ''ЫYЬ'ЭEhЮJuЯJa";
        t = t.replace(/([а-яёЁţâăşî])([a-z']+)/gi, '.replace(/$1/g,"$2")');
        ret = eval("s" + t);
        ret = ret.replace(/[ș]/g, "s");
        ret = ret.replace(/[ț]/g, "t");
        ret = ret.replace(/[^a-z0-9]/gi, "-");
        ret = ret.replace(/-{2,1000}/gi, "-");
        ret = ret.replace(/-$/gi, "").toLowerCase();
        return ret;
    }

    function cloneInput(form_id, clone_el, count_inputs, event) {

//    Add new relation

        if (form_id === 'add-form') {

            if (event === 'add') {

                var clone_input = clone_el.first().clone(true).addClass('cloned_div');
                var lastClonedEl = clone_el.last();

                clone_input.insertAfter(lastClonedEl);
                clone_input.find('input').attr('id', 'new_input_' + count_inputs).prop('value', '');

                clone_input.append('<span class="destroy_cloned_input destroy_cloned_element"></span>');

            } else if (event === 'destroy') {
                clone_el.parents('.cloned_div').remove();
            }

        }

//    Add new input


//    Edit new input

        if (form_id === 'edit-form') {

            if (event === 'add') {

                clone_input = clone_el.first().clone(true).addClass('cloned_div');
                lastClonedEl = clone_el.last();

                clone_input.insertAfter(lastClonedEl);

                clone_input.find('input').attr('id', 'new_input_' + (count_inputs * (-1))).prop('value', '');

                clone_input.find('span').removeClass('destroy_cloned_input').addClass('new_destroy_cloned_input');
            } else if (event === 'destroy') {

                if (clone_el.parents('.new_input').find('.clone_div').length > 1) {
                    if (clone_el.hasClass('destroy_cloned_input')) {
                        clone_el.parents('.clone_div').remove();

                        var curr_id = clone_el.parents('.clone_div').data('elem-id');
                        var curr_name = clone_el.siblings('input').attr('name').slice(0, -2);
                        var curr_element = clone_el.siblings('input').data('position-el');
                        var url = $('.new_input').data('url') + '/ajaxRequest/removeClone';
                        var curr_lang_id = $('#lang').val();

                        if (curr_id > 0) {
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: {
                                    curr_id: curr_id,
                                    curr_element: curr_element,
                                    input_name: curr_name,
                                    lang_id: curr_lang_id
                                },
                                success: function (response) {
                                    if (response.messages !== undefined) {
                                        if (response.status === true) {
                                            toastr[response.type](response.messages);
                                        } else {
                                            toastr[response.type](response.messages);
                                        }
                                    }

                                }
                            });
                        }
                    } else {
                        clone_el.parents('.cloned_div').remove();
                    }
                } else {
                    toastr.warning([$('.new_input').data('msg-err')]);
                }
            }


        }

//    Edit new input

    }

    //PDF file
    if ($('.pdf_response').hasClass('pdf_response_visible')) {
        setTimeout(function () {
            $('.pdf_response').removeClass('pdf_response_visible');
            window.location.href = window.location.href.split("?")[0];
        }, 4000);
        setTimeout(function () {
            $('.pdf_response').hide();

        }, 4400);
    }

    function copyToClipboard(elem) {
        // create hidden text element, if it doesn't already exist
        var targetId = "_hiddenCopyText_";
        var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
        var origSelectionStart, origSelectionEnd;
        if (isInput) {
            // can just use the original source element for the selection and copy
            target = elem;
            origSelectionStart = elem.selectionStart;
            origSelectionEnd = elem.selectionEnd;
        } else {
            // must use a temporary form element for the selection and copy
            target = document.getElementById(targetId);
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = targetId;
                document.body.appendChild(target);
            }
            target.textContent = elem.textContent;
        }
        // select the content
        var currentFocus = document.activeElement;
        target.focus();
        target.setSelectionRange(0, target.value.length);

        // copy the selection
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch (e) {
            succeed = false;
        }
        // restore original focus
        if (currentFocus && typeof currentFocus.focus === "function") {
            currentFocus.focus();
        }

        if (isInput) {
            // restore prior selection
            elem.setSelectionRange(origSelectionStart, origSelectionEnd);
        } else {
            // clear temporary content
            target.textContent = "";
        }

        if (succeed)
            toastr.info(['Text was copy in your clipboard!']);
        else
            toastr.error(['Text was not copy in your clipboard! Please contact Administrator!']);

    }

    function ChangeActionDisplay(modules_id) {
        var actions = ['new', 'save', 'active', 'del_to_rec', 'del_from_rec'];

        if ($('#modules_id-' + modules_id + '-').is(':checked')) {

            $('#taction-' + modules_id + '-').stop().slideDown();
            for (i = 0; i < actions.length; i++) {
                $('#' + actions[i] + '-' + modules_id + '-').prop('checked', true);
                $('#' + actions[i] + '-' + modules_id + '-').trigger('refresh');
            }
        } else {
            $('#taction-' + modules_id + '-').stop().slideUp();
            for (i = 0; i < actions.length; i++) {
                $('#' + actions[i] + '-' + modules_id + '-').prop('checked', false);
                $('#' + actions[i] + '-' + modules_id + '-').trigger('refresh');
            }
        }
    }

})(jQuery);

function saveForm(parentThat, functionName) {
    var form_id = $(parentThat).data('form-id');
    $('#' + form_id).submit(function (event) {
        event.preventDefault();
    });

    $('[data-type="ckeditor"]').each(function (index, el) {
        if (CKEDITOR.instances['body' + index] !== undefined)
            $(this).val(CKEDITOR.instances['body' + index].getData());
        else
            $(this).val(CKEDITOR.instances.body.getData());
    });

    var form = $('#' + $(parentThat).data('form-id'));
    var serializedForm = $(form).find("select, textarea, input").serializeArray();

    var formData = new FormData($(form)[0]);

    //Only for video gallery
    /*if (form_id === 'add-video-form' && form.hasClass('edit-video-form')) {
        serializedForm = serializedForm.push({name: 'edit-video', value: true});
    }*/
    //Only for video gallery

    if (!$(form)) {
        toastr.error('Error! Please contact administrator');
        return;
    }

    //Loader gif
    var loader_gif = $(form).find("#loader-gif");
    //Loader gif

    $.ajax({
        method: "POST",
        url: $(form).attr('action'),
        beforeSend: function () {
            loader_gif.fadeIn()
        },
        data: formData,
        async: true,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
    })
        .done(function (response) {
            loader_gif.fadeOut();
            $('.write-form').fadeOut();
            if (form_id !== 'search-form') {
                if (response.messages === null)
                    return;

                var ObjNames = Object.keys(response.messages);

                for (var messageKeyIterator in ObjNames) {
                    $(form).find("[name='" + ObjNames[messageKeyIterator] + "']").addClass('error-input');
                }

                if (response.status === true)
                    if (response.type !== null && response.type) {
                        var _toastrType = response.type;

                        for (var messageIterator in response.messages) {
                            toastr[_toastrType](response.messages[messageIterator]);
                        }
                    } else {
                        for (messageIterator in response.messages) {
                            toastr.success(response.messages[messageIterator]);
                        }
                    }
                else {
                    if (response.type !== null && response.type) {
                        _toastrType = response.type;

                        for (messageIterator in response.messages) {
                            toastr[_toastrType](response.messages[messageIterator]);
                        }
                    } else {
                        for (messageIterator in response.messages) {
                            toastr.error(response.messages[messageIterator]);
                        }
                    }

                }

                if (response.redirect) {
                    setTimeout(function () {
                        window.location = response.redirect;
                    }, 50);

                }


                if (functionName !== undefined) {
                    window[functionName]();
                }
            } else {

                $('.search-btn').removeClass('active');
                $('.empty-response').remove();

                var remove_many_objects = $('.remove-many-object');
                $(remove_many_objects).find('span').html($(remove_many_objects).find('span').attr('data-msg'));
                var send_right = $(remove_many_objects).width() + 55 + 'px';

                $(remove_many_objects).animate({
                    right: '-' + send_right
                }, 'slow');

                var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + response.url;
                window.history.pushState({path: newUrl}, '', newUrl);

                if (response.messages === null)
                    return;

                ObjNames = Object.keys(response.messages);

                for (messageKeyIterator in ObjNames) {

                    $(form).find("[name='" + ObjNames[messageKeyIterator] + "']").addClass('error-input');

                }

                if (response.status === true) {
                    $('.table').remove();
                    $('.list-table').html(response.view);

                    var count_th = $('.table').find('th').length;
                    $('.table').children('tfoot').find('td').prop('colspan', count_th);

                    for (messageIterator in response.messages) {
                        toastr.success(response.messages[messageIterator]);
                    }
                } else {
                    $('.table').remove();
                    $('.list-table').html(response.view);

                    count_th = $('.table').find('th').length;
                    $('.table').children('tfoot').find('td').prop('colspan', count_th);

                    for (messageIterator in response.messages) {
                        toastr.error(response.messages[messageIterator]);
                    }
                }
            }
        })
        .fail(function (msg) {
            loader_gif.fadeOut();
            toastr.error('Fail to send data');
        });

}

$('.new-row-variable').on('click', function () {
    let i = $(this).find('.status_new_row').val();
    $('.ajax-add').append(
        '<div class="d-flex between">\n' +
        '                        <div class="input-wrap w-20">\n' +
        '                            <input name="p[' + i + ']" id="" value="" placeholder="Название">\n' +
        '                        </div>\n' +
        '                        <div class="input-wrap w-20">\n' +
        '                            <input name="v[' + i + ']" id="" value="" placeholder="Переменная">\n' +
        '                        </div>\n' +
        '<div class="input-wrap w-20">\n' +
        '                            <input name="v_before[' + i + ']" id="" value="" placeholder="Текст перед полем">\n' +
        '                        </div>\n' +
        '<div class="input-wrap w-20">\n' +
        '                            <input name="v_after[' + i + ']" id="" value="" placeholder="Текст после полем">\n' +
        '                        </div>\n' +
        '                            <div class="delete-row-calc" data-delete="0">\n' +
        '                                удалить\n' +
        '                            </div>\n' +
        '                        </div>'
    )

    $(this).find('.status_new_row').attr('value', --i);
})

$(document).on('click', '.delete-row-calc-var', function () {

    let url = $('.new-row-variable').data('action');
    let id = $(this).attr('data-delete');

    $(this).closest('.d-flex.between').remove();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: url,
        data: {id: id},

        success: function (response) {

        }
    });

})

$(document).on('click', '.delete-row-calc-formula', function () {

    let url = $('.new-row-formula').data('action');
    let id = $(this).attr('data-delete');

    $(this).closest('.d-flex.between').remove();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: url,
        data: {id: id},

        success: function (response) {

        }
    });

})


$('.new-row-formula').on('click', function () {
    let i = $(this).find('.status_new_row').val();
    $('.formula-ajax').append(
        '<div class="d-flex between">\n' +
        '                            <div class="input-wrap w-20 ">\n' +
        '                                <input name="formula[' + i + ']" id="" value="" placeholder="Формула">\n' +
        '                            </div>\n' +
        '                            <div class="input-wrap w-20 ">\n' +
        '                                <input name="formula_name[' + i + ']" id="" value="" placeholder="Название формулы">\n' +
        '                            </div>\n' +
        '<div class="input-wrap w-20 ">' +
        '<input name="formula_dime[' + i + ']" id="" placeholder="Текст перед выводом"> </div>' +
    ' <div class="input-wrap w-20 ">' +
    ' <input name="formula_dimetext[' + i + ']" id="" placeholder="Единицы измерения"> </div>' +
    '                            <div class="delete-row-calc-var delete-row-calc" data-delete="0">\n' +
    '                                удалить\n' +
    '                            </div>\n' +
    '                        </div>'
)

    $(this).find('.status_new_row').attr('value', --i);
})
