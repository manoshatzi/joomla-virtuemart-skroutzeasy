<?php
/**
 * @package    SkroutzEasy component for Joomla 1.5.x and 1.6.x
 * @copyright  Copyright (c) 2012 Skroutz S.A. - http://www.skroutz.gr
 * @link       http://developers.skroutz.gr/oauth2
 * @license    MIT
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
class SkroutzEasyController extends JController
{
    /**
     * Default constructor
     *
     * @access    public
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {
        parent::display();
    }

    /**
     * Redirect user for oAuth authorization
     *
     * @access    public
     */
    public function login()
    {
        // Check for request forgeries
	JRequest::checkToken() or jexit('Invalid Token');

	// More info: http://docs.joomla.org/Component_parameters
        $credentials = $this->getCredentials();
        
       //url for redirect
        $url = $this->getAuthorizationUrl($credentials);

	// redirect
	$this->setRedirect($url);
    }

    /**
     * This is the callback
     */
    function callback()
    {
        $credentials = $this->getCredentials();
        
        $client_id = $credentials->client_id;
        $client_secret = $credentials->client_secret;
        $redirect_uri = $credentials->redirect_uri;

        if (isset($_GET['code']))
        {

            //set POST variables
            $url = 'https://www.skroutz.gr/oauth2/token';
            $fields = array(
                'code'=>urlencode($_GET['code']),
                'client_id'=>urlencode($client_id),
                'client_secret'=>urlencode($client_secret),
                'redirect_uri'=>urlencode($redirect_uri),
                'grant_type'=>urlencode('authorization_code')
            );

            //url-ify the data for the POST
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string,'&');

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt($ch,CURLOPT_POST,count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);
            $theResult=json_decode($result);

            $oauth_token=$theResult->access_token;
            $url = 'https://www.skroutz.gr/oauth2/address';
            $qry_str = "?oauth_token=".urlencode($oauth_token);

            $ch = curl_init();
            // Set query data here with the URL
            curl_setopt($ch, CURLOPT_URL,$url . $qry_str);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, '3');
            $content = trim(curl_exec($ch));
            curl_close($ch);

            // Create the user
            $this->registerUser($content);
        }
        
        if(isset($_GET['error']))
        {
            global $mainframe;
            $message = $mainframe->enqueueMessage(JText::_($_GET['error_description'], false));
            $this->setRedirect('index.php', $message);
        }

    }

    private function getData($json)
    {
        // get the address
        $db =& JFactory::getDBO();
        $state = 'SELECT `virtuemart_state_id`,`virtuemart_country_id` FROM #__virtuemart_states WHERE state_name='.$db->quote($json->region);
        $db->setQuery( $state );
        $st = $db->loadObject();
        
        $data['name'] = $json->first_name." ".$json->last_name;
        $data['username'] = $json->email;
        $data['password'] = "";
        $data['password2'] = "";
        $data['company'] = $json->company;
        $data['email'] = $json->email;
        $data['title'] = "";
        $data['first_name'] = $json->first_name;
        $data['middle_name'] = "";
        $data['last_name'] = $json->last_name;
        $data['address_1'] = $json->address;
        $data['address_2'] = "";
        $data['zip'] = $json->zip;
        $data['city'] = $json->city;
        $data['virtuemart_country_id'] = $st->virtuemart_country_id;
        $data['virtuemart_state_id'] = $st->virtuemart_state_id;
        $data['phone_1'] = $json->phone;
        $data['phone_2'] = $json->mobile;
        $data['fax'] = $json->company_phone;
        $data['afm'] = $json->afm;
        $data['doy'] = $json->doy;
        $data['profession'] = $json->profession;
        $data['agreed'] = "1";
        $data['address_type'] = 'BT';
        
        if(isset($json->shipping_address))
        {
            $stateST = 'SELECT `virtuemart_state_id`,`virtuemart_country_id` FROM #__virtuemart_states WHERE state_name='.$db->quote($json->shipping_address->region);
            $db->setQuery( $stateST );
            $sast = $db->loadObject();
            $data['ST']['name'] = $json->shipping_address->first_name." ".$json->shipping_address->last_name;
            $data['ST']['company'] = $json->shipping_address->company;
            $data['ST']['title'] = "";
            $data['ST']['first_name'] = $json->shipping_address->first_name;
            $data['ST']['middle_name'] = "";
            $data['ST']['last_name'] = $json->shipping_address->last_name;
            $data['ST']['address_1'] = $json->shipping_address->address;
            $data['ST']['address_2'] = "";
            $data['ST']['zip'] = $json->shipping_address->zip;
            $data['ST']['city'] = $json->shipping_address->city;
            $data['ST']['virtuemart_country_id'] = $sast->virtuemart_country_id;
            $data['ST']['virtuemart_state_id'] = $sast->virtuemart_state_id;
            $data['ST']['phone_1'] = $json->shipping_address->phone;
            $data['ST']['phone_2'] = $json->shipping_address->mobile;
            $data['ST']['fax'] = $json->shipping_address->company_phone;
            $data['ST']['afm'] = $json->shipping_address->afm;
            $data['ST']['doy'] = $json->shipping_address->doy;
            $data['ST']['profession'] = $json->shipping_address->profession;
            $data['ST']['agreed'] = "1";
            $data['address_type'] = 'ST';
        }
        
        return $data;
    }

    private function registerUser($json)
    {
        jimport('joomla.user.helper');

        $json = json_decode($json);

        $db =& JFactory::getDBO();
        $query = 'SELECT `id`,`username` FROM #__users WHERE email=' . $db->quote( $json->email );
        $db->setQuery( $query );
        $result = $db->loadObject();
        
        $data = $this->getData($json);

        // load the user model
        $userModel = JController::getModel('user');

        // new user
        if (!$result)
        {
            // Get required system objects
            $user       = clone(JFactory::getUser());
            $authorize  =& JFactory::getACL();

            $usersConfig = &JComponentHelper::getParams('com_users');

            // Initialize new usertype setting
            $newUsertype = $usersConfig->get('new_usertype');
            if (!$newUsertype) {
                $newUsertype = 'Registered';
            }

            // Bind the post array to the user object
            if (!$user->bind($data, 'usertype')) {
                JError::raiseError( 500, $user->getError());
            }

            // Set some initial user values
            $user->set('id', 0);
            $user->set('usertype', $newUsertype);
            $user->set('gid', $authorize->get_group_id('', $newUsertype, 'ARO'));
            $user->set('block', 0); // no validation necessary

            $date =& JFactory::getDate();
            $user->set('registerDate', $date->toMySQL());

            // If there was an error with registration, set the message and display form
            if (!$user->save())
            {
                JError::raiseWarning('', JText::_( $user->getError()));
                return false;
            }

            //store billing address for the user
            $userModel->storeBT($data,$user,'1');
            
            if(isset($json->shipping_address))
            {
                //store shipping address for the user
                $userModel->storeST($data['ST'],$user,'1');
            }

            $mainframe = JFactory::getApplication();
            $credentials = array(
                'username' => $user->username,
                'password' => $user->password_clear
            );
            $mainframe->login($credentials);
            
            if(!class_exists('VirtueMartCart')) require(JPATH_VM_SITE.DS.'helpers'.DS.'cart.php');
            $cart = VirtueMartCart::getCart();
            $cart->saveAddressInCart($data, $data['address_type']);
            
            $this->setRedirect('index.php', $message);

        }
        //existing user
        else
        {
            $queryBT = 'SELECT `name`,`company`,`address_1`,`city` FROM #__virtuemart_userinfos WHERE virtuemart_user_id='.$db->quote( $result->id ).' AND address_type='.$db->quote('BT');
            $db->setQuery( $queryBT );
            $addressBT = $db->loadObject();
            if($addressBT->name != $data['name'] || $addressBT->company != $data['company'] || $addressBT->address_1 != $data['address_1'] || $addressBT->city != $data['city'])
            {
                //It should always be stored
                $userModel->updateBT($data,$result->id);
            }

            if(isset($json->shipping_address))
            {
                $queryST = 'SELECT `name`,`company`,`address_1`,`city` FROM #__virtuemart_userinfos WHERE virtuemart_user_id='.$db->quote( $result->id ).' AND address_type='.$db->quote('ST');
                $db->setQuery( $queryST );
                $addressST = $db->loadObject();
                if($addressST->name != $data['ST']['name'] || $addressST->company != $data['ST']['company'] || $addressST->address_1 != $data['ST']['address_1'] || $addressST->city != $data['ST']['city'])
                {
                    //It should always be stored
                    $userModel->updateST($data['ST'],$result->id);
                }
            }
            
            $mainframe = JFactory::getApplication();
            $mainframe->login(array('username' => $result->username),array('skroutz'=>true));

            if(!class_exists('VirtueMartCart')) require(JPATH_VM_SITE.DS.'helpers'.DS.'cart.php');
            $cart = VirtueMartCart::getCart();
            $cart->saveAddressInCart($data, $data['address_type']);

            $this->setRedirect('index.php', $message);
        }
    }

	private function getAuthorizationUrl($credentials)
	{
		$site = 'https://www.skroutz.gr/oauth2/authorizations/new';

		$client_id = urlencode($credentials->client_id);
		$redirect_uri = urlencode($credentials->redirect_uri);

                $url = $site."?client_id=".$client_id."&redirect_uri=".$redirect_uri."&response_type=code";

		return $url;
	}
        
        private function getCredentials()
        {
            $db =& JFactory::getDBO();
 
            $query = 'SELECT * FROM #__skroutzeasy';
            $db->setQuery( $query );
            $credentials = $db->loadObject();
 
            return $credentials;
        }

}