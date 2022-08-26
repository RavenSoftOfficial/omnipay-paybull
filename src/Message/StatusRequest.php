<?php

namespace Omnipay\Paybull\Message;

use JsonException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Exception;

class StatusRequest extends AbstractRequest
{

    public function generateHashKey(){
        $data = $this->getOrderId().'|'.$this->getMerchantKey();
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
     * @inheritDoc
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'order_id',
            'token',
        );

        return [
            'invoice_id'=>$this->getOrderId(),
            'merchant_key'=>$this->getMerchantKey(),
            'hash_key'=>$this->generateHashKey(),
        ];
    }

    /**
     * @inheritDoc
     * @throws InvalidRequestException
     * @throws JsonException
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        try {
            return new Response($this ,$this->httpClient
                ->request('post', $this->getEndPoint('status'), [
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
