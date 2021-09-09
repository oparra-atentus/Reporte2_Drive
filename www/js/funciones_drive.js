function solicitudXhr(metodo, url,param,formato,archivo){
	var xhr = new XMLHttpRequest();
    var content_disposition = xhr.getResponseHeader('Content-Disposition');
    xhr.open(metodo,url+param,true);
      xhr.responseType = "arraybuffer";
    xhr.onload = function () {
      if (this.status === 200) {
        var blob = new Blob([xhr.response], {type:formato});
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download =archivo;
        link.click();
      }
    };
    xhr.send();
}