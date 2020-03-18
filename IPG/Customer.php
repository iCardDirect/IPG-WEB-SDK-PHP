<?php

namespace IPG;

class Customer
{

	/**
	 * Customer Email
	 * @var string 
	 */
	private $email;

	/**
	 * Customer IP Address
	 * @var string 
	 */
	private $ip;

	/**
	 * Set Customer email
	 * 
	 * @param string $email
	 * 
	 * @return Customer
	 * @throws IPG_Exception
	 */
	public function setEmail($email)
	{
		if ( !Helper::isValidEmail( $email ) ) {
			throw new IPG_Exception( 'Invalid customer email' );
		}
		$this->email = $email;

		return $this;
	}

	/**
	 * Customer email
	 * 
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set customer IP address
	 * 
	 * @param string $ip
	 * 
	 * @return Customer
	 * @throws IPG_Exception
	 */
	public function setIP($ip)
	{
		if ( !Helper::isValidIP( $ip ) ) {
			throw new IPG_Exception( 'Invalid customer IP Address' );
		}
		$this->ip = $ip;

		return $this;
	}

	/**
	 * Customer IP address
	 * 
	 * @return string
	 */
	public function getIP()
	{
		return $this->ip;
	}

}
