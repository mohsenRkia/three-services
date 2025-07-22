FROM docker.arvancloud.ir/python:3.10.12-bullseye AS builder

WORKDIR /app

# Install required packages
RUN apt-get update && \
    apt-get install -y curl tzdata vim redis-tools pkg-config libgnutls28-dev libmariadb-dev libcurl4-gnutls-dev python3-dev libcairo2-dev unixodbc-dev build-essential && \
    rm -rf /var/lib/apt/lists/*

ENV TZ=Asia/Tehran

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Set environment variables, pip configuration, and pip local repo
RUN export MYSQLCLIENT_CFLAGS=`pkg-config mysqlclient --cflags` && \
    export MYSQLCLIENT_LDFLAGS=`pkg-config mysqlclient --libs`

# Install pip packages
COPY requirements.txt /app
RUN pip install -r requirements.txt
RUN pip install --upgrade --force-reinstall pymupdf
# Copy rest of the files and add env variables
COPY . /app

ARG ENV_FILE
ENV ENV_FILE ${ENV_FILE}

COPY $ENV_FILE .env

EXPOSE 8080

CMD ["python3", "manage.py", "runserver", "0.0.0.0:8080"]