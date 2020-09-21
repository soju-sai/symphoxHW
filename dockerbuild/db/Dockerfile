FROM mysql:5.7.31

ENV MYSQL_USER demouser
ENV MYSQL_ROOT_PASSWORD password
ENV MYSQL_PASSWORD !QAZ2wsx
ENV MYSQL_DATABASE demo

USER root

COPY ./init/init-set.sql /docker-entrypoint-initdb.d/