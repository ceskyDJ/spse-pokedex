<?php

declare(strict_types = 1);

namespace App\DI;

use App\Common\DefaultObject;
use App\Exceptions\LoadNonInjectableClassException;
use App\Reflection\SmartReflectionClass;
use function end;
use function explode;
use ReflectionException;
use ReflectionObject;
use ReflectionProperty;
use function array_key_exists;
use function get_class;
use function strstr;
use function strtolower;

/**
 * Dependency injection container
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\DI
 */
class DIContainer extends DefaultObject
{
    /**
     * @var array Loaded class instances
     */
    private $loadedInstances = [];

    /**
     * Adds new class instance
     * It's used to adding instances with parametric constructors
     *
     * @param object $instance Instance for inserting
     *
     * @return DIContainer Own instance (method chaining)
     */
    public function addInstance(object $instance): self
    {
        $key = get_class($instance);

        $this->loadedInstances[$key] = $instance;

        return $this;
    }

    /**
     * Returns class instance
     *
     * @param string $classWithNamespace Full qualified class name (with namespaces)
     *
     * @return object Class instance (object)
     * @throws ReflectionException Invalid class
     * @throws LoadNonInjectableClassException Loading class that implements NonInjectable
     */
    public function getInstance(string $classWithNamespace): ?object
    {
        // Instance has been created yet
        if (array_key_exists($classWithNamespace, $this->loadedInstances)
            && $this->loadedInstances[$classWithNamespace] != null) {
            return $this->loadedInstances[$classWithNamespace];
        }

        // Instance cannot be created
        $reflection = new SmartReflectionClass($classWithNamespace);
        if ($reflection->isInstanceOf(NonInjectable::class) && !$reflection->isInstanceOf(SpecialInjectable::class)) {
            throw new LoadNonInjectableClassException("Class {$classWithNamespace} cannot be auto-loaded");
        }

        // Create instance and inject dependencies
        /**
         * Pouze pro případ třídy, do níž je nutno závislosti injektovat manuálně
         * Only in cases of class with manually-injectable property(properties)
         *
         * @var $instance SpecialInjectable Class with manually-injectable property(properties)
         */
        $instance = new $classWithNamespace();

        // Normal class
        if (!$reflection->isInstanceOf(SpecialInjectable::class)) {
            $this->injectDependencies($instance);
        } else {
            $instance->inject($this);
        }

        return $this->loadedInstances[$classWithNamespace] = $instance;
    }

    /**
     * Injects dependencies into instance
     *
     * @param object $instance Instance without injected dependencies
     *
     * @throws ReflectionException Invalid class
     * @throws LoadNonInjectableClassException Not injectable class
     */
    public function injectDependencies(object $instance): void
    {
        // Get properties (class parameters) from instance by reflection
        $reflection = new ReflectionObject($instance);
        $properties = $reflection->getProperties();

        // Parameter iteration and dependency injection
        /**
         * @var $property ReflectionProperty Class property
         */
        foreach ($properties as $property) {
            // Skip parameter, which doesn't require dependency injection
            if (!strstr(strtolower($docComment = $property->getDocComment()), "@inject")) {
                continue;
            }

            // Allow access temporary for setting value via reflection (like "setter")
            $property->setAccessible(true);

            // Extract class name from doc comment
            $matches = [];
            preg_match("%@var ([a-zA-Z0-9\\\_]+)%", $docComment, $matches);

            list(, $className) = $matches;

            // Get full qualified class name
            if(strstr($className, "\\")) {
                // Class name is full qualified type -> everything is OK
                $fullClassName = $className;
            } else {
                // It's the short one, so it's to transform to full qualified one
                // Find full qualified class name (with namespaces)
                $reflectionClass = new SmartReflectionClass($instance);

                try {
                    $reflectionUse = $reflectionClass->getUseForClass($className);
                    $fullClassName = $reflectionUse->getFullClassName();
                }
                catch (ReflectionException $e) {
                    // Dependency class is in the same namespaces like actual instance's class
                    // (=> there isn't any import (use) in the file for required dependency)
                    // Namespaces are inherited from actual instance
                    $fullClassName = $reflectionClass->getNamespaceName()."\\$className";
                }
            }

            // Instance injection into parameter
            $propertyInstance = $this->getInstance($fullClassName);

            $property->setValue($instance, $propertyInstance);
        }
    }
}