<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;

class AttachmentController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function upload(Request $request): array
    {
        $files = [];
        foreach ($request->file('files') as $key => $file) {
          $filename = $request->file('files')[$key]->getClientOriginalName();
          $fileNameExtension = $request->file('files')[$key]->getClientOriginalExtension();
            if (!Storage::disk('aws-s3-public')->exists('attachments/'.$filename)) {
              $file = Storage::disk('aws-s3-public')->putFileAs('attachments', $file, $filename);
            } else {
              $file = Storage::disk('aws-s3-public')->putFileAs('attachments', $file, basename($filename,$fileNameExtension).md5_file($file).'.'.$fileNameExtension);
            }
            $publicUrl = Storage::disk('aws-s3-public')->url($file);

            Log::info('file upload', [
              'file' => $publicUrl,
              'filename' => $filename
            ]);

            $files[] = $publicUrl;
        }
        return $files;
    }
}
