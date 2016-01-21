<?php

return [
	['GET', '/', ['Pics\Controllers\Homepage', 'show']],
	['POST', '/upload', ['Pics\Controllers\Upload', 'upload']]
];
