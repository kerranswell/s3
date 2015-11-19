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

    <xsl:template name="calc">

        <div class="calc-page" data-id="1">
            <div class="block columns1">
                <div class="column align-center">
                    Вы можете рассчитать примерную стоимость обслуживания Вашей инфраструктуры.<br/>
                    Для этого укажите количество:
                </div>
            </div>
            <div class="row">
                <table class="input-line"><tbody>
                    <tr><td class="input-line-left">серверов</td><td class="input-line-center"><input type="text" maxlength="2" data-name="count_servers" value="2" class="calc-data num" data-default="2" /></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">рабочих компьютеров</td><td class="input-line-center"><input type="text" maxlength="3" data-name="count_computers" class="calc-data num" value="12" data-default="12" /></td><td class="input-line-right"></td></tr>
                </tbody></table>
            </div>
            <div class="row"><div class="button1 calc-next-step">Продолжить</div></div>
        </div>

        <div class="calc-page" data-id="2">
            <div class="block columns1">
                <div class="column align-center">
                    Отметьте, какие услуги необходимы.
                </div>
            </div>
            <div class="inputs">
                <table class="input-line"><tbody>
                    <tr><td class="input-line-left">IT-директор</td><td class="input-line-center"><div class="calc-data input-checkbox checked" data-name="it-director" data-group="services" data-default="checked"></div></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">системный администратор</td><td class="input-line-center"><div class="calc-data input-checkbox" data-name="sysadmin" data-group="services" data-default=""></div></td><td class="input-line-right"></td></tr>
                </tbody></table>
            </div>
            <div class="row"><div class="button1 calc-next-step">Продолжить</div></div>
        </div>

        <div class="calc-page" data-id="3">
            <div class="block columns1">
                <div class="column align-center">
                    У вас производственный бизнес?
                </div>
            </div>
            <div class="row">
                <div class="button1 calc-data calc-next-step" data-group="business" data-name="business-yes" data-value="0">Да</div><div class="button1 calc-data calc-next-step" data-group="business" data-name="business-no" data-value="0">Нет</div>
            </div>
        </div>

        <div class="calc-page" data-id="4">
            <div class="block columns1">
                <div class="column align-center">
                    Если вы пришли к нам по рекомендации, укажите данные рекомендателя:
                </div>
            </div>
            <div class="row">
                <table class="input-line"><tbody>
                    <tr><td class="input-line-left">ИНН</td><td class="input-line-center"><input type="text" class="calc-data focused" data-name="inn" value="" data-default="" /></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">или номер договора</td><td class="input-line-center"><input type="text" class="calc-data" data-name="contract_number" value="" data-default="" /></td><td class="input-line-right"></td></tr>
                </tbody></table>
            </div>
            <div class="row"><div class="button1 calc-next-step">Рассчитать</div></div>
        </div>

        <div class="calc-page" data-id="5" data-finish="1">
            <div class="block columns1">
                <div class="column align-center">
                    Стоимость обслуживания Вашей инфраструктуры составляет
                </div>
            </div>
            <div class="row"><span id="calc_cost" class="cost">1 234 567 &#8381;</span></div>
        </div>

    </xsl:template>

    <xsl:template match="item[name='team']" mode="xml_block">
        <xsl:if test="count(/root/lists_class/lists/item[pid=5]) &gt; 0">
            <div class="teamlist">
            <xsl:for-each select="/root/lists_class/lists/item[pid=5]">
                <div class="teammate">
                    <div class="pic"><img src="{image_url}" /></div>
                    <span class="title"><xsl:value-of select="title"/></span>
                    <span class="description"><xsl:value-of select="description"/></span>
                </div>
            </xsl:for-each>
            </div>
        </xsl:if>
    </xsl:template>

    <xsl:template match="item[name='companies']" mode="xml_block">
        <xsl:if test="count(/root/lists_class/lists/item[pid=6]) &gt; 0">
            <div class="clientblock">
                <div class="clientlist">
                <xsl:for-each select="/root/lists_class/lists/item[pid=6]">
                    <xsl:if test="position() &lt; 10">
                    <div>
                        <xsl:attribute name="class">client <xsl:choose>
                                <xsl:when test="position() mod (6 + floor(position() div 9)*9) = 0">marginleft</xsl:when>
                                <xsl:when test="position() mod 9 = 0">marginright</xsl:when>
                            </xsl:choose></xsl:attribute>
                        <div class="pic"><a href="{url}" target="_blank"><img src="{image_url}" /></a></div>
                    </div>
                    </xsl:if>
                </xsl:for-each>
                </div>
            </div>
        </xsl:if>
    </xsl:template>

</xsl:stylesheet>