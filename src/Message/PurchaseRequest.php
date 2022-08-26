<?php

namespace Omnipay\Paybull\Message;



use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ItemBag;
use Response;

/**
 * Paybull Purchase Request
 *
 * (c) Raven Software
 * 2022, ravensoft.com.tr
 * http://www.github.com/ravensoftweb/omnipay-paybull
 */
class PurchaseRequest extends AbstractRequest {

    public function ItemBagToJson(ItemBag $itemBag):string{
        $data=[];
        foreach ($itemBag->all() as $item) {
            $data[]=[
                'name'=>$item->getName(),
                'price'=>$item->getPrice(),
                'quantity'=>$item->getQuantity(),
            ];
        }
        return json_encode($data, JSON_THROW_ON_ERROR);
    }
    public function validateHashKey($hashKey, $secretKey)
    {
        $status = $currencyCode = "";
        $total = $invoiceId = $orderId = 0;

        if(!empty($hashKey)){
            $hashKey = str_replace('_', '/', $hashKey);
            $password = sha1($secretKey);

            $components = explode(':', $hashKey);
            if(count($components) > 2){
                $iv = isset($components[0]) ? $components[0] : "";
                $salt = isset($components[1]) ? $components[1] : "";
                $salt = hash('sha256', $password.$salt);
                $encryptedMsg = isset($components[2]) ? $components[2] : "";

                $decryptedMsg = openssl_decrypt($encryptedMsg, 'aes-256-cbc', $salt, null, $iv);

                if(strpos($decryptedMsg, '|') !== false){
                    $array = explode('|', $decryptedMsg);
                    $status = isset($array[0]) ? $array[0] : 0;
                    $total = isset($array[1]) ? $array[1] : 0;
                    $invoiceId = isset($array[2]) ? $array[2] : '0';
                    $orderId = isset($array[3]) ? $array[3] : 0;
                    $currencyCode = isset($array[4]) ? $array[4] : '';
                }
            }
        }

        return [$status, $total, $invoiceId, $orderId, $currencyCode];
    }
    public function generateHashKey():string{
        $total=$this->getAmount();
        $installment=$this->getInstallment();
        $currency_code=$this->getCurrency();
        $merchant_key=$this->getMerchantKey();
        $invoice_id=$this->getOrderId();
        $app_secret=$this->getAppSecret();
        $data = $total.'|'.$installment.'|'.$currency_code.'|'.$merchant_key.'|'.$invoice_id;

        $iv = substr(sha1(mt_rand()), 0, 16);
        $password = sha1($app_secret);

        $salt = substr(sha1(mt_rand()), 0, 4);
        $saltWithPassword = hash('sha256', $password . $salt);

        $encrypted = openssl_encrypt("$data", 'aes-256-cbc', "$saltWithPassword", null, $iv);

        $msg_encrypted_bundle = "$iv:$salt:$encrypted";
        return str_replace('/', '__', $msg_encrypted_bundle);
    }
    /**
     * @throws InvalidRequestException
     * @throws InvalidCreditCardException
     */
    public function getData():array
    {
        $this->validate('card');
        $this->validate(
            'amount',
            'currency',
            'installment',
            'order_id',
            'description',
            'merchant_key',
            'items',
            'cancelUrl',
            'returnUrl',
        );


        $this->getCard()->validate();

        $card = $this->getCard();

        $data=[
            'cc_holder_name'=>$card->getName(),
            'cc_no'=>$card->getNumber(),
            'expiry_month'=>$card->getExpiryMonth(),
            'expiry_year'=>$card->getExpiryDate('Y'),
            'cvv'=>$card->getCvv(),
            'currency_code'=>$this->getCurrency(),
            'installments_number'=>$this->getInstallment(),
            'invoice_id'=>$this->getOrderId(),
            'invoice_description'=>$this->getDescription(),
            'name'=>$card->getBillingFirstName(),
            'surname'=>$card->getBillingLastName(),
            'total'=>$this->getAmount(),
            'merchant_key'=>$this->getMerchantKey(),
            'items'=> $this->ItemBagToJson($this->getItems()),
            'cancel_url'=>$this->getCancelUrl(),
            'return_url'=>$this->getReturnUrl(),
            'hash_key'=>$this->generateHashKey(),
        ];

        if(!empty($card->getBillingAddress1())){
            $data['bill_address1']=$card->getBillingAddress1();
        }

        if(!empty($card->getBillingAddress2())){
            $data['bill_address2']=$card->getBillingAddress2();
        }

        if(!empty($card->getBillingCity())){
            $data['bill_city']=$card->getBillingCity();
        }

        if(!empty($card->getBillingPostcode())){
            $data['bill_postcode']=$card->getBillingPostcode();
        }

        if(!empty($card->getBillingState())){
            $data['bill_state']=$card->getBillingState();
        }

        if(!empty($card->getBillingCountry())){
            $data['bill_country']=$card->getBillingCountry();
        }

        if(!empty($card->getEmail())){
            $data['bill_email']=$card->getEmail();
        }

        if(!empty($card->getPhone())){
            $data['bill_phone']=$card->getPhone();
        }

        if(!empty($this->getCardProgram())){
            $data['card_program']=$this->getCardProgram();
        }

        if(!empty($this->getClientIp())){
            $data['ip']=$this->getClientIp();
        }

        if(!empty($this->getTransactionType())){
            $data['transaction_type']=$this->getTransactionType();
        }

        if(!empty($this->getSaleWebHookKey())){
            $data['sale_web_hook_key']=$this->getSaleWebHookKey();
        }

        if(!empty($this->getOrderType())){
            $data['order_type']=$this->getOrderType();
        }
        else{
            $data['order_type']=0;
        }

        if((int) $this->getOrderType() === 1){
            if(!empty($this->getRecurringPaymentNumber())){
                $data['recurring_payment_number']=$this->getRecurringPaymentNumber();
            }

            if(!empty($this->getRecurringPaymentCycle())){
                $data['recurring_payment_cycle']=$this->getRecurringPaymentCycle();
            }

            if(!empty($this->getRecurringPaymentInterval())){
                $data['recurring_payment_interval']=$this->getRecurringPaymentInterval();
            }

            if(!empty($this->getRecurringWebHookKey())){
                $data['recurring_web_hook_key']=$this->getRecurringWebHookKey();
            }
        }

        if(!empty($this->getMaturityNumber())){
            $data['maturity_period']=$this->getMaturityNumber();
        }

        if(!empty($this->getPaymentFrequency())){
            $data['payment_frequency']=$this->getPaymentFrequency();
        }

        return $data;
    }

    public function sendData($data): PurchaseResponse
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
