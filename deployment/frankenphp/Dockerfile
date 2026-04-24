FROM dunglas/frankenphp:latest

WORKDIR /app

RUN install-php-extensions \
	pdo_mysql \
	gd \
	intl \
	zip \
	opcache
