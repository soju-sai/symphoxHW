#!/bin/bash
set -e
service mysql start
mysql < /tmp/init-set.sql
service mysql stop