<?php

class Dotpay_Model_TransactionConfirmation extends Dotpay_Model_Base {

  const STATUS_OK          = 'OK';
  const STATUS_FAIL        = 'FAIL';

  const T_STATUS_NEW       = 1;
  const T_STATUS_MADE      = 2;
  const T_STATUS_NEGATIVE  = 3;
  const T_STATUS_CANCELLED = 4;
  const T_STATUS_COMPLAINT = 5;

  const SEPARATOR          = ':';

  public static function getAvailableStatuses() {
    return array(
      self::STATUS_OK,
      self::STATUS_FAIL
    );
  }

  public static function getAvaliableTStatuses() {
    return array(
      self::T_STATUS_NEW,
      self::T_STATUS_MADE,
      self::T_STATUS_NEGATIVE,
      self::T_STATUS_CANCELLED,
      self::T_STATUS_COMPLAINT
    );
  }

  public function setStatus($status) {
    $this->status = $status;
    return $this;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setTId($transactionId) {
    $this->tId = $transactionId;
    return $this;
  }

  public function getTId() {
    return $this->tId;
  }

  public function setOriginalAmount($originalAmount) {
    $this->originalAmount = $originalAmount;
    return $this;
  }

  public function getOriginalAmount() {
    return $this->originalAmount;
  }

  public function setTStatus($tStatus) {
    $this->tStatus = $tStatus;
    return $this;
  }

  public function getTStatus() {
    return $this->tStatus;
  }

  public function setService($service) {
    $this->service = $service;
    return $this;
  }

  public function getService() {
    return $this->service;
  }

  public function setUsername($username) {
    $this->username = $username;
    return $this;
  }

  public function getUsername() {
    return $this->username;
  }

  public function setPassword($password) {
    $this->password = $password;
    return $this;
  }

  public function getPassword() {
    return $this->password;
  }

  public function setMd5($md5) {
    $this->md5 = $md5;
    return $this;
  }

  public function getMd5() {
    return $this->md5;
  }

  public function setTransaction(Dotpay_Model_Transaction $transaction) {
    $this->transaction = $transaction;
    return $this;
  }

  /**
   * @return Dotpay_Model_Transaction
   */
  public function getTransaction() {
    return $this->transaction;
  }

  public function computeMd5() {
    return md5(implode(self::SEPARATOR, array(
      $this->getTransaction()->getSellerAccount()->getPin(),
      $this->getTransaction()->getSellerAccount()->getId(),
      $this->getTransaction()->getControl(),
      $this->getTId(),
      $this->getTransaction()->getAmount(),
      $this->getTransaction()->getCustomer()->getEmail(),
      $this->getService(),
      $this->getTransaction()->getCode(),
      $this->getUsername(),
      $this->getPassword(),
      $this->getTStatus()
    )));
  }
}