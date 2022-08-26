<?php
/**
 * Paybull driver for the Omnipay PHP payment processing library
 *
 * @link      https://github.com/ravensoftweb/omnipay-paybull
 * @package   omnipay-paybull
 * @license   MIT
 * @copyright Copyright (c) 2022-2023, RavenSoftware (http://ravensoft.com.tr/)
 */

namespace Omnipay\Paybull\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Paybull Purchase Response.
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $this->_redirect = $request->getEndPoint('purchase');

    }

    /**
     * @var string URL to redirect client to payment system. Used when [[isRedirect]]
     */
    protected $_redirect = '';

    /**
     * Always returns `false`, because Paybull always needs redirect
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Always returns `true`, because Paybull always needs redirect
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl(): string
    {
        return $this->_redirect;
    }

    public function setRedirectUrl($redirectUrl): PurchaseResponse
    {
        return $this->_redirect=$redirectUrl;;
    }
    /**
     * Always `POST` for Paybull
     * {@inheritdoc}
     */
    public function getRedirectMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return $this->data;
    }

}
