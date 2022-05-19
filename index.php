<?php
require_once("init.php");

/**
 * LiSA Analysis - index.php
 * Home Page for LiSA Analysis
 *
 * @author <a href="mailto:lorenzini.susanna@gmail.com">Susanna Lorenzini</a>
 */

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

    <!-- Javascript events -->
    <script type="text/javascript" src="events.js"></script>

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


	<!-- INTRODUCING LiSA Analysis -->
	<div class="container-flex mx-4 mt-5 grey-paragraph">
		<h2 class="green">LiSA Analysis</h2>
		<p>
            LiSA Anlaysis uses <a href='https://github.com/UniVE-SSV/lisa' title='Github LiSA' style="text-decoration: none;">LiSA</a> to perform static analysis on your program. In order to start an analysis:
			<ul>
                <li>Insert your <a href='https://unive-ssv.github.io/lisa/imp/' title='IMP documentation' style='text-decoration: none;'>IMP</a> program, writing source code in the text area or uploading it from your computer</li>
                <li>Check the output files that you want to generate</li>
                <li>Choose the parameters of the analysis that you want to perform</li>
                <li>Press the button Start Analysis</li>
			</ul>
			Once the analysis will be completed you can see the results.
        </p>
    </div>
	<!-- /INTRODUCING LiSA Analysis -->


	<!-- FORMs x START ANALYSIS -->
	<div class="container my-5">
		<form action="analysis.php" method="post" enctype="multipart/form-data">

			<!-- insert SOURCE CODE -->
            <h3 class="green weight-normal">Insert source code<span class="text-muted small-text">&emsp;[must be written using <a href='https://unive-ssv.github.io/lisa/imp/' title='IMP documentation'>IMP</a> language]</span></h3>
			<!-- from text area -->
			<div class="mb-3">
                <label for="text-area-program" class="form-label">Write your code here</label>
                <textarea class="form-control" id="text-area-program" name="sourcecode" rows="5" placeholder="Type or copy/paste source code here" onkeydown="if(event.keyCode===9){ const v=this.value,s=this.selectionStart,e=this.selectionEnd;this.value=v.substring(0, s)+'\t'+v.substring(e);this.selectionStart=this.selectionEnd=s+1;return false;}"></textarea>
            </div>
			<!-- or upload from your computer -->
			<div class="row mb-3">
				<div class="col-md-auto col-ls-auto col-xl-auto mb-3"><label for="upload-program" class="upload">or upload your program</label></div>
                <div class="col input-group input-group-sm mb-3">
                    <input type="file" class="form-control" onclick="resetBtn()" name="sourcefile" id="upload-program" >
                    <button id="reset-btn" type="button" onclick="resetBtn()" class="btn btn-secondary btn-sm btn-block" style="background-color:rgb(230,50,35);border-color:rgb(230,50,35);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                        </svg>
                    </button>
                </div>
			</div>



            <p class="text-muted">please note that if you insert both, uploaded file will be taken for the analysis</p>
			<br><br>



			<!-- Checkbox to choose which OUTPUT will be DUMPED -->
			<h3 class="green weight-normal">Check output files to dump</h3>
			<!-- dump cfgs of the input program-->
			<input type="checkbox" name="dumpcfgs" id="dumpcfgs" value="1" class="form-check-input">
			<label class="form-check-label inline" for="dumpcfgs">Generate control flow graphs (CFGs) of the input program</label><br>
			<!-- dump report.json file -->
			<input type="checkbox" name="jsonoutput" id="jsonoutput" value="1" class="form-check-input" checked>
			<label class="form-check-label inline" for="jsonoutput">Generate report.json at the end of the analysis, containing a list of warnings and a list of generated files</label><br>
			<!-- dump the type inference result -->
			<input type="checkbox" name="typeinference" id="typeinference" value="1" class="form-check-input">
			<label class="form-check-label inline" for="typeinference">Generate results of type inference analysis</label><br>
			<br><br>

            <!-- Selectboxes to set Simple Abstract State parameters -->
            <h3 class="green weight-normal">Choose Simple Abstract State parameters</h3>
			<!-- Selectbox to set HEAP DOMAIN of the analysis to perform -->
            <p>Heap Domain</p>
			<select name="heap" id="heap" class="form-select">
                <option value="monolithicheap">Monolithic Heap</option><!-- Default option for Heap Domain -->
                <option value="fieldsensitivepointbasedheap">Field Sensitive Point Based Heap</option>
                <option value="pointbasedheap">Point Based Heap</option>
                <option value="typebasedheap">Type Based Heap</option>
            </select>
			<br>
            <!-- Selectbox to set VALUE DOMAIN of the analysis to perform -->
            <p>Value Domain</p>
            <select name="value" id="value" class="form-select">
                <option value="interval">Interval</option><!-- Default option for Value Domain -->
                <option value="parity">Parity</option>
                <option value="sign">Sign</option>
                <option value="availableexpressions">Available Expressions</option>
                <option value="constantpropagation">Constant Propagation</option>
                <option value="integerconstantpropagation">Integer Constant Propagation</option>
                <option value="reachingdefinitions">Reaching Definitions</option>
                <option value="noninterference">Non Interference</option>
            </select>
            <br>
            <!-- Selectbox to set TYPE DOMAIN of the analysis to perform -->
            <p>Type Domain</p>
            <select name="type" id="type" class="form-select">
                <option value="inferredtypes">Inferred Types</option><!-- Default option for Type Domain -->
                <option value="statictypes">Static Types</option>
            </select>
            <br><br>

            <!-- Selectbox to set INTERPROCEDURAL ANALYSIS to perform -->
            <h3 class="green weight-normal">Other analysis parameters</h3>
            <p>Interprocedural Analysis</p>
            <select name="interprocedural" id="interprocedural" class="form-select">
                <option value="modularworstcaseanalysis">Modular Worst Case Analysis</option><!-- Default option for Interprocedural Analysis -->
                <option value="contextbasedanalysis">Context Based Analysis</option>
            </select>
            <br>

            <!-- Selectbox to set CALL-GRAPH to use for the analysis -->
            <p>CallGraph<span class="text-muted x-small-text">&emsp;if not set no CallGraph will be used</span></p>
            <select name="callgraph" id="callgraph" class="form-select">
                <option value="none">None</option>
                <option value="chacallgraph">CHA CallGraph</option>
                <option value="rtacallgraph">RTACallGraph</option>
            </select>
            <br>

            <!-- Selectbox to set OPEN CALL POLICY to use for the analysis -->
            <p>Open Call Policy</p>
            <select name="opencallpolicy" id="opencallpolicy" class="form-select">
                <option value="worstcasepolicy">Worst Case Policy</option><!-- Default option for Open Call Policy -->
                <option value="returntoppolicy">Return Top Policy </option>
            </select>
            <br>

            <!-- Selectbox to set FIXPOINT WORKING SET to use for the analysis -->
            <p>Fixpoint Working Set</p>
            <select name="fixpointworkingset" id="fixpointworkingset" class="form-select" disabled>
                <option value="fifoworkingset">FIFO Working Set</option><!-- Default option for Fixpoint Working Set -->
                <option value="concurrentfifoworkingset">Concurrent FIFO Working Set</option>
                <option value="concurrentlifoworkingset">Concurrent LIFO Working Set</option>
                <option value="lifoworkingset">LIFO Working Set</option>
                <option value="visitonceworkingset">Visit Once Working Set</option>
            </select>
            <br>

            <!-- Input to set WIDENING THRESHOLD value -->
            <p>Widening Threshold <span class="text-muted x-small-text">default is 5</span></p>
            <input type="number" name="wideningthreshold" id="wideningthreshold" min="0" value="5" required>
            <br><br><br>

            <!-- Checkbox to set SYNTACTIC and SEMANTIC CHECKS -->
            <h3 class="green weight-normal">Add the Syntactic and Semantic Checks of your interest</h3>
            <!-- Semantic Checks-->
            <!-- NICheck -->
            <input type="checkbox" name="nicheck" id="nicheck" value="1" class="form-check-input"  onclick="nicheckChecked()">
            <label class="form-check-label inline" for="nicheck">Semantic NICheck</label><br>
            <span class="text-muted x-small-text py-2" id="nicheck-checked-text" style="display:none">NB: if NICheck is checked, Value Domain choosen will be forced to Non Interference</span>
            <br><br>

			<button type="submit" class="btn btn-outline-success container" name="CMD_Load">Start Analysis</button>

			<br><br>
		</form>
	</div>
	<!-- /FORMs -->



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
				<li class="ms-2"><a class="text-muted" href="https://github.com/UniVE-SSV/lisa"  title="LiSA GitHub">
                    <svg class="bi" fill="rgb(55, 115, 125)" viewBox="0 0 20 20" width="24" height="24">
					    <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path>
				    </svg></a></li>
				<!-- SSV research group (Bootstrap icon) -->
				<li class="ms-2 me-3"><a class="text-muted" href="https://github.com/UniVE-SSV"  title="SSV research group">
                    <svg class="bi" fill="rgb(70, 145, 95)" viewBox="0 0 20 20" width="24" height="24">
					    <path d="M8 .95 14.61 4h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.379l.5 2A.5.5 0 0 1 15.5 17H.5a.5.5 0 0 1-.485-.621l.5-2A.5.5 0 0 1 1 14V7H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 4h.89L8 .95zM3.776 4h8.447L8 2.05 3.776 4zM2 7v7h1V7H2zm2 0v7h2.5V7H4zm3.5 0v7h1V7h-1zm2 0v7H12V7H9.5zM13 7v7h1V7h-1zm2-1V5H1v1h14zm-.39 9H1.39l-.25 1h13.72l-.25-1z"/>
			    	</svg>
                </a></li>

			</ul>
		</footer>
	</div>
	<!-- /FOOTER -->

	<!-- Boostrap -->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>

</html>
