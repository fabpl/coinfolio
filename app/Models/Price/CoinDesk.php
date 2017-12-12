<?php

namespace App\Models\Price;

use App\Models\Price;
use Carbon\Carbon;

class Coindesk
{
    /**
     * @var string
     */
    protected $apiUrl = 'https://api.coindesk.com/v1/bpi';

    /**
     * @var string
     */
    protected $apiError;

    /**
     * @return string
     */
    public function getApiError()
    {
        return $this->apiError;
    }

    /**
     * @param Price $market
     * @return array
     */
    public function updatePrice(Price $price)
    {
        if ($current = $this->getCurrentPrice()) {
            // Need history ?
            if ($current['USD'] != $price->usd or $current['EUR'] != $price->eur) {
                // History
                $history = $price->replicate();
                $history->deleted_at = Carbon::now();
                $history->save();

                // Current
                $price->usd = $current['USD'];
                $price->eur = $current['EUR'];
                $price->save();
            }
        }

        return $price->toArray();
    }

    /**
     * @return bool
     */
    public function updatePrices()
    {
        if ($_price = $this->getCurrentPrice()) {
            if (!($price = Price::first())) {
                $price = new Price();
                $price->usd = $_price['USD'];
                $price->eur = $_price['EUR'];
                $price->save();
            }

            return true;
        }

        return false;
    }

    /**
     * @return array|mixed
     */
    protected function getCurrentPrice()
    {
        return $this->call('/currentprice.json');
    }

    /**
     * @param string $route
     * @return false[array
     */
    private function call($route)
    {
        $response = false;

        $this->apiError = null;

        $uri = $this->apiUrl . $route;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $exec = curl_exec($ch);

        if ($error = curl_error($ch)) {
            $this->apiError = $error;
        } else {
            if ($obj = json_decode($exec, true) and is_array($obj)) {
                if (
                    array_key_exists("bpi", $obj)
                    and array_key_exists("USD", $obj["bpi"])
                    and array_key_exists("rate_float", $obj["bpi"]["USD"])
                    and array_key_exists("EUR", $obj["bpi"])
                    and array_key_exists("rate_float", $obj["bpi"]["EUR"])
                ) {
                    $response = array(
                        "USD" => $obj["bpi"]["USD"]["rate_float"],
                        "EUR" => $obj["bpi"]["EUR"]["rate_float"],
                    );
                } else {
                    $this->apiError = $obj["message"];
                }
            } else {
                $this->apiError = $exec;
            }
        }

        curl_close($ch);

        return $response;
    }
}