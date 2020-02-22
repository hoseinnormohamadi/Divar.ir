<?php


class BackEnd
{
    public $dblink;

    public function __construct()
    {
        $this->dblink = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die (mysqli_connect_error());
        $this->query("SET NAMES 'UTF-8'");
        $this->dblink->set_charset('utf8');
        mysqli_set_charset($this->dblink,"utf8");
    }

    public function query($q)
    {
        $result = mysqli_query($this->dblink, $q);
        if (stristr($q, "SELECT"))
            return $result;
        elseif (stristr($q, "INSERT"))
            return mysqli_insert_id($this->dblink);
        elseif (stristr($q, "UPDATE") || stristr($q, "DELETE"))
            return mysqli_affected_rows($this->dblink);
    }
    public function getRow($result)
    {
        return mysqli_fetch_assoc($result);
    }

    public static function Proxys(){
        $username = array(
            'xagjmhhh-1','xagjmhhh-2','xagjmhhh-3','xagjmhhh-4','xagjmhhh-5','xagjmhhh-6','xagjmhhh-7','xagjmhhh-8','xagjmhhh-9','xagjmhhh-10'
        );
        return $username[mt_rand(0,count($username)-1)];
    }

    public static function GetLastPosts(){
        $proxyip = 'p.webshare.io';
        $proxyport = '80';
        $proxyUSRPSW = self::Proxys().':lzbhdmxqt61f';
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://search.divar.ir/json/',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => '{"jsonrpc":"2.0","id":1,"method":"getPostList","params":[[["place2",0,["1"]]],0]}',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
            CURLOPT_HTTPPROXYTUNNEL => true,
            CURLOPT_PROXY => $proxyip,
            CURLOPT_PROXYPORT => $proxyport,
            CURLOPT_PROXYUSERPWD => $proxyUSRPSW,
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response,true);
    }

    public static function GetNumber($token){
        $proxyip = 'p.webshare.io';
        $proxyport = '80';
        $proxyUSRPSW = self::Proxys().':lzbhdmxqt61f';
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://api.divar.ir/v5/posts/'.$token.'/contact/',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPPROXYTUNNEL => true,
            CURLOPT_PROXY => $proxyip,
            CURLOPT_PROXYPORT => $proxyport,
            CURLOPT_PROXYUSERPWD => $proxyUSRPSW,
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response,true);
        if (empty($result['widgets']['contact']['phone'])){
            return null;
        }
        return $result['widgets']['contact']['phone'];
    }

    public static function GetCatering(){
        $proxyip = 'p.webshare.io';
        $proxyport = '80';
        $proxyUSRPSW = self::Proxys().':lzbhdmxqt61f';
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://api.divar.ir/v8/web-search/tehran/catering-services',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPPROXYTUNNEL => true,
            CURLOPT_PROXY => $proxyip,
            CURLOPT_PROXYPORT => $proxyport,
            CURLOPT_PROXYUSERPWD => $proxyUSRPSW,
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response,true);
    }
    public static function AddToDb($token,$title,$district){
        $phone = self::GetNumber($token);
        if (empty($phone) || $phone == ""){
            return "Invalid";
        }
        if (self::Checknumber($phone) == true ){
            $q = "INSERT INTO `Catering`(`title`, `number`, `district`) VALUES ('$title','$phone','$district')";
            $res = (new BackEnd)->query($q);
            if ($res > 0){
                return "Added";
            }else{
                return "Error";
            }
        }else{
            return "Exists";
        }
    }
    public static function Checknumber($number){
        $q = "SELECT * FROM `Catering` WHERE `number` LIKE '%$number%'";
        $res = (new BackEnd)->query($q);
        $row = (new BackEnd)->getRow($res);
        if ($row > 0){
            return false;
        }else{
            return true;
        }
    }

}