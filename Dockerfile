# ベースイメージ
FROM php:8.2-fpm
# composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer