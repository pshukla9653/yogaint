function showerror(mydiv, erromsg) {
    $(mydiv).css({ border: '1px solid red', color: '#222', background: '#fff' });
    $("html, body").animate({ scrollTop: $(mydiv).offset().top - 150 }, 500);
    $(mydiv).focus();
    if (erromsg !== '') {
        $(mydiv).after('<span class="show-error-msg">' + erromsg + '</span>');
    }
}

function showsuccess(mydiv) {
    $(mydiv).css({ border: '1px solid #D7D7D7', background: '#fff', color: '#222' });
    $('.show-error-msg').remove();
}

function ValidateEmail(email) {
    var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return expr.test(email);
}


function addnewitems(page, data_id, action, url) {
    if (data_id === '0') heading = 'Add New ' + page;
    else heading = 'Edit ' + page;
    $.ajax({
        type: "post",
        url: "ajax/"+url+".php", //url of the page where php,mysql code
        data: { data_id: data_id, action: action },
        dataType: 'html',
        beforeSend: function() {
            $(".overlap .ajaxloader").show();
            $(".overlap").show();
        },
        success: function(data) {
            $('.overlap_js').html('<div class="overlap-cont"><div class="overlap-cont-head"><h1>' + heading + '</h1><ul><li class="close"><img src="images/icons/close.jpg" alt=""></li></ul></div>' + data + '</div>');
        }
    });
}
function saveitem($this, action, url) {
        var data_id = $this.attr('data-id');
        var name = $('.name').val();
        var slug = $('.slug').val();
        var postoption = Array();
        var img = $('.thumb img').attr('data-id');
        $('.postoption').each(function() {
            var input = $(this).find('.input');
            postoption.push(input.val());
        });
        if (name === '') {
            showerror('.name', 'Name Could not be Blank');
            return false;
        } else {
            showsuccess('.name');
        }


        if(url==='post' || url==='bpost' || url==='ppost') {
            var catpost = $('.catpost:checked').map(function() { return this.value; }).get().join(',');
            var tagpost = $('.tagpost:checked').map(function() { return this.value; }).get().join(',');
            var postData = {catpost: catpost, tagpost: tagpost}

            var description = CKEDITOR.instances['ckeditor'].getData();
        }
        else {
            var postData = {};
            var description = $('.description').val();
        }

        if(url==='category' || url==='bcategory' || url==='pcategory') {
            var parent_cat = $('.parent_cat').val();
            var catData = {parent_cat: parent_cat}
        }
        else {
            var catData = {};
        }

        if(url==='page' || url==='bpage') {
            var parent_page = $('.parent_page').val();
            var parents_page = $(".parent_page option:selected").attr("data-id");
            var pageData = {parent_page: parent_page, parents_page:parents_page}
            var description = CKEDITOR.instances['ckeditor'].getData();
        }
        else {
            var pageData = {};
        }
        
        var data = { name: name, slug: slug, description: description, postoption: postoption, img: img, data_id: data_id, action: action };


        $.ajax({
            type: "post",
            url: "ajax/"+url+".php", //url of the page where php,mysql code
            data: $.extend(data, postData, catData, pageData),
            dataType: 'html',
            beforeSend: function() {
                $this.hide();
                $(".show-sorry-msg").removeClass('success');
                $(".ajaxloader").show();
            },
            complete: function(){
                $(".ajaxloader").hide();
                $this.show();
            },
            success: function(data) {
                if (data === 'OK') {
                    $(".show-sorry-msg").html(url+' Successfully Save');
                    $(".show-sorry-msg").addClass('success');
                    if(data_id === '0') {
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        setTimeout(function() {
                            $(".show-sorry-msg").html('');
                            $(".show-sorry-msg").removeClass('success');
                        }, 3000);
                    }
                } else {
                    $(".show-sorry-msg").html('server error');
                }
            },
            error: function(){
                console.log("ajax error");
            }
        });
}
function del_items(action, $this, url) {
    var data_id = $this.parents('tr').attr('data-id');
    if (confirm("You are about to permanently delete these items from your site. This action cannot be undone. 'Cancel' to stop, 'OK' to delete.")) {
        $.ajax({
            type: "post",
            url: "ajax/"+url+".php", //url of the page where php,mysql code
            data: { data_id: data_id, action: action },
            dataType: 'html',
            beforeSend: function() {
                $this.parent().fadeOut();
            },
            success: function(data) {
                if (data === 'OK') {
                    $this.parents('tr').fadeOut();
                }
            }
        });
    }
}

function act_items(action, $this, url) {
    var data_id = $this.parents('tr').attr('data-id');
    $.ajax({
        type: "post",
        url: "ajax/"+url+".php", //url of the page where php,mysql code
        data: { data_id: data_id, action: action },
        dataType: 'html',
        success: function(data) {
            if (data == 'ACT') {
                $this.html('Deactive');
                $this.removeClass('background-blue');
                $this.addClass('background-red');
            } else if (data == 'DACT') {
                $this.html('Active');
                $this.removeClass('background-red');
                $this.addClass('background-blue');
            }
        }
    });
}

function profileDropDown() {
    var x = document.getElementById("toggle");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}
/* var colorclickme = document.getElementById('colormes');
colorclickme.addEventListener('change',function(){
    alert(colorclickme.value);
}); */
$(window).scroll(function() {
    var aTop = "100";
    if ($(this).scrollTop() >= aTop) {
        $('#top-link-block').addClass('affix');
    } else {
        $('#top-link-block').removeClass('affix');
    }
});
$(window).load(function() {
    // Animate loader off screen
    $(".se-pre-con").fadeOut("slow");;
});
$(document).ready(function() {
    $('#phone, .phone, input[name=phone]').keydown(function(event) {
        // Allow special chars + arrows 
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 ||
            event.keyCode == 27 || event.keyCode == 13 ||
            (event.keyCode == 65 && event.ctrlKey === true) ||
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            return;
        } else {
            // If it's not a number stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                event.preventDefault();
            }
        }
    });

    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/
    /******************************************jquery-function****************************************/

    /******************************************images-function****************************************/
    $('.cl_multy_del').click(function() {
        $(this).hide();
        $('.cl_reset_sel, .del_multy_img').show();
        $('.cl_img_gal ul').addClass('selected');
    });
    $('body').on('click', '.cl_img_gal ul.selected li', function() {
        $(this).toggleClass('selected');
    });
    $('.cl_reset_sel').click(function() {
        $('.cl_reset_sel, .del_multy_img').hide();
        $('.cl_multy_del').show();
        $('.cl_img_gal ul,.cl_img_gal ul li').removeClass('selected');
    });
    $('body').on('click', '.overlap-cont-head .close', function() {
        location.reload();
    });
    $('body').on('click', '.cl_sel_img li', function() {
        var src = $(this).find('img').attr('src');
        var data_id = $(this).attr('data-id');
        $('.thumb img').attr('src', src);
        $('.thumb img').attr('data-id', data_id);
        $('.imag-gallery ul').empty();
    });
    $('body').on('click', '.cl_remove_img', function() {
        $('.thumb img').attr('src', 'images/thumb.png');
        $('.thumb img').attr('data-id', '0');
    });
    $('body').on('click', '.cl_img_gal ul.choose li', function() {
        var data_id = $(this).attr('data-id');
        var classss = $(this).attr('class');
        var img = $(this).html();
        $('.post_gallery').append('<li class="' + classss + '" data-id="' + data_id + '">' + img + '</li>');
    });
    $('body').on('click', '.imag-gallery ul.post_gallery li', function() {
        $(this).remove();
    });

    /******************************************nav-sidebar-function****************************************/
    $(".sidebar-nave ul li .head").click(function() {
        $(".sidebar-nave > ul > li").removeClass('active');
        $(this).parent().addClass('active');
        $(".sidebar-nave ul li ul").slideUp();
        $(this).next('ul').slideDown();
    });

    /******************************************check-function****************************************/
    $('body').on('click', '.post-input .checkhead', function(e) {
        $(this).next().toggle();
        e.stopPropagation();
    });
    $('body').on('click', '.post-input .checklist', function(e) {
        e.stopPropagation();
    });
    $('body').click(function() {
        $('.post-input .checklist').hide();
    });


    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    /******************************************ajax-function****************************************/
    $("#cl_login").click(function() {
        var username = $('#username').val();
        var userpassword = $('#userpassword').val();
        var token = $('#token').val();
        if (username === '') {
            showerror('#username', 'Username Could not be Blank');
            return false;
        } else {
            showsuccess('#username');
        }
        if (userpassword === '') {
            showerror('#userpassword', 'Password Could not be Blank');
            return false;
        } else {
            showsuccess('#userpassword');
        }
        if (token === '') {
            return false;
        }
        $.ajax({
            type: "post",
            url: "ajax/admin.php", //url of the page where php,mysql code
            data: { username: username, userpassword: userpassword, token: token, action: 'loginform', login: 1 },
            dataType: 'html',
            beforeSend: function() {
                $("#cl_login").hide();
                $(".ajaxloader").show();
                $(".show-sorry-msg").remove();
            },
            success: function(data) {
                $(".ajaxloader").hide();
                if (data === 'OK') {
                    $("#cl_login").before('<div class="show-sorry-msg success">Login Successfully</div>');
                    setTimeout(function() {
                        window.location.href = 'home.php';
                    }, 1000);
                } else {
                    $("#cl_login").show();
                    $("#cl_login").before('<div class="show-sorry-msg">' + data + '</div>');
                }
            }
        });
    });
    $('.bttn').on('click', function() {
        $('.log-pop').show();
        $('.frg_pop_login.fgt-paswrd').show();
        $('.login-mainpanel').hide();
        $('.frg_pop_login.otp').hide();
        $('.frg_pop_login.crt_pass').hide();
    })

    $('.fa-times').on('click', function() {
        $('.log-pop').hide();
        $('.bttn').show();
        $('.login-mainpanel').show();
    })
    $('.bttn-f').on('click', function() {
        $('.log-pop').hide();
        $('.bttn').show();
        $('.login-mainpanel').show();
    })
    $('.cl_fgt_passw').on('click', function() {
        var email = $('.email').val();
        if (!ValidateEmail(email)) {
            showerror('.email', 'Email not correct.');
            return false;
        } else {
            showsuccess('.email');
        }
        $.ajax({
            type: "post",
            url: "ajax/admin.php", //url of the page where php,mysql code
            data: { email: email, action: 'fgt_passw' },
            dataType: 'html',
            beforeSend: function() {
                $(".cl_fgt_passw").hide();
                $(".ajaxloader").show();
                $(".show-sorry-msg").remove();
            },
            success: function(data) {
                $(".ajaxloader").hide();
                if (data === 'OK') {
                    $(".cl_fgt_passw").before('<div class="show-sorry-msg success">Please check your inbox or spam</div>');
                    setTimeout(function() {
                        $(".frg_pop_login.otp").show();
                    }, 5000);
                }
                else if(data === 'YES') {
                    $(".cl_fgt_passw").before('<div class="show-sorry-msg success">Please check your inbox or spam</div>');
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                    
                } else {
                    $(".cl_fgt_passw").show();
                    $(".cl_fgt_passw").before('<div class="show-sorry-msg"> Mail Id not found in database </div>');

                }
            }
        });
    });

    $(".cl_otp_pass").click(function() {
        var otp_codes = $('.otp_code').val();
        if (otp_codes === '') {
            showerror('.otp_code', 'please enter otp');
            return false;
        } else {
            showsuccess('.otp_code');
        }
        $.ajax({
            type: "post",
            url: "ajax/admin.php", //url of the page where php,mysql code
            data: { otp_codes: otp_codes, action: 'otp_pass' },
            dataType: 'html',
            beforeSend: function() {
                $(".cl_otp_pass").hide();
                $(".ajaxloader").show();
                $(".show-sorry-msg").remove();
            },
            success: function(data) {
                $(".ajaxloader").hide();
                if (data === 'OK') {
                    $(".cl_otp_pass").before('<div class="show-sorry-msg success">otp matched</div>');
                    setTimeout(function() {
                        $(".frg_pop_login.crt_pass").show();
                    }, 1000);
                } else {
                    $(".cl_otp_pass").show();
                    $(".cl_otp_pass").before('<div class="show-sorry-msg">otp incorrect </div>');
                }
            }
        });
    });

    $('.cl_crt_pass').click(function() {
        var newpass = $('.newpass').val();
        var conpass = $('.conpass').val();
        if (newpass.length < 6) {
            showerror('.newpass', 'Password Should be 6 digit');
            return false;
        } else {
            showsuccess('.newpass');
        }
        if (conpass.length < 6) {
            showerror('.conpass', 'Password Should be 6 digit');
            return false;
        } else {
            showsuccess('.conpass');
        }
        if (newpass != conpass) {
            showerror('.conpass', 'Confirm password not matched');
            return false;
        } else {
            showsuccess('.conpass');
        }
        $.ajax({
            type: "post",
            url: "ajax/admin.php", //url of the page where php,mysql code
            data: { newpass: newpass, action: 'crt_pass' },
            dataType: 'html',
            beforeSend: function() {
                $(".show-sorry-msg").removeClass('success');
                $('.cl_crt_pass').hide();
                $(".ajaxloader").show();
            },
            success: function(data) {
                $(".ajaxloader").hide();
                if (data === 'OK') {
                    $(".cl_crt_pass").before('<div class="show-sorry-msg success">password successfully changed</div>');
                    $(".show-sorry-msg").addClass('success');
                }

                setTimeout(function() {
                    location.reload();
                }, 5000);
            }
        });
    });


    /******************************************images-function****************************************/
    $(".cl_img_up").change(function() {
        var form_data = new FormData();
        var ins = $('.cl_img_up').get(0).files.length;
        var filename;
        for (var x = 0; x < ins; x++) {
            filename = $('.cl_img_up').get(0).files[x];
            form_data.append("files[]", filename);
            form_data.append("action", "cl_img_up");
        }
        $.ajax({
            url: "ajax/img-lib.php", // point to server-side PHP script 
            dataType: 'text', // what to expect back from the PHP script
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            beforeSend: function() {
                $(".cl_img_up").hide();
                $(".image-up .ajaxloader").show();
            },
            success: function(response) {
                $('.image-media-error').html(response); // display success response from the PHP script
                $(".cl_img_up").show();
                $(".cl_img_up").val('');
                $(".image-up .ajaxloader").hide();
                show_multy_img();
            },
            error: function() {
                $('.image-media-error').html("Error found"); // display error response from the PHP script
                $(".cl_img_up").show();
                $(".cl_img_up").val('');
                $(".image-up .ajaxloader").hide();
            }
        });
    });

    function show_multy_img() {
        $.ajax({
            type: "post",
            url: "ajax/img-lib.php",
            data: { action: 'show_multy_img' },
            dataType: 'html',
            beforeSend: function() {
                $(".imag-gallery .ajaxloader").show();
            },
            success: function(response) {
                $('.cl_img_gal').html(response);
                $(".imag-gallery .ajaxloader").hide();
            }
        });
    }
    show_multy_img();

    $('body').on('click', '.del_multy_img', function() {
        var data_idarray = Array();
        $('.cl_img_gal ul li.selected, .overlap-cont-right-anchor .del_multy_img').each(function() {
            data_idarray.push($(this).attr('data-id'));
        });
        if (data_idarray != '') {
            if (confirm("You are about to permanently delete these items from your site. This action cannot be undone. 'Cancel' to stop, 'OK' to delete.")) {
                $.ajax({
                    type: "post",
                    url: "ajax/img-lib.php",
                    data: { data_id: data_idarray, action: 'del_multy_img' },
                    dataType: 'html',
                    beforeSend: function() {
                        $(".cl_img_gal .ajaxloader").show();
                    },
                    success: function(response) {
                        if (response === 'OK') { show_multy_img(); }
                        $('.overlap').fadeOut();
                        $('.overlap_js').empty();
                    }
                });
            }
        }

        return false;
    });

    $('body').on('click', '.cl_view_lib ul.lblist li:not(.cl_view_lib ul.lblist.selected li)', function() {
        var data_id = $(this).attr('data-id');
        $.ajax({
            type: "post",
            url: "ajax/img-lib.php",
            data: { data_id: data_id, action: 'img_gal_view' },
            dataType: 'html',
            beforeSend: function() {
                $(".overlap .ajaxloader").show();
                $('.overlap').fadeIn();
            },
            success: function(response) {
                $('.overlap .overlap_js').html(response);
            }
        });
    });
    $('body').on('click', '.cl_img_save', function() {
        var data_id = $(this).attr('data-id');
        var title = $("#title").val();
        var caption = $("#caption").val();
        var alt = $("#alt").val();
        var description = $("#description").val();
        $.ajax({
            type: "post",
            url: "ajax/img-lib.php",
            data: { data_id: data_id, title: title, caption: caption, alt: alt, description: description, action: 'img_save' },
            dataType: 'html',
            beforeSend: function() {
                $(".overlap-cont-right .ajaxloader").show();
                $(".cl_img_save").hide();
            },
            success: function() {
                $(".overlap-cont-right .ajaxloader").hide();
                $(".overlap-cont-right-btn b").html("Save Sucessfully");
                setTimeout(function() {
                    $(".cl_img_save").show();
                    $(".overlap-cont-right-btn b").empty();
                }, 2000);
            }
        });
    });
    $('body').on('click', '.cl_choose_img', function() {
        $.ajax({
            type: "post",
            url: "ajax/img-lib.php",
            data: { action: 'show_multy_img' },
            dataType: 'html',
            beforeSend: function() {
                $(".imag-gallery .ajaxloader").show();
                $(".cl_choose_img").hide();
            },
            success: function(response) {
                $('.cl_choose_img').show();
                $(".imag-gallery .ajaxloader").hide();
                $('.imag-gallery ul').html(response);
            }
        });
    });


    /******************************************category-function****************************************/
    $('.cl_add_cat_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Category', id, 'cat_pop', 'category');
    });
    $('body').on('click', '.cl_add_cat', function() {
        var $this = $(this);
        saveitem($this, 'add_cat', 'category');
    });
    $('.cl_cat_act').click(function() {
        var id = $(this);
        act_items('cat_act', id, 'category');
    });
    $('.cl_cat_del').click(function() {
        var id = $(this);
        del_items('cat_del', id, 'category');
    });

    /******************************************Enquiry-function****************************************/
    $('.cl_add_enq_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Enquiry', id, 'enq_pop', 'enquiry');
    });
    $('.cl_enq_act').click(function() {
        var id = $(this);
        act_items('enq_act', id, 'enquiry');
    });
    $('.cl_enq_del').click(function() {
        var id = $(this);
        del_items('enq_del', id, 'enquiry');
    });

    /******************************************Tag-function****************************************/
    $('.cl_add_tag_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Tag', id, 'tag_pop', 'tag');
    });
    $('body').on('click', '.cl_add_tag', function() {
        var $this = $(this);
        saveitem($this, 'add_tag', 'tag');
    });
    $('.cl_tag_del').click(function() {
        var id = $(this);
        del_items('tag_del', id, 'tag');
    });
    /******************************************Post-function****************************************/
    $('.cl_add_post_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Post', id, 'post_pop','post');
    });

    $('body').on('click', '.cl_add_post', function() {
        var $this = $(this);
        saveitem($this, 'add_post', 'post');
    });
    $('.cl_post_act').click(function() {
        var id = $(this);
        act_items('post_act', id, 'post');
    });
    $('.cl_post_del').click(function() {
        var id = $(this);
        del_items('post_del', id, 'post');
    });
    $('.cl_gal_img').click(function() {
        var data_id = $(this).parents('tr').attr('data-id');
        var data_type = $(this).parents('tr').attr('data-type');
        $.ajax({
            type: "post",
            url: "ajax/img-lib.php", //url of the page where php,mysql code
            data: { data_type: data_type, data_id: data_id, action: 'gal_img' },
            dataType: 'html',
            beforeSend: function() {
                $(".overlap .ajaxloader").show();
                $(".overlap").fadeIn();
            },
            success: function(data) {
                $('.overlap_js').html('<div class="overlap-cont"><div class="overlap-cont-head"><h1>Gallery ' + data_type + '</h1><ul><li class="close"><img src="images/icons/close.jpg" alt=""></li></ul></div>' + data + '</div>');
                show_multy_img();
            }
        });
    });

    $('body').on('change', '.cl_lib_fold_ch', function() {

        var data_type = $(this).attr('data-id');

        var data_id = $('.cl_lib_fold_ch').val();

        //var fold_name = $(this).parent().parent().attr("id");
        //fold_name == 'p' ? fold_name = 'post_id': fold_name = 'category_id';

        $.ajax({
            type: "post",
            url: "ajax/img-lib.php",
            data: { data_id: data_id, data_type: data_type, action: 'lib_fold_ch' },
            success: function(data) {
                $('.post_gallery').html(data);
            }
        });

    });

    $('body').on('click', '.cl_gal_img_addsep', function() {
        var data_imgarray = Array();
        var data_id = $('.cl_lib_fold_ch').val();
        var data_type = $(this).attr('data-type');
        $('.imag-gallery ul.post_gallery li').each(function() {
            data_imgarray.push($(this).attr('data-id'));
        });

        if (data_id == '0') {
            showerror('.cl_lib_fold_ch', 'please select one');
            return false;
        } else {
            showsuccess('.cl_lib_fold_ch');
        }

        data_img = data_imgarray.join(",");

        $.ajax({
            type: "post",
            url: "ajax/img-lib.php",
            data: { data_id: data_id, data_type: data_type, data_img: data_img, action: 'gal_img_addsep' },
            dataType: 'html',
            beforeSend: function() {
                $('.cl_post_gal_add').hide();
                $(".add-gallery-btn .ajaxloader").show();
            },
            success: function() {
                $(".add-gallery-btn .ajaxloader").hide();
                $('.show-sorry-msg').html('Save Sucessfully');
                setTimeout(function() {
                    $('.show-sorry-msg').empty();
                    $('.cl_post_gal_add').show();
                }, 800);
            }
        });
    });
    $('body').on('change', '.cl_lib_filt', function() {
        var catlist;
        var postlist;

        catlist = $('.cl_gal_ft.category .cl_lib_filt:checked').map(function() { return this.value; }).get().join(',');

        postlist = $('.cl_gal_ft.post .cl_lib_filt:checked').map(function() { return this.value; }).get().join(',');

        $this = $(this).parents('.cl_img_gal').find('.lblist');

        var catc = $(this).parents('.category').find('.cl_lib_filt:checked').length;
        var posc = $(this).parents('.post').find('.cl_lib_filt:checked').length;

        $(this).parents('.category').find('label').find('span').html(catc);
        $(this).parents('.post').find('label').find('span').html(posc);


        $.ajax({
            type: "post",
            url: "ajax/img-lib.php", //url of the page where php,mysql code
            data: { catlist: catlist, postlist: postlist, action: 'lib_filt' },
            dataType: 'html',
            beforeSend: function() {},
            success: function(data) {
                $this.html(data);
                $('.cl_sel_img ul.lblist').html(data);
            }
        });
    });

    /*********************************img-lib-gallery*****************************************************/
    $('.cl_lib_fold_pop').click(function() {
        var data_type = $(this).attr('data-type');
        $.ajax({
            type: "post",
            url: "ajax/img-lib.php", //url of the page where php,mysql code
            data: { data_type: data_type, action: 'lib_fold_pop' },
            dataType: 'html',
            beforeSend: function() {
                $(".overlap .ajaxloader").show();
                $(".overlap").fadeIn();
            },
            success: function(data) {
                $('.overlap_js').html('<div class="overlap-cont"><div class="overlap-cont-head"><h1>Gallery ' + data_type + '</h1><ul><li class="close"><img src="images/icons/close.jpg" alt=""></li></ul></div>' + data + '</div>');
                //show_multy_img();
            }
        });
    });
    $('body').on('click', '.cl_gal_img_add', function() {
        var data_idarray = Array();
        var data_id = $(this).attr('data-id');
        var data_type = $(this).attr('data-type');
        $('.imag-gallery ul.post_gallery li').each(function() {
            data_idarray.push($(this).attr('data-id'));
        });
        $.ajax({
            type: "post",
            url: "ajax/img-lib.php",
            data: { data_type: data_type, data_img: data_idarray, data_id: data_id, action: 'gal_img_add' },
            dataType: 'html',
            beforeSend: function() {
                $('.cl_post_gal_add').hide();
                $(".add-gallery-btn ").show();
            },
            success: function() {
                $(".add-gallery-btn .ajaxloader").hide();
                $('.show-sorry-msg').html('Save Sucessfully');
                setTimeout(function() {
                    $('.show-sorry-msg').empty();
                    $('.cl_post_gal_add').show();
                }, 800);
            }
        });
    });

    /******************************************Page-function****************************************/
    $('.cl_add_page_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Page', id, 'page_pop', 'page');
    });

    $('body').on('click', '.cl_add_page', function() {
        var $this = $(this);
        saveitem($this, 'add_page', 'page');
    });
    $('.cl_page_act').click(function() {
        var id = $(this);
        act_items('page_act', id, 'page');
    });
    $('.cl_page_del').click(function() {
        var id = $(this);
        del_items('page_del', id, 'page');
    });

    /******************************************Section-function****************************************/
    $('.cl_add_section_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Section', id, 'section_pop', 'section');
    });
    $('body').on('click', '.cl_add_section', function() {
        var data_id = $(this).attr('data-id');
        var name = $('.name').val();
        var postoption = Array();
        var img = $('.thumb img').attr('data-id');
        $('.postoption').each(function() {
            var input = $(this).find('.input');
            postoption.push(input.val());
        });
        if (name === '') {
            showerror('.name', 'Name Could not be Blank');
            return false;
        } else {
            showsuccess('.name');
        }
        $.ajax({
            type: "post",
            url: "ajax/section.php", //url of the page where php,mysql code
            data: { postoption: postoption, name: name, img: img, data_id: data_id, action: 'add_section' },
            dataType: 'html',
            beforeSend: function() {
                $(".cl_add_section").hide();
                $(".show-sorry-msg").removeClass('success');
                $(".ajaxloader").show();
            },
            complete: function(){
                $(".ajaxloader").hide();
                $(".cl_add_section").show();
            },
            success: function(data) {
                if (data === 'OK') {
                    $(".show-sorry-msg").html('Section Successfully Save');
                    $(".show-sorry-msg").addClass('success');
                    if(data_id === '0') {
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        setTimeout(function() {
                            $(".show-sorry-msg").html('');
                            $(".show-sorry-msg").removeClass('success');
                        }, 3000);
                    }
                } else {
                    $(".show-sorry-msg").html('server error');
                }
            },
            error: function(){
                console.log("ajax error");
            }
        });
    });
    $('.cl_section_del').click(function() {
        var id = $(this);
        del_items('section_del', id, 'section');
    });
    /******************************************comment-function****************************************/
    $('.cl_add_comment_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Comment', id, 'comment_pop', 'comment');
    });
    $('body').on('click', '.cl_add_commment', function() {
        var data_id = $(this).attr('data-id');
        var name = $('.name').val();
        var email = $('.email').val();
        var post_name = $('.post_name').val();
        var rating = $('.rating').val();
        var description = $('.description').val();
        if (name === '') {
            showerror('.name', 'Name Could not be Blank');
            return false;
        } else {
            showsuccess('.name');
        }
        if (!ValidateEmail(email)) {
            showerror('.email', 'Email not correct email.');
            return false;
        } else {
            showsuccess('.email');
        }
        if (post_name === '') {
            showerror('.post_name', 'Choose Post');
            return false;
        } else {
            showsuccess('.post_name');
        }
        if (rating === '') {
            showerror('.rating', 'Choose Rating');
            return false;
        } else {
            showsuccess('.rating');
        }
        $.ajax({
            type: "post",
            url: "ajax/comment.php", //url of the page where php,mysql code
            data: { name: name, email: email, post_name: post_name, rating: rating, description: description, data_id: data_id, action: 'add_comment' },
            dataType: 'html',
            beforeSend: function() {
                $(".cl_add_comment").hide();
                $(".show-sorry-msg").removeClass('success');
                $(".ajaxloader").show();
            },
            complete: function(){
                $(".ajaxloader").hide();
                $(".cl_add_commment").show();
            },
            success: function(data) {
                if (data === 'OK') {
                    $(".show-sorry-msg").html('Comment Successfully Save');
                    $(".show-sorry-msg").addClass('success');
                    if(data_id === '0') {
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        setTimeout(function() {
                            $(".show-sorry-msg").html('');
                            $(".show-sorry-msg").removeClass('success');
                        }, 3000);
                    }
                } else {
                    $(".show-sorry-msg").html('server error');
                }
            },
            error: function(){
                console.log("ajax error");
            }
        });
    });
    $('.cl_comment_act').click(function() {
        var $this = $(this);
        var data_id = $this.parents('tr').attr('data-id');
        $.ajax({
            type: "post",
            url: "ajax/comment.php", //url of the page where php,mysql code
            data: { data_id: data_id, action: 'comment_act' },
            dataType: 'html',
            success: function(data) {
                if (data === 'OK') {
                    $this.fadeOut();
                }
            }
        });
    });
    $('.cl_comment_del').click(function() {
        var id = $(this);
        del_items('comment_del', id, 'comment');
    });


/*********************portfolio-post-function*****************************/

$('.cl_add_ppost_pop').click(function() {
    var id = $(this).attr('data-id');
    addnewitems('Portfolio Post', id, 'ppost_pop', 'ppost');
});
$('body').on('click', '.cl_add_ppost', function() {
    var $this = $(this);
    saveitem($this, 'add_ppost', 'ppost');
});
$('.cl_pgal_img').click(function() {
    var data_id = $(this).parents('tr').attr('data-id');
    var data_type = $(this).parents('tr').attr('data-type');
    $.ajax({
        type: "post",
        url: "ajax/img-lib.php", //url of the page where php,mysql code
        data: { data_type: data_type, data_id: data_id, action: 'pgal_img' },
        dataType: 'html',
        beforeSend: function() {
            $(".overlap .ajaxloader").show();
            $(".overlap").fadeIn();
        },
        success: function(data) {
            $('.overlap_js').html('<div class="overlap-cont"><div class="overlap-cont-head"><h1>Gallery ' + data_type + '</h1><ul><li class="close"><img src="images/icons/close.jpg" alt=""></li></ul></div>' + data + '</div>');
            show_multy_img();
        }
    });
});


$('.cl_ppost_act').click(function() {
    var id = $(this);
    act_items('ppost_act', id, 'ppost');
});

$('.cl_ppost_del').click(function() {
    var id = $(this);
    del_items('ppost_del', id, 'ppost');
});

/*********************portfolio-category-function********/
$('.cl_add_pcat_pop').click(function() {

    var id = $(this).attr('data-id');
    addnewitems('portfolio Category', id, 'pcat_pop', 'pcategory');
});
$('body').on('click', '.cl_add_pcat', function() {
    var $this = $(this);
    saveitem($this, 'add_pcat', 'pcategory');
});

$('.cl_pcat_act').click(function() {
    var id = $(this);
    act_items('pcat_act', id, 'pcategory');
});

$('.cl_pcat_del').click(function() {
    var id = $(this);
    del_items('pcat_del', id, 'pcategory');
});


    /******************************************blog-function****************************************/

    /*********************blog-category-function********/
    $('.cl_add_bcat_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Blog Category', id, 'bcat_pop', 'bcategory');
    });
    $('body').on('click', '.cl_add_bcat', function() {
        var $this = $(this);
        saveitem($this, 'add_bcat', 'bcategory');
    });

    $('.cl_bcat_act').click(function() {
        var id = $(this);
        act_items('bcat_act', id, 'bcategory');
    });

    $('.cl_bcat_del').click(function() {
        var id = $(this);
        del_items('bcat_del', id, 'bcategory');
    });

    /*********************blog-post-function*****************************/

    $('.cl_add_bpost_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Blog Post', id, 'bpost_pop', 'bpost');
    });
    $('body').on('click', '.cl_add_bpost', function() {
        var $this = $(this);
        saveitem($this, 'add_bpost', 'bpost');
    });


    $('.cl_bpost_act').click(function() {
        var id = $(this);
        act_items('bpost_act', id, 'bpost');
    });

    $('.cl_bpost_del').click(function() {
        var id = $(this);
        del_items('bpost_del', id, 'bpost');
    });

    /******************************************blog-Tag-function****************************************/
    $('.cl_add_btag_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Blog Tag', id, 'btag_pop', 'btag');
    });
    $('body').on('click', '.cl_add_btag', function() {
        var $this = $(this);
        saveitem($this, 'add_btag', 'btag');
    });
    $('.cl_btag_del').click(function() {
        var id = $(this);
        del_items('btag_del', id, 'btag');
    });


    /********************************blog-comment-function************************************/
    $('.cl_add_bcomment_pop').click(function() {
        var id = $(this).attr('data-id');
        addnewitems('Blog Comment', id, 'bcomment_pop', 'bcomment');
    });
    $('body').on('click', '.cl_add_bcommment', function() {
        var data_id = $(this).attr('data-id');
        var name = $('.name').val();
        var email = $('.email').val();
        var post_name = $('.post_name').val();
        var rating = $('.rating').val();
        var description = $('.description').val();
        if (name === '') {
            showerror('.name', 'Name Could not be Blank');
            return false;
        } else {
            showsuccess('.name');
        }
        if (!ValidateEmail(email)) {
            showerror('.email', 'Email not correct email.');
            return false;
        } else {
            showsuccess('.email');
        }
        if (post_name === '') {
            showerror('.post_name', 'Choose Post');
            return false;
        } else {
            showsuccess('.post_name');
        }
        if (rating === '') {
            showerror('.rating', 'Choose Rating');
            return false;
        } else {
            showsuccess('.rating');
        }
        $.ajax({
            type: "post",
            url: "ajax/bcomment.php", //url of the page where php,mysql code
            data: { name: name, email: email, post_name: post_name, rating: rating, description: description, data_id: data_id, action: 'add_bcomment' },
            dataType: 'html',
            beforeSend: function() {
                $(".cl_add_bcomment").hide();
                $(".show-sorry-msg").removeClass('success');
                $(".ajaxloader").show();
            },
            complete: function(){
                $(".ajaxloader").hide();
                $(".cl_add_bcommment").show();
            },
            success: function(data) {
                $(".ajaxloader").hide();
                if (data === 'OK') {
                    $(".show-sorry-msg").html('Blog Comment Successfully Save');
                    $(".show-sorry-msg").addClass('success');
                    if(data_id === '0') {
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        setTimeout(function() {
                            $(".show-sorry-msg").html('');
                            $(".show-sorry-msg").removeClass('success');
                        }, 3000);
                    }
                } else {
                    $(".show-sorry-msg").html('server error');
                }
            },
            error: function(){
                console.log("ajax error");
            }
        });
    });
    $('.cl_bcomment_del').click(function() {
        var id = $(this);
        del_items('bcomment_del', id, 'bcomment');
    });
    $('.cl_bcomment_act').click(function() {
        var $this = $(this);
        var data_id = $this.parents('tr').attr('data-id');
        $.ajax({
            type: "post",
            url: "ajax/bcomment.php", //url of the page where php,mysql code
            data: { data_id: data_id, action: 'bcomment_act' },
            dataType: 'html',
            success: function(data) {
                if (data === 'OK') {
                    $this.fadeOut();
                }
            }
        });
    });
    /******************************************profile-function****************************************/
    $('.cl_p_email').click(function() {
        var name = $('.p-name').val();
        var email = $('.p-email').val();
        if (name === '') {
            showerror('.p-name', 'Name Could not be Blank');
            return false;
        } else {
            showsuccess('.p-name');
        }
        if (!ValidateEmail(email)) {
            showerror('.p-email', 'Email not correct email.');
            return false;
        } else {
            showsuccess('.p-email');
        }
        $.ajax({
            type: "post",
            url: "ajax/profile.php", //url of the page where php,mysql code
            data: { name: name, email: email, action: 'p_email' },
            dataType: 'html',
            beforeSend: function() {
                $(".cl_p_email").hide();
                $(".show-sorry-msg").removeClass('success');
                $('.cl_p_email').hide();
                $(".ajaxloader.email-ajax").show();
            },
            complete: function(){
                $(".ajaxloader.email-ajax").hide();
            },
            success: function(data) {
                $(".ajaxloader").hide();
                if (data === 'OK') {
                    $(".show-sorry-msg.email-msg").html('Email Successfully Save');
                    $(".show-sorry-msg.email-msg").addClass('success');
                    setTimeout(function() {
                        window.location.href = 'profile.php';
                    }, 5000);
                }
                else if(data=="YES"){
                    $(".show-sorry-msg.email-msg").html('This mail cant exist');
                }
                else {
                    $(".show-sorry-msg.email-msg").html('server error');
                }
            },
            error: function(){
                console.log("ajax error");
            }
        });
    });

    $('.cl_p_pass').click(function() {
        var opass = $('.opass').val();
        var npass = $('.npass').val();
        var cpass = $('.cpass').val();
        if (opass.length < 6) {
            showerror('.opass', 'Password Should be 6 digit');
            return false;
        } else {
            showsuccess('.opass');
        }
        if (npass.length < 6) {
            showerror('.npass', 'Password Should be 6 digit');
            return false;
        } else {
            showsuccess('.npass');
        }
        if (cpass.length < 6) {
            showerror('.cpass', 'Password Should be 6 digit');
            return false;
        } else {
            showsuccess('.cpass');
        }
        if (npass != cpass) {
            showerror('.cpass', 'Confirm password not matched');
            return false;
        } else {
            showsuccess('.cpass');
        }
        $.ajax({
            type: "post",
            url: "ajax/profile.php", //url of the page where php,mysql code
            data: { opass: opass, npass: npass, action: 'p_pass' },
            dataType: 'html',
            beforeSend: function() {
                $(".show-sorry-msg.pass-msg").removeClass('success');
                $('.cl_p_pass').hide();
                $(".ajaxloader.pass-ajax").show();
            },
            success: function(data) {
                if (data === 'OK') {
                    $(".show-sorry-msg.pass-msg").html('Password has been Changed');
                    $(".show-sorry-msg.pass-msg").addClass('success');
                } else if (data === 'NOT') {
                    $(".show-sorry-msg.pass-msg").html('Incorrect Current Password');
                }
                $(".ajaxloader.pass-ajax").hide();
                setTimeout(function() {
                    window.location.href = 'profile.php';
                }, 5000);
            }
        });
    });

    $('.cl_th_change').click(function() {
        var theme1 = $('.theme1').val();
        var theme2 = $('.theme2').val();
        var theme3 = $('.theme3').val();
        var theme4 = $('.theme4').val();
        if (theme1 === '') {
            showerror('.theme1', 'please select colour');
            return false;
        } else {
            showsuccess('.theme1');
        }
        if (theme2 === '') {
            showerror('.theme2', 'please select colour');
            return false;
        } else {
            showsuccess('.theme2');
        }
        if (theme3 === '') {
            showerror('.theme3', 'please select colour');
            return false;
        } else {
            showsuccess('.theme3');
        }
        if (theme4 === '') {
            showerror('.theme4', 'please select colour');
            return false;
        } else {
            showsuccess('.theme4');
        }
        $.ajax({
            type: "post",
            url: "ajax/profile.php", //url of the page where php,mysql code
            data: { theme1: theme1, theme2: theme2, theme3: theme3, theme4: theme4, action: 'th_change' },
            dataType: 'html',
            beforeSend: function() {
                $(".cl_th_change").hide();
                $(".show-sorry-msg.theme-msg").removeClass('success');
                $('.cl_th_change').hide();
                $(".ajaxloader.theme-ajax").show();
            },
            success: function(data) {
                $(".ajaxloader").hide();
                if (data === 'OK') {
                    $(".show-sorry-msg.theme-msg").html('Color Successfully changed');
                    $(".show-sorry-msg.theme-msg").addClass('success');
                    setTimeout(function() {
                        window.location.href = 'profile.php';
                    }, 5000);
                } else {
                    $(".show-sorry-msg.theme-msg").html('Something wrong');
                }
                $(".ajaxloader.theme-ajax").hide();
            }
        });

    });


    /******************************************ajax-function-end****************************************/
    /******************************************ajax-function-end****************************************/
    /******************************************ajax-function-end****************************************/


});