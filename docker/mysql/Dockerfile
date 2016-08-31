FROM mysql:5.7.12
COPY dumps /docker-entrypoint-initdb.d
RUN chgrp -R mysql /docker-entrypoint-initdb.d