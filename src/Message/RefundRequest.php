<?php

namespace Omnipay\Paybull\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Exception;

/**
 * Paybull Purchase Request
 *
 * (c) Raven Software
 * 2022, ravensoft.com.tr
 * http://www.github.com/ravensoftweb/omnipay-paybull
 */
class RefundRequest extends AbstractRequest {
    /**
     * @throws InvalidRequestException
     */
    public function generateHashKey(){
        $data = $this->getAmount().'|'.$this->getOrderId().'|'.$this->getMerchantKey();

        $iv = substr(sha1(mt_rand()), 0, 16);
        $password = sha1($this->getAppSecret());

        $salt = substr(sha1(mt_rand()), 0, 4);
        $saltWithPassword = hash('sha256', $password . $salt);

        $encrypted = openssl_encrypt(
            (string)$data, 'aes-256-cbc', (string)$saltWithPassword, null, $iv
        );

        $msg_encrypted_bundle = "$iv:$salt:$encrypted";
        return str_replace('/', '__', $msg_encrypted_bundle);
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'order_id',
            'token',
            'amount',
            'merchant_key',
            'refund_transaction_id',
        );
        $data=[
            'invoice_id'=>$this->getOrderId(),
            'merchant_key'=>$this->getMerchantKey(),
            'hash_key'=>$this->generateHashKey(),
        ];
        if(!empty($this->getRefundwebHookKey())){
            $data['refund_web_hook_key']=$this->getRefundwebHookKey();
        }
        return $data;
    }

    /**
     * @throws InvalidRequestException
     * @throws \JsonException
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        try {
            return new Response($this ,$this->httpClient
                ->request('post', $this->getEndPoint('refund'), [
                    'Authorization' => 'Bearer '.$this->getToken(),
                    'Content-type' => 'application/json'
                ], json_encode($data, JSON_THROW_ON_ERROR))
                ->getBody()
                ->getContents());
        }catch (Exception $exception){
            throw  new InvalidRequestException($exception);
        }
    }
}
