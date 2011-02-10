<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
	<body>
		<h1>
			User Information
		</h1>
		<div>
			<h2>
				Coachings
			</h2>
			<ul>
				<xsl:for-each select="coachings/coaching">
				<li>
					<xsl:value-of select="title"/>
				</li>
				</xsl:for-each>
			</ul>
		</div>
		<div>
			<h2>
				Interactions
			</h2>
			<ul>
				<xsl:for-each select="interactions/usersinteraction">
				<li>
					<xsl:value-of select="key"/>: <xsl:value-of select="results"/>
				</li>
				</xsl:for-each>
			</ul>
		</div>
	</body>
</html>
</xsl:template>
</xsl:stylesheet>