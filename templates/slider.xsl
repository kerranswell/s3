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

            <body>
                <xsl:attribute name="class"><xsl:value-of select="/root/common_class/body_class"/> slider<xsl:if test="/root/common_class/root = 1"> intro</xsl:if><xsl:if test="/root/mobile = 1"> mobile</xsl:if></xsl:attribute>
                <xsl:call-template name="topmenu"/>
<div>
<xsl:attribute name="class">slider_contents<xsl:if test="/root/common_class/root = 1"> hidden</xsl:if></xsl:attribute>

                <div id="wrapper">
                    <div class="main">
                        <div class="left_bar">
                            <ul class="left_menu">
                                <li><a href="/news/">Новости</a></li>
                                <li><a href="/blog/">Блог директора</a></li>
                                <li><a href="/personal/" target="_blank">Вход</a></li>
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
    var p;
    <!--p = new CPage({id:-1,url:'',body_class:'light',background:0,type:'h'});-->
    <!--main_page.addChild({o:p});-->
    <!--
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
                                    <xsl:choose>
                                        <xsl:when test="translit = 'intro'">

                                        </xsl:when>
                                        <xsl:otherwise>
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
                                                    <div data-id="0">
                                                        <xsl:attribute name="class">h-page page<xsl:if test="count(xml/item[type='contacts']) &gt; 0"> h-page-contacts</xsl:if></xsl:attribute>
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
                                                                <span></span>
                                                            </li>
                                                        </xsl:for-each>
                                                    </ul>
                                                </div>
                                            </xsl:if>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </div>
                            </xsl:for-each>
                        </div>
                    </div>
                </div>
    <xsl:for-each select="/root/pages_class/backs/item">
        <div class="back fadeOut image" data-id="{idx}" data-width="{width}" data-height="{height}">
            <xsl:attribute name="style">background-image: url(<xsl:value-of select="url"/>);</xsl:attribute>
        </div>
    </xsl:for-each>
    <div class="back fadeOut map" data-id="map">
        <div class="map-over-top"></div>
        <div class="map-over-bottom"></div>
        <div id="map"></div>
        <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2246.3908890243083!2d37.819605315377274!3d55.73433700088419!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x414aca6357b8d00f%3A0x87d560c5057317f!2sZhemchugovoy+alleya%2C+5%D0%BA2%2C+Moskva%2C+111402!5e0!3m2!1sen!2sru!4v1448824196777" width="800" height="600" frameborder="0" style="border:0" allowfullscreen="true"></iframe>-->
    </div>
</div>
                <xsl:call-template name="intro"/>
                <div class="fullscreen" id="how">
                    <div class="fscontent">
                        <div class="title"><span>Как это работает?</span><div class="close"></div></div>
                        <div class="clr"></div>
                        <div class="text align-center"></div>
                    </div>
                </div>
                <div class="fullscreen_transparent bg-blue"></div>
                <div id="feedback_content" class="hidden" data-title="Обратная связь">
                    <div class="row">
                        <table class="form feedback"><tbody>
                            <tr>
                                <td class="input-title">Как к Вам обращаться?*</td><td class="input-input"><input tabindex="1" type="text" data-name="name" value="" /></td>
                                <td class="input-title">Эл. почта*</td><td class="input-input"><input type="text" tabindex="3" data-name="email" value=""/></td>
                            </tr>
                            <tr>
                                <td class="input-title">Название Вашей компании*</td><td class="input-input"><input tabindex="2" type="text" data-name="company" value="" /></td>
                                <td class="input-title">Телефон</td><td class="input-input"><input tabindex="4" type="text" data-name="phone" value="" /></td>
                            </tr>
                            <tr><td class="textarea-desc">Комментарии*</td><td colspan="3" class="input-input"><textarea tabindex="5" data-name="comments"></textarea></td></tr>
                            <tr class="padbottom-less">
                                <td class="input-title">Введите код*</td><td class="input-input"><input tabindex="6" type="text" data-name="captcha" value="" /></td>
                                <td class="input-title captcha"></td><td class="input-input"><div class="button-update"></div></td>
                            </tr>
                            <tr><td class="input-title"></td><td colspan="3" class="input-input small-desc">*Поля обязательны для заполнения</td></tr>
                            <tr class="padbottom-less"><td class="input-title"></td><td colspan="3" class="input-input"><div class="button1 feedback-send">Отправить</div>
                                <br /><br />
                                <div class="row hidden form-error" data-error="email">Проверьте правильность электронного адреса</div>
                                <div class="row hidden form-error" data-error="captcha">Неверно введен код</div>
                            </td></tr>
                        </tbody></table>
                    </div>

                    <div class="hidden" data-message="success">
                        <div class="row msg2 success-feedback">Спасибо, мы с Вами свяжемся.</div>
                    </div>
                    <div class="hidden" data-message="error">
                        <div class="row msg2">Ошибка, пожалуйста, попробуйте еще раз позже.</div>
                    </div>
                </div>
            </body>
            <xsl:call-template name="footer_codes"/>
        </html>
    </xsl:template>

    <xsl:template name="intro">
        <div>
            <xsl:attribute name="class">intro_full<xsl:if test="/root/common_class/root != 1"> hidden</xsl:if></xsl:attribute>
            <div class="intro_c">
                <div class="intro">

                    <div class="layer p1"></div>
                    <div class="layer p2"></div>
                    <div class="layer p3"></div>
                    <div class="layer2">
                        <div class="logo">
                            <div class="logo_pattern1"></div>
                            <div class="logo_pattern2"></div>
                            <div class="logo_pattern3"></div>
                            <div class="logo_pattern4"></div>
                        </div>
                        <div class="title1"></div>
                        <div class="title2"></div>
                    </div>
                    <div class="layer0">
                        <div class="intro_mouse"></div>
                        <div class="intro_desc">Для просмотра используйте колесико мыши или клавиши со стрелками</div>
                        <div class="intro_keys"></div>
                    </div>
                </div>
            </div>
            <div class="intro_arrow"></div>
        </div>
    </xsl:template>

</xsl:stylesheet>
