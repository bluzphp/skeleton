FROM mysql:5.7.12
COPY dumps/1_structure.sql /docker-entrypoint-initdb.d
COPY dumps/2_dump.sql /docker-entrypoint-initdb.d