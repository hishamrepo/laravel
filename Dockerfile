# syntax = docker/dockerfile:experimental
FROM python:3.7-buster as builder

## virtualenv setup
ENV VIRTUAL_ENV=/opt/venv
RUN python3 -m venv $VIRTUAL_ENV
ENV PATH="$VIRTUAL_ENV/bin:$PATH"

WORKDIR /app
COPY requirements.txt requirements.txt

RUN pip3 install --upgrade pip

RUN pip3 install wheel && pip3 install -r requirements.txt
FROM python:3.7-slim-buster as runtime
# Create user to run as
RUN adduser --disabled-password flaskuser
RUN apt-get -y update && apt-get -y install default-libmysqlclient-dev 

COPY --from=builder /opt/venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"
RUN mkdir -p /app/migrations/
RUN chown -R flaskuser:flaskuser /app/migrations/

COPY . /app

RUN chmod +x /app/start-server.sh

WORKDIR /app
USER flaskuser

CMD ["sh", "/app/start-server.sh"]
