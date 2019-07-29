<?php

/**
 * Simple REST interface.
 * 
 * Created by wangguangwen at 2018-02-24
 */
 
class REST
{
    protected $host_url;
    function __construct($host_url)
	{
	    $this->host_url = $host_url;
	}
	
	function request($url, $method, $params=array(), $header='')
	{		
	    $url =  $this->host_url . $url ;
	    	    
	    $headers = array('Content-Type: application/json');
	    
	    $ch = curl_init();
	    
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	    
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);	    
	    curl_setopt($ch, CURLOPT_URL, $url);
	    
	    switch ($method) {
	        case 'POST':
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	            break;
	            
	        case 'GET':
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	            break;
	            
	        case 'PUT':
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	            break;
	            
	        case 'DELETE':
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	            break;
	            
	        default:
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	            break;
	    }
	    	   
	    
	    $ret = curl_exec($ch);
	    
// 	    echo 'print_r($ret)';
// 	    echo '<br>';
// 	    print_r($ret);
// 	    echo '<br>';
	    
// 	    $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);	    
// 	    echo "responseCode is $ret";
// 	    echo '<br>';
	    	    
	    if($ret !== FALSE)
	    {
	        
	        echo "ret is $ret";
	        echo '<br>';
	        
	        $formatted = $this->format_response($ret);
	        
	        if(isset($formatted->error))
	        {
	            echo "error is $formatted";
	            echo '<br>';
	            
	            throw new RESTException($formatted->error->message, $formatted->error->code);
	        }
	        else
	        {
	            return $formatted;
	        }
	    }
	    else
	    {
	        echo 'rest : Server did not respond';
	        echo '<br>';
	        
	        throw new RESTException("Server did not respond");
	    }
	    
	    return $response;

	}
	
	    
	function format_response($response)
	{
		return @json_decode($response);
	}
	
}

class RESTException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) 
    {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString() 
    {
        return __CLASS__ . ": ".(($this->code > 0)?"[{$this->code}]:":"")." {$this->message}\n";
    }
}
