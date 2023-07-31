<?php

// ************************************************************
// Initialize the session
// 
// The session creates a file on the server that allows PHP to 
// to store data associated with each visitor to your web site.

session_start();

// ************************************************************
// Define constants
// 
// These are a set of variables that are used throughout the
// application.

define('ADMIN_STATUS', array('active' => 'Active', 'inactive' => 'Inactive'));
define('STUDENT_STATUS', array('active' => 'Active', 'inactive' => 'Inactive'));

define('TASK_STATUS', array('pending' => 'Pending', 'complete' => 'Complete'));

define('CLASS_SEMESTER', array('fall' => 'Fall', 'winter' => 'Winter', 'summer' => 'Summer'));


