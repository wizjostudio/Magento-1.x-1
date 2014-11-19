<?php

class Dotpay_Model_Address extends Dotpay_Model_Base {

  public function setStreet($street) {
    $this->street = $street;
    return $this;
  }

  public function getStreet() {
    return $this->street;
  }

  public function setStreetN1($streetN1) {
    $this->streetN1 = $streetN1;
    return $this;
  }

  public function getStreetN1() {
    return $this->streetN1;
  }

  public function setStreetN2($streetN2) {
    $this->streetN2 = $streetN2;
    return $this;
  }

  public function getStreetN2() {
    return $this->streetN2;
  }

  public function setState($state) {
    $this->state = $state;
    return $this;
  }

  public function getState() {
    return $this->state;
  }

  public function setCity($city) {
    $this->city = $city;
    return $this;
  }

  public function getCity() {
    return $this->city;
  }

  public function setPostcode($postcode) {
    $this->postcode = $postcode;
    return $this;
  }

  public function getPostcode() {
    return $this->postcode;
  }

  public function setCountry($country) {
    $this->country = $country;
    return $this;
  }

  public function getCountry() {
    return $this->country;
  }
}