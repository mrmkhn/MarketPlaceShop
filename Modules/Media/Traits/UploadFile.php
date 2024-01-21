<?php

namespace Modules\Media\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Setting\Repositores\SettingRepository;

trait UploadFile
{

    public  function store_file($file,$path)
    {
        $settingRepository=new SettingRepository();
        if($settingRepository->get_str_value('use_download_host'))
            $path = $this->ftp_store_file($file, $path);
        else
            $path = $this->simple_store_file($file, $path);
        return $path;

    }
    public static function simple_store_file($file,$p)
    {
        $filename = $file->getClientOriginalName();
        $path = public_path($p . $filename);
        if (Auth::check()) {
            $m = uniqid().auth()->user()->id;
            $filename = $m . $filename;
        }
        else{
            $m =uniqid() .rand('10000','99999');
            $filename = $m . $filename;
        }

        $file->move(public_path($p), $filename);
        return $p . "/" . $filename;

    }
    public static function ftp_store_file($file,$path)
    {
        $filename = $file->getClientOriginalName();
        $path_n = $path . $filename;
        $filename =uniqid() . $filename;
        Storage::disk('ftp')->putFileAs( '/public_html/'.$path , $file,$filename);
        return $path . "/" . $filename;
    }
}
