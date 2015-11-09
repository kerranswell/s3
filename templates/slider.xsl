<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.1">

	<xsl:output method="html" indent="yes"
		doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
		doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" encoding="utf-8"/>
	
	
	<xsl:template match="/">
        <xsl:variable name="item_id" select="/root/common_class/item_id"/>
        <html>
            <head>
                <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <link rel="stylesheet" href="/static/css/style.css" type="text/css" media="all" />
                <script src="/static/js/jquery.js"></script>
                <script src="/static/js/jquery-ui/jquery-ui.min.js"></script>
                <script src="/static/js/jquery-mousewheel-master/jquery.mousewheel.min.js"></script>
                <script src="/static/js/custom/main.js"></script>
            </head>

            <body class="{/root/common_class/body_class}">
                <div class="header_strip">
                    <div class="header">
                        <div class="logo"><a class="logo-title" href="#">Центр<br />IT-Поддержки<br />Бизнеса</a></div>
                        <div class="right_block">
                            <ul class="main_menu">
                                <xsl:for-each select="/root/pages_class/pages/item[pid = 0]">
                                    <xsl:variable name="id" select="id"/>
                                    <xsl:variable name="this_url">
                                        <xsl:choose>
                                            <xsl:when test="count(/root/pages_class/pages/item[pid = $id]) &gt; 0"><xsl:value-of select="/root/pages_class/pages/item[pid = $id]/url"/></xsl:when>
                                            <xsl:otherwise><xsl:value-of select="url"/></xsl:otherwise>
                                        </xsl:choose>
                                    </xsl:variable>
                                    <xsl:choose>
                                        <xsl:when test="is_active = 1">
                                            <li class="active" data-id="{id}" data-url="/{$this_url}/"><xsl:value-of select="title"/></li>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <li data-id="{id}" data-url="/{$this_url}/"><a href="/{$this_url}/"><xsl:value-of select="title"/></a></li>
                                        </xsl:otherwise>
                                    </xsl:choose>

                                </xsl:for-each>
                            </ul>
                            <div class="phone_number">+7 495 223 32 23</div>
                            <ul class="social_menu">
                                <li><a href="#" class="btn-vk"></a></li>
                                <li><a href="#" class="btn-fb"></a></li>
                                <li><a href="#" class="btn-in"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="wrapper">
                    <div class="back fadeIn" id="back1">
                        <xsl:variable name="bg_image">
                            <xsl:choose>
                                <xsl:when test="/root/pages_class/pages/item[id = $item_id]/bg_image != 0"><xsl:value-of select="/root/pages_class/pages/item[id = $item_id]/bg_image"/></xsl:when>
                                <xsl:when test="/root/pages_class/pages/item[id = $item_id]/bg_image_inherit != 0"><xsl:value-of select="/root/pages_class/pages/item[id = $item_id]/bg_image_inherit"/></xsl:when>
                                <xsl:otherwise>
                                    <xsl:choose>
                                        <xsl:when test="/root/pages_class/pages/item[id = $item_id]/pid &gt; 0">
                                            <xsl:variable name="pid" select="/root/pages_class/pages/item[id = $item_id]/pid"/>
                                            <xsl:choose>
                                                <xsl:when test="/root/pages_class/pages/item[id = $pid]/bg_image != 0"><xsl:value-of select="/root/pages_class/pages/item[id = $pid]/bg_image"/></xsl:when>
                                                <xsl:when test="/root/pages_class/pages/item[id = $pid]/bg_image_inherit != 0"><xsl:value-of select="/root/pages_class/pages/item[id = $pid]/bg_image_inherit"/></xsl:when>
                                            </xsl:choose>
                                        </xsl:when>
                                    </xsl:choose>
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:variable>
                        <xsl:attribute name="style">background-image: url(<xsl:value-of select="$bg_image"/>);</xsl:attribute>
                    </div>
                    <div class="back fadeOut" id="back2"></div>
                    <div class="main">
                        <div class="left_bar">
                            <xsl:for-each select="/root/pages_class/pages/item[pid = 0]"><xsl:variable name="id" select="id"/>
                                <div class="left_bar_page" data-id="{id}">
                                    <xsl:if test="count(/root/pages_class/pages/item[pid = $id]) &gt; 0">
                                        <ul class="left_menu">
                                            <xsl:for-each select="/root/pages_class/pages/item[pid = $id]">
                                                <xsl:choose>
                                                    <xsl:when test="is_active = 1">
                                                        <li class="active" data-id="{id}" data-url="/{url}/"><xsl:value-of select="title"/></li>
                                                    </xsl:when>
                                                    <xsl:otherwise><li data-id="{id}" data-url="/{url}/"><a href="/{url}/"><xsl:value-of select="title"/></a></li></xsl:otherwise>
                                                </xsl:choose>
                                            </xsl:for-each>
                                        </ul>
                                    </xsl:if>
                                </div>
                            </xsl:for-each>
                        </div>
<script type="text/javascript">
$(function() {
    main_page = new CPage({id:0,type:'v'});
    var p;<!--
--><xsl:for-each select="/root/pages_class/pages/item[pid = 0]"><xsl:variable name="id" select="id"/>
    <xsl:variable name="bg_image">
        <xsl:choose>
            <xsl:when test="bg_image != 0">'<xsl:value-of select="bg_image"/>'</xsl:when>
            <xsl:when test="bg_image_inherit != 0">'<xsl:value-of select="bg_image_inherit"/>'</xsl:when>
            <xsl:otherwise>0</xsl:otherwise>
        </xsl:choose>
    </xsl:variable>
    p = new CPage({id:<xsl:value-of select="id"/>,url:'<xsl:value-of select="url"/>',body_class:'<xsl:value-of select="body_class"/>',background:<xsl:value-of select="$bg_image"/>,type:'h',active:<xsl:choose><xsl:when test="is_active = 1">true</xsl:when><xsl:otherwise>false</xsl:otherwise></xsl:choose>});
    <xsl:for-each select="/root/pages_class/pages/item[pid = $id]"><xsl:variable name="pid" select="pid"/>
        <xsl:variable name="bg_image2">
            <xsl:choose>
                <xsl:when test="bg_image != 0">'<xsl:value-of select="bg_image"/>'</xsl:when>
                <xsl:when test="bg_image_inherit != 0">'<xsl:value-of select="bg_image_inherit"/>'</xsl:when>
                <xsl:otherwise>0</xsl:otherwise>
            </xsl:choose>
        </xsl:variable>p.addChild({id:<xsl:value-of select="id"/>,url:'<xsl:value-of select="url"/>',body_class:'<xsl:value-of select="body_class"/>',background:<xsl:value-of select="$bg_image2"/>,active:<xsl:choose><xsl:when test="(is_active = 1) or (not(is_active) and position() = 1 and not(/root/pages_class/pages/item[id = $pid]/is_active))">true</xsl:when><xsl:otherwise>false</xsl:otherwise></xsl:choose>});
    </xsl:for-each><!--
-->main_page.addChild({o:p});
</xsl:for-each>
    pager = new CPager(main_page);
});
</script>
                        <div class="content">
                            <xsl:for-each select="/root/pages_class/pages/item[pid = 0]"><xsl:variable name="id" select="id"/>
                                <div class="v-page page" data-id="{id}">
                                    <xsl:if test="count(/root/pages_class/pages/item[pid = $id]) &gt; 1">
                                        <div class="page_button button_left"><a href="#"></a></div>
                                        <div class="page_button button_right"><a href="#"></a></div>
                                    </xsl:if>
                                    <xsl:for-each select="/root/pages_class/pages/item[pid = $id]">
                                        <div class="h-page page" data-id="{id}">
                                            <h1><xsl:value-of select="title"/></h1>
                                        </div>
                                    </xsl:for-each>
                                </div>
                            </xsl:for-each>
                        </div>
                    </div>
<!--
                    <div class="footer">
                        <div class="page_button button_down"><a href="#"></a></div>
                    </div>
-->
                </div>
            </body>

        </html>
    </xsl:template>
	

</xsl:stylesheet>
