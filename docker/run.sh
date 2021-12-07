#!/bin/bash

echo -ne '#                     (3%)\r'

# Delete all containers
list=$(docker ps -a -q)
docker stop $list
docker rm -f $list

## Remove All Volumes
list=$(docker volume ls -q)
docker volume rm $list

## Remove All Network
list=$(docker network ls -f type=custom -q)
docker network rm $list

# Rebuild Sail (docker)
bash ./vendor/bin/sail up -d

echo -ne '###                        (13%)\r'
sleep 2
echo -ne '######                     (33%)\r'
sleep 2
echo -ne '################           (66%)\r'
sleep 2
echo -ne '#################          (68%)\r'
sleep 2
echo -ne '##################         (71%)\r'
sleep 2
echo -ne '####################       (78%)\r'
sleep 2
echo -ne '#####################      (81%)\r'
sleep 2
echo -ne '######################     (84%)\r'
sleep 2
echo -ne '#######################    (87%)\r'
sleep 2
echo -ne '########################   (90%)\r'
sleep 2
echo -ne '#########################  (93%)\r'


# Database migrate
# bash ./vendor/bin/sail artisan migrate:refresh --seed

#echo -ne '###########################(100%)\r'
echo -ne '\n'
echo  'Enjoy! :)  http://0.0.0.0:7780/'
echo -ne '\n'
echo -ne '\n'
