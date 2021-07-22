<?php

namespace Perevertunoff\MyToolsPhp\Specifics;

use Perevertunoff\MyToolsPhp\Functions\OtherFunctions;

class Profile
{
    protected $path_to_files;
    protected $file_extension;
    protected $name;
    protected $data = [];

    public function __construct(?string $path_to_files = null, ?string $file_extension = null)
    {
        if ($path_to_files) {
            $this->setPathToFiles((string) $path_to_files);
        } else {
            $this->setPathToFiles($_SERVER['DOCUMENT_ROOT'] . '/config/profiles');
        }

        if ($file_extension) {
            $this->setFileExtension((string) $file_extension);
        } else {
            $this->setFileExtension('.profile.php');
        }

        $profiles = $this->returnIncludedProfiles($path_to_files, $file_extension);

        if ($profiles) {
            $profile = $this->returnDetectedProfile($profiles);
            if ($profile && key($profile) && current($profile)) {
                $this->setProfile(key($profile), current($profile));
            }
        }
    }

    protected function setPathToFiles(string $path)
    {
        $this->path_to_files = $path;
    }

    protected function setFileExtension(string $file_extension)
    {
        $this->file_extension = $file_extension;
    }

    protected function setName(string $name)
    {
        $this->name = $name;
    }

    protected function setData(array $data)
    {
        $this->data = $data;
    }

    protected function setValue($key, $value)
    {
        $this->data[$key] = $value;
    }

    protected function setProfile(string $name, array $data)
    {
        $this->setName($name);
        $this->setData($data);
    }

    public function getPathToFiles()
    {
        return $this->path_to_files;
    }

    public function getFileExtension()
    {
        return $this->file_extension;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getValue($key)
    {
        if (isset($this->data[$key])) return $this->data[$key];

        return false;
    }

    public function isDetected()
    {
        if ($this->getName() && $this->getData()) return true;

        return false;
    }

    public function isNotDetected()
    {
        if ($this->isDetected()) return false;

        return true;
    }

    protected function returnIncludedProfiles(?string $path_to_files = null, ?string $file_extension = null)
    {
        if ($path_to_files === null) $path_to_files = $this->getPathToFiles();
        if ($file_extension === null) $file_extension = $this->getFileExtension();

        return OtherFunctions::returnArrayIncludedFiles($path_to_files, $file_extension);
    }

    protected function returnDetectedProfile(?array $profiles = null)
    {
        if ($profiles && key($profiles) && current($profiles)) {
            return [key($profiles) => current($profiles)];
        }

        return false;
    }
}
