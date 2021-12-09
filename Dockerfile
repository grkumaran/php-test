FROM registry.access.redhat.com/ubi8/php-73:latest

LABEL io.k8s.description="A basic Apache HTTP Server S2I builder image" \
 io.k8s.display-name="Apache HTTP Server S2I builder image for DO288" \
 io.openshift.expose-services="8080:http" \
 io.openshift.s2i.scripts-url="image:///usr/libexec/s2i"

LABEL io.openshift.tags="builder, httpd, httpd24"

ENV DOCROOT /var/www/html

RUN yum install -y --nodocs --disableplugin=subscription-manager httpd && \ 
 yum clean all --disableplugin=subscription-manager -y && \
 echo "This is the default index page from the s2i-do288-httpd S2I builder image." > ${DOCROOT}/index.html

RUN sed -i "s/Listen 80/Listen 8080/g" /etc/httpd/conf/httpd.conf       

#COPY ./php.extensions/* 

#COPY ./src/* /var/www/html/
RUN chmod 775 /var/www/html

COPY ./s2i/bin/ /usr/libexec/s2i
