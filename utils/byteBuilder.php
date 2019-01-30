<?php

class ByteBuilder {
    
    private $bytes_arr;

    public function appendUint64($ui){
        $bytes_uint = pack('Q', $ui);
        if(is_array($bytes_uint)){
            array_merge($bytes_arr, $bytes_uint);
        }
        else{
            array_push($bytes_arr, $bytes_uint);
        }

    }
    public function appendB64str($b64_str){
        $decoded_str = base64_decode($b64_str);
        $bytes_str = unpack('C*', $bytes_str);

        if(is_array($bytes_str)){
            array_merge($bytes_arr, $bytes_str);
        }
        else{
            array_merge($bytes_arr, $bytes_str);
        }
    }

    public function getBytesArr(){
        return $bytes_arr;
    }
}