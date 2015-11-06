<?xml version="1.0" encoding="UTF-8" ?> 
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

    <xsl:template match="block[@name='content_blocks']">
        <input type="button" id="toolbar_add" value="Добавить блок" /><br /><br />
        <select id="toolbar_block">
            <xsl:for-each select="item"><option value="{name}"><xsl:value-of select="title"/></option></xsl:for-each>
        </select>
    </xsl:template>

    <xsl:template match="block[@name='content_block']" mode="ajax">
        <xsl:variable name="block_name" select="block_name"/>
        <div class="content-block">
            <table><tbody><tr>
                <xsl:choose>
                    <xsl:when test="/node()/block[@name='content_blocks']/item[name=$block_name]/params/count &gt; 0">
                        <xsl:apply-templates select="/node()/block[@name='content_blocks']/item[name=$block_name]" mode="content_block_cycle">
                            <xsl:with-param name="i" select="1"/>
                        </xsl:apply-templates>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:apply-templates select="/node()/block[@name='content_blocks']/item[name=$block_name]" mode="content_block_one" />
                    </xsl:otherwise>
                </xsl:choose>
            </tr></tbody></table>
            <div class="block-tools"><a class="block-drag" href="#"><img src="/admin/static/img/drag2.png" /></a></div>
        </div>
        <div class="clr"></div>
    </xsl:template>

    <xsl:template match="item" mode="content_block_cycle">
        <xsl:param name="i"/>
        <xsl:apply-templates select="." mode="content_block" />

        <xsl:if test="$i &lt; params/count">
            <xsl:apply-templates select="." mode="content_block_cycle">
                <xsl:with-param name="i" select="$i+1"/>
            </xsl:apply-templates>
        </xsl:if>
    </xsl:template>

    <xsl:template match="item[type='column']" mode="content_block">
        <td>
            <xsl:attribute name="class">column-cell block-<xsl:value-of select="name"/></xsl:attribute>
            <div class="column-content"><span class="empty">Пусто</span></div>
            <hr/>
            <a class="open_ckeditor" href="#"><img src="/admin/static/img/eye2.png" /></a>
        </td>
    </xsl:template>

    <xsl:template match="item[type='column' and name='column1_2']" mode="content_block_one">
        <td>
            <xsl:attribute name="class">column-cell block-column1_2 first</xsl:attribute>
            <div class="column-content"><span class="empty">Пусто</span></div>
            <hr/>
            <a class="open_ckeditor" href="#"><img src="/admin/static/img/eye2.png" /></a>
        </td>
        <td>
            <xsl:attribute name="class">column-cell block-column1_2 second</xsl:attribute>
            <div class="column-content"><span class="empty">Пусто</span></div>
            <hr/>
            <a class="open_ckeditor" href="#"><img src="/admin/static/img/eye2.png" /></a>
        </td>
        <td>
            <xsl:attribute name="class">column-cell block-column1_2 second</xsl:attribute>
            <div class="column-content"><span class="empty">Пусто</span></div>
            <hr/>
            <a class="open_ckeditor" href="#"><img src="/admin/static/img/eye2.png" /></a>
        </td>
    </xsl:template>

    <xsl:template match="item[type='column' and name='column2_1']" mode="content_block_one">
        <td>
            <xsl:attribute name="class">column-cell block-column2_1 first</xsl:attribute>
            <div class="column-content"><span class="empty">Пусто</span></div>
            <hr/>
            <a class="open_ckeditor" href="#"><img src="/admin/static/img/eye2.png" /></a>
        </td>
        <td>
            <xsl:attribute name="class">column-cell block-column2_1 first</xsl:attribute>
            <div class="column-content"><span class="empty">Пусто</span></div>
            <hr/>
            <a class="open_ckeditor" href="#"><img src="/admin/static/img/eye2.png" /></a>
        </td>
        <td>
            <xsl:attribute name="class">column-cell block-column2_1 second</xsl:attribute>
            <div class="column-content"><span class="empty">Пусто</span></div>
            <hr/>
            <a class="open_ckeditor" href="#"><img src="/admin/static/img/eye2.png" /></a>
        </td>
    </xsl:template>

</xsl:stylesheet>
