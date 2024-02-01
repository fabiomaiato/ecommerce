<?php 

namespace Hcode;

/**
 * Extending the class Page
 */
class PageAdmin extends Page
{

	public function __construct( $opts = array(), $tpl_dir = "/views/admin/" )
	{
		/**
		 * Inheritance => Calling the constructor method (parent::__construct) of the parent class => $opts of the parent class, 
		 * => $tpl_dir of the method class.
		 **/
		parent::__construct( $opts, $tpl_dir );

	}

}

 ?>