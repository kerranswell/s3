<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output method="html" indent="yes"
                doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
                doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" encoding="utf-8"/>

    <xsl:template match="/">
    <html>
        <head>
            <link rel="stylesheet" href="/static/css/docs.css?ts={/root/timestamp}" type="text/css" media="all" />
            <script src="/static/js/jquery.js"></script>
            <script src="/static/js/jquery-ui/jquery-ui.min.js"></script>
        </head>

        <body>
            <div class="personal"><xsl:value-of select="/root/common_class/user"/>, <a href="/personal/logout/">выйти</a></div>
            <table class="docs">
                    <tbody>
            <xsl:for-each select="/root/contracts_class/items/item">
                <tr>
                    <td><a target="_blank" href="/personal/download/{file}"><xsl:value-of select="file"/></a></td>
                    <td><xsl:value-of select="diz" disable-output-escaping="yes" /></td>
                </tr>
            </xsl:for-each>
            </tbody>
            </table>
        </body>
    </html>
    </xsl:template>

</xsl:stylesheet>