<?php

Class Validation {

    function validateUri($uri) {
        // validate url param exists or not
        if (strpos($uri, "url=") === false) {
            return false;
        }

        // decode url
        foreach (explode("&", $uri) as $value) {
            $param = explode("=", $value);

            if ($param && ($param[0] == "url")) {
                $url = urldecode($param[1]);
            }
        }

        // Remove all characters except letters
        $url = filter_var($url, FILTER_SANITIZE_URL);

        // validate url valid or not
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return $url;
    }
}

?>