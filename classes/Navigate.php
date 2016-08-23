<?php
class Navigate
{
    private $_location = '', $_title = 'Home', $_parameters , $_pathCorrection = '';
	private function getTo($page, $isLoggedIn = false)
	{
		if ($page) {
			// if there is page information, turn it into an array
			$value = explode('_', $page, 2);
			// figur out what foulder the file is sepposed be in
			$permish = ($value[0] == 'admin') ? (($isLoggedIn) ? Redirect::to(Config::get('server_path/admin_root')) : 'public/') : (($value[0] == 'pri') ? 'private/' : 'public/');

			// make the content array
            $this->_location ='pages/' . $permish . $value[1] . '.php';	// create the path to the file
			$this->_title =	str_replace('_', ' ', $value[1]);			// create the title
			// counting / in the path structure
			if(substr_count($this->_location, '/') != 2)
			{
                    $this->_location = '';
                    $this->_title = '';
			}
		}
		else
        {
            $this->_location = 'pages/public/Front.php';
        }
	}

	// needs controllers to direct page routs,
    private function prettyTo ($path = 'pages/controller/Home.php')
    {
        // build default navigation array location
        $this->_parameters = array('Home', 'index');
        $countParams = 0;
        // strip away the unnecessary part of $_SERVER['REQUEST_URI'] and turn it into an array
        $request= str_replace(Config::get('server_path/folder'), "", $_SERVER['REQUEST_URI']);
        $params	= explode ("/", $request);
        // if there are no "parameters" found in the $_SERVER['REQUEST_URI'], return the default navigation
        if(!count($params))
        {
            $this->_location = $path;
            // if there are "parameters", save the count of them and get the Config file for legal routs
            $countParams = count($params);
        }
        $conf= '';
        require_once ('pages/template/leagalRouts.php');
        // if there is only one parameter, check if it's a legal route and if it is overwrite the default route, with the new parameters
        if($countParams == 1)
        {
            if(isset($conf[$params[0]]))
            {
                $this->_location = 'pages/controller/' . $params[0]  . '.php';
                $this->_title = $params[0];
                $this->_parameters = array($params[0], 'index');
            }
        }
        else if($countParams > 1) //if there are more then two parameters check if the two first match a legal route and if it dose then overwrite the default route, with the new parameters
        {
            if (isset($conf[$params[0]])) {
                if (in_array($params[1], $conf[$params[0]])) {
                    $this->_location  = 'pages/controller/' . $params[0] . '.php';
                    $this->_title = $params[0] . ': ' . $params[1];
                    $this->_parameters = $params;
                } elseif (is_numeric($params[1])) {
                    $this->_location = 'pages/controller/' . $params[0] . '.php';
                    $this->_title = $params[0];
                    $this->_parameters = $params;
                }
            }
        }
        // quick fix for an error I found, regarding stylesheets, when there are more then 2 parameters, could be done better
        if($countParams != 1)
        {
            for ($x = 1; $x < $countParams; $x++) {
                $this->_pathCorrection .= '../';
            }
        }
    }

    public static function page($type = 'getTo', $page, $isLoggedIn = false)
    {
        switch ($type)
        {
            case 'getTo':
                self::getTo($page, $isLoggedIn);
            break;
            case 'prettyTo':
                self::prettyTo();
            break;
        }
    }

    public function location()
    {
        return $this->_location;
    }

    public function title()
    {
        return $this->_title;
    }

    public function parameters()
    {
        return  $this->_parameters;
    }

    public function pathCorrection()
    {
        return $this->_pathCorrection;
    }
}