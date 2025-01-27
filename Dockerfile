FROM php:8.2-fpm

# 作業ディレクトリの設定
WORKDIR /var/www/html

# 必要なパッケージのインストール
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip curl git \
    && rm -rf /var/lib/apt/lists/*

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# プロジェクトファイルをコンテナにコピー
COPY . .

# Composerの依存関係インストール
RUN composer install

# デフォルトのコマンド
CMD ["php", "-S", "0.0.0.0:8000"]
