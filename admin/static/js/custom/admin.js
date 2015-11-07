$(function() {
    if ($('.datetimepick').length > 0) {
        $('.datetimepick').datetimepicker({
            showOn: 'both',
            buttonImage: './admin/images/calendar.png',
            buttonImageOnly: true,
            dateFormat: 'dd.mm.yy',
            timeFormat: ' hh:ii'
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
                $('.content_main').append(result);
            }
        });
    });

    $('.open_ckeditor').live('click', function () {
        var $thiscont = $(this).closest('.column-cell').find('.column-content');
        $thiscont.find('.empty').remove();
        $('#ckeditor_temp').val($thiscont.html());
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
            $(this).closest('.content-block').remove();
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
                block.cells = {};
                var j = 0;
                $(this).find('.block-value').each(function () {
                    block.cells[j++] = $(this).html();
                });

                xml[i++] = block;
            });

            $('#blocks_input').html(JSON.stringify(xml));
        }

        return true;
    });

});

function strip_tags(OriginalString)
{
    var StrippedString = OriginalString.replace(/(<([^>]+)>)/ig,"");
    return StrippedString;
}