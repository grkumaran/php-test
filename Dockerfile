FROM redhat/ubi8

LABEL io.k8s.description="A basic Apache HTTP Server S2I builder image" \
 io.k8s.display-name="Apache HTTP Server S2I builder image for DO288" \
 io.openshift.expose-services="8080:http" \
 io.openshift.s2i.scripts-url="image:///usr/libexec/s2i"

LABEL io.openshift.tags="builder, httpd, httpd24"

# -- software management
RUN yum -y update && yum install -y autoconf
RUN yum -y install curl 
RUN yum --disableplugin=subscription-manager -y module enable php:7.2 \
                        && yum --disableplugin=subscription-manager -y install httpd php \
                        && yum --disableplugin=subscription-manager clean all
                        
RUN sed -i 's/Listen 80/Listen 8080/' /etc/httpd/conf/httpd.conf \
  && mkdir /run/php-fpm \
  && chgrp -R 0 /var/log/httpd /var/run/httpd /run/php-fpm \
  && chmod -R g=u /var/log/httpd /var/run/httpd /run/php-fpm

RUN yum -y install openssl-devel libcurl-devel
  
RUN yum -y install --disableplugin=subscription-manager php-common php-pear php-devel make php-json sudo libcurl-devel openssl-devel openssl-libs && \
    pecl channel-update pecl.php.net && \
    echo "\n" |pecl install igbinary redis mongodb docker-php-ext-enable && \
    echo "extension=json" > /etc/php.d/20-json.ini && \
    echo "extension=igbinary" > /etc/php.d/20-igbinary.ini && \
    echo "extension=redis" > /etc/php.d/40-redis.ini && \
    echo "extension=mongodb" > /etc/php.d/50-mongodb.ini

#RUN sed -i 's#session.save_handler = files#session.save_handler = redis#g;' /etc/php.ini && \
#    sed -i 's#;session.save_path = "/tmp"#session.save_path = "tcp://redis:6379"#' /etc/php.ini && \
#    sed -i 's#variables_order = "GPCS"#variables_order = "EGPCS"#' /etc/php.ini
    
RUN sed -i 's#session.save_handler = files#session.save_handler = redis#g;s#;session.save_path = "/tmp"#session.save_path = "tcp://redis:6379"#;s#variables_order = "GPCS"#variables_order = "EGPCS"#' /etc/php.ini
RUN sed -i 's#error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT#error_reporting = E_ALL#g' /etc/php.ini
RUN sed -i 's#display_errors = Off#display_errors = On#g' /etc/php.ini
RUN sed -i 's#display_startup_errors = Off#display_startup_errors = On#g' /etc/php.ini
RUN sed -i 's#;clear_env = no#clear_env = no#;' /etc/php-fpm.d/www.conf

#RUN yum -y install --disableplugin=subscription-manager libcurl4-openssl-dev pkg-config libssl-dev
#RUN yum -y install --disableplugin=subscription-manager curl-dev openssl-dev


RUN pecl config-set php_ini /etc/php.ini

COPY ./src/* /var/www/html
COPY ./.s2i/bin/ /usr/libexec/s2i

EXPOSE 8080

RUN echo "ServerName localhost" >> /etc/httpd/conf/httpd.conf
RUN echo "%wheel ALL=(ALL) NOPASSWD: ALL" > /etc/sudoers \
    && echo "sudoers: files" >> /etc/nsswitch.conf \
    && chown -R 1001540000 /var/www/html/ /usr/libexec/s2i \
    && chmod 777 /var/lib/php/session

#USER 1001540000

# Install the dependencies
RUN /usr/libexec/s2i/assemble

# Set the default command for the resulting image
CMD /usr/libexec/s2i/run
#CMD php-fpm & httpd -D FOREGROUND
