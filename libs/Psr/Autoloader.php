<?php
namespace Psr;

/**
 *
 */
class Autoloader
{
    /**
     * @var self
     */
    private static $instance;
    
    
    /**
     * Ассоциативный массив. Ключи содержат префикс пространства имён,
     * значение — массив базовых директорий для классов в этом пространстве имён.
     *
     * @var array
     */
    protected $prefixes = array();
    
    /**
     *
     */
    private function __construct()
    {
    }
    /**
     *
     */
    private function __clone()
    {
    }
    /**
     *
     */
    private function __wakeup()
    {
    }
    
    
    /**
     *
     * @return Autoloader
     */
    public static function getInstance(): Autoloader
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
    
    
    /**
     * Регистрирует загрузчик в стеке загрузчиков SPL.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
    
    /**
     * Добавляет базовую директорию к префиксу пространства имён.
     *
     * @param string $prefix        Префикс пространства имён.
     * @param string $base_dir      Базовая директория для файлов классов из пространства имён.
     * @param bool $prepend         Если true, добавить базовую директорию в начало стека.
     *                                  В этом случае она будет проверяться первой.
     *
     * @return self
     */
    public function &addNamespace($prefix, $base_dir, $prepend = false)
    {
        $prefix = trim($prefix, '\\') . '\\';
        
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }
        
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
        
        return $this;
    }
    /**
     *
     * @param string[] $prefixes
     * @param string $base_dir
     * @param boolean $prepend
     * @return \Psr\Autoloader
     */
    public function &addNamespaces($prefixes, $base_dir, $prepend = false)
    {
        foreach ($prefixes as $prefix) {
            $this->addNamespace(
                $prefix,
                $base_dir.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $prefix)
            );
        }
        //
        return $this;
    }
    
    
    
    
    
    /**
     * Загружает файл для заданного имени класса.
     *
     * @param string $class Абсолютное имя класса.
     * @return mixed        Если получилось, полное имя файла. Иначе — false.
     */
    public function loadClass($class)
    {
        $prefix = $class;
        
        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($class, 0, $pos + 1);
            
            $relative_class = substr($class, $pos + 1);
            
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }
            
            $prefix = rtrim($prefix, '\\');
        }
        
        return false;
    }
    
    /**
     * Загружает файл, соответствующий префиксу пространства имён и относительному имени класса.
     *
     * @param string $prefix            Префикс пространства имён.
     * @param string $relative_class    Относительное имя класса.
     * @return mixed false              если файл не был загружен. Иначе имя загруженного файла.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }
        
        foreach ($this->prefixes[$prefix] as $base_dir) {
            $file = $base_dir
                . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class)
                . '.php'
            ;
            
            if ($this->requireFile($file)) {
                return $file;
            }
        }
        
        return false;
    }
    
    /**
     * Если файл существует, загружеаем его.
     *
     * @param string $file  файл для загрузки.
     * @return bool         true, если файл существует, false — если нет.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
