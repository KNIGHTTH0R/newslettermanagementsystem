#!/bin/#!/usr/bin/env bash
composer install
cp .env.docker .env
php artisan key:generate
php artisan migrate:refresh --seed
