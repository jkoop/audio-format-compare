<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioFormatCompareController extends Controller {
    public function upload() {
        request()->validate([
            'file' => 'required|max:2000|mimes:audio/mpeg,mpga,mp3,aac,m4a,wav,ogg,opus,x-mod',
        ]);

        $file = request()->file('file');
        $filehash = md5_file($file->getRealPath());

        $uploadName = $filehash . '.' . $file->extension();
        $uploadPath = '/storage/audio-uploads/' . $uploadName;

        Storage::putFileAs('public/audio-uploads', request()->file('file'), $uploadName);

        chdir(storage_path('app/public/audio-uploads'));

        // $this->convertSound($uploadName, 'aac', 8000); // aac doesn't does support bitrates this low
        $this->convertSound($uploadName, 'aac', 12000);
        $this->convertSound($uploadName, 'aac', 16000);
        $this->convertSound($uploadName, 'aac', 24000);
        $this->convertSound($uploadName, 'aac', 32000);
        $this->convertSound($uploadName, 'aac', 48000);
        $this->convertSound($uploadName, 'aac', 64000);
        // $this->convertSound($uploadName, 'mp3', 8000); // mp3 doesn't does support bitrates this low
        // $this->convertSound($uploadName, 'mp3', 12000); // mp3 doesn't does support bitrates this low
        // $this->convertSound($uploadName, 'mp3', 16000); // mp3 doesn't does support bitrates this low
        // $this->convertSound($uploadName, 'mp3', 24000); // mp3 doesn't does support bitrates this low
        $this->convertSound($uploadName, 'mp3', 32000);
        $this->convertSound($uploadName, 'mp3', 48000);
        $this->convertSound($uploadName, 'mp3', 64000);
        // $this->convertSound($uploadName, 'ogg', 8000); // ogg doesn't does support bitrates this low
        // $this->convertSound($uploadName, 'ogg', 12000); // ogg doesn't does support bitrates this low
        // $this->convertSound($uploadName, 'ogg', 16000); // ogg doesn't does support bitrates this low
        // $this->convertSound($uploadName, 'ogg', 24000); // ogg doesn't does support bitrates this low
        // $this->convertSound($uploadName, 'ogg', 32000); // ogg doesn't does support bitrates this low
        $this->convertSound($uploadName, 'ogg', 48000);
        $this->convertSound($uploadName, 'ogg', 64000);
        $this->convertSound($uploadName, 'opus', 8000);
        $this->convertSound($uploadName, 'opus', 12000);
        $this->convertSound($uploadName, 'opus', 16000);
        $this->convertSound($uploadName, 'opus', 24000);
        $this->convertSound($uploadName, 'opus', 32000);
        $this->convertSound($uploadName, 'opus', 48000);
        $this->convertSound($uploadName, 'opus', 64000);

        return view('pages.project.audio-format-compare', compact('filehash'));
    }

    private function convertSound(string $relativePath, string $extension, int $bitrate) {
        $outputPath = pathinfo($relativePath, PATHINFO_FILENAME) . '_' . ($bitrate / 1000) . 'k.' . $extension;
        exec(sprintf('ffmpeg -n -i %s -vn -sn -b:a %d -to 0:30 %s', $relativePath, $bitrate, $outputPath));
    }
}
