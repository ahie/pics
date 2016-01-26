function reply(element, id) {
	var form = document.createElement('form');
	form.setAttribute('method', 'post');
	form.setAttribute('action', '/reply/' + id);

	var input = document.createElement('textarea');
	input.setAttribute('name', 'text');
	input.setAttribute('maxlength', '15000');
	input.setAttribute('rows', '6');
	input.setAttribute('cols', '60');

	var submit = document.createElement('input');
	submit.setAttribute('type', 'submit');
	submit.setAttribute('value', 'add reply');

	var cancel = document.createElement('input');
	cancel.setAttribute('type', 'button');
	cancel.setAttribute('value', 'cancel');
	cancel.setAttribute('onclick', 'cancelReply(this)');

	form.appendChild(input);
	form.appendChild(submit);
	form.appendChild(cancel);
	
	element.parentNode.insertBefore(form, element.nextSibling);
}

function cancelReply(which) {
	var form = which.parentNode;
	form.parentNode.removeChild(form);
}
