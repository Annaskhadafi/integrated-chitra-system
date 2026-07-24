<?php


if (session_status() == PHP_SESSION_NONE ) {
	session_start ();
}

function csrf_token(){
	if(empty($_SESSION['csrf_token'])){
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}

	return $_SESSION['csrf_token'];
}

function csrf_field()	{
	return '<input type="hidden" name="csrf_token" value="' 
		. htmlspecialchars (csrf_token()) . '">';
}

function verify_csrf()	{
	$token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '' ;

	if(empty($token) || empty($_SESSION['csrf_token'])){
		http_response_code(403);
		die("Akses Ditolak: CSRF Token tidak di temukan. ");

	}
	if(!hash_equals($_SESSION['csrf_token'], $token)){
		http_response_code(403);
		die("Akses Ditolak: CSRF Token tidak di valid");
	}

	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function set_secure_session_cookie(){
	session_set_cookie_params([	
	'lifetime' => 3600,
		'path'	=> '/',
		'domain' => '',
		'secure' => true,
		'httponly' => true,
		'samesite' => 'Strict',
	
	]);
}

function enforce_single_tab() {
	if (empty($_SESSION['active_tab_id'])) {
		$_SESSION['active_tab_id'] = bin2hex(random_bytes(16));

	}
	return $_SESSION['active_tab_id'];

}

function verify_active_tab (){
	$submitted_tab = $_POST['tab_id'] ?? '' ;
	if (empty($submitted_tab) || $submitted_tab !== $_SESSION ['active_tab_id']) {
		http_response_code(403);
		die("Akses ditolak: sesi tidak aktif, Pastikan hanya satu tab yang terbuka. ");
	}
}


function verify_request() {
	verify_csrf();
	verify_active_tab();

}

?>
