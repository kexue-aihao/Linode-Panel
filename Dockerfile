FROM golang:1.24-alpine AS build
WORKDIR /src
COPY go.mod ./
COPY cmd ./cmd
COPY internal ./internal
RUN CGO_ENABLED=0 GOOS=linux GOARCH=amd64 go build -trimpath -ldflags="-s -w" -o /out/linode-panel ./cmd/linode-panel

FROM alpine:3.20
RUN adduser -D -H -s /sbin/nologin linode-panel
WORKDIR /app
COPY --from=build /out/linode-panel /usr/local/bin/linode-panel
RUN mkdir -p /app/data && chown -R linode-panel:linode-panel /app
USER linode-panel
ENV LINODE_PANEL_HOST=0.0.0.0
ENV LINODE_PANEL_PORT=8088
EXPOSE 8088
ENTRYPOINT ["/usr/local/bin/linode-panel"]
