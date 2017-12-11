<?php

namespace App\Models\Exchenge;

use App\Models\Currency;
use App\Models\Exchenge;
use App\Models\Market;
use Illuminate\Support\Carbon;

class Kraken
{
    /**
     * @var string
     */
    protected $apiUrl = 'https://api.kraken.com/0';

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
        if ($summary = $this->getMarketSummary($market->currency_from->code . $market->currency_to->code)) {
            // Need history ?
            if ($summary['c'][0] != $market->last) {
                // History
                $history = $market->replicate();
                $history->deleted_at = Carbon::now();
                $history->save();

                // Current
                $market->high = $summary['h'][1];
                $market->low = $summary['l'][1];
                $market->bid = $summary['b'][0];
                $market->ask = $summary['a'][0];
                $market->volume = $summary['v'][0];
                $market->last = $summary['c'][0];
                $market->previous = $summary['o'];
                $market->change = $summary['o'] > 0 ? (($summary['c'][0] - $summary['o']) / $summary['o'] * 100) : 0;
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
                if (!($currency = Currency::where('code', $_currency['altname'])->first())) {
                    $currency = new Currency();
                    $currency->code = $_currency['altname'];
                    $currency->name = $_currency['altname'];
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
            if (!($exchenge = Exchenge::where('name', 'Kraken')->first())) {
                $exchenge = new Exchenge();
                $exchenge->name = 'Kraken';
                $exchenge->save();
            }

            foreach ($summaries as $_code => $summary) {
                if (!($market = Market::where('exchenge_id', $exchenge->id)->where('code', $_code)->first())) {
                    $currencyCodeFrom = substr($summary['base'], -3);
                    $currencyCodeTo = substr(str_replace($summary['base'], '', $summary['altname']), -3);

                    if (!($currencyFrom = Currency::where('code', $currencyCodeFrom)->first())) {
                        continue;
                    }
                    if (!($currencyTo = Currency::where('code', $currencyCodeTo)->first())) {
                        continue;
                    }

                    if ($summary = $this->getMarketSummary($currencyCodeFrom . $currencyCodeTo)) {
                        $market = new Market();
                        $market->exchenge_id = $exchenge->id;
                        $market->currency_id_from = $currencyFrom->id;
                        $market->currency_id_to = $currencyTo->id;
                        $market->high = $summary['h'][1];
                        $market->low = $summary['l'][1];
                        $market->bid = $summary['b'][0];
                        $market->ask = $summary['a'][0];
                        $market->volume = $summary['v'][0];
                        $market->last = $summary['c'][0];
                        $market->previous = $summary['o'];
                        $market->change = $summary['o'] > 0 ? (($summary['c'][0] - $summary['o']) / $summary['o'] * 100) : 0;
                        $market->save();
                    }
                }
            }
        }
    }

    /**
     * @return array|mixed
     */
    protected function getCurrencies()
    {
        return $this->call('/public/Assets');
    }

    /**
     * @return array|mixed
     */
    protected function getMarketSummaries()
    {
        return $this->call('/public/AssetPairs');
    }

    /**
     * @param string $market
     * @return bool|mixed
     */
    protected function getMarketSummary($market)
    {
        if ($summary = $this->call('/public/Ticker', array('pair' => $market))) {
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
            if ($obj = json_decode($exec, true) and is_array($obj)) {
                if (empty($obj["error"])) {
                    $response = $obj['result'];
                } else {
                    $this->apiError = $obj["error"];
                }
            } else {
                $this->apiError = $exec;
            }
        }

        curl_close($ch);

        return $response;
    }
}