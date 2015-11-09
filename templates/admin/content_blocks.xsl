<?xml version="1.0" encoding="UTF-8" ?> 
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

    <xsl:template match="block[@name='content_blocks']">
        <input type="button" id="toolbar_add" value="Добавить блок" />
        <select id="toolbar_block">
            <xsl:for-each select="item"><option value="{name}"><xsl:value-of select="title"/></option></xsl:for-each>
        </select>
    </xsl:template>

    <xsl:template match="item" mode="blocks_admin">
        <xsl:variable name="block_name" select="name"/>
        <div class="content-block" data-name="{name}">
            <table><tbody><tr>
                <xsl:choose>
                    <xsl:when test="/node()/block[@name='content_blocks']/item[name=$block_name]/params/count &gt; 0">
                        <xsl:apply-templates select="." mode="content_block_cycle">
                            <xsl:with-param name="i" select="1"/>
                        </xsl:apply-templates>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:apply-templates select="." mode="content_block_one" />
                    </xsl:otherwise>
                </xsl:choose>
            </tr></tbody></table>
            <div class="block-tools"><div class="block-drag float-left"></div><div class="block-del float-right"></div></div>
        </div>
    </xsl:template>

    <xsl:template match="block[@name='content_block']" mode="ajax">
        <xsl:variable name="block_name" select="block_name"/>
        <xsl:apply-templates select="/node()/block[@name='content_blocks']/item[name=$block_name]" mode="blocks_admin"/>
    </xsl:template>

    <xsl:template match="item" mode="content_block_cycle">
        <xsl:param name="i"/>
        <xsl:variable name="block_name" select="name"/>
        <xsl:apply-templates select="." mode="content_block"><xsl:with-param name="cell_number" select="$i"/></xsl:apply-templates>

        <xsl:if test="$i &lt; /node()/block[@name='content_blocks']/item[name=$block_name]/params/count">
            <xsl:apply-templates select="." mode="content_block_cycle">
                <xsl:with-param name="i" select="$i+1"/>
            </xsl:apply-templates>
        </xsl:if>
    </xsl:template>

    <xsl:template match="item" mode="column-cell">
        <xsl:param name="class"/>
        <xsl:param name="cell_number"/>
        <td>
            <xsl:attribute name="class">column-cell <xsl:value-of select="$class"/></xsl:attribute>
            <xsl:call-template name="block_value">
                <xsl:with-param name="value" select="cells/item[@_key = ($cell_number - 1)]"/>
            </xsl:call-template>
            <div class="column-content"><span class="empty">Пусто</span></div>
            <hr/>
            <a class="open_ckeditor" href="#"><img src="/admin/static/img/edit2.png" /></a>
        </td>
    </xsl:template>

    <xsl:template match="item[type='column']" mode="content_block">
        <xsl:param name="cell_number"/>
        <xsl:apply-templates select="." mode="column-cell">
            <xsl:with-param name="cell_number" select="$cell_number"/>
            <xsl:with-param name="class">block-<xsl:value-of select="name"/></xsl:with-param>
        </xsl:apply-templates>
    </xsl:template>

    <xsl:template match="item[type='column' and name='column1_2']" mode="content_block_one">
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="cell_number" select="1"/><xsl:with-param name="class">block-column1_2 first</xsl:with-param></xsl:apply-templates>
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="cell_number" select="2"/><xsl:with-param name="class">block-column1_2 second</xsl:with-param></xsl:apply-templates>
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="cell_number" select="3"/><xsl:with-param name="class">block-column1_2 second</xsl:with-param></xsl:apply-templates>
    </xsl:template>

    <xsl:template match="item[type='column' and name='column2_1']" mode="content_block_one">
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="cell_number" select="1"/><xsl:with-param name="class">block-column2_1 first</xsl:with-param></xsl:apply-templates>
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="cell_number" select="2"/><xsl:with-param name="class">block-column2_1 first</xsl:with-param></xsl:apply-templates>
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="cell_number" select="3"/><xsl:with-param name="class">block-column2_1 second</xsl:with-param></xsl:apply-templates>
    </xsl:template>

    <xsl:template name="block_value">
        <xsl:param name="value"/>
        <div class="block-value"><xsl:value-of select="$value" disable-output-escaping="yes"/></div>
    </xsl:template>

</xsl:stylesheet>
