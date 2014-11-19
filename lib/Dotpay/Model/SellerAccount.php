<?php

class Dotpay_Model_SellerAccount extends Dotpay_Model_Base {

  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  public function getId() {
    return $this->id;
  }

  public function setPin($pin) {
    $this->pin = $pin;
    return $this;
  }

  public function getPin() {
    return $this->pin;
  }
}