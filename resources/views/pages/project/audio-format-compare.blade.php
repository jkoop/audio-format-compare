@extends('layouts.typical', [
    'lastChangedDate' => '2022-05-05',
    'shortLinks' => [],
    'allowOnlyContent' => false,
    'githubRepo' => 'jkoop/audio-format-compare',
])
@section('title', 'audio format compare')
@section('description', 'compare audio formats and bitrates')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<style>
    .hidden {
        display: none;
    }
</style>

<form>
    Example audio:

    <select name="file" onchange="$(this).closest('form').submit()">
        <option></option>
        <option value="toms-diner" {{ request('file') == 'toms-diner' ? 'selected' : '' }}>Tom's Diner</option>
        <option value="toms-diner-dna-remix" {{ request('file') == 'toms-diner-dna-remix' ? 'selected' : '' }}>Tom's Diner (DNA Remix)</option>
        <option value="chiptune-3" {{ request('file') == 'chiptune-3' ? 'selected' : '' }}>chiptune 3</option>
    </select>

    @switch(request('file'))
        @case('toms-diner')
            <a target="_blank" href="https://youtu.be/gi02zwXg6wo">YouTube</a>
            @break
        @case('toms-diner-dna-remix')
            <a target="_blank" href="https://youtu.be/bJNxmMk8zvA">YouTube</a>
            @break
        @case('chiptune-3')
            <a target="_blank" href="https://modarchive.org/module.php?166571">ModArchive</a>
            @break
    @endswitch
</form>

{{-- <form method="post" enctype="multipart/form-data">
    @csrf
    Audio file (max: 2MB, will clip to 30 seconds)
    <input type="file" name="file" id="file" accept="audio/mpeg,audio/mpga,audio/mp3,audio/aac,audio/m4a,audio/wav,audio/flac,audio/ogg,audio/opus,audio/x-mod" required />
    <button>Upload</button>
</form> --}}

@if (request('file'))
    @php
        // Laravel Docker local environment doesn't support HTTP Keep-Alive
        // so we need to use another web server with our folder to get the file
        $pathPrefix = env('APP_ENV') == 'local' ? env('LOCAL_PUBLIC') : '';

        $pathFolder = file_exists(public_path() . '/audio-format-compare/examples/' . request('file') . '_64k.ogg') ?
            'audio-format-compare/examples' :
            'storage/audio-uploads';
    @endphp

    @foreach (\App\Http\Controllers\AudioFormatCompareController::BITRATES as $format => $bitrates)
        @foreach ($bitrates as $bitrate)
            <p class="hidden audio">
                <audio class="{{ $format }}-{{ $bitrate }}" controls loop preload="auto">
                    <source
                        src="{{ $pathPrefix }}/{{ $pathFolder }}/{{ request('file') }}_{{ $bitrate }}k.{{ $format }}"
                        type="audio/{{ str_replace('opus', 'ogg', $format) }}" />
                </audio>
                <span class="no-wrap">12 kbps aac</span>
            </p>
        @endforeach
    @endforeach

    <table>
        @foreach (\App\Http\Controllers\AudioFormatCompareController::BITRATES as $format => $bitrates)
            <tr>
                <td>{{ $format }}</td>

                @foreach ([8, 12, 16, 24, 32, 48, 64] as $bitrate)
                    @if (in_array($bitrate, $bitrates))
                        <td><button class="{{ $format }}-{{ $bitrate }}" onclick="changeTo('{{ $format }}-{{ $bitrate }}')">
                            {{ $bitrate }}kbps
                        </button></td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </table>

    <script>
        window.active = '';
        changeTo('opus-24')

        function changeTo(type) {
            $('table button').attr('disabled', false);
            $('table button.' + type).attr('disabled', true);

            if (window.active.length > 0) {
                document.querySelector('audio.' + window.active).pause()
            }

            $('p.audio').hide()
            $('audio.' + type).closest('p.audio').show()
            window.active = type

            document.querySelector('audio.' + window.active).play()
        }

        setInterval(function() {
            if (window.active != '') {
                currentTime = document.querySelector('audio.' + window.active).currentTime
                currentTime = Math.round(currentTime * 10) / 10

                document.querySelectorAll('audio:not(.' + window.active + ')').forEach(function(element) {
                    element.currentTime = currentTime;
                })
            }
        }, 100)
    </script>
@endif

@endsection
