var filesubmit = document.getElementById('filesubmit');
var fileinput = document.getElementById('fileinput');

function checkFileSubmit() {
        if(fileinput.files.length === 0) {
                filesubmit.disabled = true;
        }
        else {
                filesubmit.disabled = false;
        }
}

checkFileSubmit();
fileinput.addEventListener('change', checkFileSubmit);

