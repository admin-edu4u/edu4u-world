function sendData(url, params, doFunction) {
    var xmlHttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlHttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            //do something

            if (doFunction)
                doFunction(xmlHttp.responseText);
            else
                return xmlHttp.responseText;

        }
        else {
            // not ready yet...
        }

    }


    xmlHttp.open("POST", url + "?" + params + "&newts=" + new Date().getTime(), true);
//    xmlHttp.open("GET", url + "?" + params, true);
    xmlHttp.send();
}

