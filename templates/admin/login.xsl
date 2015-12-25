<?xml version="1.0" encoding="UTF-8" ?> 
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:import href="layout.xsl"/> 
	
	<xsl:template match="block[@name='login']">
		<div class="center login">
			<xsl:choose>
				<xsl:when test="/root/common/_get/restore = 2">
					<div class="control-group">
						<label class="control-label" >Новый пароль был сгенерирован и отправлен на почту.</label><br /><br />
						<label class="control-label" ><a href="/admin/">Назад к авторизации</a></label>
					</div>

				</xsl:when>
				<xsl:when test="/root/common/_get/restore = 1">
					<form action="{@act}" method="post" class="form-horizontal">

						<fieldset>
							<input type="hidden" name="opcode" value="restore"/>
							<p class="admin-message"><xsl:value-of select="/root/common/msg"/></p>
							<legend>Восстановление пароля</legend>

							<div class="control-group">
								<label class="control-label" for="email">Ваш E-mail:</label>
								<div class="controls">
									<input type="text" name="email" id="email" placeholder="email"/>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="submit" value="Восстановить" class="btn btn-primary">Восстановить</button>
								</div>
							</div>
						</fieldset>
					</form>
				</xsl:when>
				<xsl:otherwise>
					<form action="{@act}" method="post" class="form-horizontal">

						<fieldset>
							<input type="hidden" name="opcode" value="login"/>
		                    <xsl:apply-templates select="/root/head/notify" />
							<legend>Вход в систему</legend>

							<div class="control-group">
								<label class="control-label" for="username">Username:</label>
								<div class="controls">
									<input type="text" name="username" id="username" placeholder="username"/>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="password">Password:</label>
								<div class="controls">
									<input type="password" name="password" placeholder="password"/>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="submit" value="Вход" class="btn btn-primary">Вход</button>
								</div>
							</div>
							<xsl:choose>
								<xsl:when test="@personal = 1"></xsl:when>
								<xsl:otherwise>
									<div class="control-group">
										<div class="controls">
											<a href="/admin/?restore=1">Забыли пароль?</a>
										</div>
									</div>
								</xsl:otherwise>
							</xsl:choose>
						</fieldset>
					</form>
				</xsl:otherwise>
			</xsl:choose>
		</div>
	</xsl:template>

    <xsl:template match="notify">
        <div class="control-group">
            <div class="error">
                <xsl:value-of select="."/>
            </div>
        </div>
    </xsl:template>

	<xsl:template match="block[@name='logout']">
		<center>
			<div class="logout">
				<div class="title">Выход из системы</div>
				<div class="form">
					<form method="post" action="">
						<input type="hidden" name="opcode" value="logout"/>
						<input type="submit" class="button" value="Logout" />
					</form>
				</div>
			</div>
		</center>
	</xsl:template>

</xsl:stylesheet>