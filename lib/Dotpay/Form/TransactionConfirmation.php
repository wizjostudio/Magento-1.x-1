<?php

class Dotpay_Form_TransactionConfirmation extends Dotpay_Form_Base {

  public function __construct(Dotpay_Model_TransactionConfirmation $confirmation, $options = NULL) {
    parent::__construct($confirmation, $options);
  }

  /**
   * @return Dotpay_Model_TransactionConfirmation
   */
  public function getModel() {
    return parent::getModel();
  }

  protected function initValidators() {
    $this->validators = array(
      'status'         => new Zend_Validate_InArray(Dotpay_Model_TransactionConfirmation::getAvailableStatuses()),
      't_id'           => new Zend_Validate_StringLength(array('max' => 255)),
      'orginal_amount' => new Zend_Validate_StringLength(array('max' => 255)),
      't_status'       => new Zend_Validate_InArray(Dotpay_Model_TransactionConfirmation::getAvaliableTStatuses()),
      'service'        => new Zend_Validate_StringLength(array('max' => 255)),
      'username'       => new Zend_Validate_StringLength(array('max' => 255)),
      'password'       => new Zend_Validate_StringLength(array('max' => 255)),
      'md5'            => array(
        new Zend_Validate_Alnum,
        new Zend_Validate_StringLength(array('max' => 32)),
        new Zend_Validate_Callback(array($this, 'validateMd5'))
      )
    );
  }

  public function validateMd5($value) {
    return $value == $this->getModel()->computeMd5();
  }
}