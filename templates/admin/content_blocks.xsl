<?xml version="1.0" encoding="UTF-8" ?> 
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

    <xsl:template match="block[@name='content_blocks']">
        <input type="button" id="toolbar_add" value="Добавить блок" />
        <select id="toolbar_block">
            <xsl:for-each select="item"><option value="{name}"><xsl:value-of select="title"/></option></xsl:for-each>
        </select>
    </xsl:template>

    <xsl:template match="block[@name='content_block']" mode="ajax">
        <xsl:variable name="block_name" select="block_name"/>
        <div class="content-block" data-name="{block_name}">
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
            <div class="block-tools"><div class="block-drag float-left"></div><div class="block-del float-right"></div></div>
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

    <xsl:template match="item" mode="column-cell">
        <xsl:param name="class"/>
        <td>
            <xsl:attribute name="class">column-cell <xsl:value-of select="$class"/></xsl:attribute>
            <xsl:apply-templates select="." mode="block_value"/>
            <div class="column-content"><span class="empty">Пусто</span></div>
            <hr/>
            <a class="open_ckeditor" href="#"><img src="/admin/static/img/edit2.png" /></a>
        </td>
    </xsl:template>

    <xsl:template match="item[type='column']" mode="content_block">
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="class">block-<xsl:value-of select="name"/></xsl:with-param></xsl:apply-templates>
    </xsl:template>

    <xsl:template match="item[type='column' and name='column1_2']" mode="content_block_one">
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="class">block-column1_2 first</xsl:with-param></xsl:apply-templates>
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="class">block-column1_2 second</xsl:with-param></xsl:apply-templates>
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="class">block-column1_2 second</xsl:with-param></xsl:apply-templates>
    </xsl:template>

    <xsl:template match="item[type='column' and name='column2_1']" mode="content_block_one">
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="class">block-column2_1 first</xsl:with-param></xsl:apply-templates>
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="class">block-column2_1 first</xsl:with-param></xsl:apply-templates>
        <xsl:apply-templates select="." mode="column-cell"><xsl:with-param name="class">block-column2_1 second</xsl:with-param></xsl:apply-templates>
    </xsl:template>

    <xsl:template match="item" mode="block_value">
        <div class="block-value"></div>
    </xsl:template>

</xsl:stylesheet>
