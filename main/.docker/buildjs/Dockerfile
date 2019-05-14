FROM node:9.2.0

RUN apt-get update && \
  apt-get install zip unzip -y && \
  apt-get clean autoclean && \
  apt-get autoremove -y && \
  npm install -g metascript && \
  npm install --global gulp