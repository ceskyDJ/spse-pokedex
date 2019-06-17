<?php

declare(strict_types = 1);

namespace App\Reflection\Entity;

use App\Reflection\SmartReflectionClass;
use ReflectionException;
use function array_pop;
use function end;
use function explode;
use function implode;

/**
 * Entity for reflection import (use)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Reflection
 */
class ReflectionUse
{

    /**
     * @var SmartReflectionClass Reflection class
     */
    public $class;
    /**
     * @var int Position in array with all import of the class
     */
    public $index;

    /**
     * ReflectionUse construct
     *
     * @param SmartReflectionClass $class Reflection class
     *
     * @param int $index Position in array
     */
    public function __construct(SmartReflectionClass $class, int $index)
    {
        $this->class = $class;
        $this->index = $index;
    }

    /**
     * Returns full qualified class name
     *
     * @return string Class name (with namespaces)
     * @throws ReflectionException Invalid class file
     */
    public function getFullClassName(): string
    {
        return $this->getData()['class'];
    }

    /**
     * Returns namespaces of the class
     *
     * @return string Namespaces
     * @throws ReflectionException Invalid class file
     */
    public function getNamespace(): string
    {
        $classParts = $this->getClassParts();

        array_pop($classParts);

        return implode("\\", $classParts);
    }

    /**
     * Returns class name
     *
     * @return string Class name
     * @throws ReflectionException Invalid class file
     */
    public function getClassName(): string
    {
        $classParts = $this->getClassParts();

        return end($classParts);
    }

    /**
     * Returns import's (use's) alias
     *
     * @return string|null Import's alias (null for no alias)
     * @throws ReflectionException Invalid class file
     */
    public function getAlias(): ?string
    {
        if($this->hasAlias())
            return $this->getData()['alias'];
        else
            return null;
    }

    /**
     * Finds out if import (use) has an alias
     *
     * @return bool Has an alias?
     * @throws ReflectionException Invalid class file
     */
    public function hasAlias(): bool
    {
        $data = $this->getData();

        return (isset($data['alias']) && $data['alias'] != "");
    }

    /**
     * Returns import's (use's) data
     *
     * @return array|null Import's data
     * @throws ReflectionException Invalid class file
     */
    private function getData(): ?array
    {
        return $this->class->getUseData($this->index);
    }

    /**
     * Returns parts of the full qualified class name
     *
     * @return array Parts of the class name (as array)
     * @throws ReflectionException Invalid class file
     */
    private function getClassParts(): array
    {
        return explode("\\", $this->getData()['class']);
    }
}