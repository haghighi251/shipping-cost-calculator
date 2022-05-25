<?php

namespace scc\functions;

class functions{
    public function __construct() {}
    
    /**
     * 
     * This method returns user IP
     * 
     * @return type
     */
    public function get_user_ip() {
        $ip = "";
        $server = filter_input_array(INPUT_SERVER);
        if (!empty($server["HTTP_CLIENT_IP"])) {
            $ip = $server["HTTP_CLIENT_IP"];
        } elseif (!empty($server["HTTP_X_FORWARDED_FOR"])) {
            $ip = $server["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $server["REMOTE_ADDR"];
        }
        return $ip;
    }
}