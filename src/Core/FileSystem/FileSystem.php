<?php

namespace Ximdex\Core\FileSystem;

use Ximdex\Core\Utils\DateHelpers;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class FileSystem
{
    protected $configs = null;
    
    protected $disk = 'default';

    public function __construct($configs, string $disk = 'default')
    {
        $this->setDisk($disk);
        $this->setConfigs($configs);
    }

    public function setDisk(string $disk): FileSystem
    {
        $this->disk = $disk;
        return $this;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }

    public function setConfigs($configs): FileSystem
    {
        if (is_null($configs) && !is_string($configs) && !is_array($configs)) {
            $type = gettype($configs);
            throw new \InvalidArgumentException("Configs attibute only acepts string or array. Input was: {$type}");
        }
        $this->configs = [
            'disks' => [
                $this->disk => $configs
            ]
        ];
        config(["filesystems.disks.{$this->disk}" => $configs]);
        return $this;
    }

    public function getConfigs()
    {
        config(["filesystems.disks.{$this->disk}" => $this->configs]);
        return config("filesystems.disks.{$this->disk}");
    }

    public function getStorage()
    {
        return Storage::disk($this->disk);
    }

    public function files(string $directory = '')
    {
        return $this->getStorage()->files($directory);
    }

    public function directories(string $directory = '')
    {
        return $this->getStorage()->directories($directory);
    }

    public function getIn(string $path = '')
    {
        return array_merge($this->directories($path), $this->files($path));
    }

    public function isFile(string $path)
    {
        $result = $this->get($path);
        return (bool)$result;
    }

    public function exists(string $path): bool
    {
        return $this->getStorage()->exists($path);
    }

    public function get(string $path)
    {
        if (!$this->exists($path)) {
            throw new FileNotFoundException("File not found on Disk: {$this->disk} at Path: {$path}");
        }
        return $this->getStorage()->get($path);
    }

    public function delete(string $path)
    {
        if (!$this->exists($path)) {
            throw new FileNotFoundException("File not found on Disk: {$this->disk} at Path: {$path}");
        }
        return $this->getStorage()->delete($path);
    }

    public function type($data)
    {
        $mimeTypes = new MimeTypes();
        return $mimeTypes->getGroup($data);
    }

    public function fileData(string $path)
    {
        if (!$this->exists($path)) {
            throw new FileNotFoundException("File not found on Disk: {$this->disk} at Path: {$path}");
        }
        $info = pathinfo($path);
        $extension = $info['extension'] ?? '';
        $type = $this->type($extension);

        $data = [
            'name' => $info['basename'] ?? $path,
            'extension' => $extension,
            'fullpath' => $this->fullPath($path),
            'path' => $info['dirname'] ?? null,
            'bytes' => $this->size($path),
            'size' => $this->size($path, true),
            'mimetype' => (new MimeTypes)->getMimeType($extension),
            'type' => $type,
            'updated_at' => DateHelpers::parse($this->getStorage()->lastModified($path))
        ];
        return array_merge($data, $this->extraData($type, $path));
    }

    protected function fullPath($path)
    {
        return $this->getStorage()->getDriver()->getAdapter()->applyPathPrefix($path);
    }

    protected function size(string $path, bool $human = false): string
    {
        $result = Storage::disk($this->disk)->size($path);
        if ($human) {
            $result = $this->humanSize($result);
        }
        return $result;
    }

    protected function humanSize(int $bytes, int $decimals = 2)
    {
        $size = array('b', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb', 'zb', 'yb');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    protected function extraData(?string $type, string $path)
    {
        $result = [];

        if (method_exists($this, $type)) {
            $result = $this->$type($path);
        }
        return $result;
    }

    protected function image(string $path)
    {
        $image = Image::make($this->get($path));
        $iptc = $image->iptc() ?? [];
        $exif = $image->exif() ?? [];
        $data = [
            'width' => $image->width(),
            'height' => $image->height()
        ];
        return array_merge($data, $iptc, $exif);
    }

    protected function tmp($file): string
    {
        $hash =  uniqid();
        $tmp = 'tmp';

        $stream = $this->getStorage()->getDriver()->readStream($file);
        Storage::put("{$tmp}/{$hash}", $stream);
        return storage_path("app/{$tmp}/{$hash}");
    }
}
