<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;

use App\Card;

use Auth;
use Redirect;
use Session;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Api\ExecutePayment;

class PayController extends Controller
{

    private $_apiContext;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function contextPaypal()
    {
        // config == Config files
        // Client Id
        $ClientId = config('paypal_payment.Account.ClientId');
        // Client Secret
        $ClientSecret = config('paypal_payment.Account.ClientSecret');
        // Came from Paypal SDK
        $OAuth = new OAuthTokenCredential($ClientId, $ClientSecret);
        // Came from Paypal SDK
        $this->_apiContext = new ApiContext($OAuth);
        // Account Connection && Log Setting
        $SetConfig = config('paypal_payment.Setting');
        // Set And Apply The Configration
        $this->_apiContext->setConfig($SetConfig);
    }

    public function checkOut(Request $request)
    {
        $selectedProducts = $request->checkOut;


        if (count($selectedProducts) <= 0) {
            return back()->with('fail', 'You Must Select Products To Check Out');
        }

        $this->contextPaypal();

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $total = 0;
        // prepare the items list array
        $items_products = [];
        foreach ($selectedProducts as $select) {
            $checkSelected = Product::findOrFail($select);
            if ($checkSelected) {
                $total += $checkSelected->price;
                // Make New Item
                $item = new Item();
                $item->setName($checkSelected->title)
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setSku($checkSelected->id) // Similar to `item_number` in Classic API
                ->setPrice($checkSelected->price);

                // prepare the items list array
                $items_products[] = $item;
            }

        }

        // list the item for PayPal
        $itemList = new ItemList();
        $itemList->setItems($items_products);

        // Details
        $details = new Details();
        $details->setShipping(0)
        ->setTax(0)
        ->setSubtotal($total);

        // Set Amount
        $amount = new Amount();
        $amount->setCurrency("USD")
        ->setTotal($total)
        ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Alaa Dragneel Payment Products")
        ->setInvoiceNumber(uniqid());

        // Base Url
        $baseUrl = url('/');
        // TO Redirect
        $redirectUrls = new RedirectUrls();
        // set setReturnUrl [success] && set setCancelUrl[Fail]
        $redirectUrls->setReturnUrl($baseUrl . '/successCharge?success=true')
        ->setCancelUrl($baseUrl . '/errorCharge?success=false');

        $payment = new Payment();
        // config the info TO Make the new Payment
        $payment->setIntent("sale")
        // Set Payer
        ->setPayer($payer)
        // Set RedirectUrls
        ->setRedirectUrls($redirectUrls)
        // Set Transactions
        ->setTransactions(array($transaction));

        $request = clone $payment;
        // to avoid this error
        // operation-timed-out-after-0-milliseconds-with-0-out-of-0-by
        // must put the next line
        $curl_info = curl_version();
        try {

            /*
            | ----------------------------------------------------------------------
            | Create The Payment
            | ----------------------------------------------------------------------
            */

            $payment->create($this->_apiContext);
            $redirect = null;
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect = $link->getHref();
                }
            }

            if ($redirect != null) {
                Session::put('total', $total);
                Session::put('products', $selectedProducts);
                return Redirect::away($redirect);
            }

            return redirect('/card')->with('fail', 'Error No.1000 Happend Try Again Again');

        } catch (Exception $e) {
            return redirect('/card')->with('fail', 'Error No.1001 Happend Try Again Again');
        }

    }

    public function GetPaymentInfoById($id)
    {
        $pay = Payment::get($id, $this->_apiContext);
        return $pay;
    }

    public function successCharge(Request $request)
    {
        if (
        $request->success == true && $request->success != '' &&
        isset($request->paymentId) && $request->paymentId != '' &&
        isset($request->token) && $request->token != '' &&
        isset($request->PayerID) && $request->PayerID != ''
        )
        {

            $this->contextPaypal();
            /*
            | ----------------------------------------------------------------------
            | Save Payments To DB && take the money from the user && add it to the website
            | ----------------------------------------------------------------------
            |
            */


            $total = Session::get('total');
            Session::forget('total');
            $selectedProducts = Session::get('products');
            Session::forget('products');

            $paymentId = $request->paymentId;
            $payment = $this->GetPaymentInfoById($paymentId);

            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);

            $transaction = new Transaction();
            $amount = new Amount();
            $details = new Details();
            $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($total);

            $amount->setCurrency('USD');

            $amount->setTotal($total);

            $amount->setDetails($details);

            $transaction->setAmount($amount);

            try {
                $result = $payment->execute($execution, $this->_apiContext);

                if ($result->state == 'approved') {

                    foreach ($selectedProducts as $select) {
                        $checkSelected = Product::findOrFail($select);
                        if ($checkSelected) {
                            $checkSelected->delete();
                        }

                    }

                    return redirect('/card')->with('success', 'Your check out Has Been Successfully');
                } else {
                    return redirect('/card')->with('fail', 'Your check out Does\'nt Been Successfully');
                }

            } catch (PayPalConnectionException  $ex) {
                return redirect('/card')->with('fail', 'Your Charge Have Been Faild Error Code 1002');
            }
        } else {
            return redirect('/card')->with('fail', 'Error Happend Try Again Again Error Code 1003');
        }
        return redirect('/card')->with('fail', 'Error Happend Try Again Again Error Code 1004');

    }

    public function errorCharge(Request $request)
    {
        return redirect('/card')->with('fail', 'Your check out Does\'nt Been Successfully');
    }
}
