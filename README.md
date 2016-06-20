#Тэстовый проект "Плэйлист"#
=========

Web-версия проекта доступна по адресу: http://ec2-54-149-179-84.us-west-2.compute.amazonaws.com/

##Установка##

Для начала нужно установить зависимости через composer

`composer install`

Затем произвести миграцию

`php bin/console doctrine:migrations:migrate`

И установить зависимости для frontend используя npm.

`cd web/assets && npm install`

Затем собрать frontend пр помощи webpack 

`node build`