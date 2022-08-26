<?php

namespace Omnipay\Paybull\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paybull Response
 *
 * (c) Raven Software
 * 2022, ravensoft.com.tr
 * http://www.github.com/ravensoftweb/omnipay-paybull
 */
class Response extends AbstractResponse implements RedirectResponseInterface {

    /**
     * Constructor
     *
     * @param  RequestInterface         $request
     * @param  string                   $data / response data
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, $data) {
        parent::__construct($request,$data);
        $this->request = $request;
        try {
            $this->data = json_decode((string)$data, false, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $ex) {
            throw new InvalidResponseException();
        }
    }

    /**
     * Whether or not response is successful
     *
     * @return bool
     */
    public function isSuccessful() {
        if (isset($this->data->status_code)) {
            return $this->data->status_code === 100;
        }
    }

    /**
     * Get is redirect
     *
     * @return bool
     */
    public function isRedirect() {
        return false; //todo
    }

    /**
     * Get a code describing the status of this response.
     *
     * @return string|null code
     */
    public function getCode() {
        return $this->isSuccessful() ? $this->data->transaction_id : '';
    }


    /**
     * Get message
     *
     * @return string
     */
    public function getMessage() {
        if ($this->isSuccessful()) {
            if (isset($this->data->status_description)) {
                return $this->data->status_description;
            }
        }
        return $this->getError();
    }

    /**
     * Get error
     *
     * @return string
     */
    public function getError() {
        if (isset($this->data->status_description)) {
            return $this->data->status_description;
        }
    }

    /**
     * Get Redirect url
     *
     * @return string
     */
    public function getRedirectUrl() {
        if ($this->isRedirect()) {
            $data = array(
                'TransId' => $this->data->transaction_id
            );
            return $this->getRequest()->getEndpoint() . '/test/index?' . http_build_query($data);
        }
    }

    /**
     * Get Redirect method
     *
     * @return POST
     */
    public function getRedirectMethod() {
        return 'POST';
    }

    /**
     * Get Redirect url
     *
     * @return null
     */
    public function getRedirectData() {
        return null;
    }

}
