FROM ubuntu:22.04

LABEL maintainer="Taylor Otwell"

ARG NODE_VERSION=20
ARG POSTGRES_VERSION=15

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

# Zona horaria y actualización
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
 && apt-get update \
 && apt-get install -y gnupg curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 dnsutils librsvg2-bin fswatch

# Repos PHP
RUN mkdir -p /etc/apt/keyrings \
 && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
 && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list

# Repo Nodejs
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
 && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list

# Repo PostgreSQL
RUN curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /etc/apt/keyrings/pgdg.gpg >/dev/null \
 && echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list

# Repor Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /etc/apt/keyrings/yarn.gpg >/dev/null \
 && echo "deb [signed-by=/etc/apt/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list

# Install dependencies
RUN apt-get update \
 && apt-get install -y \
    php8.2-cli php8.2-dev php8.2-fpm \
    php8.2-pgsql php8.2-sqlite3 php8.2-gd php8.2-imagick \
    php8.2-curl php8.2-imap php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-zip php8.2-bcmath php8.2-soap \
    php8.2-intl php8.2-readline php8.2-ldap \
    php8.2-msgpack php8.2-igbinary php8.2-redis php8.2-swoole \
    php8.2-memcached php8.2-pcov php8.2-xdebug \
 && apt-get install -y nginx \
 && apt-get install -y nodejs yarn mysql-client postgresql-client-$POSTGRES_VERSION \
 && npm install -g npm pnpm bun \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
 && apt-get -y autoremove && apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Crear user / group
RUN groupadd -g 33 www-data || true \
 && useradd -u 33 -g www-data www-data || true

# Create Routes
RUN mkdir -p /run/php /var/log/nginx /var/lib/nginx \
 && chmod -R 755 /run/php /var/log/nginx /var/lib/nginx /var/www/html

# Copy config files
COPY nginx.conf /etc/nginx/sites-available/laravel.conf
RUN ln -s /etc/nginx/sites-available/laravel.conf /etc/nginx/sites-enabled/ && rm -f /etc/nginx/sites-enabled/default

COPY www.conf /etc/php/8.2/fpm/pool.d/www.conf
COPY php.ini /etc/php/8.2/fpm/conf.d/99-custom.ini
COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN chmod +x /usr/local/bin/start-container

COPY --chown=www-data:www-data . /var/www/html

EXPOSE 8066

ENTRYPOINT ["start-container"]
