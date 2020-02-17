FROM node:10.15.0-alpine

ENV NODE_ENV=development
RUN apk --update add curl

ADD src/ /app
WORKDIR /app
