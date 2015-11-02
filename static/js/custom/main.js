$(function() {

    var content = $('.content');
    var wrapper = $('#wrapper');
    var content_width = content.width();
    var content_height = content.height();
    var header_height = $('.header_strip').height();
    var top_offset = $('.footer').height() + header_height;
    var min_top = header_height + 20;
    var max_top = header_height + content_height;

    $( window ).resize(function() {
        setContentTop();
    });

    function setContentTop()
    {
        var wrapper_height = wrapper.height();
        var new_top = Math.floor((wrapper_height - top_offset - content_height) / 2) + header_height;
        if (new_top >= min_top && new_top < max_top)
        {
            content.css('top', new_top + 'px');
        }
    }

    setContentTop();

    $('.page[data-id="1"]').show();

    var page_ids = new Array(1,2);
    var page_index = 0;
    var page_id = page_ids[page_index];

    $('.button_right a').click(function () {
        pageSlide(1);
    });

    $('.button_left a').click(function () {
        pageSlide(-1);
    });

    function pageSlide(k)
    {
        if (page_ids.length <= 1) return;

        var cur_id = page_ids[page_index];

        if (k > 0)
        {
            page_index++;
            if (page_index > page_ids.length-1) page_index = 0;
        } else {
            page_index--;
            if (page_index < 0) page_index = page_ids.length-1;
        }

        var next_id = page_ids[page_index];

        var cur_page = $('.page[data-id="' + cur_id + '"]');
        var next_page = $('.page[data-id="' + next_id + '"]');

        cur_page.animate({
            marginLeft: (k > 0 ? "-=" : "+=") + content_width,
            opacity: 0
        }, function () {$(this).hide()});

        next_page.css({marginLeft: k * content_width, opacity: 0}).show()
            .animate({
            marginLeft: (k > 0 ? "-=" : "+=") + content_width,
            opacity: 1
        }, function () {
                page_id = next_id;
            });
    }

});