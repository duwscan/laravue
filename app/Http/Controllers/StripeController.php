<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        try {
            $stripe = new StripeClient('sk_test_51MCmggA1QCzj1sCUx8yaqu7inw3EiypnTStF58TEmDj3uUZIQO187b4F2Xmk59qptPgFBavl3lMG927lRvYrC9vR00f03JEEJY');
            $product = Product::find($request->input('product_id'));
            if (!$product) {
                return response()->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }
            $amount = $product->price * 100;
            $currency = $request->input('currency') ?? 'usd';
            $charge = $stripe->charges->create([
                'amount' => $amount,
                'currency' => $currency,
                'source' => $request->input('token'),
            ]);
            return response()->json(['message' => 'Payment successful']);
        } catch (CardException $e) {
            // handle card errors
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (InvalidRequestException $e) {
            // handle invalid request errors
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (AuthenticationException $e) {
            // handle authentication errors
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        } catch (ApiConnectionException $e) {
            // handle API connection errors
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // handle other API errors
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
