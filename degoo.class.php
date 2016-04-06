<?php

class Degoo
{

    private $email;
    private $password;
    private $inviteId;
    private $url = 'https://degoo.com/me/signup';

    /**
     * 
     * @param type $email Valid email address
     * @param type $inviteId Your affiliate id from degoo.com
     * @throws Exception
     */
    public function __construct($email,
	    $inviteId)
    {
	if (!filter_var($email,
			FILTER_VALIDATE_EMAIL))
	{
	    throw new Exception('Email is invalid!');
	}
	$this->email = $email;
	$this->inviteId = $inviteId;
	$this->password = $email;
    }

    /**
     * Set password for generated accounts.
     * Else, password will be the email.
     * @param type $password Password to be used 
     */
    public function setPassword($password = null)
    {
	if ($password)
	{
	    $this->password = $password;
	}
    }

    /**
     * Register one email.
     * @param type $email - Valid Email address
     * @return boolean
     * @throws Exception
     */
    public function registerEmail($email = null)
    {

	if (!$email or
		! filter_var($email,
			FILTER_VALIDATE_EMAIL))
	{
	    throw new Exception('No Email');
	}

	$res = $this->curlURL(array(
	    'Email' => $email,
	    'Password' => $this->password,
	    'Invite' => $this->inviteId,
	    'type' => 'signup',
	    'RedirectUrl' => '',
	));
	if ($res)
	{
	    $page = new DOMDocument();
	    @$page->loadHTML($res);
	    $page->saveHTML();
	    $xpath = new DOMXPath($page);
	    $title = $xpath->query("//title/text()")
			    ->item(0)
		    ->nodeValue;
	    return (trim($title)
		    == 'Download') ? true : false;
	}
	return false;
    }

    /**
     * Register many random generated email to achive desired GB.
     * @param  int $limit  Limit how many GB to add to your account
     * @return array Returns array of registered emails and statuses 
     * @throws Exception
     */
    public function register($limit = 'max')
    {
	if ($limit
		== 'max')
	{
	    $limit = 167;
	}
	if ($limit
		% 3
		!= 0)
	{
	    $limit += 3
		    - $limit
		    % 3;
	}
	$limit = $limit
		/ 3;

	if ($limit
		< 1 or $limit
		> 167)
	    throw new Exception('Limit must be greater then 3, less then 502 or max');
	$return = array();
	for ($i = 0; $i
		< $limit; $i++)
	{
	    $currentEmail = explode('@',
		    $this->email);
	    $modifier = time();
	    $currentEmail = $currentEmail[0] . "+$modifier@" . $currentEmail[1];
	    $return[$currentEmail] = ($this->registerEmail($currentEmail)) ? 'Success' : 'Failed';
	}
	return $return;
    }

    private function curlURL(
    array $post = null)
    {
	$curl_connection = curl_init($this->url);

	if ($post and is_array($post))
	{
	    $fields_string = '';

	    foreach ($post as $key => $value)
	    {
		$fields_string .= urlencode($key) . '=' . urlencode($value) . '&';
	    }

	    rtrim($fields_string,
		    '&');

	    curl_setopt($curl_connection,
		    CURLOPT_POST,
		    count($post));
	    curl_setopt($curl_connection,
		    CURLOPT_POSTFIELDS,
		    $fields_string);
	}

	curl_setopt($curl_connection,
		CURLOPT_USERAGENT,
		"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	curl_setopt($curl_connection,
		CURLOPT_RETURNTRANSFER,
		true);
	curl_setopt($curl_connection,
		CURLOPT_SSL_VERIFYPEER,
		false);
	curl_setopt($curl_connection,
		CURLOPT_FOLLOWLOCATION,
		1);

	$result = curl_exec($curl_connection);
	curl_close($curl_connection);

	return $result;
    }

}
