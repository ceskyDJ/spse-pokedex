<?php

declare(strict_types = 1);

namespace App\Common;

use App\Exceptions\NonInjectablePropertyException;
use function lcfirst;
use function preg_match;
use ReflectionException;
use function stristr;
use InvalidArgumentException;
use ReflectionObject;
use function array_pop;
use function explode;
use function get_class;

/**
 * Default object for classes that use dependency injection (most of them)
 *
 * @author Michal ŠMAHEL (ceskyDJ) <admin@ceskydj.cz>
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Common
 */
class DefaultObject
{

    /**
     * Injects dependency instance manually
     *
     * @param object $instance Dependency instance
     *
     * @throws NonInjectablePropertyException Instance cannot be injected
     * @throws InvalidArgumentException No dependency want this instance
     */
    public function injectDependencyManually(object $instance): void
    {
        // Get class name
        $className = $this->getShortClassNameFromFullOne(get_class($instance));
        $dependencyProperty = lcfirst($className);

        // Get doc comment
        try {
            $reflection = new ReflectionObject($this);
            $property = $reflection->getProperty($dependencyProperty);

        } catch (ReflectionException $e) {
            // Invalid property - cannot occur (property comes from reflection)
            $property = null;
        }

        $docComment = $property->getDocComment();

        // Property (class parameter) have to be injectable
        if(!stristr($docComment, "@inject"))
            throw new NonInjectablePropertyException("Property {$dependencyProperty} cannot be injected");

        // There has to be dependency in the class, which requires specified instance
        $neededClassName = $this->getDependency($docComment);

        if($neededClassName != $className)
            throw new InvalidArgumentException("Specified instance isn't used in the class");

        // Insert instance into property (class parameter)
        $property->setAccessible(true);
        $property->setValue($this, $instance);
    }

    /**
     * Returns class dependencies
     *
     * @return array Array of class dependencies
     */
    public function getDependencies(): array
    {
        // Get properties (class parameters)
        $reflection = new ReflectionObject($this);
        $properties = $reflection->getProperties();

        $dependencies = [];
        foreach($properties as $property) {
            $docComment = $property->getDocComment();
            $dependencies[] = $this->getDependency($docComment);
        }

        return $dependencies;
    }

    /**
     * Returns short class name from the long one
     *
     * @param string $fullClassName Full qualified class name (with namespaces)
     *
     * @return string Short class name (only class name)
     */
    private function getShortClassNameFromFullOne(string $fullClassName): string
    {
        $classParts = explode("\\", $fullClassName);

        return array_pop($classParts);
    }

    /**
     * Gets dependency from property's doc comment
     *
     * @param string $docComment Doc comment
     *
     * @return string Dependency (short class name)
     */
    private function getDependency(string $docComment): string
    {
        // There has to be dependency in the class, which requires provided instance
        $matches = [];
        preg_match("%@var ([a-zA-Z0-9\\\_]+)%", $docComment, $matches);

        list(, $dependency) = $matches;

        return $dependency;
    }
}