<?php

Class Validation {

    function sanitizeUri2Url($uri) {
        // validate url param exists or not
        if (strpos($uri, "urlToCode=") === false) {
            return false;
        }

        // decode url
        foreach (explode("&", $uri) as $value) {
            $param = explode("=", $value);

            if ($param && ($param[0] == "urlToCode")) {
                $url = urldecode($param[1]);
            }
        }

        // Remove all characters except letters
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return $url;
    }

    function validateUrl($url) {
        // validate url valid or not
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;
    }

    // validate if the short code fit the 6 length long, md5 format string
    function validateShortCode($shortCode) {
        if (!preg_match('/^[a-f0-9]{6}$/', $shortCode)) return false;

        return true;
    }
}

?>