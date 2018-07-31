<?php

namespace IPG;

class Cart
{

	/**
	 * Array of items to purchase
	 * @var array 
	 */
	private $cart;

	/**
	 * Add item to cart
	 * 
	 * @param string $name Item name
	 * @param int $quantity Item quantity
	 * @param float $price Item single price
	 * 
	 * @return Cart
	 * @throws IPG_Exception
	 */
	public function add($name, $quantity, $price)
	{
		if ( empty( $name ) ) {
			throw new IPG_Exception( 'Invalid cart item name' );
		}

		if ( empty( $quantity ) || !is_int( $quantity ) || count( $quantity ) <= 0 ) {
			throw new IPG_Exception( 'Invalid cart item quantity' );
		}

		if ( empty( $price ) || !is_float( $price ) ) {
			throw new IPG_Exception( 'Invalid cart item price' );
		}

		$this->cart[] = array(
			'name' => $name,
			'quantity' => $quantity,
			'price' => $price
		);

		return $this;
	}

	public function getTotalItemsCount()
	{
		return count( $this->getCart() );
	}

	/**
	 * Array of all items
	 * 
	 * @return array
	 */
	public function getCart()
	{
		return $this->cart;
	}

	/**
	 * Validate cart items
	 *
	 * @return boolean
	 * @throws IPG_Exception
	 */
	public function validate()
	{
		if ( !$this->getCart() || $this->getTotalItemsCount() == 0 ) {
			throw new IPG_Exception( 'Missing cart items' );
		}
		return true;
	}

}
