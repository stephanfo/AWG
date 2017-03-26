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
  - manage prints (using LP & CUPS on Unix OS) & payments
  - cancel (if not already printed/payed) and reactivate orders
  - send the files paid by email to the user (can be disable via config)

Guests can:
  - manage their account (first/lastname, email, location)
  - see all the pictures in a responsive gallery (from their smartphone)
  - see the active pricing
  - select the picture and specify the quantity per format
  - the price is calculated in realtime
  - confirm the orders (and cancel any order if the print has not been done)
  - track the orders (pending, printed, payed, canceled, completed)
  - cancel orders not printed
  - download the photo file if the order is paid (can be disable via config)
  - change the language of the user interface (French/English)

More features will come in the future.

### Screenshots
Guest login

![Alt Guest login](http://stephane.ratelet.fr/other/AWG/profil.png)

Guest gallery

![Alt Guest gallery](http://stephane.ratelet.fr/other/AWG/gallery.png)

Guest order confirmation

![Alt Guest order confirmation](http://stephane.ratelet.fr/other/AWG/confirmation.png)

Guest pricing information

![Alt Guest pricing information](http://stephane.ratelet.fr/other/AWG/tarifs.png)

Guest orders list

![Alt Guest orders list](http://stephane.ratelet.fr/other/AWG/orders.png)

Guest order details

![Alt Guest order details](http://stephane.ratelet.fr/other/AWG/order-details.png)

Admin gallery

![Alt Admin gallery](http://stephane.ratelet.fr/other/AWG/admin_gallery.png)

Admin upload

![Alt Admin upload](http://stephane.ratelet.fr/other/AWG/admin_upload.png)

Admin pricing

![Alt Admin pricing](http://stephane.ratelet.fr/other/AWG/admin_pricing.png)

Admin orders list

![Alt Admin orders list](http://stephane.ratelet.fr/other/AWG/admin_orders.png)

Admin order details

![Alt Admin order details](http://stephane.ratelet.fr/other/AWG/admin_order-details.png)

### Installation

AWG requires:
  - [Composer](https://getcomposer.org/download/).
  - [Git](https://git-scm.com/downloads) to clone the project and contribute.
  - Apache/PHP/MySQL:
    - example: WAMP/MAMP/LAMP stacks
  - PHP in command line

Install with Git:
```sh
$ cd yourdirectory
$ git clone https://github.com/stephanfo/AWG
$ cd AWG
```
Install all required dependencies:
```sh
$ php composer.phar update
```
or depending on your installation of composer
```sh
$ composer update
```

If not already done at the previous step, edit parameters in the file app/config/parameters.yml.
```sh
parameters:
    database_host: your_database_host_IP_or_Host
    database_port: your_database_port
    database_name: your_database_name
    database_user: your_database_user
    database_password: your_database_password
    admin_password: your_admin_password
```
Update MySQL database and clear cache:
```sh
$ php bin/console doctrine:schema:update --force
$ php bin/console cache:clear --env prod ; php bin/console cache:clear
```
You will also need to update your server configuration (Vhost, DNS, ...) to allow guests to access the gallery.

### Emails
If you activate this functionality to offer your files (check the config menu), the user will be able to download the paid photos through the order page, and admin can send the photos by email in the order page.
If you use the emailing feature, all emails will be spooled, waiting for one of these 3 following command lines to send the queue.
```sh
$ php bin/console swiftmailer:spool:send --env=prod
$ php bin/console swiftmailer:spool:send --message-limit=10 --env=prod
$ php bin/console swiftmailer:spool:send --time-limit=10 --env=prod
```

### Assistance
For any assistance, issue, please post on the [AWG](https://github.com/stephanfo/AWG) Github repository.