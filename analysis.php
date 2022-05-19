<?php
require_once("init.php");

/**
 * LiSA Analysis - analysis.php
 * Page of LiSA Analysis containing analysis results
 *
 * @author <a href="mailto:lorenzini.susanna@gmail.com">Susanna Lorenzini</a>
 */



/*
 * Status contains a check status for data sent.
 *
 * 0 => No info
 * -1 => No data sent
 * -2 => No IMP file
 * -10 => Error during upload
 * -500 => Internal error, no folder structure to save files
 * 1 => OK
 */
$status = 0;

// TARGET DIRECTORY to save analysis files
$target_dir = "analysis/" . session_id() . "/";

// DEFAULT PATH & NAME for source code file
$target_file = $target_dir . "sourcecode.imp";

/* ARGUMENTS for lisa-cli based on user choices */
// BOOLEAN choices for files to dump at the end of the analysis
$flag_dumpcfgs_argument = "";
$flag_jsonoutput_argument = "";
$flag_typeinference_argument = "";
// Syntactic or Semantic Checks to execute
$flag_nicheck_argument = "";
// STRING parameters to set the analysis
$heapDomain_argument = "";
$valueDomain_argument = "";
$typeDomain_argument = "";
$interprocedural_argument = "";
$callGraph_argument = "";
$openCallPolicy_argument = "";
$fixpointWorkingSet_argument = "";
// INT argument to set widening threshold
$wideningThreshold_argument = -1;



/* MANAGE input data */
// if file is inserted both from textarea and upload, uploaded file will be taken for the analysis
if(isset($_POST["CMD_Load"]))
{
	// Manage the source code file uploading
	if (is_uploaded_file($_FILES['sourcefile']['tmp_name']))
    {
		$fileType = strtolower(pathinfo($_FILES["sourcefile"]["name"],PATHINFO_EXTENSION));
		if($fileType != "imp")
			$status = NO_IMP_FILE;
		else if(!file_exists($target_dir) && !mkdir($target_dir))
			$status = ERROR_INTERNAL;
		else if (move_uploaded_file($_FILES["sourcefile"]["tmp_name"], $target_file)) 
			$status = OK;
		else
			$status = ERROR_UPLOAD;
	
	// Manage the source code snippet
	} else if(isset($_POST["sourcecode"]) && trim($_POST["sourcecode"]) != "")
    {
		if(!file_exists($target_dir) && !mkdir($target_dir))
			$status = ERROR_INTERNAL;
		else if(file_put_contents($target_file, $_POST["sourcecode"]) === false)
			$status = ERROR_INTERNAL;
		else
			$status = OK;

	// Manage no code loaded
	}else
	{ $status = NO_DATA; }
}

// If no errors occurred
if($status == OK)
{
    /* VARIABLES to retrieve choices made from forms */
    // BOOLEAN
    $dumpcfgs = isset($_POST["dumpcfgs"]);
    $jsonoutput = isset($_POST["jsonoutput"]);
    $typeinference = isset($_POST["typeinference"]);
    $nicheck = isset($_POST["nicheck"]);
    // STRING
    $heapdomain = isset($_POST["heap"]) && trim($_POST["heap"]) != "";
    $valuedomain = isset($_POST["value"]) && trim($_POST["value"]) != "";
    $typedomain = isset($_POST["type"]) && trim($_POST["type"]) != "";
    $interprocedural = isset($_POST["interprocedural"]) && trim($_POST["interprocedural"]) != "";
    $callgraph = isset($_POST["callgraph"]) && trim($_POST["callgraph"]) != "";
    $opencallpolicy = isset($_POST["opencallpolicy"]) && trim($_POST["opencallpolicy"]) != "";
        // NB: fixpointworkingset NOT HANDLED AT THE MOMENT because it doesn't work in lisa-cli main
    $fixpointworkingset = isset($_POST["fixpointworkingset"]) && trim($_POST["fixpointworkingset"]) != "";
    // INT
    $wideningthreshold = isset($_POST["wideningthreshold"]) && (($_POST["wideningthreshold"]) > -1);

    /* CREATING arguments for lisa-cli main */
    // BOOLEAN
	if($dumpcfgs) { $flag_dumpcfgs_argument = "-dumpcfgs"; }
	if($jsonoutput) { $flag_jsonoutput_argument = "-jsonoutput"; }
	if($typeinference) { $flag_typeinference_argument = "-typeinference"; }
    if($nicheck) {$flag_nicheck_argument = '-nicheck'; }
    // STRING
    if ($heapdomain){ $heapDomain_argument = "-heapdomain \"" . $_POST["heap"] . "\""; }
    if ($valuedomain){
        $valueDomain_from_POST = $_POST["value"]; // used for naming the folder in session to store output files ($target_dir_output)
        $valueDomain_argument = "-valuedomain \"" . $_POST["value"] . "\"";
    }
    if ($typedomain){ $typeDomain_argument = "-typedomain \"" . $_POST["type"] . "\""; }
    if ($interprocedural){ $interprocedural_argument = "-interproceduralanalysis \"" . $_POST["interprocedural"] . "\""; }
    if ($callgraph){ $callGraph_argument = "-callgraph \"" . $_POST["callgraph"] . "\""; }
    if ($opencallpolicy){ $openCallPolicy_argument = "-opencallpolicy \"" . $_POST["opencallpolicy"] . "\""; }
    if ($fixpointworkingset) { $fixpointWorkingSet_argument = "-fixpointworkingset  \"" . $_POST["fixpointworkingset"] . "\""; }
    // INT
    if ($wideningthreshold){ $wideningThreshold_argument = "-wideningthreshold " . $_POST["wideningthreshold"]; }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- my CSS -->
    <link rel="stylesheet" href="styles/style.css">
    <!-- Prism CSS -->
    <link href="prism/prism-customized-default.css" rel="stylesheet" />

    <!-- Javascript events -->
    <script type="text/javascript" src="events.js" defer></script>

    <title>LiSA, Static Analysis with LiSA</title>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <div class="header-container container-flex">
        <header class="container-flex mx-5 d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 my-2">

            <!-- LiSA Analysis logo -->
            <a href="index.php" class="col-8 mb-2 col-sm-3 col-md-2 col-xl-1 mx-sm-5 d-flex align-items-center text-dark text-decoration-none">
                <img src="media/logo.png" class="img-fluid bi me-2" alt="LiSA Analysis logo">
            </a>

            <!-- NAV -->
            <ul class="nav col-md-auto mb-2 justify-content-center mb-md-0">
                <!-- LiSA documentation -->
                <li><a href="https://unive-ssv.github.io/lisa/" class="nav-link nav-link-custom p-1 m-1">Documentation</a></li>
                <!-- GitHub repository -->
                <li><a href="https://github.com/UniVE-SSV/lisa" class="nav-link nav-link-custom p-1 m-1">GitHub&nbsprepository</a></li>
                <!-- SSV research group -->
                <li><a href="https://github.com/UniVE-SSV" class="nav-link nav-link-custom p-1 m-1">SSV&nbspgroup</a></li>
            </ul>
            <!-- /NAV -->

        </header>
    </div>
    <!-- /HEADER -->



	<div class="container my-5">
    <?php
	if($status == OK)
	{
        // Target directory to save analysis output
        $target_dir_output = $target_dir. $valueDomain_from_POST . "/";

        // Remove session directory and its contents, if it already exists
        $command_clean_workingdir = "rm -r $target_dir_output 2>&1";
        $output_clean_workingdir = shell_exec($command_clean_workingdir);

        // Launch LiSA analysis with parameters chosen by the user
        $command_lisacli = "java -jar " . LISACLI_JAR . " -source \"$target_file\" -workdir $target_dir_output $flag_dumpcfgs_argument $flag_jsonoutput_argument $flag_typeinference_argument $heapDomain_argument $valueDomain_argument $typeDomain_argument $interprocedural_argument $callGraph_argument $openCallPolicy_argument "./* $fixpointWorkingSet_argument */"$flag_nicheck_argument $wideningThreshold_argument 2>&1";
        exec($command_lisacli, $output_lisacli, $output_lisacli_exitStatus);

        // Array $lines: every element is a line of the source code
        $lines = file($target_file, FILE_IGNORE_NEW_LINES);
        // keys of array $lines start from 1, matching source code lines
        array_unshift($lines,"");
        unset($lines[0]);

        // Preparing for printing Source Code in Source Code tab
        // $index = 0;

        // Creating arrays from report.json:
        // - $json_warnings_info containing warnings (with infos row, col, message);
        // - $json_files containing files dumped (now commented because not used).
        if ($jsonoutput && file_exists($target_dir_output . "report.json")) {

            $json_output = file_get_contents($target_dir_output . "report.json");
            $json_data = json_decode($json_output, true); // decode the JSON into an associative array

            // creating multidimensional array containing warning informations foreach warning in file report.json
            // each warning in the array has fields row, column, message
            if (!empty($json_data['warnings'])){
                    $json_warnings_info = array(array());
                    foreach ($json_data['warnings'] as $key => $value) {
                    $my_string = $json_data['warnings'][$key]['message'];

                    // foreach warning inserting:
                    // 1) warning row at index 'row'
                    if (preg_match('/:(.*?):/', $my_string, $match) == 1) {
                        $json_warnings_info[$key]['row'] = $match[1];
                    }
                    // 2) warning column at index 'col'
                    if (preg_match('/\d:(.*?)]/', $my_string, $match) == 1) {
                        $json_warnings_info[$key]['col'] = $match[1];
                    }
                    // 3) warning message at index 'message'
                    if (preg_match('/\[EXPRESSION] (.+)/', $my_string, $match) == 1) {
                        $json_warnings_info[$key]['message'] = $match[1];
                    }
                }

                // function to sort array elements by field 'row'. Used for multidimentional array $json_warnings_info.
                function cmp($a, $b) {
                    return $a['row'] - $b['row'];
                }
                usort($json_warnings_info,"cmp");

            }
            // DEBUG print for visualizing all elements of array $json_warnings_info (after sorting its elements by 'row' field)
            // echo '<pre><h4>DEBUG json_warnings_info AFTER SORTING FOR row NUMBER:</h4>' . print_r($json_warnings_info, true) . '</pre>';


            /* NOT USED AT THE MOMENT: better searching for files dumped directly in output directory
            // creating array with names of files dumped
            if (!empty($json_data['files'])){
                // EASY ARRAY: $json_files = $json_data['files'];

                // MULTIDIMENSIONAL ARRAY dividing file names in sub-arrays: analysis, typing, untyped

                foreach (($json_data['files']) as $key => $value){

                    if (str_starts_with($value, "analysis")){
                        $json_files['analysis'][] = $value;
                    } else if (str_starts_with($value, "typing")){
                        $json_files['typing'][] = $value;
                    } else if (str_starts_with($value, "untyped")){
                        $json_files['untyped'][] = $value;
                    } else { // if file name starts with different words it will be placed in 'others' sub-array
                        $json_files['others'][] = $value;
                    }

                }

                // DEBUG print for visualizing all elements of array $json_files
                // echo '<pre><h4>DEBUG json_files with names of file dumped:</h4>' . print_r($json_files, true) . '</pre>';
            }
            */

        }

        // Creating array:
        // - $files_dumped that contains all file's names stored in working directory;
        // - $files_dumped_multidim that divides file's names by category: analysis, untyped, typing, report, others.
        $command_workdir_ls = "cd " . $target_dir_output . " && ls 2>&1";
        $output_workdir_ls = shell_exec($command_workdir_ls);
        // array with files dumped
        $files_dumped = explode("\n",trim($output_workdir_ls));
        // multidimentional array: dividing by file names in sub-arrays: analysis, untyped, typing, report (& others, if naming convention changes => must be handled by DEVELOPER)
        // used for showing files dumped in tabs: Analysis, Input CFGs, Type Inference
        $files_dumped_multidim = array();

        foreach (($files_dumped) as $key => $value){

            if (str_starts_with($value, "analysis")){
                $files_dumped_multidim['analysis'][] = $value;
            } else if (str_starts_with($value, "untyped")){
                $files_dumped_multidim['untyped'][] = $value;
            } else if (str_starts_with($value, "typing")){
                $files_dumped_multidim['typing'][] = $value;
            } else if (str_starts_with($value, "report")) {
                $files_dumped_multidim['report'][] = $value;
            } else { // if file name starts with different words it will be placed in 'others' sub-array
                $files_dumped_multidim['others'][] = $value;
            }

        }



        // Tabs: panels to see analysis results
        // Source Code, [Debug], Analysis results, Input CFGs, Type Inference
        echo "
        <ul class='nav nav-tabs' id='myTab' role='tablist'>
            <li class='nav-item' role='presentation'><button class='nav-link active' id='sourcecode-tab' data-bs-toggle='tab' data-bs-target='#sourcecode' type='button' role='tab' aria-controls='sourcecode' aria-selected='true'>Source Code</button></li>
    <!-- DEBUG tab 4 developers only ===> to remove -->        
            <li class='nav-item' role='presentation'><button class='nav-link' id='debug-tab' data-bs-toggle='tab' data-bs-target='#debug' type='button' role='tab' aria-controls='debug' aria-selected='false'>Debug</button></li>            
        ";
        if ((!empty($files_dumped_multidim)) && (!empty($files_dumped_multidim['analysis']))) {
            echo "<li class='nav-item' role='presentation'><button class='nav-link' id='analysis-tab' data-bs-toggle='tab' data-bs-target='#analysis' type='button' role='tab' aria-controls='analysis' aria-selected='false'>Analysis</button></li>";
        }
        if ($dumpcfgs && (!empty($files_dumped_multidim)) && (!empty($files_dumped_multidim['untyped']))){
            echo "<li class='nav-item' role='presentation'><button class='nav-link' id='inputCFGs-tab' data-bs-toggle='tab' data-bs-target='#inputCFGs' type='button' role='tab' aria-controls='inputCFGs' aria-selected='false'>Input CFGs</button></li>";
        }
        if ($typeinference && (!empty($files_dumped_multidim)) && (!empty($files_dumped_multidim['typing']))){
            echo "<li class='nav-item' role='presentation'><button class='nav-link' id='type-inference-tab' data-bs-toggle='tab' data-bs-target='#type-inference' type='button' role='tab' aria-controls='type-inference' aria-selected='false'>Type Inference</button></li>";
        }
        echo "
        </ul>


        <!-- TABS -->
        <div class='tab-content' id='myTabContent'>
             
            <!-- Source Code tab content -->
            <div class='tab-pane fade show active pt-4' id='sourcecode' role='tabpanel' aria-labelledby='sourcecode-tab'>
                <div class='container'>
                    <div class='row'>";

                        if ($output_lisacli_exitStatus != 0){
                            echo "
                            <p>Something went wrong: LiSA couldn't analyze the program. Click <span onclick='showHideElement()' style='color:#155799; cursor: pointer;'>here</span> to learn more.</p>
                            <p id='exception_error_message' style='display: none;'>Prova</p>
                            ";
                        } else if ((is_dir($target_dir. $valueDomain_from_POST)) && file_exists($target_dir_output . "report.json") && (!empty($files_dumped_multidim)) && (empty($files_dumped_multidim['analysis']))){
                            echo "<p>No files generated. Please make sure that your IMP program is correct: see documentation <a href='https://unive-ssv.github.io/lisa/imp/' title='IMP documentation' style='text-decoration: none;'>here</a>.</p>";
                        }



                        /* Source Code Card */
                        // Source Code Card has maximum width if report.json is not dumped (in this case, Warnings Card is not shown)
                        // Otherwise, both Source Code Card and Warnings Card will be shown side by side
                        echo ($jsonoutput && file_exists($target_dir_output . "report.json"))? "<div class='col-md-8 col-lg-8 col-xl-8 mt-3' id='div-card-sourcecode'>" : "<div class='mt-3' id='div-card-sourcecode'>";
                        echo "
                            <div class='card h-100'>
                            
                                <!-- Source Code Card header -->
                                <div class='card-header d-flex justify-content-between align-items-center'>
                                    Source Code
                                    
                                    <div class='buttons'>
                        
                                        <!-- Button to switch to plain text or back to syntax highlighted text -->
                                        <button onclick='plain_highlighted_sourcecode()' type='button' class='btn me-5' style='padding: 0;' title='Plain / Syntax Highlighted text'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-card-text' viewBox='0 0 16 16'>
                                                <path d='M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z'/>
                                                <path d='M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z'/>
                                            </svg>
                                        </button>";

                                        if ($jsonoutput && file_exists($target_dir_output . "report.json") && !empty($json_data['warnings'])){
                                        echo"
                                            <!-- Button show all warnings highlighting them -->
                                            <button onclick='show_all_highlighting(".json_encode($json_warnings_info).")' type='button' class='btn me-2' style='padding: 0;' title='Highlight all warnings'>
                                                <svg xmlns='http://www.w3.org/2000/svg' aria-hidden='true' role='img' width='1em' height='1em' preserveAspectRatio='xMidYMid meet' viewBox='0 0 36 36'>
                                                    <path fill='currentColor' d='M15.82 26.06a1 1 0 0 1-.71-.29l-6.44-6.44a1 1 0 0 1-.29-.71a1 1 0 0 1 .29-.71L23 3.54a5.55 5.55 0 1 1 7.85 7.86L16.53 25.77a1 1 0 0 1-.71.29Zm-5-7.44l5 5L29.48 10a3.54 3.54 0 0 0 0-5a3.63 3.63 0 0 0-5 0Z' class='clr-i-outline clr-i-outline-path-1'/>
                                                    <path fill='currentColor' d='M10.38 28.28a1 1 0 0 1-.71-.28l-3.22-3.23a1 1 0 0 1-.22-1.09l2.22-5.44a1 1 0 0 1 1.63-.33l6.45 6.44A1 1 0 0 1 16.2 26l-5.44 2.22a1.33 1.33 0 0 1-.38.06Zm-2.05-4.46l2.29 2.28l3.43-1.4l-4.31-4.31Z' class='clr-i-outline clr-i-outline-path-2'/>
                                                    <path fill='currentColor' d='M8.94 30h-5a1 1 0 0 1-.84-1.55l3.22-4.94a1 1 0 0 1 1.55-.16l3.21 3.22a1 1 0 0 1 .06 1.35L9.7 29.64a1 1 0 0 1-.76.36Zm-3.16-2h2.69l.53-.66l-1.7-1.7Z' class='clr-i-outline clr-i-outline-path-3'/
                                                    <path fill='currentColor' d='M3.06 31h30v3h-30z' class='clr-i-outline clr-i-outline-path-4'/><path fill='none' d='M0 0h36v36H0z'/>
                                                </svg>
                                            </button>
                                        
                                            <!-- Button hide all highlighted warnings -->
                                            <button onclick='hide_all_highlighting()' type='button' class='btn me-5' style='padding: 0;' title='Clear all highlithed warnings'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-eraser' viewBox='0 0 16 16'>
                                                    <path d='M8.086 2.207a2 2 0 0 1 2.828 0l3.879 3.879a2 2 0 0 1 0 2.828l-5.5 5.5A2 2 0 0 1 7.879 15H5.12a2 2 0 0 1-1.414-.586l-2.5-2.5a2 2 0 0 1 0-2.828l6.879-6.879zm2.121.707a1 1 0 0 0-1.414 0L4.16 7.547l5.293 5.293 4.633-4.633a1 1 0 0 0 0-1.414l-3.879-3.879zM8.746 13.547 3.453 8.254 1.914 9.793a1 1 0 0 0 0 1.414l2.5 2.5a1 1 0 0 0 .707.293H7.88a1 1 0 0 0 .707-.293l.16-.16z'/>
                                                </svg>
                                            </button>";
                                        }

                                        if ($jsonoutput && file_exists($target_dir_output . "report.json")){
                                            echo "
                                            <!-- Button to hide Warnings card when visible -->
                                            <button onclick='hide_warnings()' id='hide-warnings' type='button' class='btn me-1' style='padding: 0;' title='Hide Warnings card'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-window' viewBox='0 0 16 16'>
                                                    <path d='M2.5 4a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zm2-.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm1 .5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z'/>
                                                    <path d='M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm13 2v2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zM2 14a1 1 0 0 1-1-1V6h14v7a1 1 0 0 1-1 1H2z'/>
                                                </svg>
                                            </button>
                                            <!-- Button to show Warnings card when hidden -->
                                            <button onclick='show_warnings()' id='show-warnings' type='button' class='btn me-1' style='padding: 0; display: none;' title='Show Warnings card'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-window-split' viewBox='0 0 16 16'>
                                                    <path d='M2.5 4a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1Zm2-.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm1 .5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1Z'/>
                                                    <path d='M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2Zm12 1a1 1 0 0 1 1 1v2H1V3a1 1 0 0 1 1-1h12ZM1 13V6h6.5v8H2a1 1 0 0 1-1-1Zm7.5 1V6H15v7a1 1 0 0 1-1 1H8.5Z'/>
                                                </svg>   
                                            </button>";
                                        }

                                    echo "
                                    </div>  
                                </div> 
                                
                                <!-- Source Code Card body -->
                                <div class='card-body' id='sourcecode-card-body'>
                                    
                                    <div id='sourcecode-btn-container'>
                                        <button type='button' id='sourcecode-btn-select-all' onclick='select_all_text()'>Select all!</button>
                                    </div>
                                                
                                    <div id='container-sourcerow'>";
                                        $index = 0;
                                        foreach ($lines as $key => $line) {
                                            $is_warning = $jsonoutput && !empty($json_data['warnings']) && $index < sizeof($json_warnings_info) && $key == $json_warnings_info[$index]['row'];

                                            echo "
                                            <div class='sourcerow " . ($is_warning ? "warning-line " : "") . "' id='row$key' " . ($is_warning ? "title='" . $json_warnings_info[$index]['message'] . "'" : "") . ">
                                                <pre class='line-numbers' data-start='$key'><code class='language-java sourcecode'>" . htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401) . "&#8203;" . "</code></pre>
                                            </div>";
                                            if ($is_warning) {$index += 1;}
                                        }

                                    echo"
                                    </div>
                                    
                                    <div class='px-4 py-2' id='container-plain-sourceraw' style='display: none;'>";
                                        $index = 0;
                                        foreach ($lines as $key => $line) {
                                            $is_warning = $jsonoutput && !empty($json_data['warnings']) && $index < sizeof($json_warnings_info) && $key == $json_warnings_info[$index]['row'];
                                            echo "
                                            <div class='plain-sourcerow " . ($is_warning ? "warning-line " : "") . "' id='plain-row$key' " . ($is_warning ? "title='" . $json_warnings_info[$index]['message'] . "'" : "") . ">
                                                <pre>" . htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401) . "</pre>
                                            </div>";
                                            if ($is_warning) {$index += 1;}
                                        }
                                    echo "
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <!-- Warnings Card -->";
                        // Show warnings Card only if report.json exists
                        // NB: Show Warnings Card even if there are no warnings, because if report.json is generated the user can read it or download it
                        if ($jsonoutput && file_exists($target_dir_output . "report.json")){
                            echo "
                            <div class='col-md-4 col-lg-4 col-xl-4 mt-3' id='div-card-warnings'>
                                <div class='card h-100'>
                                    
                                    <!-- If report.json exists, always show its header-->
                                    <div class='card-header d-flex justify-content-between align-items-center'>
                                        Warnings
                                        <div class='buttons'>";

                                                /*
                                                    To see report.json in new window overlaid to the current one, replace the <a> tag with the next line:
                                                        <a onclick='window.open(\"" . $target_dir_output . "report.json\", \"_blank\", \"location=yes,height=570,width=900,scrollbars=yes,status=yes\");'>
                                                    To see report.json in new tab replace the <a> tag with next line:
                                                        <a href='" . $target_dir_output. "report.json' target='_blank'  style='text-decoration: none;'>
                                                */

                                            echo "
                                            <!-- Button to see report.json, if generated -->
                                            <a onclick='window.open(\"" . $target_dir_output . "report.json\", \"_blank\", \"location=yes,height=570,width=900,scrollbars=yes,status=yes\");'>
                                                <button type='button' class='btn me-3' style='padding: 0;' title='See report.json'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-eye' viewBox='0 0 16 16'>
                                                        <path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z'/>
                                                        <path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z'/>
                                                    </svg>
                                                </button>
                                            </a>
                                                
                                            <!-- Button to download report.json, if generated -->  
                                            <a href='" . $target_dir_output . "report.json' download='report.json'  style='text-decoration: none;'>
                                                <button type='button' class='btn me-1' style='padding: 0;' title='Download report.json file'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-file-earmark-arrow-down' viewBox='0 0 16 16'>
                                                        <path d='M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z'/>
                                                        <path d='M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z'/>
                                                    </svg> 
                                                </button>
                                            </a>
                                        </div>   
                                    </div>";

                                    // If there is at least one warning show it, otherwise display "No Warnings."
                                    if (!(empty($json_data['warnings']))){

                                        echo "
                                        <!-- If report.json was generated it shows the list of warnings, if any -->
                                        <div class='card-body card-warnings'>
                                            <table class='table table-striped table-borderless'>
                                                    
                                                <thead>
                                                    <tr>
                                                        <th scope='col'>Line <!--style='word-break: initial;'--></th>
                                                        <th scope='col'>Message</th>
                                                    </tr>
                                                </thead>
                                                        
                                                <tbody>";

                                                foreach ($json_warnings_info as $key => $value) {
                                                    echo "
                                                        <tr onclick='highlightRow(".$value['row'].")'> 
                                                            <th scope='row'>" . $value['row'] . "</th>
                                                            <td>" . $value['message'] . "</td>
                                                        </tr>
                                                        ";
                                                }
                                                echo "
                                                </tbody>
                                            </table>
                                        </div>";
                                    }else {
                                        echo "<div class='card-body'>No warnings.</div>";
                                    }

                                echo "  
                                </div>
                            </div>";
                        }
                    echo "                
                    </div>
                </div>
            </div>
            
            
            
            <!-- DEBUG tab content: 4 DEVELOPERS ONLY ===> to remove -->
            <div class='tab-pane fade pt-4' id='debug' role='tabpanel' aria-labelledby='debug-tab'>
                <p><b>DEBUG command executed: </b><br></p>
                <strong>Cleaning working directory:</strong>
                <pre class='debug-command' style='color:darkblue;'>#$command_clean_workingdir</pre>
                <pre class='debug-command'>$output_clean_workingdir</pre>
                <br>
                <strong>Launching LiSA:</strong>
                <pre class='debug-command' style='color:darkblue;'>#$command_lisacli</pre>
                <pre class='debug-command'><strong>[Output:]</strong>
                ";
                    echo "\n";
                    foreach ($output_lisacli as $line){
                        echo htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401) . "\n";
                    }
                    echo "<br>[exit-status]: " .$output_lisacli_exitStatus;
                echo "
                </pre>
                <br><br><hr><br><br>
               
                
                <b>DEBUG generating warnings array from report.json: </b>
                <p>[after sorting array for warning line number]<br></p>";

                echo (($jsonoutput && file_exists($target_dir_output . 'report.json') && !empty($json_data['warnings'])) ? "<pre>" . print_r($json_warnings_info, true) . "</pre>" : "<p>No warnings or no report.json found</p>");

                echo"
                <br><br><hr><br><br>
                
                <p><b>DEBUG command executed: <br>$command_workdir_ls</b></p>
                <p>Files generated in " . $target_dir_output . ": </p>
                <pre>$output_workdir_ls</pre>
                <br><br><br>
                <b>MULTIDIM array for files dumped</b><br>
                files in session directory: <br>
                <br>"; echo "<pre>".print_r($files_dumped_multidim,true)."</pre>";
            echo"
            </div>
            
            
            
            <!-- Analysis tab content -->
            <div class='tab-pane fade pt-4' id='analysis' role='tabpanel' aria-labelledby='analysis-tab'>
                <h3 class='green weight-normal'>Results of the Analysis:</h3><br>";

                if ((!empty($files_dumped_multidim)) && (!empty($files_dumped_multidim['analysis']))) {
                    echo "<div id='analysis-files-dumped'>";
                    foreach ($files_dumped_multidim['analysis'] as $key => $value) {
                        echo "
                        <div class='analysis-file-dumped' id='analysis-file-dumped-". $key+1 ."'><span class='green'>" . $key+1 . ')</span>&emsp; ' . $value . "&emsp;";
                            echo " 
                            <!-- Button to download Analysis files -->  
                            <a href='".$target_dir_output.$value."' download='".$value."' style='text-decoration: none;'>
                                <button type='button' class='btn' style='padding-left:0' title='Download file'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='rgb(70, 145, 95)' class='bi bi-file-earmark-arrow-down' viewBox='0 0 16 16'>
                                        <path d='M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z'/>
                                        <path d='M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z'/>
                                    </svg> 
                                </button>
                            </a>   
                        </div>";
                    }
                    echo "</div>";
                } else {
                    echo '<p>No Analysis files.</p>';
                }

            echo "
            </div>
            
            
            
            <!-- Input CFGs tab content -->
            <div class='tab-pane fade pt-4' id='inputCFGs' role='tabpanel' aria-labelledby='inputCFGs-tab'>
                <h3 class='green weight-normal'>Input CFGs:</h3><br>";

                if ($dumpcfgs && (!empty($files_dumped_multidim)) && (!empty($files_dumped_multidim['untyped']))) {
                    echo "<div id='inputcfgs-files-dumped'>";
                    foreach ($files_dumped_multidim['untyped'] as $key => $value) {
                        echo "
                        <div class='inputcfgs-file-dumped' id='inputcfgs-file-dumped-". $key+1 ."'><span class='green'>" . $key+1 . ')</span>&emsp; ' . $value . "&emsp;";
                            echo "
                            <!-- Button to download Input CFGs files -->  
                            <a href='".$target_dir_output.$value."' download='".$value."' style='text-decoration: none;'>
                                <button type='button' class='btn' style='padding-left:0' title='Download file'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='rgb(70, 145, 95)' class='bi bi-file-earmark-arrow-down' viewBox='0 0 16 16'>
                                        <path d='M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z'/>
                                        <path d='M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z'/>
                                    </svg> 
                                </button>
                            </a>
                        </div>";
                    }
                    echo "</div>";
                } else {
                    echo '<p>No Input CFGs files.</p>';
                }

            echo "
            </div>
            
            
            
            <!-- Type Inference tab content -->
            <div class='tab-pane fade pt-4' id='type-inference' role='tabpanel' aria-labelledby='type-inference-tab'>
                <h3 class='green weight-normal'>Results of Type Inference:</h3><br>";

                if (($typeinference) && (!empty($files_dumped_multidim)) && (!empty($files_dumped_multidim['typing']))) {
                    echo "<div id='typeinference-files-dumped'>";
                    foreach ($files_dumped_multidim['typing'] as $key => $value) {
                        echo "
                        <div class='typeinference-file-dumped' id='typeinference-file-dumped-". $key+1 ."'><span class='green'>" . $key+1 . ')</span>&emsp; ' . $value . "&emsp;";
                            echo "<!-- Button to download Type Inference files -->  
                            <a href='".$target_dir_output.$value."' download='".$value."' style='text-decoration: none;'>
                                <button type='button' class='btn' style='padding-left:0' title='Download file'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='rgb(70, 145, 95)' class='bi bi-file-earmark-arrow-down' viewBox='0 0 16 16'>
                                        <path d='M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z'/>
                                        <path d='M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z'/>
                                    </svg> 
                                </button>
                            </a>  
                        </div>";
                    }
                    echo "</div>";
                } else {
                    echo '<p>No Type Inference files.</p>';
                }

           echo "     
            </div>
        </div>
        <!-- /TABS -->
       
        <br>
        <!--hr-->
        <br>
        <br>
        
        <a class='btn btn-outline-success container' href='index.php' role='button'>Perform another analysis</a>
        ";



	}else
	{
    ?>

	<p>There was an error in sending data:
	<?php
		switch($status)
		{
			case NO_DATA:
				echo "No source code. Please perform an <a href='index.php'>analysis</a> providing source code.";
				break;

			case NO_IMP_FILE:
				echo "The file is not an IMP file. Please perform <a href='index.php'>another analysis</a> inserting an IMP file.";
				break;

			case ERROR_UPLOAD:
				echo "an error during upload occured. <a href='index.php'>another analysis</a>.";
				break;

			case ERROR_INTERNAL:
				echo "an internal error occured, please contact the sysadmin.";
		}	?></p>
		<?php
	} // else
    ?>
    </div>

    <!-- SCROLL TO TOP ICON -->
    <a class="scroll-to-top" id="scroll-to-top-icon" onclick="scrollTopPage()">
        <div class="roundedFixedBtn">
            <p>^</p>
        </div>
    </a>

    <!-- FOOTER -->
    <div class="container-fluid mt-auto">

        <!-- LiSA library logo, Year, Name at the bottom left -->
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <div class="col-md-4 d-flex align-items-center">
                <a href="index.php" class="mb-3 mx-2 mb-md-0 text-muted text-decoration-none lh-1" title="Home Page">
                    <img src="media/LiSALibrary.png" class="bi img-fluid bi me-2" width="30" alt="LiSA logo">
                </a>
                <span class="text-muted"><?= COPYRIGHT ?></span>
            </div>

            <!-- icons at bottom right -->
            <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
                <!-- LiSA Documentation (Bootstrap icon) -->
                <li class="ms-2"><a class="text-muted" href="https://unive-ssv.github.io/lisa/" title="LiSA documentation">
                    <svg xmlns="http://www.w3.org/2000/svg" class="bi" fill="rgb(40, 85, 145)" viewBox="0 0 20 20" width="24" height="24">
                        <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                    </svg>
                </a></li>
                <!-- GitHub repository -->
                <li class="ms-2"><a class="text-muted" href="https://github.com/UniVE-SSV/lisa" title="LiSA GitHub">
                    <svg class="bi" fill="rgb(55, 115, 125)" viewBox="0 0 20 20" width="24" height="24">
                        <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path>
                    </svg></a></li>
                <!-- SSV research group (Bootstrap icon) -->
                <li class="ms-2 me-3"><a class="text-muted" href="https://ssv.dais.unive.it" title="SSV research group">
                    <svg class="bi" fill="rgb(70, 145, 95)" viewBox="0 0 20 20" width="24" height="24">
                        <path d="M8 .95 14.61 4h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.379l.5 2A.5.5 0 0 1 15.5 17H.5a.5.5 0 0 1-.485-.621l.5-2A.5.5 0 0 1 1 14V7H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 4h.89L8 .95zM3.776 4h8.447L8 2.05 3.776 4zM2 7v7h1V7H2zm2 0v7h2.5V7H4zm3.5 0v7h1V7h-1zm2 0v7H12V7H9.5zM13 7v7h1V7h-1zm2-1V5H1v1h14zm-.39 9H1.39l-.25 1h13.72l-.25-1z"/>
                    </svg></a></li>

            </ul>
        </footer>
    </div>
    <!-- /FOOTER -->

    <!-- Boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <!-- Prism -->
    <script src="prism/prism-customized.js"></script>
</body>

</html>
