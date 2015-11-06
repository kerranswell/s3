<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.1">
	<xsl:import href="common.xsl"/>
	<xsl:import href="content_blocks.xsl"/>

<!--
	<xsl:output method="html" indent="yes"
		doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
		doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" encoding="utf-8"/>
-->

	
	<xsl:template match="/">
        <xsl:apply-templates select="/node()/block[@align='ajax']" mode="ajax"/>
	</xsl:template>
	

</xsl:stylesheet>
