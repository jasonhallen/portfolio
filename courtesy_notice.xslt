<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:include href="header.xsl" />
  <xsl:include href="senderReceiver.xsl" />
  <xsl:include href="mailReason.xsl" />
  <xsl:include href="footer.xsl" />
  <xsl:include href="style.xsl" />
  <xsl:include href="recordTitle.xsl" />

  <xsl:template match="/">
    <html>
      <head>
        <xsl:call-template name="generalStyle" />
      </head>
      <body>
        <xsl:attribute name="style">
          <xsl:call-template name="bodyStyleCss" /><!-- style.xsl -->
        </xsl:attribute>

        <xsl:call-template name="head" /><!-- header.xsl -->
        <xsl:call-template name="senderReceiver" /> <!-- SenderReceiver.xsl -->

		<br />
		<xsl:call-template name="toWhomIsConcerned" /> <!-- mailReason.xsl -->


        <div class="messageArea">
          <div class="messageBody">

			<table cellspacing="0" cellpadding="5" border="0">
              <tr>
              	<td>
					@@message@@
					<br/>
                </td>
              </tr>

              <!-- CUSTOMIZATION - Check if any items are in a recalled status. If yes, display message.-->
              <xsl:if test="notification_data/item_loans/item_loan/process_status[.='RECALL']">
              <tr>
              	<td>
					<strong>Please note:</strong> The item(s) highlighted in yellow are recalled.  If they are not returned on time you will be charged $5/day. If they are overdue for seven days you will be billed for replacement. See the <a href="https://www.stolaf.edu/library/libinfo/circpolicies.cfm#recalls">St. Olaf</a>/<a href="https://apps.carleton.edu/campus/library/help/services/borrowing/policies/#recalls">Carleton</a> library recall policies for more information.
					<br/>
                </td>
              </tr>
              </xsl:if>
              <!-- <tr>
              	<td>
					<b>@@loans@@</b>
                </td>
              </tr> -->
				<br/>
              <tr>
                <td>
                	<table cellpadding="5" class="listing">
						<xsl:attribute name="style">
							<xsl:call-template name="mainTableStyleCss" /> <!-- style.xsl -->
						</xsl:attribute>
						<tr>
							<th>@@title@@</th>
							<th>@@description@@</th>
							<!-- <th>@@author@@</th> -->
							<th>@@due_date@@</th>
							<!-- <th>@@library@@</th> -->
						</tr>

                		<xsl:for-each select="notification_data/item_loans/item_loan">
						<!-- CUSTOMIZATION - For items in a recalled status, highlight them in yellow.-->
                        <xsl:choose>
							<xsl:when test="process_status = 'RECALL'">
								<tr style="background-color:gold;">
								<td><xsl:value-of select="title"/></td>
								<td><xsl:value-of select="description"/></td>
								<!-- <td><xsl:value-of select="author"/></td> -->
								<td><xsl:value-of select="due_date"/></td>
								<!-- <td><xsl:value-of select="library_name"/></td> -->
								</tr>
							</xsl:when>
							<xsl:otherwise>
								<tr>
								<td><xsl:value-of select="title"/></td>
								<td><xsl:value-of select="description"/></td>
								<!-- <td><xsl:value-of select="author"/></td> -->
								<td><xsl:value-of select="due_date"/></td>
								<!-- <td><xsl:value-of select="library_name"/></td> -->
								</tr>
							</xsl:otherwise>
							</xsl:choose>

						</xsl:for-each>

                	</table>
                </td>
              </tr>
             </table>
				<br />
				<br />
				@@additional_info_1@@
			<br />
			@@additional_info_2@@
				<br />
			<table>
				<tr><td>@@sincerely@@</td></tr>
				<tr><td>@@department@@</td></tr>
			</table>

          </div>
        </div>

        <!-- footer.xsl -->
        <xsl:call-template name="lastFooter" />
  <xsl:call-template name="myAccount" />
		<xsl:call-template name="contactUs" />
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
