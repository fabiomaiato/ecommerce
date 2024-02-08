<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Product;


/*
* Route to product categories screen.
* Creating Category method. List all function. 
*/
$app->get( "/admin/categories", function()
{

	User::verifyLogin();

	$search = ( isset( $_GET[ 'search' ])) ? $_GET[ 'search' ] : "";
	$page = ( isset( $_GET[ 'page' ])) ? ( int )$_GET[ 'page' ] : 1;

	if ( $search != '' ) {

		$pagination = Category::getPageSearch( $search, $page );

	} else {

		$pagination = Category::getPage( $page );

	}

	$pages = [];

	for ($x = 0; $x < $pagination['pages']; $x++)
	{

		array_push( $pages, [
			'href' => '/admin/categories?'.http_build_query([
				'page' => $x + 1,
				'search' => $search
			]),
			'text' => $x + 1
		]);

	}

	$page = new PageAdmin();

	$page->setTpl( "categories", [
		"categories" => $pagination[ 'data' ],
		"search" => $search,
		"pages" => $pages
	]);	


});

/*
* Route to product categories screen.
* Get all Category method. 
*/
$app->get( "/admin/categories/create", function()
{

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl( "categories-create" );	

});

/*
* Route to product categories screen.
* Post all Category method. 
*/
$app->post( "/admin/categories/create", function()
{

	User::verifyLogin();

	$category = new Category(); //Instantiant the category class

	$category->setData( $_POST ); //Setting on the database via post

	$category->save(); //Saving categpry with save method

	header( 'Location: /admin/categories' );
	exit;

});

/*
* Route to product categories.
* Method to category deleting. 
*/
$app->get( "/admin/categories/:idcategory/delete", function( $idcategory )
{

	User::verifyLogin();

	$category = new Category();

	$category->get(( int )$idcategory );

	$category->delete();

	header( 'Location: /admin/categories' );
	exit;

});

/*
* Route to product categories screen.
* Getting Category method to edit. 
*/
$app->get( "/admin/categories/:idcategory", function( $idcategory )
{

	User::verifyLogin();

	$category = new Category();

	$category->get(( int )$idcategory );

	$page = new PageAdmin();

	$page->setTpl( "categories-update", [
		'category' => $category->getValues()
	]);	

});

/*
* Route to product categories screen.
* Sending edited Category with post method. 
*/
$app->post( "/admin/categories/:idcategory", function( $idcategory )
{

	User::verifyLogin();

	$category = new Category();

	$category->get(( int )$idcategory );

	$category->setData( $_POST );

	$category->save();	

	header( 'Location: /admin/categories' );
	exit;

});

/*
* Route to product categories screen.
* Accessing the product Category with get method. 
*/
$app->get( "/admin/categories/:idcategory/products", function( $idcategory )
{

	User::verifyLogin();

	$category = new Category();

	$category->get(( int )$idcategory );

	$page = new PageAdmin();

	$page->setTpl( "categories-products", [
		'category' => $category->getValues(),
		'productsRelated' => $category->getProducts(),
		'productsNotRelated' => $category->getProducts( false )
	]);

});

/*
* Route to products categoty.
* Template add products in the clas category with GET method. 
*/
$app->get( "/admin/categories/:idcategory/products/:idproduct/add", function( $idcategory, $idproduct )
{

	User::verifyLogin();

	$category = new Category();

	$category->get(( int )$idcategory );

	$product = new Product();

	$product->get(( int )$idproduct );

	$category->addProduct( $product );

	header( "Location: /admin/categories/".$idcategory."/products" );
	exit;

});

/*
* Route to products categoty.
* Template remove products in the clas category with GET method. 
*/
$app->get( "/admin/categories/:idcategory/products/:idproduct/remove", function( $idcategory, $idproduct )
{

	User::verifyLogin();

	$category = new Category();

	$category->get(( int )$idcategory );

	$product = new Product();

	$product->get(( int )$idproduct );

	$category->removeProduct( $product );

	header( "Location: /admin/categories/".$idcategory."/products" );
	exit;

});

 ?>