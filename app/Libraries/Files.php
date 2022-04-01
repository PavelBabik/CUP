<?php namespace App\Libraries;

use App\Controllers\BaseController;
use App\Entities\FileEntity;
use App\Models\FilesModel;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;

class Files extends BaseController {

    const THUMB_WIDTH = 150;
    const ALWAYS_GENERATE_NEW = false; // TRUE ONLY IF DEVELOPMENT!!!

    private $FilesModel;
    private $filesDIR;

    public function __construct()
    {
        $this->FilesModel = new FilesModel();
        if (getenv('DIR_FILES')!='') {
            $this->filesDIR = getenv('DIR_FILES');
        } else {
            die("FILES LIBRARY ERROR: <b>DIR_FILES</b> NOT CONFIGURED");
        }
    }

    public function uploadFile(int $worker_id, bool $save_ext = false): bool|FileEntity
    {
        $path_parts = pathinfo($_FILES['userfile']['name']);
        $mime = mime_content_type($_FILES['userfile']['tmp_name']);

        $type = explode('/', $mime)[0];
        $mime_type = explode('/', $mime)[1];

        if ($save_ext) {
            $uploadfileName = md5_file($_FILES['userfile']['tmp_name']).'.'.$path_parts['extension'];
        } else {
            $uploadfileName = md5_file($_FILES['userfile']['tmp_name']);
        }

        $path = $this->generatePath($uploadfileName);

        $uploadPath = $this->filesDIR . $path['string'] . $uploadfileName;

        $this->createDirs($path['array']);

        $isFileExists = $this->FilesModel->where('name', $uploadfileName)->first();

        $FileEntity = new FileEntity();

        $FileEntity->load([
            'name' => $uploadfileName,
            'mime' => $mime,
            'worker_id' => $worker_id,
        ]);

        if ($isFileExists === NULL || self::ALWAYS_GENERATE_NEW) { //TODO: Test this!!!
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadPath)) {
                switch ($type) {
                    case 'image':
                        $this->generatePhotoThumbnail($uploadPath, $mime_type);
                        break;
                    case 'video':
                        $this->generateVideoThumbnail($uploadPath);
                        break;
                }

                return $this->addFileToDatabase($FileEntity);
            } else {
                return false;
            }
        } else {
            return $this->addFileToDatabase($FileEntity);
        }
    }

    private function addFileToDatabase(FileEntity $FileEntity): FileEntity
    {
        try {
            $id = $this->FilesModel->insert($FileEntity);
        } catch (\ReflectionException $e) {
            vdp($e);
        }

        $FileEntity->id = $id;

        return $FileEntity;
    }

    public function renderFile($fileName)
    {
        $path = $this->generatePath($fileName);
        $file = $this->filesDIR . $path['string'].$fileName;

        try {
            $file_bin = file_get_contents($file);
        } catch (\Exception $e) {
            return null;
        }

        $return['file'] = $file_bin;
        $return['mime'] = mime_content_type($file);

        return $return;
    }

    // -----------------------------------------------------------------------------------------------------

    private function generatePath($fileName)
    {
        $fileNameWithoutExtension = pathinfo($fileName)['filename'];
        $arr = str_split($fileNameWithoutExtension, 2);

        array_splice($arr, 6);

        $path = '';
        foreach ($arr as $item) {
            $path .= $item.'/';
        }

        $return['string'] = $path;
        $return['array'] = $arr;

        return $return;
    }

    private function createDirs($path)
    {
        $currentPath = $this->filesDIR;

        foreach ($path as $item) {
            if(!is_dir($currentPath.$item)) {
                mkdir($currentPath.$item.'/');
            }

            $currentPath .= $item.'/';
        }
    }

    private function generatePhotoThumbnail(string $uploadPath, string $mime_type): void
    {
        $filename11 = $uploadPath;

        $size=getImageSize($filename11);
        $iw=$size[0];
        $ih=$size[1];
        $koe=$iw/self::THUMB_WIDTH;
        $new_h=ceil ($ih/$koe);

        $image_p = ImageCreateTrueColor(self::THUMB_WIDTH, $new_h);

        if ($mime_type == 'jpg' || $mime_type == 'jpeg') {
            $image = ImageCreateFromJPEG($filename11);
        } elseif ($mime_type == 'png') {
            $image = ImageCreateFromPNG($filename11);
        } else {
            return;
        }

        ImageCopyResampled($image_p, $image, 0, 0, 0, 0, self::THUMB_WIDTH, $new_h, $iw, $ih);

        imagejpeg($image_p, $uploadPath . '_thumb', 50);
    }

    private function generateVideoThumbnail(string $uploadPath)
    {
        $thumb_path = $uploadPath . '_thumb';

        if (class_exists('\FFMpeg\FFMpeg')) {
            $FFMpeg = FFMpeg::create([
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe',
                'timeout'          => 3600, // the timeout for the underlying process
                'ffmpeg.threads'   => 12,   // the number of threads that FFMpeg should use
            ]);

            $video = $FFMpeg->open($uploadPath);

            $frame = $video->frame(new TimeCode(0, 0, 5, 1));

            if ($frame) {
                $frame->save($thumb_path);
            }
        } else {
            copy(ROOTPATH . 'public/media_errors/ffmpeg_not_installed.jpg', $thumb_path);
        }
    }
}