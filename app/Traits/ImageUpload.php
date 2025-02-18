<?php

namespace App\Traits;

use App\Enums\StorageMethodEnums;
use App\Http\Controllers\StorageController;
use Str;

trait ImageUpload
{
    public function imageUploadTrait($file, $old = null, $path = '', $force_use_filesystem = false): string // Taking input image as parameter
    {
        // Validations
        $allowExt = ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp'];
        $ext = strtolower($file->getClientOriginalExtension());
        if ($file->getSize() > 5100000) {
            abort('406', 'max file size:5MB ');
        }
        if (!in_array($ext, $allowExt)) {
            abort('406', 'only allow : jpeg, png, jpg, gif, svg, webp');
        }

        // AWS S3
        if(getStorageMethod() == StorageMethodEnums::AWS_S3 && !$force_use_filesystem) {
            $signed_url = StorageController::AWSUpload($file, $path);
            return $signed_url;
        } 

        // FileSystem
        if(getStorageMethod() == StorageMethodEnums::FILESYSTEM || $force_use_filesystem) {
            if ($old != null) {
                self::delete($old);
            }
            $image_name = Str::random(40);
            $image_full_name = $image_name . '.' . $ext;
            $upload_path = 'assets/global/storage/' . $path . '/';    
            $image_url = $upload_path . $image_full_name;
            $success = $file->move($upload_path, $image_full_name);
    
            return str_replace('assets/', '', $image_url);
        }

        
    }

    protected function delete($path)
    {
        if (is_string($path)) {
            if (file_exists('assets/' . $path)) {
                @unlink('assets/' . $path);
            }
        }
    }
}
