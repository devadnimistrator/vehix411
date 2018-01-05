<?php
require('library/admin_application_top.php');

tep_session_unregister(SESSION_USER_ID);

tep_session_destroy();

tep_redirect("login.php");