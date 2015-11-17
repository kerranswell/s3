$(function() {

    calc = new CCalc();
    $('.main_menu li.calc').click(function () {
        if ($(this).hasClass('active')) calc.reset(true);
        else calc.reset(false);
    });

    $('.button1.calc-data').click(function (){
        $(this).data('value', 1);
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
}

CCalc.prototype.nextPage = function ()
{
    if (!this.checkPage()) return;

    var prev = this.pages[this.index];
    this.index++;
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
    var self = this;
    page.obj.find('.calc-data').each(function () {
        self.data[$(this).data('name')] = self.getInputValue($(this));
    });

    return true;
}

CCalc.prototype.calculate = function ()
{
    var self = this;
    $('.calc-container .calc-data').each(function () {
        self.data[$(this).data('name')] = self.getInputValue($(this));
    });

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