<?php

namespace App\Modules\Communication\Utilities;

use App\Modules\Communication\Exceptions\FileSystem;
use File;

class Folder
{

    /**
     * @param $path
     * @throws FileSystem
     */
    public function folderExists($path)
    {
        if (File::isDirectory($path) !== true) {
            throw new FileSystem('folder exists');
        }
    }

    /**
     * @param $path
     * @throws FileSystem
     */
    public function folderMustExist($path)
    {
        try {
            $this->folderExists($path);
        } catch (FileSystem $e) {
            return $this->createFolder($path);
        }
    }

    /**
     * @param $path
     * @throws FileSystem
     */
    public function createFolder($path)
    {
        if (File::makeDirectory($path, 0775, true) !== true) {
            throw new FileSystem('make directory');
        }
    }

    /**
     * @param $path
     * @throws FileSystem
     */
    public function delete($path)
    {
        if (File::deleteDirectory($path) !== true) {
            throw new FileSystem('remove directory ' . $path);
        }
    }
}
