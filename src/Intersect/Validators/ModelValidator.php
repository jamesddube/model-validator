<?php

trait ModelValidator
{
    /**
     * @var $validator \Illuminate\Validation\Validator
     */
    private $validator;

    /**
     * @var $validationRules array
     */
    private $validationRules = [];

    private $modelRulesClass;

    public function __construct()
    {
        $this->init();
    }


    public function validate($key = 'default')
    {
        $this->validateRules($key);

        $this->validator = Validator::make($this->toArray(),$this->validationRules[$key]);

        return $this->validator->fails() ? false : true;
    }

    public function getValidationErrors(){

        return $this->validator->errors();
    }

    private function init(){

        if(class_exists($this->modelRules)){

            $this->modelRulesClass = App::make($this->modelRules);
        }
        else{
            throw new \Exception('Set the modelRules property');
        }

        if(!is_array($this->modelRulesClass->rules['default'])){

            throw new \Exception('Set the default rules array');
        }

        $this->validationRules = $this->modelRulesClass->rules;

    }

    private function validateRules($key){
        if(!array_has($this->validationRules,$key) OR !is_array($this->validationRules[$key])){
            throw new \Exception('rules['.$key.'] is not a valid array');
        }
    }
}