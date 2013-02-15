function getXHRInstance() {
	var httpRequest = null;
	if (window.XMLHttpRequest) {
		httpRequest = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		try { httpRequest = new ActiveXObject("Msxml2.XMLHTTP"); }
		catch(e) { try { httpRequest = new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {} }
	}
	if (httpRequest) {
		return new DzXMLHttpRequest(httpRequest)
	}
	return null;
}

/**
 * @param XMLHttpRequest aHttpRequest a generic httpRequest
 */
function DzXMLHttpRequest(aHttpRequest) {
	this.readyCallback = null;
	this.ref = null;
	this.init = function(aReadyCallback, ref) { this.readyCallback = aReadyCallback; this.ref = ref; }
	this.httpRequest = aHttpRequest;
	var thisModule = this;
	this.httpRequest.onreadystatechange = function() {
		if (thisModule.httpRequest.readyState == 4 && thisModule.httpRequest.status == 200) {
			thisModule.readyCallback(aHttpRequest, thisModule.ref);
		}
	}
	this.getAsync = function(url) {
		this.httpRequest.open('GET', serverURI + url, true); 
		this.httpRequest.send('');
	}
}