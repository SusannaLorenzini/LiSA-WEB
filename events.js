/**
 * LiSA Analysis - events.js
 * Javascript to handle LiSA Analysis events
 *
 * @author <a href="mailto:lorenzini.susanna@gmail.com">Susanna Lorenzini</a>
 */


/* index.php */
// Remove file uploaded
function resetBtn(){
    document.getElementById("upload-program").value = "";
}

// When NICheck is checked, show a message
function nicheckChecked() {
    const checkBox = document.getElementById("nicheck");
    const text = document.getElementById("nicheck-checked-text");
    if (checkBox.checked === true){
        text.style.display = "block";
    } else {
        text.style.display = "none";
    }
}


/* analysis.php */
// Switch to plain text or back to syntax highlighted text
function plain_highlighted_sourcecode() {
    const plain_text = document.getElementById("container-plain-sourceraw");
    const highlighted_text = document.getElementById("container-sourcerow");
    if (plain_text.style.display === "none") {
        highlighted_text.style.display = "none";
        plain_text.style.display = "block";
    } else {
        plain_text.style.display = "none";
        highlighted_text.style.display = "block";
    }
}

// Highlight warning line
function highlightRow(row) {
    const warning_lines = document.querySelectorAll(".warning-line");
    warning_lines.forEach(line => {
        line.classList.remove("highlight");
    });
    document.getElementById("row"+row).classList.add("highlight");
    document.getElementById("plain-row"+row).classList.add("highlight");
    document.getElementById("row"+row).scrollIntoView({behavior: 'smooth', block: 'center'});
    document.getElementById("plain-row"+row).scrollIntoView({behavior: 'smooth', block: 'center'});
}

// Show all warnings, highlighting them in Source Code tab
function show_all_highlighting(json_warnings_info){
    for (let i=0; i<json_warnings_info.length; i++){
        document.getElementById("row"+json_warnings_info[i]['row']).classList.add("highlight");
        document.getElementById("plain-row"+json_warnings_info[i]['row']).classList.add("highlight");
    }
}

// Hide all highlighting in Source Code tab
function hide_all_highlighting(){
    const warning_lines = document.querySelectorAll(".warning-line");
    warning_lines.forEach(line => {
        line.classList.remove("highlight");
    });
}

// Select all text in Source Code tab
function select_all_text() {
    window.getSelection().selectAllChildren(document.getElementById("sourcecode-card-body"));
}

// On scroll: show and hide scroll-to-top-icon and handle it not to hide footer at the bottom of the page
window.onscroll = function() {
    if(scrollY >= 30) {
        document.getElementById('scroll-to-top-icon').style.visibility="visible";
    }else{
        document.getElementById('scroll-to-top-icon').style.visibility="hidden";
    }

    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 50) {
        document.getElementById('scroll-to-top-icon').style.bottom= '7rem';
    } else {
        document.getElementById('scroll-to-top-icon').style.bottom= '2.5rem';
    }
};

// If scroll-to-top-icon was clicked, scroll to the top of the page
function scrollTopPage(){
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Hide Warnings card
function hide_warnings(){
    document.getElementById('div-card-warnings').style.display="none";
    document.getElementById('div-card-sourcecode').classList.remove("col-md-8", "col-lg-8", "col-xl-8");
    document.getElementById('hide-warnings').style.display="none";
    document.getElementById('show-warnings').style.display="inline-block";
}

// Show Warnings card
function show_warnings(){
    document.getElementById('div-card-sourcecode').classList.add("col-md-8", "col-lg-8", "col-xl-8");
    document.getElementById('div-card-warnings').style.display="block";
    document.getElementById('show-warnings').style.display="none";
    document.getElementById('hide-warnings').style.display="inline-block";
}
