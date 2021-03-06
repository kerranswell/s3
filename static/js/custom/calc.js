$(function() {

    calc = new CCalc();
    $('.main_menu li.calc').click(function () {
        if ($(this).hasClass('active')) calc.reset(true);
        else calc.reset(false);
    });

    $('input[type="text"].num, input[data-name="inn"]').keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            ((e.keyCode == 65 || e.keyCode == 86) && ( e.ctrlKey === true || e.metaKey === true ) ) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    })
    $('input[type="text"].num').on('blur', function () {
        if ($(this).val().trim() == '') $(this).val('0');
    });

    $('input[data-name="contract_number"]').on('keyup', function () {
        var self = $(this);
        var s = self.val();
        var r = new RegExp("N[0-9]{2}\-[0-9]{2}\/[0-9]{4}","ig");
        s = s.match(r);

    });

    $('#service_submit').click(function () {
        showFullscreenMessage($('#service_submit_form'));
    });

    $('#service-feedback-send').live('click', function () {
        var data = {};

        var frm = $(this).closest('.calc-page');
        data.company = frm.find('input[data-name="company"]').val().trim();
        data.name = frm.find('input[data-name="name"]').val().trim();
        data.email = frm.find('input[data-name="email"]').val().trim();
        data.phone = frm.find('input[data-name="phone"]').val().trim();
        data.comments = frm.find('textarea[data-name="comments"]').val().trim();
        data.code = frm.find('input[data-name="captcha"]').val().trim();

        var i = frm.find('input[data-name="company"]');
        if (data.company == '')
        {
            i.focus();
            if (!i.hasClass('error')) i.addClass('error');
            return;
        } else {
            if (i.hasClass('error')) i.removeClass('error');
        }

        i = frm.find('input[data-name="name"]');
        if (data.name == '')
        {
            i.focus();
            if (!i.hasClass('error')) i.addClass('error');
            return;
        } else {
            if (i.hasClass('error')) i.removeClass('error');
        }

        i = frm.find('input[data-name="email"]');
        if (!validateEmail(data.email))
        {
            i.focus();
            if (data.email != '') showFormError(frm,  'email');
            if (!i.hasClass('error')) i.addClass('error');
            return;
        } else {
            if (i.hasClass('error')) i.removeClass('error');
            hideFormError(frm,  'email');
        }

        i = frm.find('input[data-name="phone"]');
        if (data.phone == '')
        {
            i.focus();
            if (!i.hasClass('error')) i.addClass('error');
            return;
        } else {
            if (i.hasClass('error')) i.removeClass('error');
        }

        i = frm.find('textarea[data-name="comments"]');
        if (data.comments == '')
        {
            i.focus();
            if (!i.hasClass('error')) i.addClass('error');
            return;
        } else {
            if (i.hasClass('error')) i.removeClass('error');
        }

        var i = frm.find('input[data-name="captcha"]');
        if (data.code == '')
        {
            i.focus();
            if (!i.hasClass('error')) i.addClass('error');
            return;
        } else {
            if (i.hasClass('error')) i.removeClass('error');
        }

        cursorWait(1);

        data.act = 'service-submit';
        data.calc = calc.data;

        var res = $('.calc-page[data-finish="2"] .row');

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: '/ajx/feedback.php',
            data: data,
            success: function(result){
                if (result.success == 1)
                {
                    cursorWait(0);
                    hideFormError(frm,  'captcha');
                    i = frm.find('input[data-name="captcha"]');
                    if (i.hasClass('error')) i.removeClass('error');

                    res.html(result.message);
                    calc.nextPage();
//                    showFormMessageText(frm, 'success', result.message);
//                    frm.find('.text').hyphenate(false);
                } else {
                    if (result.captcha_error == 1)
                    {
                        i = frm.find('input[data-name="captcha"]');
                        i.val('').focus();
                        if (!i.hasClass('error')) i.addClass('error');
                        showFormError(frm,  'captcha');
                        updateCaptchaImg(frm.find('.captcha img'));
                    } else {
//                    showFormMessage(frm, 'error');
                    }
                }
            },
            complete : function () {
                cursorWait(0);
            },
            error : function () {
//                frm.find('.text').html($('#feedback_error').html());
            }
        });

        return false;
    });

    $('#service_refuse').click(function () {
        showFullscreenMessage($('#service_refuse_form'));
//        initCaptcha(0);

        return false;
    });

    $('#service-feedback-refuse').live('click', function () {
        var data = {};

        var frm = $(this).closest('.fullscreen');
        data.company = frm.find('input[data-name="company"]').val().trim();
        data.name = frm.find('input[data-name="name"]').val().trim();
        data.email = frm.find('input[data-name="email"]').val().trim();
        data.phone = frm.find('input[data-name="phone"]').val().trim();
        data.comments = frm.find('textarea[data-name="comments"]').val().trim();
        data.code = frm.find('input[data-name="captcha"]').val().trim();

        i = frm.find('textarea[data-name="comments"]')
        if (data.comments == '')
        {
            i.focus();
            if (!i.hasClass('error')) i.addClass('error');
            return;
        } else {
            if (i.hasClass('error')) i.removeClass('error');
        }

        var i = frm.find('input[data-name="captcha"]');
        if (data.code == '')
        {
            i.focus();
            if (!i.hasClass('error')) i.addClass('error');
            return;
        } else {
            if (i.hasClass('error')) i.removeClass('error');
        }

        cursorWait(1);

        data.act = 'service-refuse';
        data.calc = calc.data;

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: '/ajx/feedback.php',
            data: data,
            success: function(result){
                if (result.success == 1)
                {
                    hideFormError(frm,  'captcha');
                    i = frm.find('input[data-name="captcha"]');
                    if (i.hasClass('error')) i.removeClass('error');

                    showFormMessage(frm, 'success');
                    fullscreen_after = 'calc-reset';
//                    frm.find('.text').hyphenate(false);
                } else {
                    if (result.captcha_error == 1)
                    {
                        i = frm.find('input[data-name="captcha"]');
                        i.val('').focus();
                        if (!i.hasClass('error')) i.addClass('error');
                        showFormError(frm,  'captcha');
                        updateCaptchaImg(frm.find('.captcha img'));
                    } else {
                        showFormMessage(frm, 'error');
                    }
                }
            },
            complete : function () {
                cursorWait(0);
            },
            error : function () {
                showFormMessage(frm, 'error');
            }
        });

        return false;
    });

});

var calc;

function CCalc()
{
    this.data = {};
    this.pages = new Array();
    this.index = 0;
    var self = this;
    initCaptcha($('.calc-container'));
    $('.calc-container .calc-page').each(function () {
        var p = {id:$(this).data('id'),obj:$(this)};
        self.pages.push(p);
    });

    $('.calc-next-step').click(function () {
        self.nextPage();
    });

    $('.button1.calc-data').click(function (){
        var group = $(this).data('group');
        if (group != '') $('.button1.calc-data[data-group="' + group + '"]').data('value', 0);

        $(this).data('value', 1);
    });

    $('.calc-data.input-checkbox').click(function(){
        var group = $(this).data('group');
        if (group != '' && group != undefined)
        {
            if (!$(this).hasClass('checked'))
            {
                $('.calc-data.input-checkbox[data-group="' + group + '"]').removeClass('checked');
                $(this).addClass('checked');
            }
        } else {
            if ($(this).hasClass('checked'))
            {
                $(this).removeClass('checked');
            } else {
                $(this).addClass('checked');
            }
        }


    });
}

CCalc.prototype.nextPage = function ()
{
    if ($('body').hasClass('wait')) return;
    var next_page = this.checkPage();
    if (next_page <= 0) return;

    var prev = this.pages[this.index];
    this.index = next_page;
    var next = this.pages[next_page];

    if (next.obj.data('finish') == 1)
    {
        this.calculate();
    }

    if (next.obj.find('.captcha').length > 0) initCaptcha(next.obj);
    this.switchPage(prev, next, 1);
}

CCalc.prototype.nextPage2 = function (next_page)
{
    if (!next_page) return;

    var prev = this.pages[this.index];
    this.index = next_page;
    var next = this.pages[next_page];

    if (next.obj.data('finish') == 1)
    {
        this.calculate();
    }

    this.switchPage(prev, next, 1);
}

CCalc.prototype.switchPage = function (prev, next, anim)
{
    if (prev.id == next.id) return;

    k = prev.id > next.id ? -1 : 1;

    if (anim) prev.obj.animate({marginLeft: (k > 0 ? '-=' : '+=') + $win.content_width,opacity:0}, function () {$(this).hide()});
    else prev.obj.css({marginLeft: (k > 0 ? '-=' : '+=') + $win.content_width,opacity:0}).hide();

    if (anim)
    {
        next.obj.css({marginLeft:k*$win.content_width,opacity:0}).show();
        next.obj.animate({marginLeft: (k > 0 ? '-=' : '+=') + $win.content_width,opacity:1}, function () {
            next.obj.find('input.focused').focus();
        });
    } else {
        next.obj.css({marginLeft:0,opacity:1}).show();
    }
}

CCalc.prototype.reset = function (anim)
{
    this.data = {};
    this.resetControls();

    var prev = this.pages[this.index];
    this.index = 0;
    var next = this.pages[this.index];

    this.switchPage(prev, next, anim);
}

CCalc.prototype.resetControls = function ()
{
    $('.calc-container input, .calc-container textarea').each(function () {
        $(this).val($(this).data('default'));
        if ($(this).hasClass('error')) $(this).removeClass('error');
    });

    $('.calc-container .input-checkbox').each(function () {
        if ($(this).data('default') == 'checked' && !$(this).hasClass('checked')) $(this).addClass('checked');
        if ($(this).data('default') == '' && $(this).hasClass('checked')) $(this).removeClass('checked');
    });

    hideFormErrors($('.calc-container'));
}

CCalc.prototype.checkPage = function ()
{
    var page = this.pages[this.index];
    var data = {};
    var self = this;

    $('.calc-page[data-id="' + page.id + '"] .calc-data').each(function () {
        data[$(this).data('name')] = self.getInputValue($(this));
    });

    switch (page.id)
    {
        case 1 :
            var is = page.obj.find('input[data-name="count_servers"]');
            var i = page.obj.find('input[data-name="count_computers"]');

            if (is.val() == '')
            {
                is.val('0');
                i.focus();
                return false;
            }

            if (data['count_computers'] <= 0)
            {
                if (!i.hasClass('error')) i.addClass('error');
                i.focus();
                return false;
            } else {
                if (i.hasClass('error')) i.removeClass('error');
            }
            break;
        case 2 :
//            if (data['it-director'] == true) return 4;
            break;

        case 3 :
//            if (data['business-no'] == true) return 4;
            break;

        case 4 :
            cursorWait(1);
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: '/ajx/calc.php',
                data: { 'act' : 'checkContract', inn: data['inn'], num: data['contract_number']},
                success: function(result){
                    if (result.success == 1)
                    {
                        self.data.inbase = 1;
                    }
                },
                complete : function () {
                    cursorWait(0);
                    self.nextPage2(4);
                }
            });

            return -1;
            break;
    }

    return this.index + 1;
}

CCalc.prototype.calculate = function ()
{
    var self = this;
    $('.calc-container .calc-data').each(function () {
        self.data[$(this).data('name')] = self.getInputValue($(this));
    });

    this.data.count_servers = parseInt(this.data.count_servers);
    this.data.count_computers = parseInt(this.data.count_computers);

    var total = 0;
    var count = this.data.count_servers + this.data.count_computers;
    total = count * 1800;

/*
    if (this.data['it-director'] == true) total = count * 3600;
    else if (this.data['sysadmin'] == true)
    {
        if (this.data['business-yes'] == 1) total = count * 1500;
        else if (this.data['inn'] != '' && this.data['contract_number'] != ''*/
/*in-base*//*
) total = count * 1600;
    }
*/

    var j16 = 3600;
    var j15 = 1800;
    var j12 = 0.7;
    var j13 = 0.8;

    if (this.data['it-director'] == true)
    {
        if (this.data['business-yes'] == 1)
        {
            total = j16 * count * j12;
        } else {
            if (this.data.inbase) total = j16 * count * j13;
            else total = j16 * count;
        }
    } else if (this.data['sysadmin'] == true) {
        if (this.data['business-yes'] == 1)
        {
            total = j15 * count * j12;
        } else {
            if (this.data.inbase) total = j15 * count * j13;
            else total = j15 * count;
        }
    }

    total = parseInt(Math.round(total));
    if (!(total > 0)) { $('#calc_cost').html('Ошибка'); return false; }

    var s = '';
    var stotal = total + '';
    for (var i=stotal.length-1; i >= 0; i--) s = (((stotal.length - i) % 3 && i > 0) ? '' : ' ') + stotal[i] + s;

    this.data.total = total;
    this.data.total_s = s;

    $('#calc_cost').html(s + ' &#8381;');

    return true;
}

CCalc.prototype.getInputValue = function (o)
{
    switch (o.prop('tagName'))
    {
        case 'INPUT' : return o.val();
            break;

        case 'DIV' :
            if (o.hasClass('input-checkbox'))
            {
                return o.hasClass('checked');
            } else if (o.hasClass('button1')) {
                return o.data('value');
            }
            break;
    }

    return -1;
}