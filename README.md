# Omnipay: Paybull

**Paybull gateway for Omnipay payment processing library**


[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Paybull (Turkish Payment Gateways) support for Omnipay.

Paybull sanal pos hizmeti için omnipay kütüphanesi.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "ravensoftweb/omnipay-paybull": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update




## Sample App
        $gateway = Omnipay::create('Paybull');
        $gateway->setBank("Paybull");
        $gateway->setTestMode(false);

        $gateway->setAppId("App Id");
        $gateway->setAppSecret("App Secret");
        $gateway->setMerchantKey('Merchant Key');


        $options = [
            'number'        => '5555555555555555',
            'expiryMonth'   => '01',
            'expiryYear'    => '2030',
            'cvv'           => '000',
            'firstname'      => 'Raven',
            'lastname'      => 'Software'
        ];
        $items=new ItemBag();
        $items->add(new Item([
            'name'      =>'Test Product',
            'price'     =>0.50,
            'quantity'  =>1
        ]));
        $items->add(new Item([
            'name'      =>'Test 2 Product',
            'price'     =>0.50,
            'quantity'  =>1
        ]));

        ## Payment Transaction Status Get
        $responseStatus=$gateway->orderStatus([
            'order_id'   => rand(),
        ]);

        ## Card Installments Get
        $responseInstallment=$gateway->installment([
            'card'          => $options,
            'installment'   => 1,
            'amount'        => 1.00,
            'currency'      => 'TRY'
        ]);
        ## Payment Refund
        $responseRefund=$gateway->refund([
            'refund_transaction_id'     => '',
            'installment'               => 1,
            'amount'                    => 1.00,
            'currency'                  => 'TRY',
            'order_id'                  => rand(),
        ]);

        ## 3D Payment
        $response3DPayment = $gateway->purchase(
            [
                'card'          => $options,
                'installment'   => 1,
                'amount'        => 1.00,
                'currency'      => 'TRY',
                'description'   => 'Description',
                'items'         => $items,
            ]
        )->setReturnUrl('Success Url')->setCancelUrl('Cancel Url')->send();

        $responses=[
            $responseStatus,
            $responseInstallment,
            $response3DPayment,
            $responseRefund,
        ];
        foreach ($responses as $respons) {
            if ($respons->isSuccessful()) {
                echo $respons->getTransactionReference();
                echo $respons->getMessage();
            }else{
                echo $respons->getError();
            }
            // Debug
            //var_dump($respons);
        }


