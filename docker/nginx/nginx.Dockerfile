FROM nginx:latest
RUN apt-get update
RUN apt-get -y install mc
ADD ./docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
ENTRYPOINT ["nginx", "-g", "daemon off;"]
