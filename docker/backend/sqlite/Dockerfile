FROM alpine:3.22

RUN apk add --no-cache bash coreutils sqlite

WORKDIR /var/sqlite

COPY docker/backend/sqlite/rootfilesystem/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

VOLUME ["/var/sqlite"]

ENTRYPOINT ["/bin/bash", "/entrypoint.sh"]
