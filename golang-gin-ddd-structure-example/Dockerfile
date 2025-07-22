# Start from golang base image
FROM docker.arvancloud.ir/golang:1.24-alpine3.21 as builder

# Working directory
WORKDIR /app

# add go proxy
ENV GOPROXY=https://goproxy.io,direct

# Copy go mod and sum files
COPY go.mod go.sum ./

# Download all dependencies
RUN go mod download

# Copy everythings
COPY . .

# Build the Go app
RUN CGO_ENABLED=0 GOOS=linux go build -a -installsuffix cgo -o main .


# Start a new stage from scratch
FROM alpine:3.21

RUN apk add --no-cache ca-certificates tzdata

WORKDIR /root/

ARG ENV_FILE=.env
ENV ENV_FILE=${ENV_FILE}
ENV TZ=Asia/Tehran

# Copy the Pre-built binary file from the previous stage. Also copy config yml file
COPY --from=builder /app/main .

COPY $ENV_FILE ./.env

# Expose port 8000 to the outside world
EXPOSE 8000

#Command to run the executable
CMD ["./main", "serve"]
