<?php

declare(strict_types = 1);

namespace App\DI;

use ReflectionException;

/**
 * Interface determining class with manual injecting allowed
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\DI
 */
interface SpecialInjectable
{
    /**
     * Injects dependencies manually
     *
     * @param DIContainer $diContainer DI container instance
     *
     * @throws ReflectionException Non existing class
     */
    public function inject(DIContainer $diContainer): void;
}