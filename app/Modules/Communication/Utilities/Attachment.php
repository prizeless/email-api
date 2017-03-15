<?php

namespace App\Modules\Communication\Utilities;

use App\Modules\Communication\Definitions\FileMimeTypes;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Modules\Communication\Utilities\File as FileUtil;

use App\Modules\Communication\Definitions\Attachment as Definition;

class Attachment
{
    private $allowedTypes = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'odt', 'txt'];

    private $attachments = [];

    private $basePath;

    private $uploadedFiles;

    public function __construct(array $files)
    {
        $this->basePath = storage_path()
            . DIRECTORY_SEPARATOR
            . 'attachments'
            . DIRECTORY_SEPARATOR
            . Carbon::now()->timestamp
            . '_';

        $this->uploadedFiles = $files;
    }

    /**
     * @param $messageId
     */
    public function moveAllUploadedFiles($messageId)
    {
        (new Folder)->folderMustExist($this->basePath . $messageId);

        foreach ($this->uploadedFiles as $file) {
            $safeName = (new \App\Modules\Communication\Utilities\File)->nameMakeSafe($file['name']);

            $imageData = (new Encode)->base64Decode($file['data']);

            $extension = $this->getFileExtension($imageData);

            if (in_array($extension, $this->allowedTypes) !== true) {
                continue;
            }

            $filePath = $this->getFilePath($messageId, $safeName, $extension);

            File::put($filePath, $imageData);

            $this->attachments[] = new Definition($safeName, $filePath, $extension);
        }
    }

    private function getFileExtension($imageData)
    {
        $mimeType = (new FileUtil)->getMimeFromString($imageData);

        return (new FileMimeTypes)->getExtension($mimeType);
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    private function getFilePath($messageId, $safeName, $extension)
    {
        return $this->basePath . $messageId . DIRECTORY_SEPARATOR . $safeName . '.' . $extension;
    }
}
