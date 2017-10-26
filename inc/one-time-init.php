<?php

require_once(SITE_ROOT . "/inc/global.php");

function createTables(){
global $wpdb;

$charset_collate = $wpdb->get_charset_collate();

// create user roles table

$table_user_roles = _ROLE_TABLE_;
$query = "CREATE TABLE IF NOT EXISTS $table_user_roles (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   name nvarchar(10) NOT NULL,
   authority tinyint NOT NULL,
   PRIMARY KEY(id),
   CONSTRAINT UC_Name UNIQUE (name),
   CONSTRAINT UC_Authority UNIQUE (authority)
) $charset_collate;";

dbDelta($query);


// create user table

$table_users = _USER_TABLE_;
$query = "CREATE TABLE IF NOT EXISTS $table_users(
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  full_name tinytext NOT NULL,
  email nvarchar(200) NOT NULL,
  password text NOT NULL,
  role_auth tinyint NOT NULL,
  activated int NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  CONSTRAINT UC_Email UNIQUE (email),
  FOREIGN KEY (role_auth) REFERENCES $table_user_roles(authority)
) $charset_collate;";

dbDelta($query);


// create activity types table

$table_activity_type = _ACT_TYPE_TABLE_;
$query = "CREATE TABLE IF NOT EXISTS $table_activity_type (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   name nvarchar(100) NOT NULL,
   CONSTRAINT UC_Name_Auth UNIQUE (name),
   PRIMARY KEY(id)
) $charset_collate;";

dbDelta($query);


// create activities table

$table_activities = _ACTIVITY_TABLE_;
$query = "CREATE TABLE IF NOT EXISTS $table_activities (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   type_name nvarchar(100) NOT NULL,
   user_id mediumint(9) NOT NULL,
   FOREIGN KEY (type_name) REFERENCES $table_activity_type(name),
   FOREIGN KEY (user_id) REFERENCES $table_users(id),
   PRIMARY KEY(id)
) $charset_collate;";

dbDelta($query);


// request table

$table_requests = _REQUEST_TABLE_;
$query = "CREATE TABLE IF NOT EXISTS $table_requests (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    act_id mediumint(9) NOT NULL,
    mess nvarchar(10000) NOT NULL,
    status nvarchar(100) NOT NULL,
    last_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    viewed bit DEFAULT 0,
    FOREIGN KEY (act_id) REFERENCES $table_activities(id),
    PRIMARY KEY(id)
) $charset_collate;";

dbDelta($query);

// request messages

$table_req_mess = _REQUEST_MESS_;
$query = "CREATE TABLE IF NOT EXISTS $table_req_mess (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    req_id mediumint(9) NOT NULL,
    user_id mediumint(9) NOT NULL,
    content nvarchar(10000) NOT NULL,
    date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    FOREIGN KEY (req_id) REFERENCES $table_requests(id),
    FOREIGN KEY (user_id) REFERENCES $table_users(id),
    PRIMARY KEY(id)
) $charset_collate;";

dbDelta($query);
}

createTables();

insertRoleTypes(1, _USER_ROLE_);
insertRoleTypes(2, _LAWYER_ROLE_);
insertRoleTypes(3, _ADMIN_ROLE_);

function insertRoleTypes($auth, $name){
global $wpdb;

$table_user_roles = _ROLE_TABLE_;

$wpdb -> insert(
    $table_user_roles,
        array(
            'name' => $name,
            'authority' => $auth
                   ),
        array(
            "%s",
            "%d"
            )
        );
}

insertActivityTypes(_CREATE_DOCUMENT_);
insertActivityTypes(_LOGGED_IN_);
insertActivityTypes(_UPLOAD_DOCUMENT_);
insertActivityTypes(_REGISTERED_ACCOUNT_);
insertActivityTypes(_ACTIVATED_ACCOUNT_);
insertActivityTypes(_RECOVER_PASSWORD_);
insertActivityTypes(_ASK_ATTORNEY_);
insertActivityTypes(_REVIEW_DOCUMENT_);
insertActivityTypes(_REGISTER_BUSINESS_);

function insertActivityTypes($name){
global $wpdb;

$table_activity_type = _ACT_TYPE_TABLE_;

$wpdb -> insert(
    $table_activity_type,
        array(
            'name' => $name
                   ),
        array(
            "%s"
            )
        );
}

    ?>
