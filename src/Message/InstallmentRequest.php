<?php

namespace Omnipay\Paybull\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Exception;

class InstallmentRequest extends AbstractRequest
{


    /**
     * @throws InvalidRequestException
     * @throws InvalidCreditCardException
     */
    public function getData()
    {
        $this->validate('card');
        $this->validate(

            'amount',
            'currency',
            'merchant_key'
        );
        $this->getCard()->validate();

        $card = $this->getCard();
        $data=[
            'credit_card'=>substr($card->getNumber(),0,6),
            'amount'=>$this->getAmount(),
            'currency_code'=>$this->getCurrency(),
            'merchant_key'=>$this->getMerchantKey(),
        ];
        if(!empty($this->getIsRecurring())){
            $data['is_recurring']=$this->getRefundwebHookKey();
        }
        if(!empty($this->getIs2D())){
            $data['is_2d']=$this->getIs2D();
        }
        return $data;
    }

    /**
     * @throws InvalidRequestException
     * @throws \JsonException
     * @throws InvalidResponseException
     */
    public function sendData($data): Response
    {
        try {
            return new Response($this ,$this->httpClient
                ->request('post', $this->getEndPoint('installment'), [
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
