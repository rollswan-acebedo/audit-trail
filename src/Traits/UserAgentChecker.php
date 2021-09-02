<?php

namespace Rollswan\AuditTrail\Traits;

trait UserAgentChecker
{
    /**
     * Get the user's agents information.
     *
     * @param string $userAgent
     * @return array
     */
    public function getUserAgentInfo($userAgent)
    {
        $userAgent = is_null($userAgent) ? $_SERVER['HTTP_USER_AGENT'] : $userAgent;
        // Enumerate all common platforms, this is usually placed in braces (order is important! First come first serve..)
        $platforms = 'Windows|iPad|iPhone|Macintosh|Android|BlackBerry|Unix|Linux';

        // All browsers except MSIE/Trident and..
        // NOT for browsers that use this syntax: Version/0.xx Browsername
        $browsers = 'Firefox|Chrome|Opera';

        // Specifically for browsers that use this syntax: Version/0.xx Browername
        $browsers_v = 'Safari|Mobile'; // Mobile is mentioned in Android and BlackBerry UA's

        // Fill in your most common engines..
        $engines = 'Gecko|Trident|Webkit|Presto';

        // Regex the crap out of the user agent, making multiple selections and..
        $regex_pat = "/((Mozilla)\/[\d\.]+|(Opera)\/[\d\.]+)\s\(.*?((MSIE)\s([\d\.]+).*?(Windows)|({$platforms})).*?\s.*?({$engines})[\/\s]+[\d\.]+(\;\srv\:([\d\.]+)|.*?).*?(Version[\/\s]([\d\.]+)(.*?({$browsers_v})|$)|(({$browsers})[\/\s]+([\d\.]+))|$).*/i";

        // .. placing them in this order, delimited by |
        $replace_pat = '$7$8|$2$3|$9|${17}${15}$5$3|${18}${13}$6${11}';

        // Run the preg_replace .. and explode on |
        $userAgentArray = explode('|', preg_replace($regex_pat, $replace_pat, $userAgent, PREG_PATTERN_ORDER));

        if (count($userAgentArray) > 1) {
            $data['platform'] = $userAgentArray[0];  // Windows / iPad / MacOS / BlackBerry
            $data['type'] = $userAgentArray[1];  // Mozilla / Opera etc.
            $data['renderer'] = $userAgentArray[2];  // WebKit / Presto / Trident / Gecko etc.
            $data['browser'] = $userAgentArray[3];  // Chrome / Safari / MSIE / Firefox

            /*
               Not necessary but this will filter out Chromes ridiculously long version
               numbers 31.0.1234.122 becomes 31.0, while a "normal" 3 digit version number
               like 10.2.1 would stay 10.2.1, 11.0 stays 11.0. Non-match stays what it is.
            */
            if (preg_match("/^[\d]+\.[\d]+(?:\.[\d]{0,2}$)?/", $userAgentArray[4], $matches)) {
                $data['version'] = $matches[0];
            } else {
                $data['version'] = $userAgentArray[4];
            }
        } else {
            return false;
        }

        // Replace some browsernames e.g. MSIE -> Internet Explorer
        switch (strtolower($data['browser'])) {
            case 'msie':
            case 'trident':
                $data['browser'] = 'Internet Explorer';
                break;
            case '': // IE 11 is a steamy turd (thanks Microsoft...)
                if (strtolower($data['renderer']) == 'trident') {
                    $data['browser'] = 'Internet Explorer';
                }
            break;
        }

        switch (strtolower($data['platform'])) {
            case 'android':    // These browsers claim to be Safari but are BB Mobile
            case 'blackberry': // and Android Mobile
                if ($data['browser'] == 'Safari' || $data['browser'] == 'Mobile' || $data['browser'] == '') {
                    $data['browser'] = "{$data['platform']} mobile";
                }
                break;
        }

        return $data;
    }
}
