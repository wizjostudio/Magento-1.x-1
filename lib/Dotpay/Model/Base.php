<?php

abstract class Dotpay_Model_Base {

  private $data = array();

  public function __set($name, $value) {
    $this->data[$name] = $value;
  }

  public function __get($name) {
    if (array_key_exists($name, $this->data))
      return $this->data[$name];
  }

  public function getFieldNames() {
    return array_keys($this->data);
  }
}