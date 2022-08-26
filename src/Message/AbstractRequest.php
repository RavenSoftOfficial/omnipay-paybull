<?php

namespace Omnipay\Paybull\Message;

use Omnipay\Common\Http\ClientInterface;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

    protected $domain = [
        'test'=>'https://test.paybull.com',
        'production'=>'https://app.paybull.com'
    ];

    protected $endpoints = [
        'token' => '/ccpayment/api/token',
        'purchase' => '/ccpayment/api/paySmart3D',
        'refund' => '/ccpayment/api/refund',
        'installment' => '/ccpayment/api/getpos',
        'confirmPayment' => '/ccpayment/api/confirmPayment',
        'status' => '/ccpayment/api/checkstatus'
    ];

    protected function getDomain():string
    {
        if($this->getTestMode()){
            return $this->domain['test'];
        }
        return $this->domain['production'];
    }

    public function getEndPoint($key):string
    {
        return $this->getDomain().$this->endpoints[$key];
    }

    public function getAppId()
    {
        return $this->getParameter('app_id');
    }

    public function setAppId($appId): AbstractRequest
    {
        return $this->setParameter('app_id',$appId);
    }

    public function getAppSecret()
    {
        return $this->getParameter('app_secret');
    }

    public function setAppSecret($appSecret): AbstractRequest
    {
        return $this->setParameter('app_secret',$appSecret);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchant_key');
    }

    public function setMerchantKey($merchantKey): AbstractRequest
    {
        return $this->setParameter('merchant_key',$merchantKey);
    }

    public function getTransId()
    {
        return $this->getParameter('trans_id');
    }

    public function setTransId($value): AbstractRequest
    {
        return $this->setParameter('trans_id', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('order_id');
    }

    public function setOrderId($value): AbstractRequest
    {
        return $this->setParameter('order_id', $value);
    }

    public function getInstallment()
    {
        return $this->getParameter('installment');
    }

    public function setInstallment($value): AbstractRequest
    {
        return $this->setParameter('installment', $value);
    }

    public function getCardProgram()
    {
        return $this->getParameter('card_program');
    }

    public function setCardProgram($cardProgram): AbstractRequest
    {
        return $this->setParameter('card_program',$cardProgram);
    }

    public function getTransactionType()
    {
        return $this->getParameter('transaction_type');
    }

    public function setTransactionType($transactionType): AbstractRequest
    {
        return $this->setParameter('transaction_type',$transactionType);
    }

    public function getSaleWebHookKey()
    {
        return $this->getParameter('sale_web_hook_key');
    }

    public function setSaleWebHookKey($saleWebHookKey): AbstractRequest
    {
        return $this->setParameter('sale_web_hook_key',$saleWebHookKey);
    }

    public function getOrderType()
    {
        return $this->getParameter('order_type');
    }

    public function setOrderType($orderType): AbstractRequest
    {
        return $this->setParameter('order_type',$orderType);
    }

    public function getRecurringPaymentNumber()
    {
        return $this->getParameter('recurring_payment_number');
    }

    public function setRecurringPaymentNumber($recurringPaymentNumber): AbstractRequest
    {
        return $this->setParameter('recurring_payment_number',$recurringPaymentNumber);
    }

    public function getRecurringPaymentCycle()
    {
        return $this->getParameter('recurring_payment_cycle');
    }

    public function setRecurringPaymentCycle($recurringPaymentCycle): AbstractRequest
    {
        return $this->setParameter('recurring_payment_cycle',$recurringPaymentCycle);
    }

    public function getRecurringPaymentInterval()
    {
        return $this->getParameter('recurring_payment_interval');
    }

    public function setRecurringPaymentInterval($recurringPaymentInterval): AbstractRequest
    {
        return $this->setParameter('recurring_payment_interval',$recurringPaymentInterval);
    }

    public function getRecurringWebHookKey()
    {
        return $this->getParameter('recurring_web_hook_key');
    }

    public function setRecurringWebHookKey($recurringWebHookKey): AbstractRequest
    {
        return $this->setParameter('recurring_web_hook_key',$recurringWebHookKey);
    }

    public function getMaturityNumber()
    {
        return $this->getParameter('maturity_period');
    }

    public function setMaturityNumber($maturityNumber): AbstractRequest
    {
        return $this->setParameter('maturity_period',$maturityNumber);
    }

    public function getPaymentFrequency()
    {
        return $this->getParameter('payment_frequency');
    }

    public function setPaymentFrequency($paymentFrequency): AbstractRequest
    {
        return $this->setParameter('payment_frequency',$paymentFrequency);
    }

    public function getRefundwebHookKey()
    {
        return $this->getParameter('refund_web_hook_key');
    }

    public function setRefundwebHookKey($refundwebHookKey): AbstractRequest
    {
        return $this->setParameter('refund_web_hook_key',$refundwebHookKey);
    }

    public function getIsRecurring()
    {
        return $this->getParameter('is_recurring');
    }

    public function setIsRecurring($isRecurrin): AbstractRequest
    {
        return $this->setParameter('is_recurring',$isRecurrin);
    }

    public function getIs2D(){
        return $this->getParameter('is_2d');
    }

    public function setIs2D($is2D): AbstractRequest
    {
        return $this->setParameter('is_2d',$is2D);
    }
}
