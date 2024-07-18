window.active = null;
changeSong(document.querySelector('select'));

function changeSong(select) {
	document.getElementById('players').innerHTML = "";
	document.getElementById('buttons').innerHTML = "";

	document.querySelectorAll('a.source').forEach(a => { a.style.display = "none"; });
	document.querySelector('a#' + select.value).style.display = "inline";

	[
		["aac", 12],
		["aac", 16],
		["aac", 24],
		["aac", 32],
		["aac", 48],
		["aac", 64],
		["mp3", 32],
		["mp3", 48],
		["mp3", 64],
		["ogg", 48],
		["ogg", 64],
		["opus", 8],
		["opus", 12],
		["opus", 16],
		["opus", 24],
		["opus", 32],
		["opus", 48],
		["opus", 64],
	].forEach(([format, bitrate]) => {
		document.getElementById('players').innerHTML += `<p class="hidden audio">
			<audio class="${format}-${bitrate}" controls loop preload="auto">
				<source src="audio/${select.value}_${bitrate}k.${format}" type="audio/${format.replace("opus", "ogg")}" />
			</audio>
			<span class="no-wrap">${bitrate} kbps ${format}</span>
		</p>`;
	});

	[
		['aac', [12, 16, 24, 32, 48, 64]],
		['mp3', [32, 48, 64]],
		['ogg', [48, 64]],
		['opus', [8, 12, 16, 24, 32, 48, 64]],
	].forEach(([format, bitrates]) => {
		let html = `<tr><td>${format}</td>`;
		[8, 12, 16, 24, 32, 48, 64].forEach(bitrate => {
			if (bitrates.includes(bitrate)) {
				html += `<td><button class="${format}-${bitrate}" onclick="changeTo('${format}-${bitrate}')">${bitrate}kbps</button></td>`;
			} else {
				html += `<td></td>`;
			}

		});
		html += `</tr>`;

		document.getElementById('buttons').innerHTML += html;
	});

	changeTo(window.active ?? 'opus-24');
}

function changeTo(type) {
	$('table button').attr('disabled', false);
	$('table button.' + type).attr('disabled', true);

	if ((window.active ?? "").length > 0) {
		document.querySelector('audio.' + window.active).pause();
	}

	$('p.audio').hide();
	$('audio.' + type).closest('p.audio').show();
	window.active = type;

	document.querySelector('audio.' + window.active).play();
}

setInterval(function () {
	if (window.active != '') {
		currentTime = document.querySelector('audio.' + window.active).currentTime;
		currentTime = Math.round(currentTime * 10) / 10;

		document.querySelectorAll('audio:not(.' + window.active + ')').forEach(function (element) {
			element.currentTime = currentTime;
		});
	}
}, 100)
