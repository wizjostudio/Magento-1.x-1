<?php

abstract class Dotpay_Form_Base extends Zend_Form {

  private $model;
  protected $required   = array();
  protected $validators = array();

  public function __construct(Dotpay_Model_Base $model, $options = NULL) {
    $this->model = $model;
    parent::__construct($options);
  }

  /**
   * @return Dotpay_Model_Base
   */
  public function getModel() {
    return $this->model;
  }

  public function loadDefaultDecorators() {
    $this->setDecorators(array('FormElements', 'Form'));
  }

  protected function getModelFieldNames() {
    return array_merge($this->model->getFieldNames(), $this->required);
  }

  public function init() {
    $this->initValidators();
    foreach ($this->getModelFieldNames() as $field)
      $this->__addElement($field);
  }

  abstract protected function initValidators();

  private function getValidatorsForField($field) {
    $validators = array();
    if (array_key_exists($field, $this->validators)) { 
      $validators = $this->validators[$field];
      if (!is_array($validators))
        $validators = array($validators);
    }
    return $validators;
  }

  private function getValueFromModel($field) {
    $filter = new Zend_Filter_Word_UnderscoreToCamelCase;
    $getterName = 'get'.$filter->filter($field);
    return $this->model->$getterName();
  }

  private function getFormForModel($value) {
    $formName = str_replace('Dotpay_Model_', 'Dotpay_Form_', get_class($value));
    return new $formName($value);
  }

  protected function normalizeFieldName($field) {
    $filter = new Zend_Filter;
    $filter->
      addFilter(new Zend_Filter_Word_CamelCaseToUnderscore)->
      addFilter(new Zend_Filter_StringToLower);
    return $filter->filter($field);
  }

  private function __addElement($field) {

    $value = $this->getValueFromModel($field);

    if (is_object($value) && !($value instanceof Dotpay_Model_Base))
      return;

    $normalizedField = $this->normalizeFieldName($field);

    if (is_object($value)) {
      $form = $this->getFormForModel($value);
      $form->removeDecorator('Form');
      return $this->addSubForm($form, $normalizedField);
    }

    $element = new Zend_Form_Element_Hidden($normalizedField);

    $element->
      setRequired(in_array($normalizedField, $this->required) ? TRUE : FALSE)->
      setValue($value)->
      removeDecorator('Label')->
      removeDecorator('HtmlTag');

    foreach ($this->getValidatorsForField($normalizedField) as $validator)
      $element->addValidator($validator);

    $this->addElement($element);
  }

  public function getValues($suppressArrayNotation = FALSE) {

    $values = array();
    foreach ($this->getElements() as $key => $element)
      if (!$element->getIgnore())
        $values[$key] = $element->getValue();

    foreach ($this->getSubForms() as $key => $subForm)
      $values = array_merge($values, $subForm->getValues());

    return $values;
  }
}