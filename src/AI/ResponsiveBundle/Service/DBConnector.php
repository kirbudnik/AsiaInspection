<?php 

namespace AI\ResponsiveBundle\Service;

class DBConnector {
   /**
     * [getDBconnection Get a connection to the database]
     * @return [array] [array with the connection (if successful), error result (bool), and error message]
     */
    //    database_host: 54.251.119.26
    // database_port: null
    // database_name: symfony
    // database_user: aidata
    // database_password: byNdf9W44T

   public function __construct($host, $user, $password, $salesdb, $datadb)
   {
    $this->host = $host;
    $this->user = $user;
    $this->password = $password;
    $this->salesdb = $salesdb;
    $this->datadb = $datadb;
   }

    public function getDBconnection($reqdb = ""){

        try {
            if ($reqdb == 'Data') {
                $db = mysqli_connect($this->host,$this->user,$this->password,$this->datadb);
            } else {
                $db = mysqli_connect($this->host,$this->user,$this->password,$this->salesdb);

            }
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
}