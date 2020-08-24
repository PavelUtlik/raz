<?php


namespace App\Helpers;

use App\Exceptions\HelperMethodException;
use Exception;
use Illuminate\Support\Facades\File;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class FileHelper
{


    /**
     * @param $path string
     * @param $fileName string
     * @param $file string
     * @return bool|int
     * @throws HelperMethodException
     */
    public static function saveFile($path, $fileName, $file)
    {


        $result = File::put($path . '/' . $fileName, $file);



//        if (self::isImage ($fileName)){
//            self::compressImage ($fileName,$path);
//        }


        if ($result === false) {
            throw new HelperMethodException('Cannot save file to server', 503);
        }
        return $result;

    }

    /**
     * @param $extension string
     * @param bool $moreEntropy
     * @param string $prefix
     * @return string
     */
    public static function getUniqueFilename($extension, $moreEntropy = false, $prefix = '')
    {
        return uniqid($prefix, $moreEntropy) . '.' . $extension;
    }


    /**
     * @param $base64String
     * @return false|string
     */
    public static function base64Decode($base64String)
    {
        $image = explode(',', $base64String)[1];
        return base64_decode($image);
    }


    /**
     * @param $image
     * @return mixed
     */
    public static function getBase64Extension($image)
    {
        try {
            $mimeContent = mime_content_type($image);
        } catch (Exception $exception) {
            throw new HelperMethodException("Can't  get file mime type", 422);
        }

        return explode('/', $mimeContent)[1];
    }


    /**
     * @param $base64String
     * @return bool
     * @throws HelperMethodException
     */
    public static function checkBase64Format($base64String)
    {
//        $result = false;
//        if (strpos($base64String, ',')) {
//            $image = explode(',', $base64String)[1];
//            if (explode(',', $base64String)[0] . ',' . base64_encode(base64_decode($image)) === $base64String) {
//                $result = true;
//            }
//        }
//
//        if ($result === false) {
//            throw new HelperMethodException('File does not match base64 format', 422);
//        }

        try {
            $image = explode(',', $base64String)[1];
            return base64_decode($image);
        } catch (Exception $exception) {
            throw new HelperMethodException('File does not match base64 format', 422);
        }

//        return $result;
    }


    /**
     * @param $base64String
     * @param $savePath
     * @return string
     * @throws HelperMethodException
     */
    public static function storeBase64File($base64String, $savePath)
    {
        self::checkBase64Format($base64String);
        $extension = self::getBase64Extension($base64String);
        $decodedImage = self::base64Decode($base64String);
        $filename = self::getUniqueFilename($extension, true);
        self::saveFile(
            $savePath,
            $filename,
            $decodedImage
        );
        return $filename;
    }


    /**
     * @param $filename string
     * @param $path string
     * @return bool
     * @throws HelperMethodException
     */
    public static function deleteFile($filename, $path)
    {


        $result = File::delete($path . '/' . $filename);

        if ($result === false) {
            throw new HelperMethodException('Cannot delete file to server', 503);
        }
        return $result;
    }

    /**
     * @param $fileNames array<string> consist full path to files
     * @return bool
     */
    public static function deleteMany($fileNames)
    {
        return File::delete($fileNames);
    }

    public static function fetchFullFilePaths($filenames, $path)
    {
        return array_map(function ($fileName) use ($path) {
            return $path . '/' . $fileName;
        }, $filenames);
    }

    public static function compressImage($fileName,$path)
    {
        $optimizerChain = OptimizerChainFactory::create();
        $optimizerChain ->optimize($path . '/' .$fileName);

    }

    public static function isImage($fileName)
    {
        $allowImgFormat = ['jpeg','jpg','png','WebP','SVG','GIF'];
        $fileFormat = substr (strrchr($fileName,"."),1);
        if(in_array ($fileFormat,$allowImgFormat)){
            return true;
        }else {
            return false;
        }
    }
}


















