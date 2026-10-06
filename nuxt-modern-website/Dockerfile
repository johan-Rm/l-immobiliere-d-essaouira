# Dockerfile pour Nuxt.js (Node 16)
FROM node:16-bullseye

WORKDIR /app

COPY package.json yarn.lock ./
RUN yarn install --frozen-lockfile

COPY . .

EXPOSE 3000

CMD ["yarn", "dev"]
