<?php
require_once("init.php");
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

	<title>LiSA, Static Analysis with LiSA</title>
</head>

<body class="d-flex flex-column min-vh-100">

	<!-- HEADER -->
	<div class="header-container container-flex">
		<header class="container-flex mx-5 d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 my-2">

			<!-- LiSA Analysis logo -->
			<a href="index.php" class="col-3 col-sm-3 col-md-2 col-xl-1 d-flex align-items-center text-dark text-decoration-none">
				<img src="media/logo.png" class="img-fluid bi me-2" alt="LiSA Analysis logo">
			</a>

			<!-- NAV -->
			<ul class="nav col-md-auto mb-2 justify-content-center mb-md-0">
				<!-- LiSA documentation -->
				<li><a href="https://unive-ssv.github.io/lisa/" class="nav-link p-1 m-1">Documentation</a></li>
				<!-- GitHub repository -->
				<li><a href="https://github.com/UniVE-SSV/lisa" class="nav-link p-1 m-1">GitHub&nbsprepository</a></li>
				<!-- SSV research group -->
				<li><a href="https://github.com/UniVE-SSV" class="nav-link p-1 m-1">SSV&nbspgroup</a></li>
			</ul>
			<!-- /NAV -->

		</header>
	</div>
	<!-- /HEADER -->


	<!-- INTRODUCING LiSA Analysis -->
	<div class="container-flex mx-4 mt-5 grey-paragraph">
		<h2 class="green">LiSA Analysis</h2>
		<p>
			LiSA Anlaysis uses LiSA to perform static analysis on your program. In order to start an analysis:
			<ul>
				<li>Insert your program, writing source code in the text area or uploading it from your computer</li>
				<li>Choose the parameters to set</li>
				<li>Choose the type of analysis that you want to perform</li>
				<li>Press the button Start analysis</li>
			</ul>
			Once the analysis will be completed you can see the results.
		</p>
	</div>
	<!-- /INTRODUCING LiSA Analysis -->


	<!-- FORMs x START ANALYSIS -->

	<div class="container my-5">
		<form action="analysis.php" method="post" enctype="multipart/form-data">

			<!-- insert SOURCE CODE -->
			<h3 class="green weight-normal">Insert source code</h3>

			<!-- from text area -->
			<div class="mb-3">
				<label for="text-area-program" class="form-label">Write your code here</label>
				<textarea class="form-control" id="text-area-program" name="sourcecode" rows="5" placeholder="Type or copy/paste source code here"></textarea>
			</div>

			<!-- or upload from your computer -->
			<div class="row mb-3">
				<div class="col-auto"><label for="upload-program" class="upload">or upload your program</label></div>
				<div class="col"><input class="form-control form-control-sm" type="file" name="sourcefile" id="upload-program"></div>
			</div>
			<br><br>


			<!-- Checkbox to set PARAMETERS for the analysis to perform -->
			<h3 class="green weight-normal">Check the parameters of your interest</h3>
			<!-- dump cfgs of the input program-->
			<input type="checkbox" name="dumpcfgs" id="dumpcfgs" value="1" class="form-check-input">
			<label class="form-check-label" for="dumpcfgs">Dump control flow graphs (CFGs) of the input program</label><br>
			<!-- dump report.json file -->
			<input type="checkbox" name="jsonoutput" id="jsonoutput" value="1" class="form-check-input">
			<label class="form-check-label" for="jsonoutput">Dump report.json at the end of the analysis, containing a list of warnings and a list of generated files</label><br>
			<!-- dump the type inference resutl -->
			<input type="checkbox" name="typeinference" id="typeinference" value="1" class="form-check-input">
			<label class="form-check-label" for="typeinference">Perform the type inference analysis</label><br>
			<br><br>


			<!-- Selectbox to set TYPE of the analysis to perform -->
			<h3 class="green weight-normal">Choose the type of the analysis to perform</h3>
			<select name="type" id="type" class="form-select">
				<option value="sign">Sign</option>
				<option value="parity">Parity</option>
				<option value="interval">Interval</option>
				<option value="integerConstantPropagation">Integer Constant Propagation</option>
				<option value="typesCollection">Type Inference</option>
				<option value="availableExpressions">Available Expressions</option>
				<option value="constantPropagationDF">Constant Propagation DF</option>
				<option value="reachingDefinitions">Reaching Definitions</option>
				<option value="fieldInsensitivePointBasedHeap">Field Insensitive Point Based Heap</option>
				<option value="fieldSensitivePointBasedHeap">Field Sensitive Point Based Heap</option>
				<option value="typeBasedHeap">Type Based Heap</option>
				<option value="RTAContextSensitive1">RTA Context Sensitive Sign</option>
				<option value="RTAContextSensitive5">RTA Context Sensitive Interval</option>
				<option value="CHACallGraph">CHA CallGraph</option>
				<option value="RTACallGraph">RTA CallGraph</option>
				<option value="confidentialityNI">Confidentiality NI</option>
				<option value="integrityNI">Integrity NI</option>
				<option value="interproc">Interproc</option>
			</select>
			<br><br>

			<button type="submit" class="btn btn-outline-success container" name="CMD_Load">Start Analysis</button>

			<br><br>
		</form>




	</div>
	<!-- /FORMs -->





	<!-- FOOTER -->
	<div class="container-fluid mt-auto">

		<!-- LiSA library logo, Year, Company, Inc at the bottom left -->
		<footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
			<div class="col-md-4 d-flex align-items-center">
				<a href="index.html" class="mb-3 mx-2 mb-md-0 text-muted text-decoration-none lh-1">
					<img src="media/LiSALibrary.png" class="bi img-fluid bi me-2" width="30" alt="LiSA logo">
				</a>
				<span class="text-muted"><?= COPYRIGHT ?></span>
			</div>

			<!-- icons at bottom right -->
			<ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
				<!-- LiSA Documentation (Bootstrap icon) -->
				<li class="ms-2"><a class="text-muted" href="https://unive-ssv.github.io/lisa/">
					<svg xmlns="http://www.w3.org/2000/svg" class="bi" fill="rgb(40, 85, 145)" viewBox="0 0 20 20" width="24" height="24">
						<path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
					</svg>
				</a></li>
				<!-- GitHub repository -->
				<li class="ms-2"><a class="text-muted" href="https://github.com/UniVE-SSV/lisa"><svg class="bi" fill="rgb(55, 115, 125)" viewBox="0 0 20 20" width="24" height="24">
					<path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path>
				</svg></a></li>
				<!-- SSV research group (Bootstrap icon) -->
				<li class="ms-2 me-3"><a class="text-muted" href="https://github.com/UniVE-SSV"><svg class="bi" fill="rgb(70, 145, 95)" viewBox="0 0 20 20" width="24" height="24">
					<path d="M8 .95 14.61 4h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.379l.5 2A.5.5 0 0 1 15.5 17H.5a.5.5 0 0 1-.485-.621l.5-2A.5.5 0 0 1 1 14V7H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 4h.89L8 .95zM3.776 4h8.447L8 2.05 3.776 4zM2 7v7h1V7H2zm2 0v7h2.5V7H4zm3.5 0v7h1V7h-1zm2 0v7H12V7H9.5zM13 7v7h1V7h-1zm2-1V5H1v1h14zm-.39 9H1.39l-.25 1h13.72l-.25-1z"/>
				</svg></a></li>

			</ul>
		</footer>
	</div>
	<!-- /FOOTER -->

	<!-- Boostrap -->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>

</html>