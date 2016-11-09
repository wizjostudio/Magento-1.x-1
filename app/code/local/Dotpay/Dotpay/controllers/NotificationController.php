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
* DISCLAIMER 
*
* Do not edit or add to this file if you wish to upgrade Drupal Commerce to newer
* versions in the future. If you wish to customize Drupal Commerce for your
* needs please refer to http://www.dotpay.pl for more information.
*
*  @author    Dotpay Team <tech@dotpay.pl>
*  @copyright Dotpay
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*
*/

/**
 * Controller which supports notification from Dotpay server
 */

class Dotpay_Dotpay_NotificationController extends Mage_Core_Controller_Front_Action {
    /**
     * Dotpay server IP
     */
    const DOTPAY_IP = '195.150.9.37';
    
    /**
     * Dotpay office IP
     */
    const OFFICE_IP = '77.79.195.34';
    
    /**
     * Local IP
     */
    const LOCAL_IP = '127.0.0.1';

    /**
     * Currently processed order
     *
     * @var Mage_Sales_Model_Order Object of current order
     */
    protected $_order;
    
    /**
     *
     * @var Dotpay_Dotpay_Model_Api_Api 
     */
    protected $_api;

    /**
     *
     * @var array
     */
    protected $_fields;
    
    /**
     * Default action, which is executed when Dotpay send URLC notification to shop
     */
    public function indexAction() {
        $this->displayOfficeInformation();
        $this->setApi();
        $this->checkRequest();
        $this->checkCurrency();
        $this->checkAmount();
        $this->checkEmail();
        if(!$this->api->checkSignature($this->getOrder()->getPayment()->getMethodInstance()->getConfigData('pin'))) {
            die('MAGENTO1 - FAIL SIGNATURE');
        }
        $this->updatePaymentStatus();
    }
    
    /**
     * Updates status of payment thanks to confirmation data
     */
    private function updatePaymentStatus() {
        $payment = $this->getOrder()->getPayment();
        $api = $this->api;
        if ($this->api->getStatus() === $api::operationCompleted) {
            $this->setPaymentStatusCompleted($payment);
        } elseif ($this->api->getStatus() === $api::operationRejected) {
            $this->setPaymentStatusCanceled($payment);
        }
        die('OK');
    }
    
    /**
     * Sets status of payment as completed
     * @param Mage_Sales_Model_Order_Payment $payment object with payment data
     */
    private function setPaymentStatusCompleted(Mage_Sales_Model_Order_Payment $payment) {
        if (!$payment->getTransaction($this->getTransactionId())) {
            $payment->setTransactionId($this->getTransactionId())
                ->setCurrencyCode($payment->getOrder()->getBaseCurrencyCode())
                ->setIsTransactionApproved(true)
                ->setIsTransactionClosed(true)
                ->registerCaptureNotification($this->api->getTotalAmount(), true)
                ->save();

            $payment->addTransaction(Mage_Sales_Model_Order_Payment_Transaction::TYPE_ORDER, null, false)
                ->setAdditionalInformation(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $this->api->getConfirmFieldsList())
                ->save();
            
            $this->getOrder()->setTotalPaid($this->api->getTotalAmount());
        }

        $lastStatus = $this->getOrder()->getStatus();
        if ($lastStatus !== Mage_Sales_Model_Order::STATE_COMPLETE || $lastStatus !== Mage_Sales_Model_Order::STATE_PROCESSING) {
            $this->getOrder()
                ->sendOrderUpdateEmail(true)
                ->save();
        }
    }
    
    /**
     * Sets status of payment as canceled
     * @param Mage_Sales_Model_Order_Payment $payment object with payment data
     */
    private function setPaymentStatusCanceled(Mage_Sales_Model_Order_Payment $payment) {
        if (!$payment->getTransaction($this->getTransactionId())) {
            $payment->setTransactionId($this->getTransactionId())
                ->setIsTransactionApproved(true)
                ->setIsTransactionClosed(true)
                ->save();

            $payment->addTransaction(Mage_Sales_Model_Order_Payment_Transaction::TYPE_ORDER, null, false)
                ->setAdditionalInformation(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $this->api->getConfirmFieldsList())
                ->save();
        }
    }

    /**
     * Returns object with data of current order
     * @return Mage_Sales_Model_Order
     */
    protected function getOrder() {
        if (!$this->_order) {
            $this->_order = Mage::getModel('sales/order')->loadByIncrementId($this->api->getControl());
            if (!$this->_order) {
                die('MAGENTO1 - FAIL ORDER: not exist');
            }
        }
        return $this->_order;
    }
    
    /**
     * Displays basic information for workers of Dotpay customer service
     */
    protected function displayOfficeInformation() {
        if($_SERVER['REMOTE_ADDR'] == self::OFFICE_IP && $_SERVER['REQUEST_METHOD'] == 'GET')
            die("--- Dotpay Magento1 ---"."<br>".
                "Active: ".(int)Mage::getModel('dotpay/paymentMethod')->getConfigData('test')."<br><br>".
                "--- System Info ---"."<br>".
                "PrestaShop Version: ". Mage::getVersion() ."<br>".
                "Module Version: ".Mage::getConfig()->getNode()->modules->Dotpay_Dotpay->version."<br>".
                "PHP Version: ".PHP_VERSION."<br><br>".
                "--- Dotpay PLN ---"."<br>".
                "ID: ".Mage::getModel('dotpay/paymentMethod')->getConfigData('id')."<br>".
                "API Version: ".Mage::getModel('dotpay/paymentMethod')->getConfigData('apiversion')."<br>".
                "Test Mode: ".(int)Mage::getModel('dotpay/paymentMethod')->getConfigData('test')."<br>".
                "Widget: ".(int)Mage::getModel('dotpay/paymentMethod')->getConfigData('widget'));
    }
    
    /**
     * Sets used API class
     */
    protected function setApi() {
        if(Mage::getModel('dotpay/paymentMethod')->getConfigData('apiversion') == 'dev')
            $this->api = new Dotpay_Dotpay_Model_Api_Dev();
        else
            $this->api = new Dotpay_Dotpay_Model_Api_Legacy();
        $this->api->getConfirmFieldsList();
    }
    
    /**
     * Checks request, if it comes from good source and if its method is correct
     */
    protected function checkRequest() {
        if(
            !($_SERVER['REMOTE_ADDR'] == self::DOTPAY_IP ||
                (Mage::getModel('dotpay/paymentMethod')->getConfigData('test') && 
                 ($_SERVER['REMOTE_ADDR'] == self::OFFICE_IP ||
                  $_SERVER['REMOTE_ADDR'] == self::LOCAL_IP
                 )
                )
            )
        )
            die("MAGENTO1 - ERROR (REMOTE ADDRESS: ".$_SERVER['REMOTE_ADDR'].")");
        
        if($_SERVER['REQUEST_METHOD'] != 'POST')
            die("MAGENTO1 - ERROR (METHOD <> POST)");
    }
    
    /**
     * Checks, if currency from order is the same as from notification
     */
    protected function checkCurrency() {
        $orderCurrency = $this->getOrder()->getOrderCurrencyCode();
        $receivedCurrency = $this->api->getOperationCurrency();
        if ($orderCurrency !== $receivedCurrency) {
            die('MAGENTO1 - FAIL CURRENCY ('.$orderCurrency.' <> '.$receivedCurrency.')');
        }
    }
    
    /**
     * Checks, if amount from order is the same as from notification
     */
    protected function checkAmount() {
        $amount = round($this->getOrder()->getGrandTotal(), 2);
        $amountOrder = sprintf("%01.2f", $amount);
        if ($amountOrder !== $this->api->getTotalAmount()) {
            die('MAGENTO1 - FAIL AMOUNT');
        }
    }
    
    /**
     * Checks, if email of customer from order is the same as from notification
     */
    protected function checkEmail() {
        $emailBilling = $this->getOrder()->getBillingAddress()->getEmail();
        if ($emailBilling !== $this->api->getEmail()) {
            die('MAGENTO1 - FAIL EMAIL');
        }
    }
    
    /**
     * Returns id of payment transaction
     * @return string
     */
    private function getTransactionId() {
        return $this->api->getTransactionId() ? $this->api->getTransactionId() : microtime(true);
    }
}
