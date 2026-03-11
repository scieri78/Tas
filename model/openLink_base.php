<?php

class openLink_base_model
{
	// set database config for mysql
	// open mysql data base
	private $_db;

	/**
	 * {@inheritDoc}
	 * @see db_driver::__construct()
	 */
	public function __construct()
	{
		$this->_db = new db_driver();
	}
	
	/**
	 * getListaUtentiForzaDip
	 *
	 * @return void
	 */
	

	
}
