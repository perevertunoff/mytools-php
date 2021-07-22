<?php

namespace Perevertunoff\MyToolsPhp\Specifics;

class FileVar
{
    protected $path_to_files;
    protected $file_extension;
    protected $file_premissions;

    public function __construct(?string $path_to_files = null, ?string $file_extension = null, ?numeric $file_premissions = null)
    {
        if ($path_to_files) {
            $this->setPathToFiles((string) $path_to_files);
        } else {
            $this->setPathToFiles($_SERVER['DOCUMENT_ROOT'] . '/var/filevar');
        }

        if ($file_extension) {
            $this->setFileExtension((string) $file_extension);
        } else {
            $this->setFileExtension('.fvr');
        }

        if ($file_premissions) {
            $this->setFilePremissions((int) $file_premissions);
        } else {
            $this->setFilePremissions(0777);
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

    protected function setFilePremissions(int $file_premissions)
    {
        $this->file_premissions = $file_premissions;
    }

    protected function getPathToFiles()
    {
        return $this->path_to_files;
    }

    protected function getFileExtension()
    {
        return $this->file_extension;
    }

    protected function getFilePremissions()
    {
        return $this->file_premissions;
    }

    protected function returnPathToFiles(?string $subdir = null)
    {
        if ($subdir) {
            return $this->getPathToFiles() . '/' . $subdir;
        } else {
            return $this->getPathToFiles();
        }
    }

    protected function returnFileName(string $name)
    {
        return $name . $this->getFileExtension();
    }

    protected function returnFullPath(string $name, ?string $subdir = null)
    {
        return $this->returnPathToFiles($subdir) . '/' . $this->returnFileName($name);
    }

    public function set(string $name, $var, ?string $subdir = null)
    {
        $path_to_files = $this->returnPathToFiles($subdir);
        $full_path = $this->returnFullPath($name, $subdir);
        $file_premissions = $this->getFilePremissions();
        if (!is_dir($path_to_files)) mkdir($path_to_files, $file_premissions, true);
        file_put_contents($full_path, $var);
        return true;
    }

    public function get(string $name, ?string $subdir = null)
    {
        $full_path = $this->returnFullPath($name, $subdir);
        if (is_file($full_path)) return file_get_contents($full_path);
        return false;
    }
}
