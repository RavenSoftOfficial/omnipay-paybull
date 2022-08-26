<?php

namespace Omnipay\Paybull\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\Exception;

/**
 * Paybull Authorize Request
 *
 * (c) Raven Software
 * 2022, ravensoft.com.tr
 * http://www.github.com/ravensoftweb/omnipay-paybull
 */
class AuthorizeRequest extends AbstractRequest
{

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate(
            'app_id',
            'app_secret',
        );

        return [
            'app_id'=>$this->getAppId(),
            'app_secret'=>$this->getAppSecret()
        ];
    }

    /**
     * @throws InvalidRequestException
     * @throws \JsonException
     */
    public function sendData($data)
    {
        try {
            $res=json_decode($this->httpClient
                ->request('post', $this->getEndPoint('token'), [
                    'Content-type' => 'application/json'
                ], json_encode($data, JSON_THROW_ON_ERROR))
                ->getBody()
                ->getContents(), false, 512, JSON_THROW_ON_ERROR);
            if($res->status_code !== 100){
                throw  new InvalidRequestException([
                    'status'=>False,
                    'message'=>'Get token is failed'
                ]);
            }

            return $res->data->token;
        }catch (Exception $exception){
            throw  new InvalidRequestException($exception);
        }
    }
}
