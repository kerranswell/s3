<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="item[name='column2']" mode="xml_block">
        <div class="block columns2">
            <div class="column column1">
                <xsl:value-of select="cells/item[@_key=0]" disable-output-escaping="yes"/>
            </div>
            <div class="column column2">
                <xsl:value-of select="cells/item[@_key=1]" disable-output-escaping="yes"/>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="item[name='column1']" mode="xml_block">
        <div class="block columns1">
            <div class="column">
                <xsl:value-of select="cells/item[@_key=0]" disable-output-escaping="yes"/>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="item[name='quote']" mode="xml_block">
        <div class="quote">
            <div class="left"></div>
            <div class="right"></div>
            <xsl:value-of select="cells/item" disable-output-escaping="yes"/>
        </div>
    </xsl:template>

    <xsl:template match="item[name='contacts']" mode="xml_block">
        <div class="contacts-icons">
            <div><span class="address"></span></div>
            <div><span class="phone"></span></div>
            <div><span class="email"></span></div>
        </div>
        <div class="contacts">
            <div class="address">
                <span><xsl:value-of select="cells/item[@_key=0]" disable-output-escaping="yes"/></span>
            </div>
            <div class="phone">
                <xsl:value-of select="cells/item[@_key=1]" disable-output-escaping="yes"/>
            </div>
            <div class="email">
                <a href="mailto:{cells/item[@_key=2]}"><xsl:value-of select="cells/item[@_key=2]" disable-output-escaping="yes"/></a>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>