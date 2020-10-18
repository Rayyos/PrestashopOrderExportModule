<?php 


class IsufOrderLog extends ObjectModel
{

    	/**
	 * Fields
	 */
	public $id_orderexporter;
	public $id_order;
	public $id_customer;
	public $id_potal;
	public $id_cart;
	public $sent;
	public $data;
	public $date_add;

	/**
	 * Definition
	 * @var unknown
	 */
	public static $definition = array (
			'table' => 'orderexporter',
			'primary' => 'id_orderexporter',
			'fields' => array (
					'id_order' => 	array('type' => self::TYPE_INT),
					'id_customer' => 	array('type' => self::TYPE_INT),
					'id_potal' => 	array('type' => self::TYPE_STRING),
					'id_cart' => 		array('type' => self::TYPE_INT),
					'sent' => 	array('type' => self::TYPE_BOOL),
					'data' => 	array('type' => self::TYPE_STRING),
					'date_add' => 	array('type' => self::TYPE_DATE),
					
			)
	);


}