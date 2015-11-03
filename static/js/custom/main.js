$(function() {

    $win = new CWin();

    $( window ).resize(function() {
        $win.setContentTop();
    });

    $win.setContentTop();

    var main_page = new CPage({id:0,type:'v'});
    var p;
    p = new CPage({id:3,type:'h',active:true}); p.addChild({id:1,active:true}); p.addChild({id:2});
    main_page.addChild({o:p});
    p = new CPage({id:4,type:'h'}); p.addChild({id:6,active:true}); p.addChild({id:5});
    main_page.addChild({o:p});

    var pager = new CPager(main_page);


/*

    var v_page_index = 0;
    var v_page_id = page_ids[page_index];
    $('.v-page[data-id="' + v_page_id + '"]').show();

    var page_ids = new Array(1,2);
    var page_index = 0;
    var page_id = page_ids[page_index];
    $('.page[data-id="' + page_id + '"]').show();

*/
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

});

/* CWIN() */

function CWin() {
    this.content = $('.content');
    this.wrapper = $('#wrapper');
    this.content_width = this.content.width();
    this.content_height = this.content.height();
    this.header_height = $('.header_strip').height();
    this.top_offset = $('.footer').height() + this.header_height;
    this.min_top = this.header_height + 20;
    this.max_top = this.header_height + this.content_height;
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

var $win;

/* CPAGER() */

function CPager(p) {
    this.pages = p;
    this.page_ids = {};
    this.page_ids[0] = this.pages;
    this.busy = false;

    this.init_pages(this.pages);
};

CPager.prototype.init_pages = function(pages)
{
    if (!pages.childs) return;

    for (var i in pages.childs)
    {
        pages.childs[i].obj = $('.page[data-id="' + pages.childs[i].id + '"]');
        pages.childs[i].my_index = i;

        if (pages.childs[i].active)
        {
            pages.childs[i].child_index = i;
            pages.childs[i].obj.show();
        }

        this.init_pages(pages.childs[i]);
        this.page_ids[pages.childs[i].id] = pages.childs[i];
    }
}

CPager.prototype.pageSlide = function(k, page_id)
{
    if (this.busy) return;

    this.busy = true;

    if (this.page_ids[page_id] === undefined) return;
    var page = this.page_ids[page_id];

    var cur_page = page.childs[page.child_index].obj;
    if (k > 0)
    {
        page.child_index++;
        if (page.child_index > page.childs.length-1) page.child_index = 0;
    } else {
        page.child_index--;
        if (page.child_index < 0) page.child_index = page.childs.length-1;
    }
    var next_page = page.childs[page.child_index].obj;

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
    next_page.animate(o, function () {self.busy = false;});
}


/* CPAGE() */

function CPage(args) {
    this.id = 0;
    this.type = 'n'; // not scrollable
    this.active = false;
    this.childs = null;
    this.obj = null;
    this.my_index = 0;
    this.child_index = 0;

    if (args.id !== undefined) this.id = args.id;
    if (args.type !== undefined) this.type = args.type;
    if (args.active !== undefined) this.active = args.active;
};

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
