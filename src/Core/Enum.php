<?php

namespace Ximdex\Core;

use ReflectionClass;

abstract class Enum
{
    protected $current_constant;
    
    private static $constCacheArray;
    
    abstract protected static function data();
    
    public function __construct($constant, $strict = true)
    {
        if ($strict && !static::isValidValue($constant)) {
            throw new \InvalidArgumentException('Invalid constant name');
        }
        if (static::isValidValue($constant)) {
            $this->current_constant = $constant;
            $this->getData();
        }
    }
    
    /**
     * Get all constants in the class
     * 
     * @return array
     * @throws null
     */
    public static function getConstants()
    {
        if (self::$constCacheArray == null) {
            self::$constCacheArray = [];
        }
        $calledClass = static::class;
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }
    
    /**
     * Check if enum name exist in the class
     * 
     * @param string $name Name of enum value
     * @param bool $strict If it is false, it ignore case sensitive.
     * @return bool
     */
    public static function isValidName($name, $strict = false)
    {
        $constants = self::getConstants();
        if ($strict) {
            return array_key_exists($name, $constants);
        }
        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }
    
    /**
     * Check if enum value exist in the class
     *
     * @param mixed $value Value of enum
     * @param bool $strict If it is false, it ignore the type.
     * @return bool
     */
    public static function isValidValue($value, $strict = true)
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }
    
    public static function getValues($attribute, array $less = [])
    {
        $values = [];
        foreach (static::data() as $key => $info) {
            if (!in_array($key, $less)) {
                $values[$key] = is_array($info) ? $info[$attribute] : $info;
            }
        }
        return $values;
    }
    
    public function __call($name, $arguments)
    {
        $func = strtolower(str_replace('get', '', $name));
        if (isset($this->$func)) {
            return $this->$func;
        }
        throw new \BadMethodCallException("Method {$name} does not exists");
    }
    
    private function getData()
    {
        $current = static::data()[$this->current_constant];
        foreach ($current as $key => $value) {
            $this->{strtolower($key)} = $value;
        }
    }
}
