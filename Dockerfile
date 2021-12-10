FROM bitnami/php-fpm

LABEL io.openshift.expose-services="8080:http"
LABEL io.openshift.tags="builder, httpd, httpd24"

# -- software management
RUN apt-get -y update && apt-get install -y autoconf
RUN apt-get -y install curl 
                        
#RUN sed -i 's/Listen 80/Listen 8080/' /etc/httpd/conf/httpd.conf \
#  && mkdir /run/php-fpm \
#  && chgrp -R 0 /var/log/httpd /var/run/httpd /run/php-fpm \
#  && chmod -R g=u /var/log/httpd /var/run/httpd /run/php-fpm
  
#RUN apt-get -y install php-common php-pear php-devel make php-json && \
#    pecl channel-update pecl.php.net && \
#    echo "\n" |pecl install igbinary redis mongodb docker-php-ext-enable && \
#    echo "extension=json" > /etc/php.d/20-json.ini && \
#    echo "extension=igbinary" > /etc/php.d/20-igbinary.ini && \
#    echo "extension=redis" > /etc/php.d/40-redis.ini && \
#    echo "extension=mongodb" > /etc/php.d/50-mongodb.ini

COPY ./src/* /app/
RUN chown -R 1001 /app/

EXPOSE 9000

USER 1001

#CMD php-fpm & httpd -D FOREGROUND
