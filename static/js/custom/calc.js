$(function() {

    calc = new CCalc();
    $('.main_menu li.calc').click(function () {
        if ($(this).hasClass('active')) calc.reset(true);
        else calc.reset(false);
    });

    $('input[type="text"].num').keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    }).on('blur', function () {
        if ($(this).val().trim() == '') $(this).val('0');
    });

});

var calc;

function CCalc()
{
    this.data = {};
    this.pages = new Array();
    this.index = 0;
    var self = this;
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
        if (group != '')
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
    var next_page = this.checkPage();
    if (!next_page) return;

    var prev = this.pages[this.index];
    this.index = next_page;
    var next = this.pages[this.index];

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
    $('.calc-container input').each(function () {
        $(this).val($(this).data('default'));
    });

    $('.calc-container .input-checkbox').each(function () {
        if ($(this).data('default') == 'checked' && !$(this).hasClass('checked')) $(this).addClass('checked');
        if ($(this).data('default') == '' && $(this).hasClass('checked')) $(this).removeClass('checked');
    });
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
            if (data['count_servers'] == 0 && data['count_computers'] == 0)
            {
                page.obj.find('input[data-name="count_servers"]').focus();
                return false;
            }
            break;
        case 2 :
            if (data['it-director'] == true) return 4;
            break;

        case 3 :
            if (data['business-no'] == true) return 4;
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

    if (this.data['it-director'] == true) total = count * 3600;
    else if (this.data['sysadmin'] == true)
    {
        if (this.data['business-yes'] == 1) total = count * 1500;
        else if (this.data['inn'] != '' && this.data['contract_number'] != ''/*in-base*/) total = count * 1600;
    }

    total = parseInt(total);
    if (!(total > 0)) { $('#calc_cost').html('Ошибка'); return false; }

    var s = '';
    var stotal = total + '';
    for (var i=stotal.length-1; i >= 0; i--) s = (((stotal.length - i) % 3 && i > 0) ? '' : ' ') + stotal[i] + s;

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