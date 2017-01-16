<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2017. 01. 16.
 * Time: 21:52
 */

namespace AppBundle\Service;




use AppBundle\Entity\UploadedFile;

class FileUploadHelper
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $fileObj){

        if(!isset($fileObj)) return null;
        $file = $fileObj->getContent();
        $file_extension = $file->guessExtension();
        $file_client_name = str_replace(' ', '-',pathinfo($file->getClientOriginalName())['filename']);

        $fileObj->setExtension($file_extension);
        // if filename is not given, use the client_filename
        if(null===$fileObj->getFileName())
            $fileObj->setFileName($file_client_name);
        $fileObj->setSize($file->getSize());
        //0123456977554filename.ex
        $db_filename = md5(uniqid()).$fileObj->getFileName().'.'.$file_extension;
        //path/to/file/filename.ex
        $db_abs_path = $this->targetDir.$db_filename;
        $fileObj->setAbsolutePath($db_abs_path);
        $fileObj->setUri($db_filename);
        $file->move($this->targetDir,$db_filename);
        return $fileObj;


    }
}