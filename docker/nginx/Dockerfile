FROM nginx:1.10.1
COPY default.conf.template /

RUN sed -i 's/user *nginx;/user www-data;/g' /etc/nginx/nginx.conf \
    && sed -i 's/${SERVER_NAME}/bluz.com/g' /default.conf.template \
    && sed -i 's/${DOC_ROOT}/\/var\/www/g' /default.conf.template \
    && cp default.conf.template /etc/nginx/conf.d/default.conf