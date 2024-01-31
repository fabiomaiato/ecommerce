<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;


/*
* Route to password user edit button. Via GET
* New button user password edit.
*/
$app->get("/admin/users/:iduser/password", function($iduser)
{

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-password", [
		"user"=>$user->getValues(),
		"msgError"=>User::getError(),
		"msgSuccess"=>User::getSuccess()
	]);

});

/*
* Route to password user edit button. Via POST
* New button user password edit.
*/
$app->post("/admin/users/:iduser/password", function($iduser)
{

	User::verifyLogin();

	/**
	 * This code does the password checks.
	 * If the password was entered. If the password field is not empty.
	 * If the password confirmation is the same as the entered password.
	 **/
	if (!isset($_POST['despassword']) || $_POST['despassword']==='') {

		User::setError("Preencha a nova senha.");
		header("Location: /admin/users/$iduser/password");
		exit;

	}

	if (!isset($_POST['despassword-confirm']) || $_POST['despassword-confirm']==='') {

		User::setError("Preencha a confirmação da nova senha.");
		header("Location: /admin/users/$iduser/password");
		exit;

	}

	if ($_POST['despassword'] !== $_POST['despassword-confirm']) {

		User::setError("Confirme corretamente as senhas.");
		header("Location: /admin/users/$iduser/password");
		exit;

	}

	$user = new User();

	$user->get((int)$iduser);

	$user->setPassword(User::getPasswordHash($_POST['despassword']));

	User::setSuccess("Senha alterada com sucesso.");

	header("Location: /admin/users/$iduser/password");
	exit;

});

/*
* Route for user search and pagination.
* Searches for the user and limits the number  of records per page.
*/
$app->get("/admin/users", function()
{

	User::verifyLogin();

	$search = (isset($_GET['search'])) ? $_GET['search'] : "";
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if ($search != '') {

		$pagination = User::getPageSearch($search, $page);

	} else {

		$pagination = User::getPage($page);

	}

	$pages = [];

	for ($x = 0; $x < $pagination['pages']; $x++)
	{

		array_push($pages, [
			'href'=>'/admin/users?'.http_build_query([
				'page'=>$x+1,
				'search'=>$search
			]),
			'text'=>$x+1
		]);

	}

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$pagination['data'],
		"search"=>$search,
		"pages"=>$pages
	));

});

/*
* Route to screen new user create.
*/
$app->get("/admin/users/create", function()
{

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

/*
* Route to screen user delete.
*/
$app->get("/admin/users/:iduser/delete", function($iduser)
{

	User::verifyLogin();	

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

/*
* Route to screen user update/edit.
*/
$app->get("/admin/users/:iduser", function($iduser)
{

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

/*
* Route to screen new user create.
*/
$app->post("/admin/users/create", function()
{

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$_POST['despassword'] = User::getPasswordHash($_POST['despassword']);

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;

});

/*
* Route to screen user updating.
*/
$app->post("/admin/users/:iduser", function($iduser)
{

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();	

	header("Location: /admin/users");
	exit;

});

 ?>