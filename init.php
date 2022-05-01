<?php
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);

session_start();

/** STATUS CODES */
define("NO_DATA", -1);
define("NO_IMP_FILE", -2);
define("ERROR_UPLOAD", -10);
define("ERROR_INTERNAL", -500);
define("OK", 1);

/** PLATFORM CONSTANT */
define("LISACLI_JAR", "bin/lisa-cli.jar");
define("ANALYSIS_FOLDER", "analysis");

/** GENERAL CONSTANTS */
define("COPYRIGHT", "&copy; 2022 Susanna Lorenzini");

if(!file_exists(ANALYSIS_FOLDER) && !mkdir(ANALYSIS_FOLDER))
	die("GENERAL ERROR");