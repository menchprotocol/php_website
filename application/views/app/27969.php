<IfModule mod_ssl.c>
    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName mench.com

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.mench.com

        SSLCertificateChainFile /etc/apache2/ssl.crt/mench_com.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/mench_com.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/mench_com_key.txt

    </VirtualHost>
    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName shervinsresume.com

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.shervinsresume.com

        SSLCertificateChainFile /etc/apache2/ssl.crt/shervinsresume.com.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/shervinsresume.com.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/shervinsresume_com_key.txt

    </VirtualHost>
    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName creatorsonly.club

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.creatorsonly.club

        SSLCertificateChainFile /etc/apache2/ssl.crt/creatorsonly.club.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/creatorsonly.club.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/creatorsonly_club_key.txt

    </VirtualHost>
    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName atlascamp.org

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.atlascamp.org

        SSLCertificateChainFile /etc/apache2/ssl.crt/atlascamp.org.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/atlascamp.org.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/atlascamp_org_key.txt

    </VirtualHost>
    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName valentine.flowers

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.valentine.flowers

        SSLCertificateChainFile /etc/apache2/ssl.crt/valentine.flowers.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/valentine.flowers.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/valentine_flowers_key.txt

    </VirtualHost>
    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName freeapp.ninja

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.freeapp.ninja

        SSLCertificateChainFile /etc/apache2/ssl.crt/freeapp_ninja.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/freeapp_ninja.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/freeapp_ninja_key.txt

    </VirtualHost>
    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName joinroyalty.com

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.joinroyalty.com

        SSLCertificateChainFile /etc/apache2/ssl.crt/joinroyalty_com.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/joinroyalty_com.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/joinroyalty_com_key.txt

    </VirtualHost>
    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName musk.tips

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.musk.tips

        SSLCertificateChainFile /etc/apache2/ssl.crt/musk_tips.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/musk_tips.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/musk_tips_key.txt

    </VirtualHost>
    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName unicornsonly.club

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.unicornsonly.club

        SSLCertificateChainFile /etc/apache2/ssl.crt/unicornsonly_club.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/unicornsonly_club.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/unicornsonly_club_key.txt

    </VirtualHost>

    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName scrapless.team

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.scrapless.team

        SSLCertificateChainFile /etc/apache2/ssl.crt/scrapless_team.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/scrapless_team.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/scrapless_team_key.txt

    </VirtualHost>


    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName shorttermrealty.ca

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.shorttermrealty.ca

        SSLCertificateChainFile /etc/apache2/ssl.crt/shorttermrealty_ca.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/shorttermrealty_ca.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/shorttermrealty_ca_key.txt

    </VirtualHost>

    <VirtualHost *:443>

        ServerAdmin shervin@mench.com
        DocumentRoot /var/www/platform
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        ServerName moop.club

        SSLEngine on
        SSLProtocol -all +TLSv1.2
        SSLHonorCipherOrder on

        ServerAlias www.moop.club

        SSLCertificateChainFile /etc/apache2/ssl.crt/moop_club.ca-bundle
        SSLCertificateFile /etc/apache2/ssl.crt/moop_club.crt
        SSLCertificateKeyFile /etc/apache2/ssl.crt/moop_club_key.txt

    </VirtualHost>



</IfModule>
