<?php

return [
	['GET', '/', ['Pics\Controllers\Homepage', 'show']],
	['GET', '/{id}', ['Pics\Controllers\Picture', 'show']],
	['POST', '/upload', ['Pics\Controllers\Upload', 'upload']]
];
