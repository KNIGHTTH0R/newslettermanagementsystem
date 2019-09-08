#!/bin/#!/usr/bin/env bash
chown -R www:www /var/www # Dockerfile sometimes fails to change ownerships, so I added this here to ensure ...
sudo -u www cp .env.docker .env
sudo -u www composer install
sudo -u www php artisan key:generate
sudo -u www php artisan migrate:refresh --seed
