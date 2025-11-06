FROM node:22-alpine AS build

WORKDIR /app

# Copy depndency files and install
COPY frontend/package*.json ./
RUN npm install

# Copy source files and build
COPY frontend .
RUN npm run build

# Final stage - NGINX to serve the SPA
FROM nginx:alpine

# Copy built files from the build stage
COPY --from=build /app/dist /usr/share/nginx/html

# Copy custom NGINX configuration (SPA with fallback)
COPY docker/frontend/nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 5173 80

CMD ["nginx", "-g", "daemon off;"]
