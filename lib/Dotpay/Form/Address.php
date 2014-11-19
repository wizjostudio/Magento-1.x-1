<?php

class Dotpay_Form_Address extends Dotpay_Form_Base {

  public function __construct(Dotpay_Model_Address $address, $options = NULL) {
    parent::__construct($address, $options);
  }

  protected function initValidators() {
    $this->validators = array(
      'street'    => new Zend_Validate_StringLength(array('max' => 255)),
      'street_n1' => new Zend_Validate_StringLength(array('max' => 255)),
      'street_n2' => new Zend_Validate_StringLength(array('max' => 255)),
      'state'     => new Zend_Validate_StringLength(array('max' => 255)),
      'city'      => new Zend_Validate_StringLength(array('max' => 255)),
      'city'      => new Zend_Validate_StringLength(array('max' => 255)),
      'postcode'  => new Zend_Validate_StringLength(array('max' => 255)),
      'country'   => new Zend_Validate_StringLength(array('max' => 255))
    );
  }
}