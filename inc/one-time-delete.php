<?php
require_once(SITE_ROOT . "/inc/global.php");

$table_lawyer_req = _LAWYER_REQ_;
$wpdb->query("DROP TABLE IF EXISTS $table_lawyer_req");

$table_notifs = _NOTIF_TABLE_;
$wpdb->query("DROP TABLE IF EXISTS $table_notifs");

$table_req_files = _REQ_FILES_;
$wpdb->query("DROP TABLE IF EXISTS $table_req_files");

$table_lawyer_rev = _LAWYER_REV_;
$wpdb->query("DROP TABLE IF EXISTS $table_lawyer_rev");

$table_lawyer_bus = _LAWYER_BUS_;
$wpdb->query("DROP TABLE IF EXISTS $table_lawyer_bus");

$table_doc = _DOC_TABLE_;
$wpdb->query("DROP TABLE IF EXISTS $table_doc");

$table_req_mess = _REQUEST_MESS_;
$wpdb->query( "DROP TABLE IF EXISTS $table_req_mess" );

$table_requests = _REQUEST_TABLE_;
$wpdb->query( "DROP TABLE IF EXISTS $table_requests" );

$table_reg_mess = _BUS_MESS_TABLE_;
$wpdb->query( "DROP TABLE IF EXISTS $table_reg_mess" );

$table_regs = _BUS_REG_TABLE_;
$wpdb->query( "DROP TABLE IF EXISTS $table_regs" );

$table_doc_files = _DOC_FILES_;
$wpdb->query( "DROP TABLE IF EXISTS $table_doc_files" );

$table_doc_mess = _DOC_REVIEW_MESS_;
$wpdb->query( "DROP TABLE IF EXISTS $table_doc_mess" );

$table_doc_rev = _DOC_REVIEW_TABLE_;
$wpdb->query( "DROP TABLE IF EXISTS $table_doc_rev" );

$table_activities = _ACTIVITY_TABLE_;
$wpdb->query( "DROP TABLE IF EXISTS $table_activities" );

$table_activity_type = _ACT_TYPE_TABLE_;
$wpdb->query( "DROP TABLE IF EXISTS $table_activity_type" );

$table_users = _USER_TABLE_;
$wpdb->query( "DROP TABLE IF EXISTS $table_users" );

$table_user_roles = _ROLE_TABLE_;
$wpdb->query( "DROP TABLE IF EXISTS $table_user_roles" );

$table_feedback = _FEEDBACK_TABLE_;
$wpdb->query("DROP TABLE IF EXISTS $table_feedback");

?>
