<?php

/**
*
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to tech@dotpay.pl so we can send you a copy immediately.
*
*
*  @author    Dotpay Team <tech@dotpay.pl>
*  @copyright Dotpay
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*
*/

/**
 * Abstract Dotpay API class
 */

abstract class Dotpay_Dotpay_Model_Api_Api {
    /**
     *
     * @var arrya|null Contains fields, which are given during confirmation by Dotpay URLC
     */
    protected $_confirmFields = null;
    
    /**
     * Returns data of order which should be gave to Dotpay
     */
    abstract public function getPaymentData($id, $order, $type);
    
    /**
     * Gets payment data from payment confirmation request and returns it
     */
    abstract public function getConfirmFieldsList();
    
    /**
     * Returns total amount from payment confirmation
     */
    abstract public function getTotalAmount();
    
    /**
     * Returns operation currency from payment confirmation
     */
    abstract public function getOperationCurrency();
    
    /**
     * Returns status value from payment confirmation
     */
    abstract public function getStatus();
    
    /**
     * Returns transaction id from payment confirmation
     */
    abstract public function getTransactionId();
    
    /**
     * Checks consistency of payment confirmation
     */
    abstract public function checkSignature($pin);
    
    /**
     * Returns CHK for request params
     */
    abstract public function generateCHK($DotpayId, $DotpayPin, $ParametersArray);
    
    /**
     * Returns customer email address from payment confirmation
     * @return string
     */
    public function getEmail() {
        return $this->_confirmFields['email'];
    }
    
    /**
     * Returns control field from payment confirmation
     * @return string
     */
    public function getControl() {
        return $this->_confirmFields['control'];
    }

    /**
     * Returns array with street and building number
     * @return array
     */
    public function getDotStreetAndStreetN1($street) {
        $street_n1 = '';
        preg_match("/\s[\w\d\/_\-]{0,30}$/", $street, $matches);
        if(count($matches)>0) {
            $street_n1 = trim($matches[0]);
            $street = str_replace($matches[0], '', $street);
        }
        return array(
            'street' => $street,
            'street_n1' => $street_n1
        );
    }
    
    /**
     * Gets values from payment confirmation and saves them into internal variable
     */
    protected function getConfirmValues() {
        foreach ($this->_confirmFields as $k => &$v) {
            $value = Mage::app()->getRequest()->getPost($k);
            if ($value !== '') {
                $v = $value;
            }
        }
    }
}
