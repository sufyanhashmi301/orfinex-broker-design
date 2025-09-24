<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ImageUpload
{
    public function imageUploadTrait($query, $old = null): string // Taking input image as parameter
    {

        $allowExt = ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp'];
        $ext = strtolower($query->getClientOriginalExtension());

        if ($query->getSize() > 5242880) { // 5 MiB
            abort('406', 'Max file size is 5 MB.');
        }

        if (! in_array($ext, $allowExt)) {
            abort('406', 'only allow : jpeg, png, jpg, gif, svg, webp');
        }

        if ($old != null) {
            self::delete($old);
        }
        $image_name = Str::random(20);
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'assets/global/images/';    //Creating Sub directory in Assets folder to put image
        $image_url = $upload_path.$image_full_name;
        $success = $query->move($upload_path, $image_full_name);
        if (!$success) {
            abort(406, 'Image upload failed.');
        }
        return str_replace('assets/', '', $image_url); // Just return image
    }
    public function depositImageUploadTrait($query, $old = null): string // Taking input image as parameter
    {

        $allowExt = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
        $ext = strtolower($query->getClientOriginalExtension());

        if ($query->getSize() > 5242880) { // 5 MiB
            abort('406', 'Max file size is 5 MB.');
        }

        if (! in_array($ext, $allowExt)) {
            abort('406', 'only allow : jpeg, png, jpg, gif, svg');
        }

        if ($old != null) {
            self::delete($old);
        }
        $image_name = Str::random(20);
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'assets/global/images/deposit/';    //Creating Sub directory in Assets folder to put image
        $image_url = $upload_path.$image_full_name;
        $success = $query->move($upload_path, $image_full_name);

        if (!$success) {
            abort(406, 'Image upload failed.');
        }

        return str_replace('assets/', '', $image_url); // Just return image
    }
    public function kycImageUploadTrait($query, $old = null): string // Taking input image as parameter
    {

        $allowExt = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
        $ext = strtolower($query->getClientOriginalExtension());

        if ($query->getSize() > 5242880) { // 5 MiB
            abort('406', 'Max file size is 5 MB.');
        }

        if (! in_array($ext, $allowExt)) {
            abort('406', 'only allow : jpeg, png, jpg, gif, svg');
        }

        if ($old != null) {
            self::delete($old);
        }
        $image_name = Str::random(20);
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'assets/global/images/kyc/';    //Creating Sub directory in Assets folder to put image
        $image_url = $upload_path.$image_full_name;
        $success = $query->move($upload_path, $image_full_name);

        if (!$success) {
            abort(406, 'Image upload failed.');
        }

        return str_replace('assets/', '', $image_url); // Just return image
    }

    public function paymentDepositFileUploadTrait($query, $old = null): string // Taking input file as parameter
    {
        $allowExt = ['jpeg', 'png', 'jpg', 'gif', 'svg', 'pdf', 'doc', 'docx', 'txt'];
        $ext = strtolower($query->getClientOriginalExtension());

        if ($query->getSize() > 5242880) { // 5 MiB
            abort('406', 'Max file size is 5 MB.');
        }

        if (! in_array($ext, $allowExt)) {
            abort('406', 'only allow : jpeg, png, jpg, gif, svg, pdf, doc, docx, txt');
        }

        if ($old != null) {
            self::delete($old);
        }
        $file_name = Str::random(20);
        $file_full_name = $file_name.'.'.$ext;
        $upload_path = 'assets/global/images/Custom-Payments/';    //Creating Sub directory in Assets folder to put file
        $file_url = $upload_path.$file_full_name;
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        
        $success = $query->move($upload_path, $file_full_name);

        if (!$success) {
            abort(406, 'File upload failed.');
        }

        return str_replace('assets/', '', $file_url); // Just return file path
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
