$(function() {

    backs = {};
    $('.back').each(function () {
        backs[$(this).data('id')] = new CBack($(this));
    });

    $win = new CWin();

    $( window ).resize(function() {
//        $win.setContentTop();
        $win.setBackShifts();
    });

    $( window).on('mousewheel', function(event) {
//        console.log(event.deltaX, event.deltaY, event.deltaFactor);
        if (event.deltaY != 0 && event.deltaX == 0) pager.pageSlide(event.deltaY, 0);

        return false;
    });

//    $win.setContentTop();

    $('.button_right a').click(function () {
        pager.pageSlide(1, $(this).closest('.page').data('id'));
        return false;
    });

    $('.button_left a').click(function () {
        pager.pageSlide(-1, $(this).closest('.page').data('id'));
        return false;
    });

    $('.button_down a').click(function () {
        pager.pageSlide(1, 0);
        return false;
    });

    $('.left_menu li a, .main_menu li a').live('click', function () {
        pager.pageJump($(this).closest('li').data('id'));
        return false;
    });

});

var main_page;
var pager;
var backs;

/* CWIN() */

function CWin(pager) {
    this.content = $('.content');
    this.wrapper = $('#wrapper');
    this.body = $('body');
    this.content_width = this.content.width();
    this.content_height = this.content.height();
    this.header_height = $('.header_strip').height();
    this.header_height_px = this.header_height + 'px';
    this.top_offset = $('.footer').height() + this.header_height;
    this.min_top = this.header_height + 20;
    this.max_top = this.header_height + this.content_height;
    this.backs = $('.back');
    this.back1 = $('#back1');
    this.back2 = $('#back2');
    this.body = $('body');
    this.mapover_top_height = 323;
    this.map_height = 311;
}

CWin.prototype.setBackShifts = function () {
    var w = this.body.width();
    var h = this.body.height();
    var k_h = h/w;
    var page = pager.getCurrentPage();
    var back = backs[page.getActiveChildProperty('background')];
    if (!back) return;
    if (back.id == 'map') {
        back.iframe.attr('width', w);
        back.iframe.attr('height', h-this.header_height);
        back.mapover_top.css('height', this.mapover_top_height + 'px');
        back.mapover_bottom.css('height', h - this.header_height - this.mapover_top_height - this.map_height + 'px');
        back.mapover_bottom.css('top', this.header_height + this.mapover_top_height + this.map_height + 'px');

        return;
    }
//    var koff_h = 0.74;

    var koff_h = back.height/back.width;
    var k_w = w/h;
    var koff_w = 1/koff_h;
    var posx = '0px';
    var posy = this.header_height + 'px';

    if (k_h < koff_h)
    {
        var t = this.header_height - Math.floor(((koff_h - k_h)/2)*w);
        posy = t + 'px';
    }
    if (k_w < koff_w)
    {
        posx = '-' + Math.floor(((koff_w - k_w)/2)*h) + 'px';
    }

    back.moveBackground(posx, posy);
}

CWin.prototype.setContentTop = function ()
{
    var wrapper_height = this.wrapper.height();
    var new_top = Math.floor((this.wrapper_height - this.top_offset - this.content_height) / 2) + this.header_height;
    if (new_top >= this.min_top && new_top < this.max_top)
    {
        this.content.css('top', new_top + 'px');
    }
}

CWin.prototype.switchBodyClass = function (class1, class2) {
    this.body.switchClass(class1, class2);
}

var $win;

/* CPAGER() */

function CPager(p) {
    this.pages = p;
    this.page_ids = {};
    this.page_ids[0] = this.pages;
    this.busy = false;

    this.init_pages(this.pages);
};

CPager.prototype.showCurrentBack = function ()
{
    var back = backs[this.getCurrentPage().getActiveChildProperty('background')];
    if (!back) return;
    for (var i in backs) if (backs[i].id != back.id && backs[i].active) backs[i].hide();
    back.show();
}

CPager.prototype.init_pages = function(pages)
{
    if (!pages.childs) return;

    for (var i in pages.childs)
    {
        pages.childs[i].obj = $('.page[data-id="' + pages.childs[i].id + '"]');
        pages.childs[i].initButtons();
        pages.childs[i].initLeftBar();
        pages.childs[i].my_index = i;

        if (pages.childs[i].active)
        {
            pages.child_index = i;
            pages.childs[i].showAll();
        }

        this.init_pages(pages.childs[i]);
        this.page_ids[pages.childs[i].id] = pages.childs[i];
        pages.childs[i].parent = pages;
    }

    pages.checkButtonLeft();
    pages.checkButtonRight();
}

CPager.prototype.getCurrentPage = function () {
    return this.getCurrentPage_(this.pages);
}

CPager.prototype.getCurrentPage_ = function (root) {
    if (!root.childs) return 0;
    for (var i in root.childs)
    {
        if (i == root.child_index)
        {
            if (root.childs[i].childs && root.childs[i].childs.length > 0)
            {
                var t = this.getCurrentPage_(root.childs[i]);
                if (t) return t; else return root.childs[i];
            } else {
                return root.childs[i];
            }
        }
    }

    return 0;
}

CPager.prototype.pageJump = function(page_id)
{
    if (this.page_ids[page_id] === undefined) return;
    var next_page = this.page_ids[page_id];

    var page = next_page.parent;
    if (page.childs == null) return;

    var prev_page = page.childs[page.child_index];

    if (prev_page.id == next_page.id) return;

    var k = prev_page.my_index < next_page.my_index ? 1 : -1;

    this.pageSwitch(k, page, prev_page, next_page);
}

CPager.prototype.pageSlide = function(k, page_id)
{
    if (this.page_ids[page_id] === undefined) return;
    var page = this.page_ids[page_id];

    if (page.childs == null) return;

    var index = page.child_index;

    var prev_page = page.childs[index];
    if (k > 0)
    {
        index++;
        if (index > page.childs.length-1) index = 0;
    } else {
        index--;
        if (index < 0) index = page.childs.length-1;
    }
    var next_page = page.childs[index];

    this.pageSwitch(k, page, prev_page, next_page);
}

CPager.prototype.pageSwitch = function(k, page, prev_child, next_child)
{
    if (this.busy) return;

    this.busy = true;

    var prev_index = page.child_index;
    var cur_page = prev_child.obj;
    var cur_leftbar = prev_child.leftbar;

    page.child_index = next_child.my_index;

    var next_page = next_child.obj;
    var next_leftbar = next_child.leftbar;

    var o = {};
    if (page.type == 'h') o.marginLeft = (k > 0 ? "-=" : "+=") + $win.content_width;
    if (page.type == 'v') o.marginTop = (k > 0 ? "-=" : "+=") + $win.content_height;
    o.opacity = 0;
    cur_page.animate(o, function () {$(this).hide()});

    o = {};
    if (page.type == 'h') o.marginLeft = k * $win.content_width;
    if (page.type == 'v') o.marginTop = k * $win.content_height;
    o.opacity = 0;
    next_page.css(o).show();

    o = {};
    if (page.type == 'h') o.marginLeft = (k > 0 ? "-=" : "+=") + $win.content_width;
    if (page.type == 'v') o.marginTop = (k > 0 ? "-=" : "+=") + $win.content_height;
    o.opacity = 1;
    var self = this;
    next_page.animate(o, function () {
        self.deactivateLI(prev_child.getActiveChildProperty('id'), 'left_menu');
        self.activateLI(next_child.getActiveChildProperty('id'), 'left_menu');
        self.deactivateLI(prev_child.id, 'main_menu');
        self.activateLI(next_child.id, 'main_menu');
        self.busy = false;
    });

    page.checkButtonLeft();
    page.checkButtonRight();

    if (page.type == 'v')
    {
        cur_leftbar.animate({opacity: 0}, function () {$(this).hide()});
        next_leftbar.show().animate({opacity: 1});
    }

    var dots = page.dots;
    if (dots)
    {
        var prev_dot = dots.find('li[data-id="' + prev_child.id + '"]');
        var next_dot = dots.find('li[data-id="' + next_child.id + '"]');
        prev_dot.removeClass('active');
        next_dot.addClass('active');
    } else if (next_child.dots) {
        var prev_dot = next_child.dots.find('li');
        var next_dot = next_child.dots.find('li[data-id="' + next_child.getActiveChildProperty('id') + '"]');
        prev_dot.removeClass('active');
        next_dot.addClass('active');
    }


    var pb = prev_child.getActiveChildProperty('background');
    var nb = next_child.getActiveChildProperty('background');
    if (pb != nb)
    {
        var back1 = backs[pb];
        var back2 = backs[nb];

        if (back1) back1.hideAnimated();
        if (back2) back2.showAnimated();
    }

    var prev_body_class = prev_child.getActiveChildProperty('body_class');
    var next_body_class = next_child.getActiveChildProperty('body_class');
    if (prev_body_class != next_body_class) $win.switchBodyClass(prev_body_class, next_body_class);

    window.history.pushState("object or string", "Title", '/' + next_child.getActiveChildProperty('url') + '/');
    $win.setBackShifts();
}

CPager.prototype.activateLI = function (id, cs)
{
    var obj = $('.' + cs + ' li[data-id="' + id + '"]');
    var a = obj.find('a');
    var t = a.text();
    a.remove();
    obj.text(t).addClass('active');
}

CPager.prototype.deactivateLI = function (id, cs)
{
    var obj = $('.' + cs + ' li[data-id="' + id + '"]');
    var t = obj.text();
    var url = obj.data('url');
    obj.html('<a href="' + url + '">' + t + '</a>').removeClass('active');
}

/* CPAGE() */

var $button_down = 0;

function CPage(args) {
    this.id = 0;
    this.type = 'n'; // not scrollable
    this.active = false;
    this.childs = null;
    this.obj = null;
    this.my_index = 0;
    this.parent = 0;
    this.child_index = 0;
    this.buttons = {};
    this.leftbar = 0;
    this.background = 0;
    this.back_width = 0;
    this.back_height = 0;
    this.body_class = 0;
//    this.back_type = 'image';
    this.url = 0;
    this.toptitle = 0;
    this.dots = 0;

    if (args.id !== undefined) this.id = args.id;
    if (args.type !== undefined) this.type = args.type;
    if (args.active !== undefined) this.active = args.active;
    if (args.background !== undefined) this.background = args.background;
    if (args.body_class !== undefined) this.body_class = args.body_class;
    if (args.back_width !== undefined) this.back_width = args.back_width;
    if (args.back_height !== undefined) this.back_height = args.back_height;
//    if (args.back_type !== undefined) this.back_type = args.back_type;
    if (args.url !== undefined) this.url = args.url;
};

CPage.prototype.getActiveChildProperty = function (p)
{
    var par = this.parent[p];
    var ret = this[p] ? this[p] : par;

    if (!this.childs) return ret;

    if (!this.childs[this.child_index][p]) return ret;

    return this.childs[this.child_index][p];
}

CPage.prototype.initButtons = function ()
{
    if (!this.obj) return;
    if (this.type != 'h') return;
    this.buttons.left = this.obj.find('.button_left');
    this.buttons.right = this.obj.find('.button_right');

    if (this.type == 'h')
    {
        this.toptitle = this.obj.find('.toptitle');
        this.toptitle.addClass(this.getActiveChildProperty('body_class'));
        var l = this.toptitle.find('h1').text().length;
        var fs = Math.floor(80/l) + 5;
    //    if (fs % 2 == 0) fs--;
        if (fs > 17) fs = 17;
        this.toptitle.find('h1').css('font-size', fs + 'px').css('line-height', 103 + Math.floor(fs/2)-2 + 'px');

        if (this.id == 'map') this.obj.height('');

        this.dots = this.obj.find('.dots');
        if (this.dots.length == 0) this.dots = 0;
        else {
            if (this.active)
            {
                for (var i in this.childs) if (this.childs[i].active) this.dots.find('li[data-id="' + this.childs[i].id + '"]').addClass('active');
            }
        }

        var self = this;
        this.obj.find('.quote .left, .quote .right').each(function () {
            var my_page_id = $(this).closest('.h-page').data('id');
            for (var i in self.childs) if (self.childs[i].id == my_page_id) {
                $(this).addClass(self.childs[i].getActiveChildProperty('body_class'));
            }
        });


    }


//    if (!$button_down) $button_down = $('.button_down');
//    this.buttons.down = $button_down;
}

CPage.prototype.initLeftBar = function ()
{
    if (!this.obj) return;
    if (this.type != 'h') return;

    this.leftbar = $('.left_bar_page[data-id="' + this.id + '"]');
}

CPage.prototype.showAll = function ()
{
    this.obj.show();
    if (this.leftbar) this.leftbar.show();
}

CPage.prototype.checkButtonLeft = function()
{
    if (this.buttons.left)
    {
        if (this.child_index == 0 && this.buttons.left.css('display') != 'none') this.buttons.left.hide();
        if (this.child_index > 0 && this.buttons.left.css('display') == 'none') this.buttons.left.show();
    }
}

CPage.prototype.checkButtonRight = function()
{
    if (this.buttons.right)
    {
        if (this.child_index == this.childs.length-1 && this.buttons.right.css('display') != 'none') this.buttons.right.hide();
        if (this.child_index < this.childs.length-1 && this.buttons.right.css('display') == 'none') this.buttons.right.show();
    }
}

CPage.prototype.addChild = function (args)
{
    var p;
    if (args.o !== undefined)
    {
        p = args.o;
    } else {
        p = new CPage(args);
    }

    if (this.childs == null) this.childs = new Array();
    this.childs.push(p);
}

/* BACKS */
function CBack($div)
{
    this.obj = $div;
    this.width = $div.data('width');
    this.height = $div.data('height');
    this.id = $div.data('id');
    this.active = false;
    this.type = this.id > 0 ? 'image' : 'html';

    if (this.id == 'map')
    {
/*
        var myOptions = {
            center: new google.maps.LatLng(55.709875,37.6345693),
            zoom: 18,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true
        };

        var map = new google.maps.Map(document.getElementById("map"), myOptions);
*/

        this.iframe = this.obj.find('iframe');
        this.mapover_top = this.obj.find('.map-over-top');
        this.mapover_bottom = this.obj.find('.map-over-bottom');
    }
}

CBack.prototype.show = function ()
{
    this.active = true;
    this.obj.removeClass('fadeOut').addClass('fadeIn');
    if (this.id == 'map') this.obj.css('z-index', 1);
}

CBack.prototype.hide = function ()
{
    this.active = false;
    this.obj.removeClass('fadeIn').addClass('fadeOut');
    if (this.id == 'map') this.obj.css('z-index', -100);
}

CBack.prototype.showAnimated = function ()
{
    this.active = true;
    var self = this;
    this.obj.switchClass('fadeOut', 'fadeIn', {complete: function () {
        if (self.id == 'map') self.obj.css('z-index', 1);
    }});
}

CBack.prototype.hideAnimated = function ()
{
    this.active = false;
    if (this.id == 'map') this.obj.css('z-index', -100);
    this.obj.switchClass('fadeIn', 'fadeOut');
}

CBack.prototype.moveBackground = function (posx, posy)
{
    this.obj.css('background-position-x', posx);
    this.obj.css('background-position-y', posy);
}