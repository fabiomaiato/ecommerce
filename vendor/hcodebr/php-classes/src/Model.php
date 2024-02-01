<?php 

namespace Hcode;

class Model
{

	private $values = [];

	public function __call( $name, $args )
	{

		$method = substr( $name, 0, 3 );
		$fieldName = substr( $name, 3, strlen( $name ));

		switch ( $method )
		{

			case "get":
				/**
				 * Returns the name in the login field 
				 * and validating whether the idcategory is defined or not.
				 * Checking with ternary if => isset + ? + NULL
				 **/
				return ( isset( $this->values[ $fieldName ])) ? $this->values[ $fieldName ] : NULL;
			break;

			case "set":
				$this->values[ $fieldName ] = $args[ 0 ]; //return the name position on the deslogin field
			break;

		}

	}

	/**
	 * Creating a method that get the database informations
	 * and automaticlly inserting the gets and sets.
	 **/
	public function setData( $data = array())
	{

		foreach ( $data as $key => $value ) {
			
			$this->{"set".$key}( $value ); // Togettering the set name with the variable key => { "set".key }

		}

	}

	public function getValues()
	{

		return $this->values;

	}

}

 ?>