PHPAdmin
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

`define('BASE_URL', $protocol . ':' . '//' . $_SERVER['SERVER_NAME'] . $port . 'project1');`

- Edit Database config `config/database.php`

- Load in browser depending your environment maybe: 
    
    `http://localhost/project1`
    
    `http://localhost/project1/admin`

- Admin Credentials

    `admin@gmail.com`
    
    `admin`
    
** Basic concepts and structure for tables recomended **
- Database Table names.
    must be in lowercase and singular, for example:

    city, company, gym.
- Table column names, must be in lowercase you need a status of type ENUM if you may bot want delete permanently, see examples below.

- Table referenced names must be of format: tablename_id, ending with "_id" after table name in lowercase.

- 
    
Examples
--------


1. ** Building a simple admin for a table called gym referencing two tables: city/company **
 
```
CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `status` enum('active','deleted') DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
```


```
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `style` enum('pfc','mfc','psf','nn') DEFAULT 'nn',
  `name` varchar(250) NOT NULL,
  `logo` varchar(500) NOT NULL,
  `logo_small` varchar(500) NOT NULL,
  `logo_big` varchar(500) NOT NULL,
  `marker` varchar(500) NOT NULL,
  `permalink` varchar(500) NOT NULL,
  `status` enum('active','deleted') DEFAULT 'active',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
```

```
CREATE TABLE IF NOT EXISTS `gym` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `short_name` varchar(100) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` varchar(500) NOT NULL,
  `permalink` varchar(500) NOT NULL,
  `status` enum('active','incoming','deleted') DEFAULT 'active',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `city_id` (`city_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
```

** Import that tables into database and build three next models inside `models` folder **
`city_model.php`
```
<?php

require_once(BASE_PATH . 'system/module.php');
require_once(BASE_PATH . 'system/auth.php');

class CityModel extends Module
{
    function __construct()
    {
        // Calls to parent constructor with table name and table_id column name
        parent::__construct('city', 'id');
        
        // You can comment this line if no prefix are used
        $this->phisical_table = DB_PREFIX . 'city';

        // key is exactly column name, lang function uses laguage/en.php by default you can make change the logic
        $this->addField(
            array(
                "key" => "id",
                "comparator" => 'IN',
                "name" => lang("ID"),
                "type" => "hidden"
            )
        );

        // Validation format is like jqyer validation but in php format
        $this->addField(
            array(
                "key" => "name",
                // Default comparator when making background search yout can specify, see filters line
                "comparator" => "LIKE",
                "name" => lang("Name"),
                "type" => "text", // supported types are: 
                /*
                text
                textarea
                password
                upload
                editor
                date
                daterange
                hidden
                email
                dropdown -> for ENUM types or external table
                composite -> composite table(C) starting from (A) like:  A -> C <- B (You cannot add more C rows)
                inline -> simple composite table A, B then A -> C <- B (You can only add or edit C rows)
                */
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Name is required')
                    )
                ),
                // You can customize attributtes for each here
                'attr' => array(
                    array('name' => 'placeholder', 'value' => lang('Name') )
                )
            )
        );
        
        // Added status column for persist data after deleting (deleted row = deleted value for this column)
        $this->addField(
            array(
                "key" => "status",
                "comparator" => '!='
            )
        );

        // This compares with != like described above in comparator field parameter
        $this->filters = array(
            'status' => 'deleted'
        );

        // You can access to current user authenticated as below, roles are specified in user table, you can add more roles there also in user_model.php
        if(Auth::$user['role'] != 'sadmin') {
            $this->filters = array(
                'status' => 'deleted'
            );
        }
        
        // Defines what be showed in form and grid alt is set the order
        $this->addGroup('form', array('id', 'name'));
        $this->addGroup('grid', array('id', 'name'));
    }
}
```

`company_model.php`

```
<?php

require_once(BASE_PATH . 'system/module.php');
require_once(BASE_PATH . 'system/auth.php');

class CompanyModel extends Module
{
    function __construct()
    {
        parent::__construct('company', 'id');

        $this->phisical_table = DB_PREFIX . 'company';

        $this->addField(
            array(
                "key" => "id",
                "name" => lang("ID"),
                "type" => "hidden"
            )
        );

        $this->addField(
            array(
                "key" => "name",
                "comparator" => "=",
                "name" => lang("Name"),
                "type" => "text",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Name is required')
                    )
                ),
                'attr' => array(
                    array('name' => 'placeholder', 'value' => lang('Name') )
                ),
                'group' => 'column1'
            )
        );

        $this->addField(
            array(
                "key" => "style",
                "comparator" => "LIKE",
                "name" => lang("Style"),
                "type" => "dropdown",
                // Setting up values for each enum type supported un table structure
                "values" => array(
                    array('id' => '', 'name' => lang('Select Style')),
                    array('id' => 'pfc', 'name' => lang('PFC')),
                    array('id' => 'mfc', 'name' => lang('MFC')),
                    array('id' => 'psf', 'name' => lang('PSF'))
                ),
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Style is required')
                    )
                ),
                // You can organize fields in columns put any name here or leave empty for no columns
                'group' => 'column1'
            )
        );

        $this->addField(
            array(
                "key" => "logo",
                "name" => lang("Logo"),
                "type" => "upload",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Logo is required')
                    )
                ),
                // settings are required for upload/editor type field
                'settings' => array(
                    // url for uploader admin/[controller_name]/upload
                    'url' => BASE_URL . 'admin/company/upload',
                    // absolute path for upload dir
                    'path' => BASE_PATH . 'uploads' . DIRECTORY_SEPARATOR . 'logos' . DIRECTORY_SEPARATOR,
                    // Download path by default are using timthumb for thumbnails in framework.js
                    'download' => BASE_URL . 'uploads/logos/'
                ),
                'group' => 'column2'
            )
        );

        $this->addField(
            array(
                "key" => "logo_small",
                "name" => lang("Logo Small"),
                "type" => "upload",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Logo Small is required')
                    )
                ),
                'settings' => array(
                    'url' => BASE_URL . 'admin/company/upload',
                    'path' => BASE_PATH . 'uploads' . DIRECTORY_SEPARATOR . 'logos' . DIRECTORY_SEPARATOR,
                    'download' => BASE_URL . 'uploads/logos/'
                ),
                'group' => 'column1'
            )
        );

        /* Inline example, you can add/edit referenced external table and external columns specified en model parameter
        $this->addField(
            array(
                "key" => "images",
                "type" => "inline",
                "model" => new Company_imagesModel(),
                'field_group' => lang('Add Image'),
                "group" => "column1",
            )
        );
        */

        $this->addField(
            array(
                "key" => "logo_big",
                "name" => lang("Logo Big"),
                "type" => "upload",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Logo Big is required')
                    )
                ),
                'settings' => array(
                    'url' => BASE_URL . 'admin/company/upload',
                    'path' => BASE_PATH . 'uploads' . DIRECTORY_SEPARATOR . 'logos' . DIRECTORY_SEPARATOR,
                    'download' => BASE_URL . 'uploads/logos/'
                ),
                'group' => 'column2'
            )
        );

        $this->addField(
            array(
                "key" => "marker",
                "name" => lang("Marker for map"),
                "type" => "upload",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Marker is required')
                    )
                ),
                'settings' => array(
                    'url' => BASE_URL . 'admin/company/upload',
                    'path' => BASE_PATH . 'uploads' . DIRECTORY_SEPARATOR . 'markers' . DIRECTORY_SEPARATOR,
                    'download' => BASE_URL . 'uploads/markers/'
                ),
                'group' => 'column2'
            )
        );

        $this->addField(
            array(
                "key" => "status"
            )
        );

        $this->filters = array(
            DB_PREFIX . 'company.status!=' => 'deleted'
        );

        $this->pagination = array(
            'start' => 0,
            'per_page' => 10
        );

        $this->addGroup('form', array('id', 'style', 'name', 'logo_small', 'images', 'logo', 'logo_big', 'marker'));
        $this->addGroup('grid', array('id', 'style', 'name', 'logo_small', 'logo', 'marker'));
    }
    
    /* Callback before saving data (add/edit), for example sets permalink  */
    function callback_before_save($id, $data) {

        $data['permalink'] = toAscii($data['name']);

        return $data;
    }
}
```

```
<?php

require_once(BASE_PATH . 'system/module.php');
require_once(BASE_PATH . 'system/auth.php');

require_once(BASE_PATH . 'models/city_model.php');
require_once(BASE_PATH . 'models/company_model.php');

class GymModel extends Module
{
    function __construct()
    {
        parent::__construct('gym', 'id');

        $this->phisical_table = DB_PREFIX . 'gym';
        /* customizing for dropdown when referencing this model on a dropdown field */
        $this->field_custom_name_dropdown = "CONCAT(company.name, ' - ', gym.name)";

        $this->addField(
            array(
                "key" => "id",
                "name" => lang("ID"),
                "type" => "hidden"
            )
        );

        $this->addField(
            array(
                "key" => "name",
                "comparator" => "=",
                "name" => lang("Name"),
                "type" => "text",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Name is required')
                    )
                ),
                'attr' => array(
                    array('name' => 'placeholder', 'value' => lang('Name') )
                ),
                'group' => 'column1',
                'filtrable' => true,
            )
        );

        $this->addField(
            array(
                "key" => "city_id",
                "comparator" => "=",
                "name" => lang("City"),
                "type" => "dropdown",
                // instead on ENUM here are using a external table for reference city_id column
                "model" => new CityModel(),
                "default" => array(
                    array('id' => '', 'name' => lang('Select City'))
                ),
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('City is required')
                    )
                ),
                'attr' => array(
                    array('name' => 'placeholder', 'value' => lang('City') )
                ),
                'group' => 'column1',
                // This paramter creates a searc bar and enable this column for make searches
                'filtrable' => true,
                // You cam agrupate by tabs, then tab bar appears on top of form
                'tab' => lang('Main Settings')
            )
        );

        $this->addField(
            array(
                "key" => "company_id",
                "comparator" => "=",
                "name" => lang("Company"),
                "type" => "dropdown",
                "model" => new CompanyModel(),
                "default" => array(
                    array('id' => '', 'name' => lang('Select Company'))
                ),
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Company is required')
                    )
                ),
                'attr' => array(
                    array('name' => 'placeholder', 'value' => lang('Company') )
                ),
                'group' => 'column1',
                'filtrable' => true
            )
        );

        $this->addField(
            array(
                "key" => "short_name",
                "comparator" => "=",
                "name" => lang("Short name"),
                "type" => "text",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Short name is required')
                    )
                ),
                'attr' => array(
                    array('name' => 'placeholder', 'value' => lang('Short name') )
                ),
                'group' => 'column1'
            )
        );

        $this->addField(
            array(
                "key" => "phone",
                "comparator" => "=",
                "name" => lang("Phone"),
                "type" => "text",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Phone is required')
                    )
                ),
                'attr' => array(
                    array('name' => 'placeholder', 'value' => lang('Phone') )
                ),
                'group' => 'column1'
            )
        );

        $this->addField(
            array(
                "key" => "email",
                "comparator" => "=",
                "name" => lang("Email"),
                "type" => "text",
                'validation' => array(
                    'rules' => array(
                        'required' => true,
                        'email' => true
                    ),
                    'messages' => array(
                        'required' => lang('Email is required'),
                        'email' => lang('Invalid email address')
                    )
                ),
                'attr' => array(
                    array('name' => 'placeholder', 'value' => lang('Email') )
                ),
                'group' => 'column1'
            )
        );

        $this->addField(
            array(
                "key" => "address",
                "comparator" => "=",
                "name" => lang("Address"),
                "type" => "textarea",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Address is required')
                    )
                ),
                'attr' => array(
                    array('name' => 'placeholder', 'value' => lang('Address') ),
                    array('name' => 'class', 'value' => 'full-width' )
                ),
                'group' => 'column1'
            )
        );

        $this->addField(
            array(
                "key" => "location",
                "comparator" => "=",
                "name" => lang("Location"),
                // This type will show a google map
                "type" => "map",
                'validation' => array(
                    'rules' => array(
                        'required' => true
                    ),
                    'messages' => array(
                        'required' => lang('Location is required')
                    )
                ),
                // Settings are required for map type in this format :
                "settings" => array(
                    'type' => 'selector',
                    'lat_key' => 'latitude',
                    'lon_key' => 'longitude',
                    'center' => array(
                        'latitude' => -17.130948,
                        'longitude' => -64.753418
                    ),
                    'zoom' => 18,
                    'map_type' => 'google.maps.MapTypeId.ROADMAP'
                ),
                'group' => 'column2'
            )
        );

        $this->addField(
            array(
                "key" => "status",
                'name' => lang('Status'),
                'type' => 'dropdown',
                'values' => array(
                    array('id' => '', 'name' => lang('Select Status')),
                    array('id' => 'active', 'name' => lang('Active')),
                    array('id' => 'incoming', 'name' => lang('Incoming'))
                ),
                'filtrable' => true,
                "group" => "column1"
            )
        );
        
        // this for show company name in grid
        $this->addField(
            array(
                "grid_key" => "company",
                "name" => lang('Company'),
                "field" => 'company_id'
            )
        );

        $this->filters = array(
            DB_PREFIX . 'gym.status!=' => 'deleted'
        );
        
        // This setup pagination for grid
        $this->pagination = array(
            'start' => 0,
            'per_page' => 10
        );

        $this->addGroup('form', array('id', 'city_id', 'company_id', 'name', 'short_name', 'email', 'phone', 'address', 'status', 'location'));
        $this->addGroup('grid', array('id', 'company', 'name', 'short_name', 'status'));
    }

    function callback_before_save($id, $data) {

        $data['permalink'] = toAscii($data['name']);

        return $data;
    }

    // Example for custom methods for frontend
    function getFor($company_id) {

        $sql = "
        SELECT gym.id, gym.permalink, gym.name, gym.short_name, gym.status, company.permalink as company_permalink, company.id as company_id, company.name as company, city.name as city
            FROM gym
            INNER JOIN company ON(company.id=gym.company_id)
            INNER JOIN city ON(city.id=gym.city_id)

            WHERE gym.company_id='$company_id' AND gym.status != 'deleted'
            ORDER BY gym.short_name ASC";

        return $this->fetch_result($sql);
    }
}
```

** now create three controllers inside `admin` folder **
controllers must be each one in a separated file, name must be "table name" in lower case

```
<?php

require_once(BASE_PATH . 'controllers/admin/backend.php');
require_once(BASE_PATH . 'models/city_model.php');

class City extends Backend {

    function __construct() {

        parent::__construct();

        $this->model = new CityModel();
    }
}
```

```
<?php

require_once(BASE_PATH . 'controllers/admin/backend.php');
require_once(BASE_PATH . 'models/company_model.php');

class Company extends Backend {

    function __construct() {

        parent::__construct();

        $this->model = new CompanyModel();
    }
}
```

```
<?php

require_once(BASE_PATH . 'controllers/admin/backend.php');
require_once(BASE_PATH . 'models/gym_model.php');

class Gym extends Backend {

    function __construct() {

        parent::__construct();

        $this->model = new GymModel();
    }
}
```

yes it is very simple, extending of Backend you have session control redirects to login if are not logged, chek home controller for no session control

** Now you are ready to add modules in the admin panel **
1. Open dashboard controller and add something like next code:
```
<?php

require_once(BASE_PATH . 'controllers/admin/backend.php');

//require_once(BASE_PATH . 'models/dashboard.php');
require_once(BASE_PATH . 'models/user_model.php');
require_once(BASE_PATH . 'models/gym_model.php');
require_once(BASE_PATH . 'models/city_model.php');
require_once(BASE_PATH . 'models/company_model.php');

class Dashboard extends Backend
{

    function __construct()
    {

        parent::__construct();

        //$this->model = new DashboardModel();
    }

    function index()
    {

        global $lang;
        $userModule         = new UserModel();
        $cityModule         = new CityModel();
        $gymModule          = new GymModel();
        $companyModel       = new CompanyModel();
        
        $data['title'] = lang("Admin Panel");
        
        if (Auth::$user['role'] == 'sadmin')
            $data['modules']['user'] = array(
                'model' => $userModule->purgeModel(),
                'settings' => array(
                    'container' => '#page',
                    'class' => 'page'
                ),
                'containers' => array(
                    'modal' => array(
                        'settings' => array()
                    ),
                    'form' => array(
                        'settings' => array()
                    ),
                    'grid' => array(
                        'settings' => array(
                            'actions' => array(
                                array('type' => 'edit', 'text' => lang('Edit'), 'icon' => ''),
                                array('type' => 'delete', 'text' => lang('Delete'), 'icon' => '')
                            )
                        )
                    ),
                    'toolbar' => array(
                        'settings' => array(
                            'actions' => array(
                                array(
                                    'type' => 'text',
                                    'title' => lang('Users Manager'),
                                    'template' => 'h2'
                                ),
                                array(
                                    'type' => 'text',
                                    'title' => lang('Manage users and manage roles'),
                                    'template' => 'h5'
                                ),
                                array(
                                    'type' => 'break'
                                ),
                                array(
                                    'type' => 'button',
                                    'title' => lang('New User'),
                                    'action' => 'new'
                                )
                            )
                        )
                    )
                )
            );

        if (Auth::$user['role'] == 'sadmin')
            $data['modules']['city'] = array(
                'model' => $cityModule->purgeModel(),
                'settings' => array(
                    'container' => '#page',
                    'class' => 'page'
                ),
                'containers' => array(
                    'modal' => array(
                        'settings' => array()
                    ),
                    'form' => array(
                        'settings' => array()
                    ),
                    'grid' => array(
                        'settings' => array(
                            'actions' => array(
                                array('type' => 'edit', 'text' => lang('Edit'), 'icon' => ''),
                                array('type' => 'delete', 'text' => lang('Delete'), 'icon' => '')
                            )
                        )
                    ),
                    'toolbar' => array(
                        'settings' => array(
                            'actions' => array(
                                array(
                                    'type' => 'text',
                                    'title' => lang('City Manager'),
                                    'template' => 'h2'
                                ),
                                array(
                                    'type' => 'text',
                                    'title' => lang('Manage cities'),
                                    'template' => 'h5'
                                ),
                                array(
                                    'type' => 'break'
                                ),
                                array(
                                    'type' => 'button',
                                    'title' => lang('New City'),
                                    'action' => 'new'
                                )
                            )
                        )
                    )
                )
            );
        );
        
        if (Auth::$user['role'] == 'sadmin')
            $data['modules']['gym'] = array(
                'model' => $gymModule->purgeModel(),
                'settings' => array(
                    'container' => '#page',
                    'class' => 'page'
                ),
                'containers' => array(
                    'modal' => array(
                        'settings' => array()
                    ),
                    'form' => array(
                        'settings' => array()
                    ),
                    'grid' => array(
                        'settings' => array(
                            'actions' => array(
                                array('type' => 'edit', 'text' => lang('Edit'), 'icon' => ''),
                                array('type' => 'delete', 'text' => lang('Delete'), 'icon' => '')
                            )
                        )
                    ),
                    'toolbar' => array(
                        'settings' => array(
                            'actions' => array(
                                array(
                                    'type' => 'text',
                                    'title' => lang('Gym manager'),
                                    'template' => 'h2'
                                ),
                                array(
                                    'type' => 'text',
                                    'title' => lang('Manage gym'),
                                    'template' => 'h5'
                                ),
                                array(
                                    'type' => 'break'
                                ),
                                array(
                                    'type' => 'button',
                                    'title' => lang('New Gym'),
                                    'action' => 'new'
                                )
                            )
                        )
                    )
                )
            );
            
            
            if (Auth::$user['role'] == 'sadmin')
            $data['modules']['company'] = array(
                'model' => $companyModel->purgeModel(),
                'settings' => array(
                    'container' => '#page',
                    'class' => 'page'
                ),
                'containers' => array(
                    'modal' => array(
                        'settings' => array()
                    ),
                    'form' => array(
                        'settings' => array()
                    ),
                    'grid' => array(
                        'settings' => array(
                            'actions' => array(
                                array('type' => 'edit', 'text' => lang('Edit'), 'icon' => ''),
                                array('type' => 'delete', 'text' => lang('Delete'), 'icon' => '')
                            )
                        )
                    ),
                    'toolbar' => array(
                        'settings' => array(
                            'actions' => array(
                                array(
                                    'type' => 'text',
                                    'title' => lang('Company manager'),
                                    'template' => 'h2'
                                ),
                                array(
                                    'type' => 'text',
                                    'title' => lang('Manage companies'),
                                    'template' => 'h5'
                                ),
                                array(
                                    'type' => 'break'
                                ),
                                array(
                                    'type' => 'button',
                                    'title' => lang('New Company'),
                                    'action' => 'new'
                                )
                            )
                        )
                    )
                )
            );
            
        
        // View obect for render content
        $this->view = new View();

        $data['modules'] = $this->view->putDefaults($data['modules']);
        $data['sidebar'] = array();

        // Adds user module to sidebar
        if (isset($data['modules']['user']))
            $data['sidebar'][] = array(
                'title' => lang('Manage Users'),
                'attr' => array(
                    'onclick' => 'javascript: ;'
                ),
                'icon' => '',
                // this must be table name
                'module' => 'user'
            );

        if (isset($data['modules']['company']))
            $data['sidebar'][] = array(
                'title' => lang('Manage Companies'),
                'attr' => array(
                    'onclick' => 'javascript: ;'
                ),
                'icon' => '',
                'module' => 'company'
            );
            
        $data['language'] = $lang;
        
        // Sets layout for page inside views folder, check depending css/js 
        $this->view->add('admin/layouts/backend', $data);

        $this->view->render();
        
        }
}
```

** Now enjoy refresh admin, you can setup several interfaces like next screens: **
