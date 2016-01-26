var commentsubmit = document.getElementById('commentsubmit');
var textinput = document.getElementById('textinput');

function checkCommentSubmit() {
        if(textinput.value.trim() === '') {
                commentsubmit.disabled = true;
        }
        else {
                commentsubmit.disabled = false;
        }
}

checkCommentSubmit();
textinput.addEventListener('input', checkCommentSubmit);

