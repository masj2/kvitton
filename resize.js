'use strict';
window.onload = function () {
var croppr = new Croppr('#croppr', {
	startSize: [80, 80, '%'],
	onCropMove: function onCropMove(value) {
		updateValue(value.x, value.y, value.width, value.height);
	}
	});
	var value = croppr.getValue();
	updateValue(value.x, value.y, value.width, value.height);
};

/** Functions */
function updateValue(x, y, w, h) {
	document.getElementById("x").value =  x;
	document.getElementById("y").value = y;
	document.getElementById("w").value = w;
	document.getElementById("h").value = h;
}