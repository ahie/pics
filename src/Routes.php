<?php

return [
	['GET', '/', ['Pics\Controllers\Frontpage', 'show']],
	['GET', '/{id}', ['Pics\Controllers\Picture', 'show']],
	['GET', '/next{num:\d+}/{pid}', ['Pics\Controllers\Picture', 'nextPics']],
	['POST', '/upload', ['Pics\Controllers\Upload', 'upload']],
	['POST', '/comment/{pid}', ['Pics\Controllers\Comment', 'comment']],
	['POST', '/reply/{cid}', ['Pics\Controllers\Comment', 'reply']],
];
