<?php

class Dotpay_Form_Customer extends Dotpay_Form_Base {

  public function __construct(Dotpay_Model_Customer $customer, $options = NULL) {
    parent::__construct($customer, $options);
  }

  protected function initValidators() {
    $this->validators = array(
      'firstname' => new Zend_Validate_StringLength(array('max' => 50)),
      'lastname'  => new Zend_Validate_StringLength(array('max' => 50)),
      'email'     => array(
        new Zend_Validate_EmailAddress,
        new Zend_Validate_StringLength(array('max' => 40))
      ),
      'phone'     => new Zend_Validate_StringLength(array('max' => 255))
    );
  }
}