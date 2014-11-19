<?php

class Dotpay_Model_TransactionConfiguration extends Dotpay_Model_Base {

  const LANGUAGE_POLISH    = 'pl';
  const LANGUAGE_ENGLISH   = 'en';
  const LANGUAGE_GERMAN    = 'de';
  const LANGUAGE_ITALIAN   = 'it';
  const LANGUAGE_FRENCH    = 'fr';
  const LANGUAGE_SPANISH   = 'es';
  const LANGUAGE_CZECH     = 'cz';
  const LANGUAGE_RUSSIAN   = 'ru';
  const LANGUAGE_BULGARIAN = 'bg';

  public static function getAvailableLanguages() {
    return array(
      self::LANGUAGE_POLISH,
      self::LANGUAGE_ENGLISH,
      self::LANGUAGE_GERMAN,
      self::LANGUAGE_ITALIAN,
      self::LANGUAGE_FRENCH,
      self::LANGUAGE_SPANISH,
      self::LANGUAGE_CZECH,
      self::LANGUAGE_RUSSIAN,
      self::LANGUAGE_BULGARIAN
    );
  }

  public function setLang($lang) {
    $this->lang = $lang;
    return $this;
  }

  public function getLang() {
    return $this->lang;
  }

  public function setOnlinetransfer($onlinetransfer) {
    $this->onlinetransfer = $onlinetransfer;
    return $this;
  }

  public function getOnlinetransfer() {
    return $this->onlinetransfer;
  }

  public function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrlc($urlc) {
    $this->urlc = $urlc;
    return $this;
  }

  public function getUrlc() {
    return $this->urlc;
  }
}