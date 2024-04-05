<?php

namespace App\Http\Controllers;

use App\Models\FileTag;
use App\Models\Philanthropist;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use function App\Helpers\readeble_file_size;

class TestController extends Controller
{

    public function index(Request $request)
    {
        return view('auth.verify');
//        return Philanthropist::with('profileImage')->where('firstname', 'like', '%abraham%')->paginate(5);

//        dd($request->getHost());
        $philanthopistId = 182;
//        return redirect(route('philanthropists.edit', $philanthopistId))->with('success', 'Philanthropist is created successfully');
//
//        $savePath = 'public/uploads/philanthropists/' . 182;
////        if (!Storage::exists($savePath)) {
////            Storage::makeDirectory($savePath);
////        }
//
//        $fileList = [];
//
//        $files = Storage::allFiles($savePath);


//        foreach ($files as $file){
//            dump($file);
//            dump(Storage::getMimeType($file));
//        }
//        foreach ($files as $file) {
//            dump(readeble_file_size(Storage::size($file)));
//            dump($file);
//            dump($file);
//           dump( date("m/d/Y H:i:s", Storage::lastModified($file)));
//            $fileName = pathinfo($file, PATHINFO_FILENAME) . pathinfo($file, PATHINFO_EXTENSION);
//            $extension = basename(dirname($file));
//            $fileList[$extension][] = ['file' => $file, 'icon' => 'fa-fas-file'];
//        }
////
//        foreach ($fileList as $ext => $files) {
//            foreach ($files as $file) {
//
//                $fileUrl = str_replace('public', 'storage', asset($file['file']));
//                $startIndex = strpos($fileUrl, '/uploads/');
//                $filePath = substr($fileUrl, $startIndex, strlen($fileUrl) - $startIndex);
//                $filePath = 'public' . $filePath;
////                dump($filePath);
////                dump(filemtime(str_replace('public', 'storage', asset($file['file']))));
//            }
//        }
////        dd($fileList);
////        return view('test.index');
    }
}
