$(function() {
    if ($('.datetimepick').length > 0) {
        $('.datetimepick').datetimepicker({
            showOn: 'both',
            buttonImage: '/admin/static/img/calendar.png',
            buttonImageOnly: true,
            dateFormat: 'dd.mm.yy',
            timeFormat: ' hh:mm'
        });

        $('#ui-datepicker-div').hide();
    }

    $('a.delete').click(function() {
        if (!confirm('Удалить?')) return false;

        return true;
    });

    $('.tree_node_list[sortable="1"]').sortable({
        items : 'tr:not(.table_header)',
        handle: 'div.drag',
        stop: function(event,ui){ sortNodeList(); }
    });

    $('.content_main').sortable({
        items : 'div.content-block',
        handle: 'div.block-drag'
//        stop: function(event,ui){ sortNodeList(); }
    });

    function sortNodeList()
    {
        var ids = [];
        var index_start = $('.tree_node_list').attr('index_start');
        $(".tree_node_list tr:not(.table_header)").each(function(ind){ ids[ids.length] = $(this).attr('item_id'); /*$('td:eq(1)', this).html(ind+1 + parseInt(index_start));*/ });

        $.ajax({
            type: 'POST',
            dataType: 'text',
            url: '/admin/',
            data: ({ ids: ids.join(), 'index_start': index_start, 'table': $('.tree_node_list').attr('table'), 'opcode': 'common', 'act' : 'tree_node_list_sort'})
        });
    }

    $('.selectAll').click(function () {
        var frm = $(this).closest('form');
        $('input[type="checkbox"].tree', frm).attr('checked', 'checked');
    });

    $('.deselectAll').click(function () {
        var frm = $(this).closest('form');
        $('input[type="checkbox"].tree', frm).each(function () { this.checked = false;} );
    });

    $('.edit_item_form').on('submit', function (){

    });

    $('#toolbar_add').click(function () {
        var block_name = $('#toolbar_block').val();

        $.ajax({
            type: 'POST',
            dataType: 'text',
            url: '/admin/',
            data: { 'opcode': 'content', 'act' : 'getBlockHtml', block_name : block_name},
            success: function(result){
                var $res = $(result);
                $('.content_main').append($res);
                if (block_name == 'picture')
                {
                    var pic_id = $('.content_main').find('.pic_uploader[data-id!="0"]').length + 1;
                    $res.find('.pic_uploader').attr('data-id', pic_id).attr('name', 'block_picture[u'+pic_id+']');
                    $res.find('.block-value').val('u' + pic_id);
                }
            }
        });
    });

    $('.open_ckeditor').live('click', function () {
        var $thiscont = $(this).closest('.column-cell').find('.column-content');
        $thiscont.find('.empty').remove();
        $('#ckeditor_temp').val($(this).closest('.column-cell').find('.block-value').html());
        var w = window.open('/admin/ckeditor.php', 'ckeditor');


        var pollTimer = window.setInterval(function() {
            if (w.closed !== false) { // !== is required for compatibility with Opera
                window.clearInterval(pollTimer);
                $thiscont.html(strip_tags($('#ckeditor_temp').val()).substring(0,50));
                $thiscont.closest('.column-cell').find('.block-value').html($('#ckeditor_temp').val());
                if ($('#ckeditor_temp').val().trim() == '') $thiscont.html('<span class="empty">Пусто</span>');
            }
        }, 200);

        return false;
    });

    $('.block-del').live('click', function () {
        if (confirm('Удалить блок?'))
        {
            var block = $(this).closest('.content-block');
/*
            if (block.data('type') == 'picture')
            {
                var idx = block.find('.block-value').val();
                if (block.find('.pic_uploader').length == 0)
                {
                    $.ajax({
                        type: 'POST',
                        dataType: 'text',
                        url: '/admin/',
                        data: { 'opcode': 'content', 'act' : 'delete_image', id: idx},
                        success: function(result){
                        }
                    });
                }
            }
*/

            block.remove();
        }
    });

    $('.edit_item_form').on('submit', function(){
        if ($('.content_main').length > 0)
        {
            var xml = {};
            var i = 0;
            $('.content_main').find('.content-block').each(function () {
                var block = {};
                block.name = $(this).data('name');
                block.type = $(this).data('type');
                block.cells = {};
                var j = 0;
                $(this).find('.block-value').each(function () {
                    var v;
                    switch (block.type)
                    {
                        case 'column' : v = $(this).html(); break;
                        case 'quote' : case 'contacts' : v = $(this).val(); break;
                        default: v = $(this).val();
                    }
                    block.cells[j++] = v;
                });

                xml[i++] = block;
            });

            $('#blocks_input').html(JSON.stringify(xml));
        }

        return true;
    });

    // preparing cell previews
    if ($('.content_main').length > 0)
    {
        $('.content_main').find('.column-cell').each(function () {
            var s = $(this).find('.block-value').html();
            s = strip_tags(s).substring(0,50);
            if (s.trim() != '')
            {
                $(this).find('span.empty').remove();
                $(this).find('.column-content').html(s);
            }
        });
    }

    var options = {
        dataType:  'json'
    };

    $('.pic_uploader').live('change', function() {
        var obj = this;
        options.success = function (data, statusText, xhr, $form)  {
            $(obj).parent().find('.block-value').val(data.val);
            $(obj).parent().find('img').attr('src', data.picture_url).removeClass('hidden');
        }

//        var opcode = $('.edit_item_form').find('input[name="opcode"]').val();
//        ajaxSubmit(options);
    });

    $('.table2items').bind('keyup', function () {
        var inp = $(this).attr('value');
        if(inp.length < 3) { $('.table2items_list').html( '').hide(); return; }
        var self = $(this);
        if(self.data('checking') == 1) return;
        if( inp ){
            self.data('checking', 1);
            jQuery.ajax({
                dataType: 'text',
                url: '/admin/',
                data: { 'opcode': 'table2items', act:'getList', s : inp, table: self.data('table')},
                type: 'POST',
                success: function(response){
                    if( response ) $('.table2items_list').html( response).show();
                    else $('.table2items_list').html( '').hide();
                    self.data('checking', 0);
                },
                error:  function(xhr, str){
                    $('.table2items_list').html( '').hide();
                    self.data('checking', 0);
                }
            });
        }
    });

    $('.table2items_list .item').live('click', function () {
        var c = $(this).closest('.table2items_cont');
        var table = c.find('input.table2items').data('table');
        var title = $(this).html();
        var id = $(this).data('id');
        var s = '<div class="table2items_item">' + title + '<input type="hidden" name="table2items[' + table + '][]" value="' + id + '" /><a href="#" class="table2items_del">[X]</a></div>';
        c.find('.table2items_items').append(s);
        $(this).parent().html('').hide();
        c.find('input.table2items').val('');


        return false;
    });

    $('.table2items_del').live('click', function () {
        $(this).parent().remove();
        return false;
    })

});

function strip_tags(OriginalString)
{
    var StrippedString = OriginalString.replace(/(<([^>]+)>)/ig,"");
    return StrippedString;
}

