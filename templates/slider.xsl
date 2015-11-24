<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.1">

    <xsl:import href="blocks.xsl"/>

    <!--<xsl:output method="html" indent="no" doctype-system="about:legacy-compat" encoding="utf-8"/>-->

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
                <!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>-->
                <script src="/static/js/custom/main.js"></script>
                <script src="/static/js/custom/calc.js"></script>
            </head>

            <body class="{/root/common_class/body_class}">

                <xsl:for-each select="/root/pages_class/backs/item">
                    <div class="back fadeOut image" data-id="{idx}" data-width="{width}" data-height="{height}">
                        <xsl:attribute name="style">background-image: url(<xsl:value-of select="url"/>);</xsl:attribute>
                    </div>
                </xsl:for-each>
                <div class="back fadeOut map" data-id="map">
                    <div class="map-over-top"></div>
                    <div class="map-over-bottom"></div>
                    <!--<div id="map"></div>-->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5164.100512652813!2d37.63129646557121!3d55.70975591705963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54b380fdd3fbf%3A0xb564fc21f4fd9644!2sDanilovskaya+nab.%2C+8%D1%8112%2C+Moskva%2C+115114!5e0!3m2!1sen!2sru!4v1447462332900" width="800" height="600" frameborder="0" style="border:0" allowfullscreen="true"></iframe>
                </div>

                <div class="header_strip">
                    <div class="header">
                        <div class="logo"><a class="logo-title" href="/">Центр<br />IT-Поддержки<br />Бизнеса</a></div>
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
                                    <xsl:variable name="calc_class"><xsl:if test="id = 19">calc</xsl:if></xsl:variable>
                                    <xsl:choose>
                                        <xsl:when test="is_active = 1">
                                            <li class="active {$calc_class}" data-id="{id}" data-url="/{$this_url}/"><xsl:value-of select="title"/></li>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <li class="{$calc_class}" data-id="{id}" data-url="/{$this_url}/"><a href="/{$this_url}/"><xsl:value-of select="title"/></a></li>
                                        </xsl:otherwise>
                                    </xsl:choose>

                                </xsl:for-each>
                            </ul>
                            <div class="phone_number"><xsl:value-of select="/root/pages_class/pages/item[translit='contacts']/xml/item[name='contacts']/cells/item[@_key=1]" disable-output-escaping="yes"/></div>
                            <ul class="social_menu">
                                <li><a href="http://vk.com" target="_blank" class="btn-vk"></a></li>
                                <li><a href="http://facebook.com" target="_blank" class="btn-fb"></a></li>
                                <li><a href="http://linkedin.com" target="_blank" class="btn-in"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="wrapper">
                    <div class="main">
                        <div class="left_bar">
                            <ul class="left_menu">
                                <li><a href="/news/" target="_blank">Новости</a></li>
                                <li><a href="/blog/" target="_blank">Блог директора</a></li>
                                <li><a href="#">Вход</a></li>
                            </ul>
<!--
                            <xsl:for-each select="/root/pages_class/pages/item[pid = 0]"><xsl:variable name="id" select="id"/>
                                <div class="left_bar_page" data-id="{id}">
                                    <xsl:if test="count(/root/pages_class/pages/item[pid = $id]) &gt; 1">
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
-->
                        </div>
<script type="text/javascript">
$(function() {
    main_page = new CPage({id:0,type:'v'});
    var p;<!--
--><xsl:for-each select="/root/pages_class/pages/item[pid = 0]"><xsl:variable name="id" select="id"/>
    <xsl:variable name="image_id"><xsl:choose><xsl:when test="template = 3">'map'</xsl:when><xsl:otherwise><xsl:value-of select="image_id"/></xsl:otherwise></xsl:choose></xsl:variable>
    p = new CPage({id:<xsl:value-of select="id"/>,url:'<xsl:value-of select="url"/>',body_class:'<xsl:value-of select="body_class"/>',background:<xsl:value-of select="$image_id"/>,type:'h',active:<xsl:choose><xsl:when test="is_active = 1">true</xsl:when><xsl:otherwise>false</xsl:otherwise></xsl:choose>});
    <xsl:for-each select="/root/pages_class/pages/item[pid = $id]"><xsl:variable name="pid" select="pid"/>
        <xsl:variable name="image_id2"><xsl:choose><xsl:when test="template = 3">'map'</xsl:when><xsl:otherwise><xsl:value-of select="image_id"/></xsl:otherwise></xsl:choose></xsl:variable>
    p.addChild({id:<xsl:value-of select="id"/>,url:'<xsl:value-of select="url"/>',body_class:'<xsl:value-of select="body_class"/>',background:<xsl:value-of select="$image_id2"/>,active:<xsl:choose><xsl:when test="(is_active = 1) or (not(is_active) and position() = 1 and not(/root/pages_class/pages/item[id = $pid]/is_active))">true</xsl:when><xsl:otherwise>false</xsl:otherwise></xsl:choose>});
    </xsl:for-each><!--
-->main_page.addChild({o:p});
</xsl:for-each>
    pager = new CPager(main_page);
    pager.showCurrentBack();
    $win.setBackShifts();
});
</script>
                        <div class="content">
                            <xsl:for-each select="/root/pages_class/pages/item[pid = 0]"><xsl:variable name="id" select="id"/>
                                <div class="v-page page" data-id="{id}">
                                    <div class="toptitle"><h1><xsl:value-of select="title"/></h1></div>
                                    <xsl:if test="count(/root/pages_class/pages/item[pid = $id]) &gt; 1">
                                        <div class="page_button button_left"><a href="#"></a></div>
                                        <div class="page_button button_right"><a href="#"></a></div>
                                    </xsl:if>
                                    <xsl:choose>
                                        <xsl:when test="count(/root/pages_class/pages/item[pid = $id]) &gt; 0">
                                            <xsl:for-each select="/root/pages_class/pages/item[pid = $id]">
                                                <div class="h-page page" data-id="{id}">
                                                    <h2><xsl:value-of select="title"/></h2>
                                                    <xsl:apply-templates select="xml/item" mode="xml_block"/>
                                                    <xsl:if test="template = 4">
                                                        <div class="calc-container">
                                                            <xsl:call-template name="calc"/>
                                                        </div>
                                                    </xsl:if>
                                                </div>
                                            </xsl:for-each>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <div class="h-page page" data-id="0">
                                                <xsl:apply-templates select="xml/item" mode="xml_block"/>
                                            </div>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                    <xsl:if test="count(/root/pages_class/pages/item[pid = $id]) &gt; 1">
                                        <div class="dots">
                                            <ul>
                                                <xsl:for-each select="/root/pages_class/pages/item[pid = $id]">
                                                    <li data-id="{id}">
                                                        <xsl:attribute name="class"><xsl:if test="position() = last()">last</xsl:if></xsl:attribute>
                                                    </li>
                                                </xsl:for-each>
                                            </ul>
                                        </div>
                                    </xsl:if>
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
