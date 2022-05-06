<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioFormatCompareController extends Controller {
    const BITRATES = [
        'aac' => [12, 16, 24, 32, 48, 64],
        'mp3' => [32, 48, 64],
        'ogg' => [48, 64],
        'opus' => [8, 12, 16, 24, 32, 48, 64],
    ];

    public function upload() {
        abort(405);
        return;

        request()->validate([
            'file' => 'required|max:20000|mimetypes:audio/mpeg,audio/mpga,audio/mp3,audio/aac,audio/m4a,audio/wav,audio/flac,audio/ogg,audio/opus,audio/x-mod',
        ]);

        $file = request()->file('file');
        $fileHash = md5_file($file->getRealPath());

        $uploadName = $fileHash . '.' . $file->extension();
        $uploadPath = '/storage/audio-uploads/' . $uploadName;

        Storage::putFileAs('public/audio-uploads', request()->file('file'), $uploadName);

        chdir(storage_path('app/public/audio-uploads'));

        foreach (self::BITRATES as $format => $bitrates) {
            foreach ($bitrates as $bitrate) {
                $this->convertSound($uploadName, $format, $bitrate * 1000);
            }
        }

        Storage::delete('public/audio-uploads/' . $uploadName);

        return redirect(url()->current() . "?file=$fileHash");
    }

    private function convertSound(string $relativePath, string $extension, int $bitrate) {
        $outputPath = pathinfo($relativePath, PATHINFO_FILENAME) . '_' . ($bitrate / 1000) . 'k.' . $extension;
        exec(sprintf('nice -n 19 ffmpeg -n -i %s -vn -sn -b:a %d -t 0:30 %s', $relativePath, $bitrate, $outputPath));
    }
}
