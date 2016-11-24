<?php
function login(){
	$admin = session('admin');
	if(empty($admin)){
		$url = U('Login/index');
		header("location:".$url);
	}
}
?>