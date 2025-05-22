<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/cars">
    <html>
      <body>
        <h2>Seznam aut</h2>
        <ul>
          <xsl:for-each select="car">
            <li>
              <xsl:value-of select="brand"/> 
              <xsl:text> </xsl:text>
              <xsl:value-of select="model"/> 
              (<xsl:value-of select="year"/>)
            </li>
          </xsl:for-each>
        </ul>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
