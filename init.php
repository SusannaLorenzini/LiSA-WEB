<?php

/**
 * LiSA Analysis - init.php
 * General info for LiSA Analysis
 *
 * @author <a href="mailto:lorenzini.susanna@gmail.com">Susanna Lorenzini</a>
 */

ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);

session_start();

/** STATUS CODES */
const NO_DATA = -1;
const NO_IMP_FILE = -2;
const ERROR_UPLOAD = -10;
const ERROR_INTERNAL = -500;
const OK = 1;

/** PLATFORM CONSTANT */
const LISACLI_JAR = "bin/lisa-cli.jar";
const ANALYSIS_FOLDER = "analysis";

/** GENERAL CONSTANTS */
const COPYRIGHT = "&copy; 2022 Susanna Lorenzini";

if(!file_exists(ANALYSIS_FOLDER) && !mkdir(ANALYSIS_FOLDER))
	die("GENERAL ERROR");
