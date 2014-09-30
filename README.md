phpadmin
========

Nice and easy admin for generate backend and implement frontend side also.

Requirements
--------------

- Rewrite module enabled, for example on ubuntu, apache2:

`$sudo a2enmod rewrite`
`$sudo service apache2 restart`

Installation
--------------

- Copy files and folders inside some directory, for example "project1".
- Edit `config/constants.php` #8

`define('BASE_URL', $protocol . ':' . '//' . $_SERVER['SERVER_NAME'] . $port . '/project1/');`

- Edit Database config `config/database.php`

- Load in browser depending your environment maybe: `http://localhost/phpadmin`, `http://localhost/admin`
- Admin Credentials

`admin@gmail.com`
`admin`