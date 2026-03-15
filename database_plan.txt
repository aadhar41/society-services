

users
- id
- unique_code
- name
- email

------------------------------------------------------

users_details
- id
- user_id
- name
- email

------------------------------------------------------
php artisan make:model Society -mfcsr
php artisan make:observer SocietyObserver --model=Society
php artisan make:request SocietyStoreRequest
php artisan migrate:refresh --path=/database/migrations/2023_03_11_083306_create_societies_table.php
php artisan db:seed --class=SocietySeeder

societies
- id
- unique_code
- user_id [added by]
- name
- address
- contact
- description
- country
- state
- city
- postcode
- status
- created_at
- updated_at

------------------------------------------------------
php artisan make:model Block -mfcsr
php artisan make:observer BlockObserver --model=Block
php artisan make:request BlockStoreRequest
php artisan migrate:refresh --path=/database/migrations/2023_03_12_042315_create_blocks_table.php
php artisan db:seed --class=BlockSeeder

blocks
- id
- unique_code
- user_id [added by]
- society_id
- name
- total_flats
- description
- status
- created_at
- updated_at

php artisan migrate
php artisan db:seed --class=BlockSeeder
------------------------------------------------------
php artisan make:model Plot -mfcsr
php artisan make:observer PlotObserver --model=Plot
php artisan make:request PlotStoreRequest
php artisan migrate:refresh --path=/database/migrations/2023_03_12_083525_create_plots_table.php
php artisan db:seed --class=PlotSeeder

plots
- id
- unique_code
- user_id [added by]
- society_id
- block_id
- name
- total_floors
- total_flats
- description
- status
- created_at
- updated_at

php artisan migrate
php artisan db:seed --class=PlotSeeder

------------------------------------------------------
php artisan make:model Flat -mfcsr
php artisan make:observer FlatObserver --model=Flat
php artisan make:request FlatStoreRequest


flats
- id
- unique_code
- user_id [added by]
- society_id
- block_id
- plot_id
- name [ Owner Name ]
- flat_no
- description
- mobile_no
- property_type [self occupied | rented | locked]
- tenant_name
- tenant_contact
- status
- created_at
- updated_at

php artisan migrate
php artisan migrate:refresh --path=/database/migrations/2023_03_12_083525_create_Flats_table.php
php artisan db:seed --class=FlatSeeder

------------------------------------------------------
php artisan make:model Maintenance -mfcsr
php artisan make:observer MaintenanceObserver --model=Maintenance
php artisan make:request MaintenanceStoreRequest

maintenance
- id
- unique_code
- user_id [added by]
- society_id
- block_id
- plot_id
- flat_id
- type [ 1 => "Monthly", 2 => "Lift", 3 => "donation", 4 => "contribution", 5 => "other"] | [ static array from helper ]
- date [submission date]
- year
- month
- amount
- description
- attachments
- payment_status [done|pending|extra] | [ static array from helper ]
- status
- created_at
- updated_at

php artisan migrate
php artisan migrate:refresh --path=/database/migrations/2023_03_12_083525_create_Flats_table.php
php artisan db:seed --class=MaintenanceSeeder

------------------------------------------------------

php artisan make:model Expense -mfcsr
php artisan make:observer ExpenseObserver --model=Expense
php artisan make:request ExpenseStoreRequest

payments/expenses
- id
- unique_code
- user_id [added by]
- type [ 1 => "Comman Stationary", 2 => "Road Light Repairing", 3 => "Security", 4 => "Electricity", 5 => "Cleaning", 6 => "Other"] | [ static array from helper ]
- total_received
- approx_expense
- balance_remaining
- date
- year
- month
- description
- attachments
- payment_status [done|due]
- status
- created_at
- updated_at

php artisan migrate
php artisan migrate:refresh --path=/database/migrations/2023_03_12_083525_create_Flats_table.php
php artisan db:seed --class=ExpenseSeeder

------------------------------------------------------
php artisan make:model Contact -mfcsr
php artisan make:observer ContactObserver --model=Contact
php artisan make:request ContactStoreRequest

contacts
- id
- unique_code
- user_id [added by]
- type [ 1 => "lift", 2 => "electricity", 3 => "water", 4 => "cleaning", 5 => "sewer", 6 => "security"] | [ static array from helper ]
- name
- mobile [mobile 1]
- contact [mobile 2]
- address
- comments
- attachments
- status
- created_at
- updated_at

php artisan migrate
php artisan migrate:refresh --path=/database/migrations/2023_03_12_083525_create_Flats_table.php
php artisan db:seed --class=ContactSeeder

------------------------------------------------------
php artisan make:model Grievance -mfcsr
php artisan make:observer GrievanceObserver --model=Grievance
php artisan make:request GrievanceStoreRequest

grievance
- id
- unique_code
- user_id [added by]
- complainer_id [user id who register the complain ]
- type [ 1 => "lift", 2 => "electricity", 3 => "water", 4 => "cleaning", 5 => "sewer", 6 => "security"] | [ static array from helper ]
- title
- details
- attachments
- contact [mobile 2]
- approved_by_admin
- date
- time
- status
- created_at
- updated_at

php artisan migrate
php artisan migrate:refresh --path=/database/migrations/2023_03_12_083525_create_Flats_table.php
php artisan db:seed --class=GrievanceSeeder
------------------------------------------------------

php artisan make:model GrievanceComment -mfcsr
php artisan make:observer GrievanceCommentObserver --model=GrievanceComment
php artisan make:request GrievanceCommentStoreRequest

grievance_comments
- id
- unique_code
- user_id [added by]
- grievance_id
- comment
- commenter_id
- attachments
- date
- time
- status
- created_at
- updated_at

php artisan migrate
php artisan migrate:refresh --path=/database/migrations/2023_03_12_083525_create_Flats_table.php
php artisan db:seed --class=GrievanceCommentSeeder

------------------------------------------------------


php artisan make:model Country -mfcsr

php artisan make:model State -mfcsr

php artisan make:model City -mfcsr


php artisan make:observer SocietyObserver --model=Society

php artisan make:request SocietyStoreRequest

php artisan migrate:refresh --path=/database/migrations/2023_03_11_083306_create_societies_table.php


php artisan db:seed --class=SocietySeeder


composer require yajra/laravel-datatables-oracle:"^10.0"
composer require yajra/laravel-datatables-buttons:"^9.0"



TO DO


CREATE TABLE `yii-app`.`posts` ( `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT , `title` VARCHAR(255) NULL , `description` TEXT NULL , `created_by` INT(20) UNSIGNED NULL , `status` ENUM('0','1') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;


SocietyController

SocietyRepositoryInterface.php


php artisan make:controller Api/SocietyController --api

php artisan make:resource SocietyResource

php artisan make:resource UserResource


API Resource Created for Society Module

- Created Society Api Controller
- Created Society Resource and Collection
- Updated Society Relationships in SocietyModelTraits
- Created UserResource

-------------------------------------------
https://morioh.com/p/d8cd425a0b3e

Laravel API Authentication :

STEP - 1 :
composer require tymon/jwt-auth:*


Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,

'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
'JWTFactory' => Tymon\JWTAuthFacades\JWTFactory::class,

- API Resource Society enable and disable added.
- language label, message and placeholders added.
- Routes Updated.
- Flash messages for warning added.
- updateSociety Updated.
- enableRecord, disableRecord Updated.

------------------------------------------- Test Cases -------------------------------------------

vendor/bin/phpunit

php artisan make:test SocietyPageTest

php artisan make:test CreateSocietyTest

php artisan make:test SocietyModuleApiTest

## Running a specific test
php artisan test --filter SocietyPageTest

## Running a specific test
php artisan test --filter CreateSocietyTest
php artisan test --filter SocietyPageTest

php artisan migrate:generate --tables="countries,states,cities"


php artisan make:component ContentHeader

php artisan make:component Alert

php artisan make:component CardTitle
- route
- title
- type

php artisan make:component CardTools
- route
- title
- type

