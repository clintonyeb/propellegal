<?php
// TABLES
define("_USER_TABLE_", $wpdb -> prefix .  "my_users");
define("_DOC_TABLE_",  $wpdb -> prefix . "my_docs");
define("_ROLE_TABLE_",  $wpdb -> prefix . "my_user_roles");
define("_ACT_TYPE_TABLE_",  $wpdb -> prefix . "my_activity_types");
define("_ACTIVITY_TABLE_",  $wpdb -> prefix . "my_activities");
define("_REQUEST_TABLE_", $wpdb -> prefix . "my_requests");
define("_REQUEST_MESS_", $wpdb -> prefix . "my_req_mess");
define("_DOC_REVIEW_TABLE_", $wpdb -> prefix . "my_doc_reviews");
define("_DOC_REVIEW_MESS_", $wpdb -> prefix . "my_doc_rev_mess");
define("_DOC_FILES_", $wpdb -> prefix . "my_doc_files");
define("_BUS_REG_TABLE_", $wpdb -> prefix . "my_buss");
define("_BUS_MESS_TABLE_", $wpdb -> prefix . "my_buss_mess");
define("_LAWYER_TASK_", $wpdb -> prefix . "my_lawyer_tasks");

// USER ROLES
define("_USER_ROLE_", "USER");
define("_LAWYER_ROLE_", "LAWYER");
define("_ADMIN_ROLE_", "ADMIN");
define("_USER_ID_", 3);
define("_LAWYER_ID_", 2);
define("_ADMIN_ID_", 1);

// ACTIVITY TYPES
define("_CREATE_DOCUMENT_", "CREATE_DOCUMENT");
define("_LOGGED_IN_", "LOGGED_IN");
define("_UPLOAD_DOCUMENT_", "UPLOAD_DOCUMENT");
define("_REGISTERED_ACCOUNT_", "REGISTERED_ACCOUNT");
define("_ACTIVATED_ACCOUNT_", "ACTIVATED_ACCOUNT");
define("_RECOVER_PASSWORD_", "RECOVER_PASSWORD");
define("_ASK_ATTORNEY_", "ASK_ATTORNEY");
define("_REVIEW_DOCUMENT_", "REVIEW_DOCUMENT");
define("_REGISTER_BUSINESS_", "REGISTER_BUSINESS");

// SECRET KEYS - PROBABLY NOT MEANT FOR YOUR EYES.
define("SECRET_KEY",  "940f4f3e0bbacef5eb6ac977547672e2ef9dd106c713ef25cfbfd4b5abea2");
define("CLIENT_KEY", "OGZhZGQ0ZDI5NWRjOTUxZGIwOTdjNWFj");
define("SALT_PASSWORD", "OTI4NjYwZjUyM2UyYmY5NGVhOGUwMDUxMmQ3MDAy");
define("CAPTCHA_SECRET_KEY", "6LeW0TIUAAAAAORH8phjOTkBbZncYrXpBuTldKhC");
define("OTP_KEY", "MGMxMjA2NzViYThmMTA3ZmUxMDQwYzI1");

// STATUS MESSAGES
define("_RECEIVED_", "RECEIVED");
define("_PROCESSING_", "PROCESSING");
define("_COMPLETED_", "COMPLETED");

// USER STATUS
define("_REGISTERED_", "REGISTERED");
define("_ACTIVATED_", "ACTIVATED");
define("_SUBSCRIBED_", "SUBSCRIBED");
?>
