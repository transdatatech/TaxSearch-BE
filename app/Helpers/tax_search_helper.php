<?php
/**********Tax Search Helper Functions************/

use \Illuminate\Http\Response;

//helper function to CSV to Array
if (!function_exists('csvToArray')) {
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}

//Helper to create randomstring
if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

//Api json response helpers
if(!function_exists('setSuccessResponse')){
    function setSuccessResponse($message,$data=[],$code=Response::HTTP_OK)
    {
        return response()->json([
            'status'=>true,
            'message'=>$message,
            'data'=>$data
        ],$code);
    }
}

if(!function_exists('setErrorResponse')){
    function setErrorResponse($message,$errors=[],$code=Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'status'=>false,
            'message'=>$message,
            'errors'=>$errors
        ],$code);
    }
}

?>
