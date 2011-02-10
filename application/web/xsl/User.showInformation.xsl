<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>
<xsl:template match="usersinformation">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="Content-Language" content="en"/>
		<title>User Information</title>
	</head>
	<body>
		<h1>
			User Information
		</h1>
		<dl>
			<dt>
				E-Mail Address
			</dt>
			<dd>
				<xsl:value-of select="user/emailaddress"/>
			</dd>
			<dt>
				First Name
			</dt>
			<dd>
				<xsl:value-of select="user/firstname"/>
			</dd>
			<dt>
				Last Name
			</dt>
			<dd>
				<xsl:value-of select="user/lastname"/>
			</dd>
			<dt>
				Temporary
			</dt>
			<dd>
				<xsl:value-of select="user/temporary"/>
			</dd>
		</dl>
		<div>
			<h2>
				Coachings
			</h2>
			<ol>
				<xsl:for-each select="coachings/coaching">
				<li>
					<xsl:value-of select="title"/>
				</li>
				</xsl:for-each>
			</ol>
		</div>
		<div>
			<h2>
				Interactions
			</h2>
			<dl>
				<xsl:for-each select="interactions/usersinteraction">
				<dt>
					<xsl:value-of select="key"/>
				</dt>
				<dd>
					<xsl:value-of select="results"/>
				</dd>
				</xsl:for-each>
			</dl>
		</div>
	</body>
</html>
</xsl:template>
</xsl:stylesheet>