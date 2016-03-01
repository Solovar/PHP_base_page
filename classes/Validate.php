<?php
class Validate
{
	private $_passed 		 = false,
			$_errors 		 = array(),
			$_db	 		 = null,
			$_file_lim		 = null,
			$_total_file_lim = null,
			$_max_file_count = null,
			$_exclude		 = null;

	// construct the database handler and needed data information
	public function __construct()
	{
		$this -> _db			 = DB::getInstance();
		$this -> _file_lim		 = Config::get('upload/upload_limet');
		$this -> _total_file_lim = Config::get('upload/uploade_total');
		$this -> _max_file_count = Config::get('upload/max_file_count');
		$this -> _exclude		 = require_once 'functions/wordExcludeArr.php';
	}
	// perfoarm the check on values against the rules
	public function check($source, $items = array())
	{
		$this -> _passed = false;
		foreach($items as $item => $rules)
		{
			foreach($rules as $rule => $rule_value)
			{
				// trim and escape the values and rules
				$value = trim($source[$item]);
				$item  = escape($item);
				// check the if there is a required rule and if the value is empty
				if($rule === 'required' && empty($value))
				{
					$this -> addError("{$item} is required");
				}
				// if the value is not empty run the switch
				else if(!empty($value))
				{
					switch($rule)
					{
						// check for special characters, if any return error
						case 'sumbols':
							if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $value))
							{
								$this -> addError("{$item} may not contain special characters");
							}
							break;
						// check if mail
						case'mail':
							$bool = true;
							if(!filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
								$this -> addError("{$item} must be a valid email");
								$bool = false;
							}
							if($bool)
							{
								$arr = explode('.', $value);
								if(count(end($arr)) >= 2)
								{
									$this -> addError("{$item} must be a valid email");
								}
							}
						break;
						// minimum check
						case'min':
							if(strlen($value) < $rule_value)
							{
								$this -> addError("{$item} must be a minimun of {$rule_value} characters.");
							}
						break;
						// maximum check
						case'max':
							if(strlen($value) > $rule_value)
							{
								$this -> addError("{$item} must be a maximum of {$rule_value} characters.");
							}
						break;
						// check is this value matches anohter value
						case'matches':
							if($value != $source[$rule_value])
							{
								$this -> addError("{$rule_value} must match {$item}");
							}
						break;
						// number check, if $rule_value is true, then check if the number is not a number, else check if the string contains numbers
						case'number':
							if($rule_value) {
								if (!is_numeric($value)) {
									$this->addError("{$item} must be a number");
								}
							}
							else
							{
								if (preg_match('/[0-9]/', $value))
								{
									$this->addError("{$item} may not contain numbers");
								}
							}
						break;
						// check if a match is found in the excludes array and if so return error return error
						case 'exclude':
							foreach(exclude() as $thisValue)
							{
								if(preg_match('/' . $thisValue . '/i', $value))
								{
									$this->addError("{$item} is not allowed to contain $thisValue");
								}
							}
						break;
						// check if this value is unique compared to a spisifyed db row
						case'unique':
							$check = $this ->_db->get($rule_value, array($item, '=', $value));
							if($check->count())
							{
								$this -> addError("{$item} already exsists");
							}
						break;
					}
				}
			}
		}
		// if there are no errors return true
		if(empty($this -> _errors))
		{
			$this -> _passed = true;
		}
		
		return $this;
	}
// for validation of files
	public function fileCheck($number_lim, $files, $required = false, $fileTypes = array())
	{
		// set some predefiens and set _passed to false just to make sure that even if validation is passed then it will still have to evaluater files before being able to carry on
		$this -> _passed = false;
		if(count($files['error']) > $number_lim)
		{
			$this->addError("Total number of files exceed the allowed number of {$number_lim} files per upload");
		}
		else {
			$file_limet = $this->_file_lim / 1000000;
			$total_limet = $this->_total_file_lim / 1000000;
			$i = 0;
			// check if there are any errors returned from file post
			foreach ($files['error'] as $error) {
				switch ($error) {
					case UPLOAD_ERR_INI_SIZE:
						$this->addError("file {$files['name'][$i]} file must be smaller then: {$file_limet} mega bytes");
						break;

					case UPLOAD_ERR_PARTIAL:
						$this->addError("error file {$files['name'][$i]} was only partially uploaded");
						break;
					// if there is no file
					case UPLOAD_ERR_NO_FILE:
						if($required) {
							$this->addError("Chooseing a file is required");
						}
						break;

					case UPLOAD_ERR_NO_TMP_DIR:
						$this->addError("The temporary folder is missing for file nr, {$files['name'][$i]}, contact the site developer");
						break;
					// if there is an error in the file extention
					case UPLOAD_ERR_EXTENSION:
						$this->addError("Uploade stopped on nr, {$files['name'][$i]}, due to unsupported or missing file extension");
						break;
				}
				$i++;
			}
			$i = 0;
			$total_files_size = 0;
			// check file size
			foreach ($files['size'] as $size) {
				$total_files_size += $size;
				if ($size > $this->_file_lim) {
					$this->addError("file {$files['name'][$i]} file must be smaller then: {$file_limet} mega bytes");
				}
				$i++;
			}
			// total file limet
			if ($total_files_size > $this->_total_file_lim) {
				$this->addError("Total uploade exceeds uploade limet of {$total_limet} Mega bytes");
			}
			// check for the limet of files
			if (count($files['error']) > $this->_max_file_count) {
				$this->addError("count of files selected for uploade exceede the limet of {$this->_max_file_count}");
			}
			// check against give file types
			if (count($fileTypes) != 0) {
				$i = 0;
				foreach ($files['type'] as $file) {
					if (!in_array($file, $fileTypes)) {
						if($files['error'][$i] != '4') {
							$file_name = explode('/', $file, 2);
							$this->addError("{$files['name'][$i]}'s filetype: {$file_name[1]} is not a correct file type");
						}
					}
					$i++;
				}
			}
		}
		// if there is no errors set passed to true.
		if(empty($this -> _errors))
		{
			$this -> _passed = true;
		}
/*
		if(!$required && empty($this -> _errors))
		{
			$this -> _passed = true;
		}
*/
		return $this;
	}

	// add errors to the validation
	private function addError($error)
	{
		$this -> _errors[] = $error; 
	}
	// return errors
	public function errors()
	{
		return $this -> _errors;
	}
	// return passed
	public function passed()
	{
		return $this -> _passed;
	}
}