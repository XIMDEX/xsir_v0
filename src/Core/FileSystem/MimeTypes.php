<?php
namespace Ximdex\Core\FileSystem;

use Mimey\MimeMappingBuilder;
use Mimey\MimeTypes as MMimetypes;
use Illuminate\Support\Facades\File;

class MimeTypes extends MMimeTypes
{
    protected static $customMimesFolder = 'app/cache';
    protected static $customMimesFile = 'mime.types';
    public function __construct()
    {
        $customMimes = null;
        $path = storage_path(static::$customMimesFolder . "/" . static::$customMimesFile);
        if (\File::exists($path)) {
            $builder = MimeMappingBuilder::load($path);
            $customMimes = $builder->getMapping();
        }
        parent::__construct($customMimes);
    }
    public function getGroup(string $value)
    {
        $value = explode('; ', $value)[0];
        $result = $this->getExtension($value);
        $mime = $this->getMimeType($result ?? $value);
        $result = explode('/', $mime)[0];
        return empty($result) ? $value : $result;
    }
    public static function add(string $mimetype, string $extension)
    {
        $builder = MimeMappingBuilder::create();
        $builder->add($mimetype, $extension);
        if (!File::exists(storage_path(static::$customMimesFolder))) {
            File::makeDirectory(storage_path(static::$customMimesFolder), 0775, true, true);
        }
        $builder->save(storage_path(static::$customMimesFolder . "/" . static::$customMimesFile));
    }
}
