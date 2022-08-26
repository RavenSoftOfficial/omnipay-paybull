<?php

namespace Omnipay\Paybull;

use Omnipay\Common\AbstractGateway;
use Omnipay\Paybull\Message\InstallmentRequest;
use Omnipay\Paybull\Message\RefundRequest;
use Omnipay\Paybull\Message\StatusRequest;
use Omnipay\Paybull\Message\PurchaseRequest;
use Omnipay\Paybull\Message\AuthorizeRequest;

/**
 * Paybull Gateway
 *
 * (c) Raven Software
 * 2022, ravensoft.com.tr
 * http://www.github.com/ravensoftweb/omnipay-paybull
 */
class Gateway extends AbstractGateway {

    public function getName() {
        return 'Paybull';
    }

    public function getDefaultParameters() {
        return array(
            'bank' => 'Paybull',
            'api_id' => '',
            'token'=>'',
            'app_secret' => '',
            'merchant_key' => '',
            'installment' => '',
            'currency' => 'TRY',
            'order_id' => rand(),
            'testMode' => false,
            'returnUrl' => 'http://google.com',
            'cancelUrl' => 'http://yahoo.com'
        );
    }

    public function authorize(array $parameters = array()) {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    public function purchase(array $parameters = array()) {
        $token=$this->authorize($parameters)->send();
        $parameters['token']=$token;
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function status(array $parameters = array()): \Omnipay\Common\Message\AbstractRequest
    {
        $token=$this->authorize($parameters)->send();
        $parameters['token']=$token;
        return $this->createRequest(StatusRequest::class, $parameters);
    }

    public function installment(array $parameters = array()): \Omnipay\Common\Message\AbstractRequest
    {
        $token=$this->authorize($parameters)->send();
        $parameters['token']=$token;
        return $this->createRequest(InstallmentRequest::class, $parameters);
    }

    public function orderStatus(array $parameters = array()): \Omnipay\Common\Message\AbstractRequest
    {
        $token=$this->authorize($parameters)->send();
        $parameters['token']=$token;
        return $this->createRequest(StatusRequest::class, $parameters);
    }

    public function refund(array $parameters = array()): \Omnipay\Common\Message\AbstractRequest
    {
        $token=$this->authorize($parameters)->send();
        $parameters['token']=$token;
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    public function getAppId(){
        return $this->getParameter('app_id');
    }

    public function setAppId($appId){
        return $this->setParameter('app_id',$appId);
    }

    public function getAppSecret(){
        return $this->getParameter('app_secret');
    }

    public function setAppSecret($appSecret){
        return $this->setParameter('app_secret',$appSecret);
    }

    public function getMerchantKey(){
        return $this->getParameter('merchant_key');
    }

    public function setMerchantKey($merchantKey){
        return $this->setParameter('merchant_key',$merchantKey);
    }

    public function getTransId() {
        return $this->getParameter('trans_id');
    }

    public function setTransId($value) {
        return $this->setParameter('trans_id', $value);
    }

    public function getOrderId() {
        return $this->getParameter('order_id');
    }

    public function setOrderId($value) {
        return $this->setParameter('order_id', $value);
    }

    public function getInstallment() {
        return $this->getParameter('installment');
    }

    public function setInstallment($value) {
        return $this->setParameter('installment', $value);
    }
    public function getBank() {
        return $this->getParameter('bank');
    }

    public function setBank($value) {
        return $this->setParameter('bank', $value);
    }


    public function getType() {
        return $this->getParameter('type');
    }

    public function setType($value) {
        return $this->setParameter('type', $value);
    }
}
