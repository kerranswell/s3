<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.1">

    <xsl:import href="common.xsl"/>

    <!--<xsl:output method="html" indent="no" doctype-system="about:legacy-compat" encoding="utf-8"/>-->

	<xsl:output method="html" indent="yes"
		doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
		doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" encoding="utf-8"/>

	
	<xsl:template match="/">
        <xsl:variable name="item_id" select="/root/common_class/item_id"/>
        <html>
            <head>
                <xsl:call-template name="header"/>
            </head>

            <body class="dark news">

                <xsl:call-template name="topmenu"/>

                <div id="wrapper">
                    <div class="main">
                        <ul class="submenu">
                            <xsl:for-each select="/root/news_class/parts/item">
                                <xsl:choose>
                                    <xsl:when test="active = 1"><li class="active"><xsl:value-of select="title"/></li></xsl:when>
                                    <xsl:otherwise><li><a href="/{translit}/"><xsl:value-of select="title"/></a></li></xsl:otherwise>
                                </xsl:choose>
                                <xsl:if test="position() &lt; last()"><li class="delimiter">|</li></xsl:if>
                            </xsl:for-each>
                        </ul>
                        <xsl:for-each select="/root/news_class/items/item">
                            <xsl:apply-templates select="xml/item" mode="xml_block"/>
                        </xsl:for-each>
                    </div>
                </div>
            </body>

        </html>
    </xsl:template>
	

</xsl:stylesheet>
