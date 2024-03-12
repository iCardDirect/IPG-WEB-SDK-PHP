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
     * Customer identifier
     * @var string
     */
    private $identifier;

	/**
	 * Customer IP Address
	 * @var string 
	 */
	private $ip;

    /**
     * Customer mobile number. Used for 3DS authentication
     * @var string
     */
    private $mobileNumber;

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
     * Set customer identifier
     *
     * @param string $identifier
     * @return Customer
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Customer identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
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

    /**
     * @param string $mobileNumber
     * @return Customer
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }
}
