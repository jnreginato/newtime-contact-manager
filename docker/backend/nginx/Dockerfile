FROM nginx:alpine

# Expose NGINX ports (HTTP and HTTPS)
EXPOSE 80 443

# Copy configuration files to the container
COPY docker/backend/nginx/rootfilesystem/ /

# Install OpenSSL and set execute permissions for the entrypoint script
RUN apk add --no-cache openssl && \
    chmod +x /entrypoint.sh

# Set the entrypoint script
ENTRYPOINT ["/entrypoint.sh"]

# Run NGINX in the foreground
CMD ["nginx", "-g", "daemon off;"]
