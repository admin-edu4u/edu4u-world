String.prototype.replaceAll = function (find, replace) {
    var str = this;
    return str.replace(new RegExp(find, 'g'), replace);
};

Number.prototype.formatMoney = function (decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

function randomNum() {
    return Math.floor(Math.random() * 9999999999)
}

function refreshCodeImg(img_id, srcFrom) {
    document.getElementById(img_id).src = "vcode.aspx?r=" + randomNum() + "&srcFrom=" + srcFrom;
}

function toggleForgotPasswordPanel() {
    if (document.getElementById("forgotPasswordTable").style.display == "")
        document.getElementById("forgotPasswordTable").style.display = "none";
    else
        document.getElementById("forgotPasswordTable").style.display = "";

}

function logout() {
    top.window.location = "logout.aspx"
}

function parseLogin(result) {
    var loginDetails = result.split("#");
    var loginTableHTML = "";

    if (loginDetails[0] != "0") {
        loginTableHTML += "<table><tr><td>Email:</td><td>" + loginDetails[3] + "</td></tr>";
        loginTableHTML += "<tr><td>Full name:</td><td>" + loginDetails[2] + "</td></tr>";
        loginTableHTML += "<tr><td>Currency:</td><td>" + loginDetails[5] + "</td></tr>";
        loginTableHTML += "<tr><td colspan='2' align='right'><input type='button' value='logout' onclick='logout()' /></td></tr></table>";

        document.getElementById("loginTable").innerHTML = loginTableHTML;
    }
    else {
        alert(loginDetails[1]);
    }

}


function transWindow(url) {
    popupWindow = window.open(url, 'winTrans', 'height=700,width=780,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes');
    popupWindow.focus();

}



function numbersOnly(e) {
    var evt = (e) ? e : window.event;
    var key = (evt.keyCode) ? evt.keyCode : evt.which;

    if (key != null) {
        key = parseInt(key, 10);

        if ((key < 48 || key > 57) && (key < 96 || key > 105)) {
            if (!isUserFriendlyChar(key))
                return false;
        }
        else {
            if (evt.shiftKey)
                return false;
        }
    }
    return true;
}


function isUserFriendlyChar(val) {
    // Backspace, Tab, Enter, Insert, and Delete
    if (val == 8 || val == 9 || val == 13 || val == 45 || val == 46)
        return true;
    // Ctrl, Alt, CapsLock, Home, End, and Arrows
    if ((val > 16 && val < 21) || (val > 34 && val < 41))
        return true;
    // The rest
    return false;
}




function newPopup(url) {
    popupWindow = window.open(
            url, 'popUpWindow', 'height=600,width=917,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
}




function validateEmail(x) {
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length)
        return false;
    else
        return true;
    

}



function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr) {
    // MM/DD/YYYY

    var dtCh = "/";
    var minYear = 1900;
    var maxYear = 2100;


	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strMonth=dtStr.substring(0,pos1)
	var strDay=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
//		alert("The date format should be : mm/dd/yyyy")
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
//		alert("Please enter a valid month")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
//		alert("Please enter a valid day")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
//		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
//		alert("Please enter a valid date")
		return false
	}
return true
}

function ValidateForm(){
	var dt=document.frmSample.txtDate
	if (isDate(dt.value)==false){
		dt.focus()
		return false
	}
    return true
 }


 function padZero(num, size) {
     var s = "00000000000" + num;
     return s.substr(s.length - size);
 }