FROM mariadb:10.4
COPY ./docker/mysql/my.cnf /etc/mysql/my.cnf
RUN chmod 0444 /etc/mysql/my.cnf
#ENTRYPOINT mysqld