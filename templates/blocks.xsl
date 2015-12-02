<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="item[name='fullscreen_text']" mode="xml_block">
        <div class="how"><a href="#">Как это работает?</a></div>
        <div class="fullscreen_text" data-title="Как это работает?">
            <div class="block columns2">
                <div class="column column1">
                    <xsl:value-of select="cells/item[@_key=0]" disable-output-escaping="yes"/>
                </div>
                <div class="column column2">
                    <xsl:value-of select="cells/item[@_key=1]" disable-output-escaping="yes"/>
                </div>
            </div>
        </div>
    </xsl:template>

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
            <div class="feedback_link">
                <a href="#">Обратная связь</a>
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
                    <tr><td class="input-line-left">серверов</td><td class="input-line-center"><input type="text" maxlength="2" data-name="count_servers" value="" class="calc-data num" data-default="" /></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">рабочих компьютеров</td><td class="input-line-center"><input type="text" maxlength="3" data-name="count_computers" class="calc-data num" value="" data-default="" /></td><td class="input-line-right"></td></tr>
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
                    <tr><td class="input-line-left">IT-директор</td><td class="input-line-center"><div class="calc-data input-checkbox checked" data-name="it-director" data-group="service" data-default="checked"></div></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">системный администратор</td><td class="input-line-center"><div class="calc-data input-checkbox" data-name="sysadmin" data-group="service" data-default=""></div></td><td class="input-line-right"></td></tr>
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
                    <tr><td class="input-line-left">ИНН</td><td class="input-line-center"><input type="text" class="calc-data focused left" maxlength="12" data-name="inn" value="" data-default="" /></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">или номер договора</td><td class="input-line-center"><input type="text" class="calc-data left" maxlength="11" data-name="contract_number" value="" data-default="" /></td><td class="input-line-right"></td></tr>
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
            <div class="row">
                <div class="button1" id="service_submit">Оформить запрос услуги</div><div class="button1" id="service_refuse">Меня не устраивает</div>
            </div>
        </div>

        <div id="service_submit_form" class="hidden" data-title="Запрос услуги">
            <div class="block columns1 align-center">
                <div class="column align-center service-submit-message">
                    Спасибо за проявленный интерес! Пожалуйста, заполните анкету заказа. Это не займет у Вас много времени, но поможет нам более качественно подготовиться к презентации наших услуг.
                </div>
            </div>
            <div class="row">
                <table class="input-line"><tbody>
                    <tr><td class="input-line-left">Название компании*</td><td class="input-line-center"><input type="text" data-name="company" value="" /></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">Контактное лицо*</td><td class="input-line-center"><input type="text" data-name="name" value="" /></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">Электронная почта*</td><td class="input-line-center"><input type="text" data-name="email" value=""/></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">Телефон*</td><td class="input-line-center"><input type="text" data-name="phone" value="" /></td><td class="input-line-right"></td></tr>
                </tbody></table>
            </div>
            <div class="row">
                <table class="input-line"><tbody>
                    <tr><td class="input-line-left textarea-desc">Комментарии*</td><td class="input-line-center"><textarea data-name="comments"></textarea></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left"></td><td class="input-line-center small-desc">*Поля обязательны для заполнения</td><td class="input-line-right"></td></tr>
                </tbody></table>
            </div>
            <div class="row"><div class="button1" id="service-feedback-send">Отправить</div></div>
            <div class="row hidden form-error" data-error="email">Проверьте правильность электронного адреса</div>
            <div class="hidden align-center" data-message="success">
                <div class="row msg3">Спасибо, мы с Вами свяжемся.</div>
            </div>
            <div class="hidden" data-message="error">
                <div class="row msg2">Ошибка, пожалуйста, попробуйте еще раз позже.</div>
            </div>
        </div>

        <div id="service_refuse_form" class="hidden" data-title="Мы вас не устраиваем?">
            <div class="block columns1 align-center">
                <div class="column align-center service-refuse-message">
                    Спасибо за проявленный интерес к нашей компании. Мы будем очень признательны, если Вы укажете причины, по которым мы не подошли Вашим требованиям. Хотим отметить, что у нас работает система мониторинга качества наших услуг. Мы проводим опрос сотрудников о качестве предоставляемых  услуг. Также в нашей компании действует <a href="/upload/Etic_Codecs.pdf" target="_blank">этический кодекс</a>. Ваше сообщение будет отправлено генеральному директору с пометкой «срочно». И, если позволите, мы свяжемся с Вами, чтобы устранить или развеять Ваши сомнения.
                </div>
            </div>
            <div class="row">
                <table class="input-line"><tbody>
                    <tr><td class="input-line-left">Название компании</td><td class="input-line-center"><input type="text" data-name="company" value="" /></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">Контактное лицо</td><td class="input-line-center"><input type="text" data-name="name" value="" /></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">Электронная почта</td><td class="input-line-center"><input type="text" data-name="email" value=""/></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left">Телефон</td><td class="input-line-center"><input type="text" data-name="phone" value="" /></td><td class="input-line-right"></td></tr>
                </tbody></table>
            </div>
            <div class="row">
                <table class="input-line"><tbody>
                    <tr><td class="input-line-left textarea-desc">Комментарии*</td><td class="input-line-center"><textarea data-name="comments"></textarea></td><td class="input-line-right"></td></tr>
                    <tr><td class="input-line-left"></td><td class="input-line-center small-desc">*Поля обязательны для заполнения</td><td class="input-line-right"></td></tr>
                </tbody></table>
            </div>
            <div class="row"><div class="button1" id="service-feedback-refuse">Отправить</div></div>
            <div class="hidden align-center" data-message="success">
                <div class="row msg3">
                    Спасибо, Ваш запрос передан ответственному лицу. Ответственный: Горохов Виталий. Вы можете с ним связаться, позвонив по телефону<br />
                    +7 (495) 123-45-67 доп. номер #107 в рабочее время с 10 до 18 по московскому времени.<br/>
                </div>
            </div>
            <div class="hidden" data-message="error">
                <div class="row msg2">Ошибка, пожалуйста, попробуйте еще раз позже.</div>
            </div>
        </div>

<div id="submit_test" class="hidden" data-title="Запрос услуги">
    <div class="row msg3">
Благодарим за оказанное нам доверие. Ваш запрос передан ответственному лицу. Ответственный: Горохов Виталий. Вы можете с ним связаться, позвонив по телефону<br />
        +7 (495) 123-45-67 доп. номер #107 в рабочее время с 10 до 18 по московскому времени. Предварительный номер вашего договора:  T03-12/2015.<br/>
<br />
Пока мы обрабатываем Ваш запрос, Вы можете ознакомиться с шаблоном нашего договора, а также посмотреть наше коммерческое предложение.<br/><br />
<a href="/upload/Dogovor_Template_wForms.pdf" target="_blank">Договор на обслуживание информационной системы предприятия</a><br />
<a href="/upload/Commercial-Prop-IT-Dir.pdf" target="_blank">Доп. соглашение на услугу IT директор</a><br />
<a href="/upload/Commercial-Prop.pdf" target="_blank">Шаблон коммерческого предложения</a><br />
        </div>
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

<!--
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

-->
    <xsl:template match="item[name='companies']" mode="xml_block">
        <xsl:if test="count(/root/lists_class/lists/item[pid=6]) &gt; 0">
            <div class="clientblock">
                <div class="clientlist">
                <xsl:variable name="cnt" select="count(/root/lists_class/lists/item[pid=6])" />
                <xsl:for-each select="/root/lists_class/lists/item[pid=6]">
                    <xsl:if test="position()">
                    <div>
                        <xsl:attribute name="class">client<xsl:choose>
                                <xsl:when test="position() mod (6 + floor(position() div 9)*9) = 0 and (position() + 3 &lt;= $cnt)"> marginleft</xsl:when>
                                <xsl:when test="position() mod 9 = 0"> marginright</xsl:when>
                            </xsl:choose></xsl:attribute>
                        <div class="pic"><a href="{url}" target="_blank"><img src="{image_url}" /></a></div>
                    </div>
                    </xsl:if>
                </xsl:for-each>
                </div>
            </div>
        </xsl:if>
    </xsl:template>

    <xsl:template match="item[name='picture']" mode="xml_block">
        <div class="picture">
            <img src="{cells/item[@_key=6]/path}" />
        </div>
    </xsl:template>

    <xsl:template match="paginator">
        <xsl:if test="total_pages &gt; 1">
            <div class="paginator">
                <xsl:choose>
                    <xsl:when test="page = 2"><a href="{pre_url}">&lt;</a></xsl:when>
                    <xsl:when test="page &gt; 2"><a href="{pre_url}page/{number(page)-1}/">&lt;</a></xsl:when>
                </xsl:choose>
                <xsl:for-each select="pages/item">
                    <xsl:variable name="url">
                        <xsl:choose>
                            <xsl:when test="num &gt; 1">page/<xsl:value-of select="num"/>/</xsl:when>
                            <xsl:otherwise></xsl:otherwise>
                        </xsl:choose>
                    </xsl:variable>
                    <xsl:choose>
                        <xsl:when test="active = 1"><span><xsl:value-of select="num"/></span></xsl:when>
                        <xsl:otherwise><a href="{../../pre_url}{$url}"><xsl:value-of select="num"/></a></xsl:otherwise>
                    </xsl:choose>
                </xsl:for-each>
                <xsl:if test="page &lt; total_pages"><a href="{pre_url}page/{number(page)+1}/">&gt;</a></xsl:if>
            </div>
        </xsl:if>
    </xsl:template>

</xsl:stylesheet>