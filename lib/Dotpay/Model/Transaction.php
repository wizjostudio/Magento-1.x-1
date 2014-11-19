<?php

class Dotpay_Model_Transaction extends Dotpay_Model_Base {

  const CURRENCY_PLN = 'PLN';
  const CURRENCY_EUR = 'EUR';
  const CURRENCY_USD = 'USD';
  const CURRENCY_GBP = 'GBP';
  const CURRENCY_JPY = 'JPY';
  const CURRENCY_CZK = 'CZK';
  const CURRENCY_SEK = 'SEK';

  const STATUS_UNKNOWN   = -1;

  const STATUS_NEW       = Dotpay_Model_TransactionConfirmation::T_STATUS_NEW;
  const STATUS_MADE      = Dotpay_Model_TransactionConfirmation::T_STATUS_MADE;
  const STATUS_NEGATIVE  = Dotpay_Model_TransactionConfirmation::T_STATUS_NEGATIVE;
  const STATUS_CANCELLED = Dotpay_Model_TransactionConfirmation::T_STATUS_CANCELLED;
  const STATUS_COMPLAINT = Dotpay_Model_TransactionConfirmation::T_STATUS_COMPLAINT;

  public static function getAvailableCurrencies() {
    return array(
      self::CURRENCY_PLN,
      self::CURRENCY_EUR,
      self::CURRENCY_USD,
      self::CURRENCY_GBP,
      self::CURRENCY_JPY,
      self::CURRENCY_CZK,
      self::CURRENCY_SEK
    );
  }

  public function setAmount($amount) {
    $this->amount = $amount;
    return $this;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function setCurrency($currency) {
    $this->currency = $currency;
    return $this;
  }

  public function getCurrency() {
    return $this->currency;
  }

  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setControl($control) {
    $this->control = $control;
    return $this;
  }

  public function getControl() {
    return $this->control;
  }

  public function setCode($code) {
    $this->code = $code;
    return $this;
  }

  public function getCode() {
    return $this->code;
  }

  public function setSellerAccount(Dotpay_Model_SellerAccount $sellerAccount) {
    $this->sellerAccount = $sellerAccount;
    return $this;
  }

  /**
   * @return Dotpay_Model_SellerAccount
   */
  public function getSellerAccount() {
    return $this->sellerAccount;
  }

  public function setCustomer(Dotpay_Model_Customer $customer) {
    $this->customer = $customer;
    return $this;
  }

  /**
   * @return Dotpay_Model_Customer
   */
  public function getCustomer() {
    return $this->customer;
  }

  public function setConfiguration(Dotpay_Model_TransactionConfiguration $configuration) {
    $this->configuration = $configuration;
    return $this;
  }

  /**
   * @return Dotpay_Model_TransactionConfiguration
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  public static function getStatus($tId, $tStatus, $lastTId, $lastTStatus) {

    if ($tId == $lastTId && $tStatus == $lastTStatus)
      return NULL;

    if ($lastTStatus == self::STATUS_UNKNOWN)
      return NULL;

    if (in_array($tStatus, array(self::STATUS_CANCELLED, self::STATUS_COMPLAINT)) && $lastTStatus != self::STATUS_MADE)
      return self::STATUS_UNKNOWN;

    if ($lastTStatus === NULL && in_array($tStatus, array(self::STATUS_NEW, self::STATUS_MADE, self::STATUS_NEGATIVE)))
      return $tStatus;

    $data = array(
      array(FALSE, self::STATUS_MADE,     self::STATUS_CANCELLED, NULL),
      array(FALSE, self::STATUS_MADE,     self::STATUS_COMPLAINT, NULL),
      array(TRUE,  self::STATUS_MADE,     self::STATUS_CANCELLED, self::STATUS_CANCELLED),
      array(TRUE,  self::STATUS_MADE,     self::STATUS_COMPLAINT, self::STATUS_COMPLAINT),
      array(TRUE,  self::STATUS_NEW,      self::STATUS_MADE,      self::STATUS_MADE),
      array(TRUE,  self::STATUS_NEW,      self::STATUS_NEGATIVE,  self::STATUS_NEGATIVE),
      array(FALSE, self::STATUS_NEW,      self::STATUS_NEW,       NULL),
      array(FALSE, self::STATUS_NEW,      self::STATUS_MADE,      self::STATUS_UNKNOWN),
      array(FALSE, self::STATUS_NEW,      self::STATUS_NEGATIVE,  NULL),
      array(TRUE,  self::STATUS_MADE,     self::STATUS_NEW,       self::STATUS_UNKNOWN),
      array(TRUE,  self::STATUS_MADE,     self::STATUS_NEGATIVE,  self::STATUS_UNKNOWN),
      array(FALSE, self::STATUS_MADE,     self::STATUS_NEW,       NULL),
      array(FALSE, self::STATUS_MADE,     self::STATUS_MADE,      self::STATUS_UNKNOWN),
      array(FALSE, self::STATUS_MADE,     self::STATUS_NEGATIVE,  self::STATUS_MADE),
      array(TRUE,  self::STATUS_NEGATIVE, self::STATUS_NEW,       NULL),
      array(TRUE,  self::STATUS_NEGATIVE, self::STATUS_MADE,      self::STATUS_UNKNOWN),
      array(FALSE, self::STATUS_NEGATIVE, self::STATUS_NEW,       NULL),
      array(FALSE, self::STATUS_NEGATIVE, self::STATUS_MADE,      self::STATUS_UNKNOWN),
      array(FALSE, self::STATUS_NEGATIVE, self::STATUS_NEGATIVE,  NULL),
    );

    foreach ($data as $row) {
      list($_tIdsEquality, $_lastTStatus, $_tStatus, $_nextTStatus) = $row;
      if (($tId == $lastTId && !$_tIdsEquality) || ($tId != $lastTId && $_tIdsEquality))
        continue;
      if ($lastTStatus == $_lastTStatus && $tStatus == $_tStatus)
        return $_nextTStatus;
    }

    return self::STATUS_UNKNOWN;
  }
}