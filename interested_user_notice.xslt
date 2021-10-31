<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:include href="header.xsl" />
<xsl:include href="senderReceiver.xsl" />
<xsl:include href="mailReason.xsl" />
<xsl:include href="footer.xsl" />
<xsl:include href="style.xsl" />

<xsl:template match="/">
	<html>
		<head>
		<xsl:call-template name="generalStyle" />
		</head>

			<body>
			<xsl:attribute name="style">
				<xsl:call-template name="bodyStyleCss" /> <!-- style.xsl -->
			</xsl:attribute>

				<xsl:call-template name="head" /> <!-- header.xsl -->
				<xsl:call-template name="senderReceiver" /> <!-- SenderReceiver.xsl -->

				<br />
				<xsl:call-template name="toWhomIsConcerned" /> <!-- mailReason.xsl -->
					@@You_were_specify@@ To see the item’s current availability and request options, please visit the
				<xsl:choose>
				<xsl:when test="/notification_data/receivers/receiver/user/campus_code='CARLETON'">
					<a>
						<xsl:attribute name="href">https://bridge.primo.exlibrisgroup.com/discovery/search?query=any,contains,<xsl:value-of select="/notification_data/mms_id"/>&amp;tab=Everything&amp;search_scope=CCO_MyCampus_PCI&amp;vid=01BRC_INST:CCO&amp;offset=0</xsl:attribute>
               		item's record in Catalyst</a>.
				</xsl:when>
				<xsl:when test="/notification_data/receivers/receiver/user/campus_code='STOLAF'">
					<a>
						<xsl:attribute name="href">https://bridge.primo.exlibrisgroup.com/discovery/search?query=any,contains,<xsl:value-of select="/notification_data/mms_id"/>&amp;tab=Everything&amp;search_scope=SOC_MyCampus_CI&amp;vid=01BRC_INST:SOC&amp;offset=0</xsl:attribute>
               		item's record in Catalyst</a>.
               	</xsl:when>
               	<xsl:otherwise>
               	To see the item’s current availability and request options, please visit Catalyst.
               	</xsl:otherwise>
               	</xsl:choose>
				<br />
				<table cellspacing="0" cellpadding="5" border="0">
				<!--
<tr>
					<td>
				<br />
				@@orderNumber@@	

						<br />

					</td>
						<td>
				<br />
				<xsl:value-of  select="notification_data/line_number"/>

						<br />

					</td>
					</tr>
-->
				<tr>
					<td>
				<br />
				<strong>@@title@@</strong> 

						<br />

					</td>
						<td>
				<br />
				<xsl:choose>
				<xsl:when test="/notification_data/receivers/receiver/user/campus_code='CARLETON'">
					<a>
						<xsl:attribute name="href">https://bridge.primo.exlibrisgroup.com/discovery/search?query=any,contains,<xsl:value-of select="/notification_data/mms_id"/>&amp;tab=Everything&amp;search_scope=CCO_MyCampus_PCI&amp;vid=01BRC_INST:CCO&amp;offset=0</xsl:attribute>
               		<xsl:value-of  select="notification_data/title"/></a>
				</xsl:when>
				<xsl:when test="/notification_data/receivers/receiver/user/campus_code='STOLAF'">
					<a>
						<xsl:attribute name="href">https://bridge.primo.exlibrisgroup.com/discovery/search?query=any,contains,<xsl:value-of select="/notification_data/mms_id"/>&amp;tab=Everything&amp;search_scope=SOC_MyCampus_CI&amp;vid=01BRC_INST:SOC&amp;offset=0</xsl:attribute>
               		<xsl:value-of  select="notification_data/title"/></a>
               	</xsl:when>
               	<xsl:otherwise>
               	<xsl:value-of  select="notification_data/title"/>
               	</xsl:otherwise>
               	</xsl:choose>

						<br />

					</td>
					</tr>
				<xsl:if test="notification_data/message != 'E-resource was activated.'">
					<tr>
					<td>
				<br />
				<strong>@@callNumber@@</strong>

						<br />

					</td>
						<td>
				<br />
				<xsl:value-of  select="notification_data/poline_inventory/call_number"/>

						<br />

					</td>
					</tr>
                </xsl:if>
				<!--
					<tr>
					<td>
				<br />
				@@message@@	:

						<br />

					</td>
						<td>
				<br />
				<xsl:value-of  select="notification_data/message"/>

						<br />

					</td>
					</tr>
                                -->
				</table>
				<br />
				<table>
						<tr><td>@@sincerely@@</td></tr>
						<tr><td>@@department@@</td></tr>
				</table>

				<xsl:call-template name="lastFooter" /> <!-- footer.xsl -->
			</body>
	</html>
</xsl:template>

</xsl:stylesheet>