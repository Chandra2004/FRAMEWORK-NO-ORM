<?php

namespace TheFramework\App;

use Exception;
use ReflectionClass;
use ReflectionNamedType;
use Closure;

class Container
{
    /**
     * The current globally available container (if any).
     */
    protected static $instance;

    /**
     * The container's bindings.
     */
    protected $bindings = [];

    /**
     * The container's shared instances (singletons).
     */
    protected $instances = [];

    /**
     * Get the globally available instance of the container.
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Register a binding with the container.
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    /**
     * Register a shared binding in the container.
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Resolve the given type from the container.
     */
    public function make($abstract)
    {
        // 1. Jika sudah ada instance singleton, kembalikan
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->getConcrete($abstract);

        // 2. Jika concrete bisa di-callable (Closure), eksekusi
        if ($concrete instanceof Closure) {
            $object = $concrete($this);
        } else {
            // 3. Jika tidak, resolve class via Reflection
            $object = $this->resolve($concrete);
        }

        // 4. Jika binding ditandai shared/singleton, simpan instance
        // atau jika abstract == concrete (autowired singleton behavior optional, 
        // tapi di sini kita simpan hanya jika terdaftar sebagai shared)
        if (isset($this->bindings[$abstract]) && $this->bindings[$abstract]['shared']) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * Get the concrete type for a given abstract.
     */
    protected function getConcrete($abstract)
    {
        if (isset($this->bindings[$abstract])) {
            return $this->bindings[$abstract]['concrete'];
        }

        return $abstract;
    }

    /**
     * Resolve a class instance using Reflection.
     */
    protected function resolve($concrete)
    {
        try {
            $reflector = new ReflectionClass($concrete);
        } catch (Exception $e) {
            throw new Exception("Target class [$concrete] does not exist.", 0, $e);
        }

        // Cek apakah class bisa diinstansiasi
        if (!$reflector->isInstantiable()) {
            throw new Exception("Target class [$concrete] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        // Jika tidak ada constructor, langsung return instance baru
        if (is_null($constructor)) {
            return new $concrete;
        }

        // Resolve dependencies
        $dependencies = $constructor->getParameters();
        $instances = $this->resolveDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    /**
     * Resolve a list of dependencies.
     */
    protected function resolveDependencies($dependencies)
    {
        $results = [];

        foreach ($dependencies as $dependency) {
            $type = $dependency->getType();

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                // Tipe primitif (string, int) atau tidak ada type hint
                if ($dependency->isDefaultValueAvailable()) {
                    $results[] = $dependency->getDefaultValue();
                } else {
                    // Tidak bisa resolve variable scalar tanpa default value
                    throw new Exception("Unresolvable dependency resolving [$dependency] in class {$dependency->getDeclaringClass()->getName()}");
                }
            } else {
                // Class dependency
                $results[] = $this->make($type->getName());
            }
        }

        return $results;
    }
}
