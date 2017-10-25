<?php
require_once(SITE_ROOT . "/inc/global.php");

function deleteTables(){
    global $wpdb;

    $table_req_mess = _REQUEST_MESS_;
    $wpdb->query( "DROP TABLE IF EXISTS $table_req_mess" );
    
    $table_requests = _REQUEST_TABLE_;
    $wpdb->query( "DROP TABLE IF EXISTS $table_requests" );

    $table_activities = _ACTIVITY_TABLE_;
    $wpdb->query( "DROP TABLE IF EXISTS $table_activities" );

    $table_activity_type = _ACT_TYPE_TABLE_;
    $wpdb->query( "DROP TABLE IF EXISTS $table_activity_type" );

    $table_users = _USER_TABLE_;
    $wpdb->query( "DROP TABLE IF EXISTS $table_users" );

    $table_user_roles = _ROLE_TABLE_;
    $wpdb->query( "DROP TABLE IF EXISTS $table_user_roles" );
}

deleteTables();
?>
