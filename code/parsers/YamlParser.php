<?php

namespace BenManu\StyleGuide;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\YamlFixture;
use SilverStripe\Control\Director;
use Exception;
use BenManu\StyleGuide\StyleGuideFixtureFactory;
use BenManu\StyleGuide\StyleGuideBlueprint;
use BenManu\StyleGuide\StyleGuide;

/**
 * YamlParser
 * Turn a yaml file into an ArrayData and ArrayList, and some basic searching.
 */
class YamlParser {

    protected $path;

    protected $factory;

    public function __construct($path) {
        if(!$this->isYaml($path)) {
            throw new \Exception(sprintf(
                "You can only process .yml files. (Path: %s)",
                $path
            ), 1);
        }

        $this->path = $path;
        $this->setFactory();
    }

    public function setFactory() {
        $factory = Injector::inst()->create(StyleGuideFixtureFactory::class);
        $blueprint = Injector::inst()->create(StyleGuideBlueprint::class, StyleGuide::class, StyleGuide::class);
        $factory->define(StyleGuide::class, $blueprint);
        $fixture = Injector::inst()->create(YamlFixture::class, $this->path);
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
        $baseDir = realpath(Director::baseFolder());

        if(!$realFile || !file_exists($realFile)) {
            return false;
        } else if(substr($realFile,0,strlen($baseDir)) != $baseDir) {
            return false;
        } else if(substr($realFile,-4) != '.yml') {
            return false;
        }

        return true;
    }

    public static function hasYaml($path) {
        $realFile = realpath(BASE_PATH.'/'.$path);
        $baseDir = realpath(Director::baseFolder());

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
