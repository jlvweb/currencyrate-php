<?php
/* =============================================================
 * JLV CurrencyRate
 * https://github.com/jlvweb/jlv-framework
 * =============================================================
 * Copyright 2013 Johan Lingvall
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================= */

class CurrencyRate {
    private $from_code = 'EUR';
    private $to_code = 'USD';
    private $source = 'google';
    private $available_sources = array('google', 'ecb');
    private $available_currencies_google = array("AED", "ANG", "ARS", "AUD", "BDT", "BGN", "BHD", "BND", "BOB",
                                        "BRL", "BWP", "CAD", "CHF", "CLP", "CNY", "COP", "CRC", "CZK", "DKK", "DOP", "DZD", "EEK", "EGP",
                                        "EUR", "FJD", "GBP", "HKD", "HNL", "HRK", "HUF", "IDR", "ILS", "INR", "JMD", "JOD", "JPY", "KES",
                                        "KRW", "KWD", "KYD", "KZT", "LBP", "LKR", "LTL", "LVL", "MAD", "MDL", "MKD", "MUR", "MVR", "MXN",
                                        "MYR", "NAD", "NGN", "NIO", "NOK", "NPR", "NZD", "OMR", "PEN", "PGK", "PHP", "PKR", "PLN", "PYG", "QAR",
                                        "RON", "RSD", "RUB", "SAR", "SCR", "SEK", "SGD", "SKK", "SLL", "SVC", "THB", "TND", "TRY", "TTD",
                                        "TWD", "TZS", "UAH", "UGX", "USD", "UYU", "UZS", "VEF", "VND", "XOF", "YER", "ZAR", "ZMK");
    private $available_currencies_ecb = array("USD", "JPY", "BGN", "CZK", "DKK", "EUR", "GBP", "HUF", "LTL", "LVL", "PLN", "RON", "SEK", "CHF", "NOK", 
                                    "HRK", "RUB", "TRY", "AUD", "BRL", "CAD", "CNY", "HKD", "IDR", "ILS", "INR", "KRW", "MXN", "MYR", 
                                    "NZD", "PHP", "SGD", "THB", "ZAR");
    public function convert($from='', $to='', $source='') {
        if (strlen($from) == 3 && strlen($to) == 3) {
            if ($from == $to) {
                return 1;
            }
            $this->from_code = $from;
            $this->to_code = $to;
            if (!empty($source) && in_array($source, $this->available_sources)) {
                $this->source = $source;
            }
            return $this->fetch();
        }
        return 0;
    }
    private function fetch() {
        if ($this->source == 'google' && in_array($this->from_code, $this->available_currencies_google) && in_array($this->to_code, $this->available_currencies_google)) {
            $url = "http://www.google.com/ig/calculator?hl=en&q=1".$this->from_code."=?".$this->to_code;
            $response = file_get_contents($url);
            if ($response !== false) {
                $data = $this->convert_google_json($response);
                return round((float)$data['rhs'],4);
            }
        } else if ($this->source == 'ecb' && in_array($this->from_code, $this->available_currencies_ecb) && in_array($this->to_code, $this->available_currencies_ecb)) {
            $url = "http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml";
            $response = file_get_contents($url);
            if ($response !== false) {
                $data = simplexml_load_string($response); 
                foreach($data->Cube->Cube->Cube as $values){
                    if ($values['currency'] == $this->from_code){
                        $from_rate = $values['rate'];
                    } 
                    if ($values['currency'] == $this->to_code){
                        $to_rate = $values['rate'];
                    }             
                } 
                if ($this->from_code == "EUR"){$from_rate = 1;}
                if ($this->to_code == "EUR"){$to_rate = 1;}
                
                return round((1 / (float) $from_rate) * (float) $to_rate, 4);
            }
        }
        return 0;
    }
    private function convert_google_json($json) {
        $output = array();
        $values = explode(",", substr($json,1,strlen($json)-2));
        foreach($values as $val) {
            list($var_name, $var_content) = explode(":", $val);
            $var_name = trim(str_replace('"','',$var_name));
            $var_content = trim(str_replace('"','',$var_content));
            $output[$var_name] = $var_content;
        }
        return $output;
    }
}
?>