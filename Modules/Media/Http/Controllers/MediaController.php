<?php

namespace Modules\Media\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Media\Repositories\MediaRepository;
use Illuminate\Http\Request;
use Modules\Media\Traits\UploadFile;
use Modules\Setting\Repositores\SettingRepository;
use ReflectionClass;

class MediaController extends Controller
{
    use UploadFile;

    public $mediaRepository;

    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;

    }

    public function create($file, $path)
    {
        $media_array = [
            'product_id' => $file['product_id'] ?? null,
            'title' => $file['title'] ?? null,
            'link' => is_string($file['file']) ? $file['file'] : $this->store_file($file['file'], $path),
            'download_name' => $file['download_name'] ?? $file['file']->getClientOriginalName(),
            'type' => $file['type'],
            'video_length' => $file['video_length'] ?? null,
            'video_hash' => $file['video_hash'] ?? null,
            'created_by' => auth()->id(),
       ];
        return $this->mediaRepository->create($media_array);
    }

    public function deleteFiles(request $request)
    {
        if (!is_null($request->class_name)) {
            $class = 'Modules\\' . $request->module_name . '\Models\\' . $request->class_name;
            $object = $class::find($request->record_id);
            $object->update([$request->field_title => null]);
        }

        //delete media
        $this->mediaRepository->delete($request->file_id);
        return response()->json(true);

    }

    public function uploadFiles(Request $request)
    {
        $image = $this->store_file($request->file('file'), $request->upload_file_path);
        return response(['url' => $image, 'ext' => $request->file('file')->getClientOriginalExtension()]);
    }


    public function remove_file(Request $request)
    {

        $media = $this->mediaRepository->find($request->id);
        $media = $this->mediaRepository->upadte($media, ['link' => null]);
        unlink(public_path($request->name));
        return response(true);
    }
//    public function remove_file(Request $request)
//    {
//        unlink(public_path($request->name));
//        $media=$this->mediaRepository->find($request->id);
//        $media=$this->mediaRepository->upadte($media,[
//            'link'=>null
//        ]);
//
//        return response(true);
//    }

    public function update($media, $file, $path)
    {

        $media_array = [
            'title' => $file['title'] ?? null,
            'link' => is_string($file['file']) ? $file['file'] : $this->store_file($file['file'], $path),
            'download_name' => $file['download_name'] ?? $file['file']->getClientOriginalName(),
            'type' => $file['type'],
            'video_length' => $file['video_length'] ?? null,
            'video_hash' => $file['video_hash'] ?? null,
            'created_by' => auth()->id(),

        ];
        return $this->mediaRepository->upadte($media, $media_array);
    }

    public function download($media_id)
    {
        $media = $this->mediaRepository->find($media_id);
        if (!is_null($media->link) && file_exists(public_path($media->link)))
            return response()->download(public_path($media->link), $media->download_name);
        toast('دانلود فایل امکان پذیر نمی باشد', 'warning')->background('#F08080')->timerProgressBar();
        return back();

    }
}
