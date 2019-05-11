FROM node:8
COPY . /home/node/app
WORKDIR /home/node/app
RUN npm install -g
EXPOSE 80
CMD ["node","app.js"]