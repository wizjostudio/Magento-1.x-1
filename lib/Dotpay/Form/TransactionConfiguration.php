<?php

class Dotpay_Form_TransactionConfiguration extends Dotpay_Form_Base {

  public function __construct(Dotpay_Model_TransactionConfiguration $configuration, $options = NULL) {
    parent::__construct($configuration, $options);
  }

  protected function normalizeFieldName($field) {
    if (in_array($field, array('url', 'urlc')))
      return strtoupper($field);
    return parent::normalizeFieldName($field);
  }

  protected function initValidators() {
    $this->validators = array(
      'lang'           => new Zend_Validate_InArray(Dotpay_Model_TransactionConfiguration::getAvailableLanguages()),
      'onlinetransfer' => new Zend_Validate_Callback(array($this, 'validateBoolean')),
      'URL'            => array(
        new Zend_Validate_StringLength(array('max' => 255)),
        new Zend_Validate_Callback(array($this, 'validateUrl'))
      ),
      'URLC'           => array(
        new Zend_Validate_StringLength(array('max' => 255)),
        new Zend_Validate_Callback(array($this, 'validateUrl'))
      )
    );
  }

  public function validateBoolean($value) {
    return is_bool($value);
  }

  public function validateUrl($value) {
    return Zend_Uri::check($value);
  }
}