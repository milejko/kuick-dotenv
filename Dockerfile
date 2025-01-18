# syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.3

FROM milejko/php:${PHP_VERSION}

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage
