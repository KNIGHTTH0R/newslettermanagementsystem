#!/bin/#!/usr/bin/env bash

sudo -u www composer install
sudo -u www cp .env.docker .env
sudo -u www php artisan key:generate
sudo -u www php artisan config:cache
sudo -u www php artisan migrate --seed
cp supervisor/laravel-worker.conf /etc/supervisor/conf.d/
supervisord
