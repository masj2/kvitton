const url = 'upload.php';
document.getElementById("form1").style.display='block';
document.getElementById("form2").style.display='none';
document.querySelector('.first').addEventListener('submit', (e) => {
	e.preventDefault();

	var files = document.querySelector('[type=file]').files;

	if(files.length>0){
		var formData = new FormData();

		for (let i = 0; i < files.length; i++) {
			let file = files[i];
			formData.append('files[]', file); 
		}
		document.getElementById("form1").style.display='none';
		document.getElementById("form2").style.display='block';
		fetch(url, {
			method: 'POST',
			body: formData,
		}).then((response) => {
			console.log(response);
		})
	}
})
var rad = document.sec.typ;
var prev = null;
for (var i = 0; i < rad.length; i++) {
	rad[i].addEventListener('change', function() {
		(prev) ? console.log(prev.value): null;
		if (this !== prev) prev = this;
		if(this.value==1) document.getElementById("bank").style.display='block';
		else document.getElementById("bank").style.display='none';
	});
}