<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

class StripeController extends Controller
{
    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request)
    {
        $stripe = new StripeClient('sk_test_51MCmggA1QCzj1sCUx8yaqu7inw3EiypnTStF58TEmDj3uUZIQO187b4F2Xmk59qptPgFBavl3lMG927lRvYrC9vR00f03JEEJY');
        // default 10$
        $amount = 1000; // replace with the amount of the payment in cents
        $currency = 'usd'; // replace with the currency of the payment

        try {
            $charge = $stripe->charges->create([
                'amount' => $amount,
                'currency' => $currency,
                'source' => $request->input('token'),
            ]);
            return response()->json(['message' => 'Payment successful']);
        } catch (CardException $e) {
            // handle card errors
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (InvalidRequestException $e) {
            // handle invalid request errors
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (AuthenticationException $e) {
            // handle authentication errors
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (ApiConnectionException $e) {
            // handle API connection errors
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // handle other API errors
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
