FROM debian:12.6
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN apt update -y && apt upgrade -y && \
    apt install -y apt-transport-https lsb-release ca-certificates wget && \
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list && \
    apt install -y php8.2 && apt-get install -y php8.2-curl php8.2-sqlite3 php8.2-common  php8.2-bcmath php8.2-cli\
    php8.2-gd php8.2-intl php8.2-mbstring php8.2-opcache php8.2-xml php8.2-zip php8.2-dev php8.2-apcu \
    curl libmcrypt-dev php8.2-fpm 
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    HASH="$(wget -q -O - https://composer.github.io/installer.sig)" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    apt install -y git nginx nodejs npm && \
    echo  'source /root/.bashrc \n\
           if [[ -z "${IS_INIT}" ]]; then \n\
           echo IS_INIT=1 | cat >> /root/.bashrc \n\
           cd /var/www/core \n\
           composer install && composer update && php artisan migrate && npm install && npm run build && \n\
           chown -R www-data:www-data /var/www/core \n\
           fi' > /root/initial_setup.sh && \
    ln -s /etc/nginx/sites-available/core_conf /etc/nginx/sites-enabled/ && \
    chown root /root/initial_setup.sh && chmod +x /root/initial_setup.sh 
COPY core_conf /etc/nginx/sites-available
COPY core_pool.conf /etc/php/8.2/fpm/pool.d
WORKDIR /var/www/core
COPY ./core .
CMD ["/bin/bash", "-c",  "/root/initial_setup.sh && service php8.2-fpm start && service nginx restart && sleep infinity"]