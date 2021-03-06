<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.1">

    <xsl:import href="blocks.xsl"/>

    <xsl:template name="header">
        <title>Центр IT-поддержки бизнеса</title>
        <meta name="title" content="Центр IT-поддержки бизнеса" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="icon" type="image/png" href="/favicon.png" />
        <link rel="stylesheet" href="/static/css/style.css?v={/root/version}" type="text/css" media="all" />
        <script src="/static/js/jquery.js"></script>
        <script src="/static/js/jquery-ui/jquery-ui.min.js"></script>
        <script src="/static/js/jquery-mousewheel-master/jquery.mousewheel.min.js"></script>
        <script src="/static/js/jquery.hyphen.ru.js?v={/root/version}"></script>
        <script src="/static/js/base64.js"></script>
        <!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>-->
        <script src="/static/js/custom/intro.js?v={/root/version}"></script>
        <script src="/static/js/custom/main.js?v={/root/version}"></script>
        <script src="/static/js/custom/calc.js?v={/root/version}"></script>
    </xsl:template>


    <xsl:template name="topmenu">
        <div id="header_burger">
            <xsl:attribute name="class">header_strip<xsl:choose>
                        <xsl:when test="/root/common_class/root != 1"> transparent hidden</xsl:when>
                <xsl:otherwise> visible</xsl:otherwise>
                    </xsl:choose></xsl:attribute>
            <div class="header"><div class="burger"></div></div></div>
        <div id="header_menu">
            <xsl:attribute name="class">header_strip<xsl:if test="/root/common_class/root = 1"> transparent hidden</xsl:if></xsl:attribute>
            <div class="header">
                <div class="logo"><a class="logo-title" href="/about/mission/"></a></div>
                <div class="right_block">
                    <ul class="main_menu">
                        <xsl:for-each select="/root/pages_class/pages/item[pid = 0 and translit != 'intro']">
                            <xsl:variable name="id" select="id"/>
                            <xsl:variable name="this_url">
                                <xsl:choose>
                                    <xsl:when test="count(/root/pages_class/pages/item[pid = $id]) &gt; 0"><xsl:value-of select="/root/pages_class/pages/item[pid = $id]/url"/></xsl:when>
                                    <xsl:otherwise><xsl:value-of select="url"/></xsl:otherwise>
                                </xsl:choose>
                            </xsl:variable>
                            <xsl:variable name="calc_class"><xsl:if test="id = 19">calc</xsl:if></xsl:variable>
                            <xsl:choose>
                                <xsl:when test="is_active = 1 and /root/common_class/root != 1">
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
                        <li><a href="http://vk.com/citsb" target="_blank" class="btn-vk"></a></li>
                        <li><a href="https://www.facebook.com/citsb.citsb" target="_blank" class="btn-fb"></a></li>
                        <li><a href="https://www.linkedin.com/in/vitaly-gorokhov-831b9993" target="_blank" class="btn-in"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </xsl:template>

    <xsl:template name="afterbody_codes">
        <xsl:comment>LiveInternet counter</xsl:comment><script type="text/javascript"><xsl:comment>
        new Image().src = "//counter.yadro.ru/hit?r"+
        escape(document.referrer)+((typeof(screen)=="undefined")?"":
        ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
        screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
        ";"+Math.random();//</xsl:comment></script><xsl:comment>/LiveInternet</xsl:comment>
    </xsl:template>

    <xsl:template name="footer_codes">
        <script async="async" defer="defer"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjXTwuj2uf0fI8TGTQYKLFmk2qol2NV-w&amp;callback=initMap">
        </script>
        <xsl:comment> Yandex.Metrika counter </xsl:comment>
        <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter34323920 = new Ya.Metrika({
                            id:34323920,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true,
                            webvisor:true,
                            trackHash:true
                        });
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/34323920" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <xsl:comment> /Yandex.Metrika counter </xsl:comment>

        <xsl:comment>LiveInternet logo</xsl:comment><a href="//www.liveinternet.ru/click"
        target="_blank"><img src="//counter.yadro.ru/logo?26.10"
        title="LiveInternet: number of visitors for today is shown"
        alt="" border="0" width="0" height="0"/></a><xsl:comment>/LiveInternet</xsl:comment>
    </xsl:template>

</xsl:stylesheet>
