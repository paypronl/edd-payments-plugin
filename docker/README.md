# Paypro docker for easy digital downloads (wordpress)

base image: [Official wordpress docker image](https://hub.docker.com/_/wordpress)

## Requirements

- ports `80`, `8080`, `3306` are available
- [docker-compose](https://docs.docker.com/compose/install/)

## Start docker

    docker-compose up -d

After it is installed open [wordpress - http://localhost:8080](http://localhost:8080)

- Database name: `paypro_edd_db`
- Database user: `paypro_edd_user`
- Database password: `paypro`

## Installing easy digital downloads

You can install easy digital downloads through our script.

    docker exec <container-name OR container-id> /.install_edd.sh

## Installing Paypro edd-payments-plugin

If you haven't already clone/download this repository.

open the terminal in the cloned/downloaded repository

### copy the `repo` into the container

    docker cp ./ <container-id OR container-name>/var/www/html/wp-content/plugins/paypro-gateways-edd
