FROM ubuntu
ENV DEBIAN_FRONTEND=noninteractive 
RUN apt-get update
RUN apt-get install -y php7.2-cli composer
RUN apt-get install -y php-pgsql php7.2-mbstring
RUN useradd php
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
RUN chown php /usr/src/myapp -R
USER php
RUN composer install
EXPOSE 8080
CMD [ "php", "-S", "0.0.0.0:8080", "./graphql.php" ]
