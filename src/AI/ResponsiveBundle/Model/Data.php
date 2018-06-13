<?php
namespace AI\ResponsiveBundle\Model;

class Data {
    /**
     * [CallRest Used to call the REST API on the client side]
     * @param [string] $service         [the name of the REST service being called]
     * @param [string] $environment     [The environment to be used: prod or dev]
     * @param [string] $submitType      [post, get or postget]
     * @param [string or array] $data   [the data to be passed]
     * * @param [boolean] $encode       [wether or not to json encode 'postget' submissions]
     */
    public function CallRest($service, $environment, $submitType, $data, $encode = false) {
        $ch = curl_init();

        switch ($environment) {
            case 'prod':
                $RESTserver = "http://prod-load-balancer-8090-754838643.ap-southeast-1.elb.amazonaws.com";
                break;
            case 'dev':
                //$RESTserver = "http://202.66.128.138:8090"; // Old Server
                $RESTserver = "http://202.66.128.138:8093"; // New Angular JS Setup Server
                break;
            case 'preprod':
                $RESTserver = "http://202.66.128.138:8090";
                break;
            default:
                $RESTserver = "http://202.66.128.138:8090";
        }

        switch ($service) {
            case 'create-new-account':
                $url = "/customer-service/customer-legacy/create-new-account?clientInfo=";
                break;
            case 'log-in-by-email':
                $url = "/customer-service/customer-legacy/log-in-by-email?";
                break;
            case 'auth-test':
                $url = "/customer-service/customer-legacy/auth-test";
                break;
            case 'is-login-exist':
                $url = "/customer-service/customer-legacy/is-login-exist";
                break;
            case 'is-email-exist':
                $url = "/customer-service/customer-legacy/is-email-exist";
                break;
            case 'need-more-information':
                $url = "/customer-service/online-inquiry/create";
                break;
            case 'content-download':
                $RESTserver = "http://www.asiainspection.com:1028";
                $url = "/customer-service/content-download/create";
                break;
                
        }

        switch ($submitType) {
            case 'post':
                curl_setopt($ch, CURLOPT_URL, $RESTserver . $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8'));
                curl_setopt($ch, CURLOPT_POST, 1);
                $data = json_encode($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;

            case 'postget':
                if ($encode) {
                    $data = urlencode(json_encode($data));
                } else {
                    $data = http_build_query($data);
                }
                curl_setopt($ch, CURLOPT_URL, $RESTserver . $url . $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8'));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, array());
                break;

            case 'get':
                $data = http_build_query($data);
                curl_setopt($ch, CURLOPT_URL, $RESTserver.$url."?".$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                break;

            case 'postform':
                $headers = array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'service-call-token: 4d09a5553e772093a7fea071b54cc510'
                );
                $data = http_build_query($data);
                curl_setopt($ch, CURLOPT_URL, $RESTserver . $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
        }

        //Sending the Request
        $returnedval = curl_exec($ch);
        $CURLinfo = curl_getinfo($ch);
        if($errno = curl_errno($ch)) {
             $error_message = curl_strerror($errno);
                return array('error' =>  $error_message);
            }
        if ($returnedval && $CURLinfo['http_code'] != 500) {
            if($service == 'is-login-exist' || $service == 'is-email-exist')
                return ($returnedval == "true" ? '1' : '0');
            else
                return ($returnedval === true ? 'true' : $returnedval);
        } else {
            //Error Handling
            return array('error' => $returnedval);
        }
        curl_close($ch);

    } //End of 'CallRest' Function


//following function not fuctioning yet.
    /**
     * [callLotus description]
     * @param  [string] $agent [agent's name ]
     * @param [string] $environment [The environment to be used: prod or dev,default to dev]
     * @param [string] $agentLoc [the database with the lotus agent in it]
     * @param [string] $submitType [defaults to GET but can be set to POST (depending on what the database agent is expecting)]
     * @param [boolean] $spam [defaults to false but if any error checking below marks the submission as spam, this will be set to true]
     */
    public function callLotus($agent, $data, $environment, $agentLoc = "qi/aiweb.nsf", $submitType = "GET") {
        //$agentdomain = $_SERVER['HTTP_HOST'];
        if ($agent != "") {
            if ($environment =="prod") {
                $url = "http://aius3.asiainspection.com/";
            }
            //For staging Server
            else {
                $url = "http://preprod2.asiainspection.com/";
            }
            //For Staging Server
            $path = $agentLoc . "/" . $agent . "?OpenAgent";
            $kv = array();
            //$kv[] = "domain=" . urlencode($agentdomain);
            
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $subarray = array();
                    foreach ($value as $subkey => $subvalue) {
                        $subarray[] = $subvalue;
                    }
                    $sub = join(",", $subarray);
                    $kv[] = "$key=" . rawurlencode($sub);
                } else {
                    $kv[] = "$key=" . rawurlencode($value);
                }
            }
            
            $params = join("&", $kv);

            if ($submitType == "POST") {
                $ch = curl_init($url . $path);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $returnedval = curl_exec($ch);

            } else {
                $returnedval = file_get_contents($url . $path . "&" . $params);
            }
            return array('error' => $returnedval);
        }
    } //End of 'callLotus' function

    /**
     * [getDBconnection Get a connection to the database]
     * @return [array] [array with the connection (if successful), error result (bool), and error message]
     */
    public function getDBconnection(){
        try {
            $db = mysqli_connect("54.251.119.26","aidata","byNdf9W44T","Staging");
        } catch (\Exception $e) {
            //Catch Exceptions and send back the errors
            return array(
                "connection" => false,
                "error" => true,
                "msg" => $e->getMessage()
            );
        }

        if(!$db){
            //Catch DB errors and send back the errors
            return array(
                "connection" => false,
                "error" => true,
                "msg" => $db->connect_error
            );
        }else{
            //No problems, set error to false and send back the connection
            return array(
                "connection" => $db,
                "error" => false,
                "msg" => ""
            );
        }
    }

} //End of 'Data' Class

?>