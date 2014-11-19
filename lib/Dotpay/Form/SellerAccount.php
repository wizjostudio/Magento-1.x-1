<?php

class Dotpay_Form_SellerAccount extends Dotpay_Form_Base {

  protected $required = array('id');

  public function __construct(Dotpay_Model_SellerAccount $sellerAccount, $options = NULL) {
    parent::__construct($sellerAccount, $options);
  }

  protected function getModelFieldNames() {
    return array_intersect_key(parent::getModelFieldNames(), array('pin'));
  }

  protected function initValidators() {
    $this->validators = array(
      'id' => new Zend_Validate_Regex(array('pattern' => '/^[0-9]{1,7}$/'))
    );
  }
}