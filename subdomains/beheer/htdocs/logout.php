<?php
session_start();
session_abort();
session_destroy();

header('Location: /pages/account/login.php');