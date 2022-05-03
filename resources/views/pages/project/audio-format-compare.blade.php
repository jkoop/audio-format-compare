@extends('layouts.typical', [
    'lastChangedDate' => '2022-04-29',
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

<form method="post" enctype="multipart/form-data">
    @csrf
    Audio file (max: 2MB, will clip to 30 seconds)
    <input type="file" name="file" id="file" accept="audio/aac,audio/m4a,audio/mp3,audio/ogg,audio/opus" required />
    <button>Upload</button>
</form>

@if (isset($filehash))
    <p class="hidden audio"><audio class="aac-12" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_12k.aac" type="audio/aac" /></audio> 12 kbps aac</p>
    <p class="hidden audio"><audio class="aac-16" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_16k.aac" type="audio/aac" /></audio> 16 kbps aac</p>
    <p class="hidden audio"><audio class="aac-24" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_24k.aac" type="audio/aac" /></audio> 24 kbps aac</p>
    <p class="hidden audio"><audio class="aac-32" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_32k.aac" type="audio/aac" /></audio> 32 kbps aac</p>
    <p class="hidden audio"><audio class="aac-48" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_48k.aac" type="audio/aac" /></audio> 48 kbps aac</p>
    <p class="hidden audio"><audio class="aac-64" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_64k.aac" type="audio/aac" /></audio> 64 kbps aac</p>
    <p class="hidden audio"><audio class="mp3-32" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_32k.mp3" type="audio/mp3" /></audio> 32 kbps mp3</p>
    <p class="hidden audio"><audio class="mp3-48" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_48k.mp3" type="audio/mp3" /></audio> 48 kbps mp3</p>
    <p class="hidden audio"><audio class="mp3-64" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_64k.mp3" type="audio/mp3" /></audio> 64 kbps mp3</p>
    <p class="hidden audio"><audio class="ogg-48" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_48k.ogg" type="audio/ogg" /></audio> 48 kbps ogg</p>
    <p class="hidden audio"><audio class="ogg-64" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_64k.ogg" type="audio/ogg" /></audio> 64 kbps ogg</p>
    <p class="hidden audio"><audio class="opus-8" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_8k.opus" type="audio/ogg" /></audio> 8 kbps opus</p>
    <p class="hidden audio"><audio class="opus-12" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_12k.opus" type="audio/ogg" /></audio> 12 kbps opus</p>
    <p class="hidden audio"><audio class="opus-16" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_16k.opus" type="audio/ogg" /></audio> 16 kbps opus</p>
    <p class="hidden audio"><audio class="opus-24" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_24k.opus" type="audio/ogg" /></audio> 24 kbps opus</p>
    <p class="hidden audio"><audio class="opus-32" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_32k.opus" type="audio/ogg" /></audio> 32 kbps opus</p>
    <p class="hidden audio"><audio class="opus-48" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_48k.opus" type="audio/ogg" /></audio> 48 kbps opus</p>
    <p class="hidden audio"><audio class="opus-64" controls loop preload="auto"><source src="/storage/audio-uploads/{{ $filehash }}_64k.opus" type="audio/ogg" /></audio> 64 kbps opus</p>

    <table>
        <tr>
            <td>aac</td>
            <td></td>
            <td><button class="aac-12" onclick="changeTo('aac-12')">12kbps</button></td>
            <td><button class="aac-16" onclick="changeTo('aac-16')">16kbps</button></td>
            <td><button class="aac-24" onclick="changeTo('aac-24')">24kbps</button></td>
            <td><button class="aac-32" onclick="changeTo('aac-32')">32kbps</button></td>
            <td><button class="aac-48" onclick="changeTo('aac-48')">48kbps</button></td>
            <td><button class="aac-64" onclick="changeTo('aac-64')">64kbps</button></td>
        </tr>
        <tr>
            <td>mp3</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><button class="mp3-32" onclick="changeTo('mp3-32')">32kbps</button></td>
            <td><button class="mp3-48" onclick="changeTo('mp3-48')">48kbps</button></td>
            <td><button class="mp3-64" onclick="changeTo('mp3-64')">64kbps</button></td>
        </tr>
        <tr>
            <td>ogg</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><button class="ogg-48" onclick="changeTo('ogg-48')">48kbps</button></td>
            <td><button class="ogg-64" onclick="changeTo('ogg-64')">64kbps</button></td>
        </tr>
        <tr>
            <td>opus</td>
            <td><button class="opus-8" onclick="changeTo('opus-8')">8kbps</button></td>
            <td><button class="opus-12" onclick="changeTo('opus-12')">12kbps</button></td>
            <td><button class="opus-16" onclick="changeTo('opus-16')">16kbps</button></td>
            <td><button class="opus-24" onclick="changeTo('opus-24')">24kbps</button></td>
            <td><button class="opus-32" onclick="changeTo('opus-32')">32kbps</button></td>
            <td><button class="opus-48" onclick="changeTo('opus-48')">48kbps</button></td>
            <td><button class="opus-64" onclick="changeTo('opus-64')">64kbps</button></td>
        </tr>
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
