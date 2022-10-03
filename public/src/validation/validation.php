<?php
use Respect\Validation\validator As Respect;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;
class validator{

	public $errors;
	public function validate(array $data, array $rules){

		
		foreach ($rules as $field=>$rule){
			try
			{
				$rule->setName($field)->assert($data[$field]);
			}
			catch(NestedValidationException $e){

				$this->errors[$field] = $e->getMessages();

			}
			
		}
		
		return $this;

	}

	public function failed(){
		return !empty($this->errors);
	}

}
