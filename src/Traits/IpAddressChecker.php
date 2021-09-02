<?php

namespace Rollswan\AuditTrail\Traits;

trait IpAddressChecker
{
    /**
     * Get the location of the ip address.
     *
     * @param string|null $ip
     * @return array|null
     */
    public function getIpAddressLocation($ip = null)
    {
        // Prepare the data
        $data = null;

        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = $_SERVER['REMOTE_ADDR'];
     
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }

        $continents = [
            'AF' => 'Africa',
            'AN' => 'Antarctica',
            'AS' => 'Asia',
            'EU' => 'Europe',
            'OC' => 'Australia (Oceania)',
            'NA' => 'North America',
            'SA' => 'South America',
        ];

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $ipLocation = @json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip=' . $ip));
            if (@strlen(trim($ipLocation->geoplugin_countryCode)) == 2) {
                $data = [
                    'city' => @$ipLocation->geoplugin_city,
                    'state' => @$ipLocation->geoplugin_regionName,
                    'country' => @$ipLocation->geoplugin_countryName,
                    'country_code' => @$ipLocation->geoplugin_countryCode,
                    'continent' => @$continents[strtoupper($ipLocation->geoplugin_continentCode)],
                    'continent_code' => @$ipLocation->geoplugin_continentCode,
                    'latitude' => @$ipLocation->geoplugin_latitude,
                    'longitude' => @$ipLocation->geoplugin_longitude,
                    'currency_code' => @$ipLocation->geoplugin_currencyCode,
                    'area_code' => @$ipLocation->geoplugin_areaCode,
                    'dma_code' => @$ipLocation->geoplugin_dmaCode,
                    'region' => @$ipLocation->geoplugin_region,
                ];
            }
        }

        return $data;
    }
}
