<?php

class Dotpay_Form_Transaction extends Dotpay_Form_Base {

  public function __construct(Dotpay_Model_Transaction $transaction, $options = NULL) {
    parent::__construct($transaction, $options);
  }

  protected function initValidators() {
    $this->validators = array(
      'amount'      => new Zend_Validate_Regex('/^[0-9]{1,6}(.[0-9]{1,2})?$/'),
      'currency'    => new Zend_Validate_InArray(Dotpay_Model_Transaction::getAvailableCurrencies()),
      'description' => new Zend_Validate_StringLength(array('max' => 255)),
      'control'     => new Zend_Validate_StringLength(array('max' => 128)),
      'code'        => new Zend_Validate_StringLength(array('max' => 255))
    );
  }
}