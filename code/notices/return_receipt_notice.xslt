<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

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

      <!-- CUSTOMIZATION
      If there is no fine associated with this returned item, do not send an email notification. -->

      <xsl:if test="/notification_data/items/item_loan/fine = '' or contains(/notification_data/items/item_loan/fine,'-')">
        <xsl:message terminate="yes">there is no fine to report!</xsl:message>
      </xsl:if>

      <!-- END CUSTOMIZATION -->

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
            
            <!-- CUSTOMIZATION 
            Explain fine information. If a fine accrued before the item was renewed, explain that. -->
            
            <tr>
              <td>
                <h>@@inform_returned_items@@
                  <xsl:value-of select="notification_data/organization_unit/name" />. Please see the fine information listed below. If you have any questions or believe these charges have been made in error, please reply to this email.</h>
                <br />
                <xsl:for-each select="notification_data/loans_by_library/library_loans_for_display/item_loans/overdue_and_lost_loan_notification_display/fines_fees_list/user_fines_fees">
                  <xsl:variable name="renew" select="fine_comment" />
                  <xsl:if test="contains($renew, 'Overdue fine policy: 5.00 All Days Overdue Fine , Action: Renewed')">
                    <p>Note that $
                      <xsl:value-of select="fine_fee_ammount/sum" /> of the fine below is because you renewed the item on
                      <xsl:value-of select="create_date" /> after the item was already overdue.</p>
                  </xsl:if>
                </xsl:for-each>
                <xsl:if test=" notification_data/items/item_loan/user_group='ccstudent' or notification_data/items/item_loan/user_group='sostudent'">
                  <p>Fines will be posted to your student account online.</p>
                </xsl:if>
              </td>
            </tr>

            <!-- END CUSTOMIZATION -->
            
            <xsl:for-each select="notification_data/loans_by_library/library_loans_for_display">
              <tr>
                <td>
                  <table cellpadding="5" class="listing">
                    <xsl:attribute name="style">
                      <xsl:call-template name="mainTableStyleCss" />
                    </xsl:attribute>
                    <!-- <tr align="center" bgcolor="#f5f5f5">
									<td colspan="7">
										<h3><xsl:value-of select="organization_unit/name" /></h3>
									</td>
								</tr> -->
                    <tr>
                      <th>@@title@@</th>
                      <th>@@description@@</th>
                      <th>@@author@@</th>
                      <th>@@due_date@@</th>
                      <th>@@return_date@@</th>
                      <th>@@fine@@</th>
                      <!-- <th>@@library@@</th> -->
                    </tr>

                    <xsl:for-each select="item_loans/overdue_and_lost_loan_notification_display/item_loan">
                      <tr>
                        <td>
                          <xsl:value-of select="title" />
                        </td>
                        <td>
                          <xsl:value-of select="description" />
                        </td>
                        <td>
                          <xsl:value-of select="author" />
                        </td>
                        <td>
                          <xsl:value-of select="new_due_date_str" />
                        </td>
                        <td>
                          <xsl:value-of select="return_date_str" />
                        </td>
                        <td><strong>$
                            <xsl:value-of select="fine" /></strong></td>
                        <!-- <td><xsl:value-of select="library_name"/></td> -->
                      </tr>
                    </xsl:for-each>
                  </table>
                </td>
              </tr>
              <br />
            </xsl:for-each>
          </table>

          <table>
            <tr>
              <td>@@sincerely@@</td>
            </tr>
            <tr>
              <td>@@department@@</td>
            </tr>
          </table>

        </div>
      </div>
      <!-- footer.xsl -->
      <xsl:call-template name="lastFooter" />
    </body>

    </html>
  </xsl:template>
</xsl:stylesheet>
