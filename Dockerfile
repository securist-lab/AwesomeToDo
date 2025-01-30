FROM php:8.2-fpm

WORKDIR /var/www/
COPY ./data ./
RUN ls -la
RUN chmod 640 /var/www/data/db/todo.db \
    && chmod 750 /var/www/data/db \
    && chown www-data:www-data /var/www/data/db \
    && chown www-data:www-data /var/www/data/db/todo.db

# 作業ディレクトリの設定
WORKDIR /var/www/html

# 必要なパッケージのインストール
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip curl git \
    && rm -rf /var/lib/apt/lists/*

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# プロジェクトファイルをコンテナにコピー
COPY ./src/composer.json ./src/composer.lock ./

# Composerの依存関係インストール
RUN composer install --no-dev --optimize-autoloader

# アプリケーションソースコードのコピー
COPY ./src .

# PHP-FPMを起動する
CMD ["php-fpm"]
