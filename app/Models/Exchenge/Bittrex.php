<?php

namespace App\Models\Exchenge;

use App\Models\Currency;
use App\Models\Exchenge;
use App\Models\Market;
use Illuminate\Support\Carbon;

class Bittrex
{
    /**
     * @var string
     */
    protected $apiUrl = 'https://bittrex.com/api/v1.1';

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
     * @param Market $market
     * @return array
     */
    public function updateMarket(Market $market)
    {
        if ($summary = $this->getMarketSummary($market->currency_from->code . '-' . $market->currency_to->code)) {
            // Need history ?
            if ($summary['Last'] != $market->last) {
                // History
                $history = $market->replicate();
                $history->deleted_at = Carbon::now();
                $history->save();

                // Current
                $market->high = $summary['High'];
                $market->low = $summary['Low'];
                $market->bid = $summary['Bid'];
                $market->ask = $summary['Ask'];
                $market->volume = $summary['Volume'];
                $market->last = $summary['Last'];
                $market->previous = $summary['PrevDay'];
                $market->change = $summary['PrevDay'] > 0 ? (($summary['Last'] - $summary['PrevDay']) / $summary['PrevDay'] * 100) : 0;
                $market->save();
            }
        }

        return $market->toArray();
    }

    /**
     *
     */
    public function updateCurrencies()
    {
        if ($currencies = $this->getCurrencies()) {
            foreach ($currencies as $_currency) {
                if (!($currency = Currency::where('code', $_currency['Currency'])->first())) {
                    $currency = new Currency();
                    $currency->code = $_currency['Currency'];
                    $currency->name = $_currency['CurrencyLong'];
                    $currency->save();
                } elseif ($currency->code == $currency->name) {
                    $currency->name = $_currency['CurrencyLong'];
                    $currency->save();
                }
            }
        }
    }

    /**
     *
     */
    public function updateMarkets()
    {
        if ($summaries = $this->getMarketSummaries()) {
            if (!($exchenge = Exchenge::where('name', 'Bittrex')->first())) {
                $exchenge = new Exchenge();
                $exchenge->name = 'Bittrex';
                $exchenge->save();
            }

            foreach ($summaries as $summary) {
                if (!($market = Market::where('exchenge_id', $exchenge->id)->where('code', $summary['MarketName'])->first())) {
                    list($currencyCodeFrom, $currencyCodeTo) = explode('-', $summary['MarketName']);

                    if (!($currencyFrom = Currency::where('code', $currencyCodeFrom)->first())) {
                        var_dump($currencyCodeFrom);
                        continue;
                    }
                    if (!($currencyTo = Currency::where('code', $currencyCodeTo)->first())) {
                        var_dump($currencyCodeTo);
                        continue;
                    }

                    $market = new Market();
                    $market->exchenge_id = $exchenge->id;
                    $market->currency_id_from = $currencyFrom->id;
                    $market->currency_id_to = $currencyTo->id;
                } else {
                    // History
                    $history = $market->replicate();
                    $history->deleted_at = Carbon::now();
                    $history->save();
                }

                $market->high = $summary['High'];
                $market->low = $summary['Low'];
                $market->bid = $summary['Bid'];
                $market->ask = $summary['Ask'];
                $market->volume = $summary['Volume'];
                $market->last = $summary['Last'];
                $market->previous = $summary['PrevDay'];
                $market->change = $summary['PrevDay'] > 0 ? (($summary['Last'] - $summary['PrevDay']) / $summary['PrevDay'] * 100) : 0;
                $market->save();
            }
        }
    }

    /**
     * @return array|mixed
     */
    protected function getCurrencies()
    {
        return $this->call('/public/getcurrencies');
    }

    /**
     * @return array|mixed
     */
    protected function getMarketSummaries()
    {
        return $this->call('/public/getmarketsummaries');
    }

    /**
     * @param string $market
     * @return bool|mixed
     */
    protected function getMarketSummary($market)
    {
        if ($summary = $this->call('/public/getmarketsummary', array('market' => $market))) {
            return current($summary);
        }

        return false;
    }

    /**
     * @param string $route
     * @param array $params
     * @return false[array
     */
    private function call($route, array $params = array())
    {
        $response = false;

        $this->apiError = null;

        $uri = $this->apiUrl . $route;
        if (!empty($params)) {
            $uri = $uri . '?' . http_build_query($params);
        }

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $exec = curl_exec($ch);

        if ($error = curl_error($ch)) {
            $this->apiError = $error;
        } else {
            if (preg_match('#summar()#', $route)) {
                $exec = preg_replace('/\:*([0-9]+\.?[0-9]{8,})/', ':"\\1"', $exec);
            }
            if ($obj = json_decode($exec, true) and is_array($obj)) {
                if ($obj["success"] == true) {
                    $response = $obj['result'];
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