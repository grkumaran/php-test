FROM php:7.2-apache




WORKDIR /var/www/html

COPY . .

EXPOSE 8080


