<?php

class Dotpay_Model_Customer extends Dotpay_Model_Base {

  public function setFirstname($firstName) {
    $this->firstname = $firstName;
    return $this;
  }

  public function getFirstname() {
    return $this->firstname;
  }

  public function setLastname($lastName) {
    $this->lastname = $lastName;
    return $this;
  }

  public function getLastname() {
    return $this->lastname;
  }

  public function setEmail($email) {
    $this->email = $email;
    return $this;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setPhone($phone) {
    $this->phone = $phone;
    return $this;
  }

  public function getPhone() {
    return $this->phone;
  }

  public function setAddress(Dotpay_Model_Address $address) {
    $this->address = $address;
    return $this;
  }

  /**
   * @return Dotpay_Model_CustomerAddress
   */
  public function getAddress() {
    return $this->address;
  }
}