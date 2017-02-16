# AWG - Ephemeral Wedding Gallery

AWG is a web gallery app, easy-to-use, responsive, that allows a photographer to show his photos and take orders for prints during the wedding events.

This web gallery app is based on the following technologies:
  - [Symfony](https://symfony.com/download) 3.2
  - HTML/CSS/Jquery
  - [Salvattore](http://salvattore.com)

Thanks to this web gallery, as a photographer, you can:
  - upload your pictures to sell the prints
  - show these pictures to the guests
  - receive orders from the guests
  - check the current value of the carts in progress
  - manage pricing per format and quantity
  - define special discount/pricing, that can time limited
  - manage prints & payments
  - cancel (if not already printed/payed) and reactivate orders

Guests can:
  - manage their account (first/lastname, email, location)
  - see all the pictures in a responsive gallery (from their smartphone)
  - see the active pricing
  - select the picture and specify the quantity per format
  - the price is calculated in realtime
  - confirm the orders (and cancel any order if the print has not been done)
  - track the orders (pending, printed, payed, canceled, completed)
  - cancel orders not printed

More features will come in the future.

### Installation

AWG requires:
  - [Composer](https://getcomposer.org/download/) to run.
  - you may also use [Git](https://git-scm.com/downloads) to clone the project and contribute.
  - a working Apache/PHP/MySQL:
    - example: WAMP/MAMP/LAMP Stack
  - php in command line

Install with Git:
```sh
$ cd yourdirectory
$ git clone https://github.com/stephanfo/AWG
$ cd AWG
```
Install all required dependencies as well as Symfony:
```sh
$ php composer.phar update
```
Edit the file app/config/parameters.yml the parameters according to your MYSQL setup.
```sh
parameters:
    database_host: your_database_host_IP_or_Host
    database_port: your_database_port
    database_name: your_database_name
    database_user: your_database_user
    database_password: your_database_password
```
Edit the file app/config/security.yml to update the password.
```sh
security:
    providers:
        in_memory:
            memory:
                users:
                    admin:
                        password: admin
                        roles: 'ROLE_ADMIN'
```
Update MySQL database and clear cache:
```sh
$ php bin/console doctrine:schema:update --force
$ php bin/console cache:clear --env prod ; php bin/console cache:clear
```
### Assistance
For any assistance, issue, please post on the [AWG](https://github.com/stephanfo/AWG) Github repository.
