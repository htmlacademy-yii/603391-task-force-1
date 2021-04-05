FROM nginx:stable
ADD ./docker/nginx/conf.d/default.nginx /etc/nginx/conf.d/default.conf
ENTRYPOINT ["nginx", "-g", "daemon off;"]
