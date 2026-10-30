function MG_getAccountBalance(user) {
    sendData("api/mg/api.aspx", "action=AccountBalance&accountNumber=x" + user, MG_displayAccountBalance);
}

function MG_displayAccountBalance(myOutput) {
    var myCode = "mg";
    common_displayAccountBalance(myCode, myOutput);
}

function OSG_getAccountBalance(user) {
    sendData("api/osg/api.aspx", "action=AccountBalance&username=x" + user, OSG_displayAccountBalance);
}

function OSG_displayAccountBalance(myOutput) {
    var myCode = "osg";
    common_displayAccountBalance(myCode, myOutput);
}

function OSG88_getAccountBalance(user) {
    sendData("api/osg88/api.aspx", "action=AccountBalance&username=x" + user, OSG88_displayAccountBalance);
}

function OSG88_displayAccountBalance(myOutput) {
    var myCode = "osg88";
    common_displayAccountBalance(myCode, myOutput);
}

function WFT_getAccountBalance(user) {
    sendData("api/wft/api.aspx", "action=AccountBalance&username=x" + user, WFT_displayAccountBalance);
}

function WFT_displayAccountBalance(myOutput) {
    var myCode = "wft";
    common_displayAccountBalance(myCode, myOutput);
}

function AG_getAccountBalance(user) {
    sendData("api/ag/api.aspx", "action=AccountBalance&username=x" + user, AG_displayAccountBalance);
}

function AG_displayAccountBalance(myOutput) {
    var myCode = "ag";
    common_displayAccountBalance(myCode, myOutput);
}

function XPRO_getAccountBalance(user) {
    sendData("api/xpro/api.aspx", "action=AccountBalance&username=x" + user, XPRO_displayAccountBalance);
}

function XPRO_displayAccountBalance(myOutput) {
    var myCode = "xpro";
    common_displayAccountBalance(myCode, myOutput);
}


function HO_getAccountBalance(user) {
    sendData("api/ho/api.aspx", "action=AccountBalance&username=x" + user, HO_displayAccountBalance);
}

function HO_displayAccountBalance(myOutput) {
    var myCode = "ho";
    common_displayAccountBalance(myCode, myOutput);
}


function PT_getAccountBalance(user) {
    sendData("api/pt/api.aspx", "action=AccountBalance&username=x" + user, PT_displayAccountBalance);
}

function PT_displayAccountBalance(myOutput) {
    var myCode = "pt";
    common_displayAccountBalance(myCode, myOutput);
}


function W88_getAccountBalance(user) {
    sendData("api/w88/api.aspx", "action=AccountBalance&username=x" + user, W88_displayAccountBalance);
}

function W88_displayAccountBalance(myOutput) {
    var myCode = "w88";
    common_displayAccountBalance(myCode, myOutput);
}

function YY_getAccountBalance(user) {
    sendData("api/yy/api.aspx", "action=AccountBalance&username=x" + user, YY_displayAccountBalance);
}

function YY_displayAccountBalance(myOutput) {
    var myCode = "yy";
    common_displayAccountBalance(myCode, myOutput);
}


function SBO_getAccountBalance(user) {
    sendData("api/sbo/api.aspx", "action=AccountBalance&username=x" + user, SBO_displayAccountBalance);
}

function SBO_displayAccountBalance(myOutput) {
    var myCode = "sbo";
    common_displayAccountBalance(myCode, myOutput);
}


function H4D_getAccountBalance(user) {
    sendData("api/h4d/api.aspx", "action=AccountBalance&username=x" + user, H4D_displayAccountBalance);
}

function H4D_displayAccountBalance(myOutput) {
    var myCode = "h4d";
    common_displayAccountBalance(myCode, myOutput);
}


function EVO_getAccountBalance(user) {
    sendData("api/evo/api.aspx", "action=AccountBalance&username=" + user, EVO_displayAccountBalance);
}

function EVO_displayAccountBalance(myOutput) {
    var myCode = "evo";
    common_displayAccountBalance(myCode, myOutput);
}


function IBC_getAccountBalance(user) {
    sendData("api/ibc/api.aspx", "action=AccountBalance&username=x" + user, IBC_displayAccountBalance);
}

function IBC_displayAccountBalance(myOutput) {
    var myCode = "ibc";
    if (myOutput != "Maintenance") {
        var tmpJson;
        eval("tmpJson = " + myOutput);
        myOutput = checkBalanceOutput(tmpJson.Data[0]["balance"].toFixed(2), "ibc");
    }
    common_displayAccountBalance(myCode, myOutput);
}


function POKER8_getAccountBalance(user) {
    sendData("api/poker8/api.aspx", "action=AccountBalance&username=x" + user, POKER8_displayAccountBalance);
}

function POKER8_displayAccountBalance(myOutput) {
    var myCode = "poker8";
    common_displayAccountBalance(myCode, myOutput);
}




function POKER6_getAccountBalance(user) {
    sendData("api/poker6/api.aspx", "action=AccountBalance&username=x" + user, POKER6_displayAccountBalance);
}

function POKER6_displayAccountBalance(myOutput) {
    var myCode = "poker6";
    common_displayAccountBalance(myCode, myOutput);
}

function SBTech_getAccountBalance(user) {
    sendData("api/sbtech/api.aspx", "action=AccountBalance&username=x" + user, SBTech_displayAccountBalance);
}

function SBTech_displayAccountBalance(myOutput) {
    var myCode = "sbtech";
    common_displayAccountBalance(myCode, myOutput);
}


function common_displayAccountBalance(myCode,myOutput) {
    document.getElementById(myCode + "_balance").innerHTML = checkBalanceOutput(myOutput, myCode);

    if (document.getElementById(myCode + "_balance2"))
        document.getElementById(myCode + "_balance2").innerHTML = checkBalanceOutput(myOutput, myCode);
}




function checkBalanceOutput(myOutput, my3rdParty) {
    if (isNaN(myOutput)) {
        if (myOutput.indexOf("aintenance") > -1) {
            myOutput = "Maintenance";
        }
        else {
            myOutput = "Error!";
        }
    }
    else {
        if (document.getElementById("xferFrom")) {
            var myFromOptions = document.getElementById("xferFrom").options;
            var myToOptions = document.getElementById("xferTo").options;
            
            // From option
            for (var i = 0; i < myFromOptions.length; i++) {
                if (myFromOptions[i].value == "from_" + my3rdParty) myFromOptions[i].disabled = false;
            }

            // To option
            for (var i = 0; i < myToOptions.length; i++) {
                if (myToOptions[i].value == "to_" + my3rdParty) myToOptions[i].disabled = false;
            }
        }
        myOutput = parseFloat(myOutput).formatMoney(2, ',', '.');
    }
    return myOutput;
}


function wallet_getAccountBalance() {
    sendData("api/wallet/api.aspx", "action=AccountBalance", wallet_displayAccountBalance);
}

function wallet_displayAccountBalance(myOutput) {
    document.getElementById("wallet_balance").innerHTML = parseFloat(myOutput).formatMoney(2, ',', '.');
    clearTimeout(balanceTimer);
    balanceTimer = window.setTimeout("wallet_getAccountBalance()", 10000);
}

var balanceTimer;

function wallet_timerGetAccountBalance() {
    balanceTimer = window.setTimeout("wallet_getAccountBalance()", 10000);
}


function AB_getAccountBalance(user) {
    sendData("api/ab/api.aspx", "action=AccountBalance&username=x" + user, AB_displayAccountBalance);
}

function AB_displayAccountBalance(myOutput) {
    var myCode = "ab";
    common_displayAccountBalance(myCode, myOutput);
}
