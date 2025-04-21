<?php
namespace App\Services;

use Illuminate\Support\Facades\File;

class Image {
    public static function proccess($file_to_store, $file_path, $image_name, $last_image_name = null){
        if(!$file_to_store) return $last_image_name ?? null;

        if ($last_image_name){
            $path = public_path($file_path . '/'. $last_image_name);
            if (File::exists($path))
                File::delete($path);
        }

        $image_name = $image_name . '.' . $file_to_store->extension();
        $file_to_store->move(public_path($file_path), $image_name);

        return $image_name;

    }
    
    public static function proccessDefName($file_to_store, $file_path, $last_image_name = null){
        if(!$file_to_store) return $last_image_name ?? null;

        if ($last_image_name){
            $path = public_path($file_path . '/'. $last_image_name);
            if (File::exists($path))
                File::delete($path);
        }
        
        $filename= date('YmdHi').$file_to_store->getClientOriginalName();
        $file_to_store->move(public_path($file_path), $filename);

        return $filename;
    } 
    
    public static function remove($file_path, $image_name){
        $path = public_path($file_path . '/'. $image_name);
        if (File::exists($path))
            File::delete($path);
    }
}
?>