#!/bin/bash
DOCKERXDEBUG_FILE="/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
XDEBUG_FILE="/usr/local/etc/php/conf.d/xdebug.ini"

if [ -f "$XDEBUG_FILE" ]; then
  mv "$XDEBUG_FILE" "$XDEBUG_FILE.disabled"
  mv "$DOCKERXDEBUG_FILE" "$DOCKERXDEBUG_FILE.disabled"
  echo "Xdebug disabled"
else
  mv "$XDEBUG_FILE.disabled" "$XDEBUG_FILE"
  mv "$DOCKERXDEBUG_FILE.disabled" "$DOCKERXDEBUG_FILE"
  echo "Xdebug enabled"
fi

kill -USR2 1

