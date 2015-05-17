<?php
/**
 * YamlParser
 * Turn a yaml file into an ArrayData and ArrayList, and some basic searching.
 */
namespace StyleGuide;

class YamlParser {

    protected $path;

    protected $factory;

    public function __construct($path) {
        if(!$this->isYaml($path)) {
            throw new Exception("You can only process .yml files.", 1);
        }

        $this->path = $path;
        $this->setFactory();
    }

    public function setFactory() {
        $factory = \Injector::inst()->create('StyleGuideFixtureFactory');
        $blueprint = \Injector::inst()->create('StyleGuideBlueprint', 'StyleGuide', 'StyleGuide');
        $factory->define('StyleGuide', $blueprint);
        $fixture = \Injector::inst()->create('YamlFixture', $this->path);
        $fixture->writeInto($factory);
        $this->factory = $factory;
    }

    public function get($class) {
        if($obj = $this->factory->get($class)) {
            return $obj;
        }
    }

    public function isYaml($path) {
        $realFile = realpath(BASE_PATH.'/'.$path);
        $baseDir = realpath(\Director::baseFolder());
        
        if(!$realFile || !file_exists($realFile)) {
            return false;
        } else if(substr($realFile,0,strlen($baseDir)) != $baseDir) {
            return false;
        } else if(substr($realFile,-4) != '.yml') {
            return false;
        }

        return true;
    }

}
