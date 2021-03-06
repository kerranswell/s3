<?xml version="1.0" encoding="UTF-8" ?> 
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:dyn="http://exslt.org/dynamic"
                extension-element-prefixes="dyn" version="1.0">


    <xsl:template name="button1">
        <xsl:param name="title"/>
        <xsl:param name="link"/>
        <a href="{$link}"><xsl:value-of select="$title"/></a>
    </xsl:template>

    <!-- EDIT -->

    <xsl:template match="field" mode="table_row">
        <tr valign="top"><td align="right">
            <xsl:value-of select="./@title"/></td><td><xsl:apply-templates select="." mode="input"/>
            <xsl:if test="./@error != ''"><br /><span class="error"><xsl:value-of select="./@error"/></span></xsl:if>
        </td></tr>
    </xsl:template>


    <xsl:template match="field[@showtype='string']" mode="input">
        <input type="text" value="{.}" name="record[{./@name}]" />
    </xsl:template>

    <xsl:template match="field[@showtype='password']" mode="input">
        <input type="password" value="" name="record[{./@name}]" />
    </xsl:template>

    <xsl:template match="field[@showtype='table2items']" mode="input">
        <div class="table2items_cont">
            <input type="text" value="" autocomplete="off" class="{./@showtype}" name="new_table2items[{./@name}]" data-checking="0" data-table="{./@table}" />
            <div class="table2items_items">
                <xsl:for-each select="item">
                    <div class="{../@showtype}_item"><xsl:value-of select="title"/><input type="hidden" name="{../@showtype}[{../@table}][]" value="{id}" /><a href="#" class="{../@showtype}_del">[X]</a></div>
                </xsl:for-each>
            </div>
            <div class="{./@showtype}_list" data-table="{./@table}"></div>
        </div>
    </xsl:template>

    <xsl:template match="field[@showtype='date']" mode="input">
        <input type="text" value="{.}" class="datetimepick" name="record[{./@name}]" />
    </xsl:template>

    <xsl:template match="field[@showtype='image']" mode="input">
        <input type="file" value="" name="record[{./@name}]" />
        <xsl:if test=". != 0"><br /><input type="checkbox" name="{./@name}_delete" value="1" /> Удалить<br /><img src="{.}" /></xsl:if>
    </xsl:template>

    <xsl:template match="field[@showtype='editor']" mode="input">
        <textarea class="ckeditor" name="record[{./@name}]"><xsl:value-of select="."/></textarea>
    </xsl:template>


    <xsl:template match="field[@showtype='checkbox']" mode="input">
        <input type="checkbox" name="record[{./@name}]" value="1"><xsl:if test=". = 1"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if></input>
    </xsl:template>

    <xsl:template match="field[@showtype='select']" mode="input">
        <xsl:variable name="value" select="."/>
        <select name="record[{./@name}]">
            <xsl:for-each select="dyn:evaluate(./@xml_options)/item">
                <option value="{value}">
                    <xsl:if test="$value = value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
                    <xsl:value-of select="title"/></option>
            </xsl:for-each>
        </select>
    </xsl:template>

    <xsl:template match="field[@showtype='xml']" mode="input">
        <xsl:choose>
            <xsl:when test="/root/common/_get/id &gt; 0">
                <div id="ckeditor_temp" style="display:none"></div>
                <div class="content_toolbar">
                    <xsl:apply-templates select="/node()/block[@name='content_blocks']"/>
                </div>
                <div class="content_main">
                    <xsl:apply-templates select="item" mode="blocks_admin" />
                </div>
                <textarea id="blocks_input" name="record[{./@name}]"><xsl:value-of select="."/></textarea>
            </xsl:when>
            <xsl:otherwise>
                Заполнение контента будет доступно после создания страницы
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="field[@showtype='none']" mode="table_row" />


    <xsl:template match="item" mode="edit_item">
        <xsl:if test="/root/common/msg != ''">
            <p class="admin-message"><xsl:value-of select="/root/common/msg"/></p>
        </xsl:if>
        <form method="post" class="edit_item_form" action="" enctype="multipart/form-data">
            <table border="0" width="100%">
                <tr>
                <th width="100px"></th>
                <th></th>
                </tr>
                <xsl:apply-templates select="field" mode="table_row"/>
                <tr><td></td><td><input type="submit" value="Сохранить" name="save" /></td></tr>
            </table>
            <input type="hidden" name="do_save" value="1" />
            <input type="hidden" name="op" value="{/root/common/op}" />
            <input type="hidden" name="opcode" value="item_edit" />
            <input type="hidden" name="no_redirect" value="1" />
        </form>
    </xsl:template>

    <!-- LIST -->

    <xsl:template match="item" mode="list_item">
        <tr item_id="{field[@name='id']}">
            <xsl:attribute name="class">
                <xsl:if test="field[@name='status'] = 0">unactive</xsl:if>
            </xsl:attribute>

            <xsl:if test="count(../../fields/field[. = 'pos']) &gt; 0"><td><div class="drag"></div></td></xsl:if>
            <xsl:apply-templates select="field" mode="list_field" />
            <td><a href="/admin/?op={/root/common/op}&amp;act=edit&amp;id={field[@name='id']}"><img src="/admin/static/img/edit.png" /></a></td>
            <td><a class="delete" href="/admin/?op={/root/common/op}&amp;act=delete&amp;id={field[@name='id']}"><img src="/admin/static/img/del.png" /></a></td>
        </tr>
    </xsl:template>

    <xsl:template match="field[@showtype='none']" mode="list_field" />

    <xsl:template match="field[@showtype='label']" mode="list_field">
        <td><xsl:value-of select="." /></td>
    </xsl:template>

    <xsl:template match="field[@showtype='link_children']" mode="list_field">
        <td><a href="/admin/?op={/root/common/op}&amp;pid={../field[@name='id']}"><xsl:value-of select="." /></a></td>
    </xsl:template>

    <xsl:template match="field[@showtype!='none']" mode="list_header">
        <th><xsl:value-of select="./@title" /></th>
    </xsl:template>

    <xsl:template match="field[@showtype='none']" mode="list_header" />

    <xsl:template match="list" mode="list">
        <table border="0" class="tree_node_list" index_start="0" table="{/root/common/op}" sortable="1">
            <tr class="table_header">
                <xsl:if test="count(../fields/field[. = 'pos']) &gt; 0"><th></th></xsl:if>
                <xsl:apply-templates select="../fields/field" mode="list_header" />
                <th></th>
                <th></th>
            </tr>
            <xsl:apply-templates select="item" mode="list_item"/>
        </table>
    </xsl:template>

    <!-- PATH -->

    <xsl:template match="block[@name='path']">
        <ul class="path">
            <xsl:for-each select="item">
                <li>
                    <xsl:choose>
                        <xsl:when test="position() = count(../item) and /root/mod_params/act = 'list'"><xsl:value-of select="title"/></xsl:when>
                        <xsl:otherwise><a href="/admin/?op={/root/common/op}&amp;pid={id}"><xsl:value-of select="title"/></a></xsl:otherwise>
                    </xsl:choose>
                </li>
                <xsl:if test="position() &lt; count(../item)">
                    <li>&#187;</li>
                </xsl:if>
            </xsl:for-each>
        </ul>
    </xsl:template>


</xsl:stylesheet>
