<?php

namespace App\Http\Controllers\AdminApi\Attachments;

use App\Http\Controllers\Controller;
use App\Laravue\Models\Media\Attachment;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class AttachmentsController extends Controller
{
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'image',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $files = $request->file();
        $full_paths = [];
        /**
         * @var UploadedFile $file
         */
        foreach ($files as $file) {
            $now = now();
            $pathName = $now->year . '/' . $now->month . '/' . $now->day;
            /**
             * @var Attachment $attachment
             */
            $stateHash = md5_file($file->path());
            $attachment = Attachment::query()->where('state_hash', $stateHash)->first();

            if (is_null($attachment)) {
                $attachment = Attachment::query()->firstOrCreate([
                    'state_hash' => $stateHash,
                    'name' => $file->hashName(),
                    'dir_path' => $pathName,
                    'ext' => $file->extension(),
                    'alt' => ''
                ]);

                $file->store($pathName, 'public');
            }

            $attachment->full = $attachment->getUrl();
            $full_paths[] = $attachment;
        }

        return response()->json([
            'files' => $full_paths
        ]);
    }
}
