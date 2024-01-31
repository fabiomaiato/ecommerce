<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;

/*
* Route to admin template.
*/
$app->get('/admin', function()
{
    
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

});

/*
* Route to admin login. GET
* Route to admin login template page.
*/
$app->get('/admin/login', function()
{

	$page = new PageAdmin([
		/*
		* Desabling the automatic call of the default header and footer,
		* because the login page is different from the others and the user is not yet logged in.
		*/
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");

});

/*
* Route to admin login. POST
* Route to admin login template page.
*/
$app->post('/admin/login', function()
{

	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");
	exit;

});

/*
* Route to admin logout. GET
* Route to admin logout destroy session.
*/
$app->get('/admin/logout', function()
{

	User::logout();

	header("Location: /admin/login");
	exit;

});

/*
* Creating route to admin user page forgot password. GET
* Route to forgot admin user password. 
*/
$app->get("/admin/forgot", function()
{

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");	

});

/*
* Creating route to admin user page forgot password. POST
* Route to forgot admin user sending password reset. 
*/
$app->post("/admin/forgot", function()
{

	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");
	exit;

});

$app->get("/admin/forgot/sent", function()
{

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-sent");	

});

/*
* Creating route to admin user page forgot password. POST
* Route to forgot admin user sending password reset. 
*/
$app->get("/admin/forgot/reset", function()
{

	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));

});

$app->post("/admin/forgot/reset", function()
{

	$forgot = User::validForgotDecrypt($_POST["code"]);	

	User::setFogotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = User::getPasswordHash($_POST["password"]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset-success");

});

 ?>