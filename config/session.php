<?php

// Session config. - 3 Horas.
ini_set("session.cookie_lifetime", "10800");
ini_set("session.gc_maxlifetime", "10800");

// Initializing sesion.
ini_set('session.use_trans_sid', false);
ini_set('session.use_cookies', true);
ini_set('session.use_only_cookies', true);

session_start();
ob_flush();

//echo session_id();


