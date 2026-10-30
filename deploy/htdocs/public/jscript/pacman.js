var myBalance = 0;
var myPrize = '';

// after load complete, get init token
function getInitToken() {
    return myBalance;
}


function getResult() {
    sendData("../jscript/pacman.aspx", "r=result", parsePot);
}

function goDeposit() {
    //redirect page to deposit page
    //alert("Please Login!");
    sendData("../jscript/pacman.aspx", "r=deposit", displaypacmantoken);
}

function displaypacmantoken(myOutput) {
    if (myOutput == "login") {
        //parent.showloginmodal();
        alert("Please Login!");
    }
    else if (myOutput == "deposit") {
        parent.location.href = "../deposit.aspx";
    }

}

function parsePot(myXML) {
    try //Internet Explorer 
    {
        xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
        xmlDoc.async = "false";
        xmlDoc.loadXML(myXML);
    }
    catch (e) {
        try // Firefox, Mozilla, Opera, etc.
        {
            parser = new DOMParser();
            xmlDoc = parser.parseFromString(myXML, "text/xml");
        }
        catch (e) {
            alert(e.message);
            return;
        }
    }

    var balance = 0;
    var prize = "";
    var error = "";
    var transaction_type, node_transaction;

    var tmpS = ["You WIN\nMYR ", "Game Over!!"]
    var typeS = [0, 1, 2, 3, 4, 5, 6, -1];
    var tmpSS;
    var typeSS;

    if (xmlDoc.getElementsByTagName("transaction").length > 0) {
        node_transaction = xmlDoc.getElementsByTagName("transaction");
        transaction_type = node_transaction[0].getAttribute("type");
        
        switch (transaction_type) {

            case "luckyPotBalance":
                if (node_transaction[0].hasChildNodes()) {
                    node_tranx = node_transaction[0].getElementsByTagName("pot");
                    balance = node_tranx[0].getAttribute("balance");
                    //window.parent.document.getElementById("tokenBalance").innerHTML = parseInt(balance);
                }
                
                myBalance = parseInt(balance);
                break;

            case "luckyPotResult":
                if (node_transaction[0].hasChildNodes()) {
                    node_tranx = node_transaction[0].getElementsByTagName("pot");
                    balance = node_tranx[0].getAttribute("balance");
                    prize = node_tranx[0].getAttribute("prize");
                    error = node_tranx[0].getAttribute("error");
                }

                if (error == '') {
                    myBalance = parseInt(balance);
                    myPrize = prize;
                    
                    var param = new Object();
                    param.token = myBalance;

                    if (parseFloat(prize) <= 0)
                    {
                        typeSS = typeS[7];
                        tmpSS = tmpS[1];
                    }
                    else if (parseFloat(prize) >= 0.01 && parseFloat(prize) <= 4.99) {
                        typeSS = typeS[0];
                        tmpSS = tmpS[0] + parseFloat(prize).toFixed(2);
                    }
                    else if (parseFloat(prize) >= 5.00 && parseFloat(prize) <= 19.99) {
                        typeSS = typeS[1];
                        tmpSS = tmpS[0] + parseFloat(prize).toFixed(2);
                    }
                    else if (parseFloat(prize) >= 20.00 && parseFloat(prize) <= 99.99) {
                        typeSS = typeS[2];
                        tmpSS = tmpS[0] + parseFloat(prize).toFixed(2);
                    }
                    else if (parseFloat(prize) >= 100.00 && parseFloat(prize) <= 999.99) {
                        typeSS = typeS[3];
                        tmpSS = tmpS[0] + parseFloat(prize).toFixed(2);
                    }
                    else if (parseFloat(prize) >= 1000.00 && parseFloat(prize) <= 2999.99) {
                        typeSS = typeS[4];
                        tmpSS = tmpS[0] + parseFloat(prize).toFixed(2);
                    }
                    else if (parseFloat(prize) >= 3000.00) {
                        typeSS = typeS[5];
                        tmpSS = tmpS[0] + parseFloat(prize).toFixed(2);
                    }
                    
                    
                    param.type = typeSS
                    param.message = tmpSS;
                    tmpS.shift();
                    typeS.shift();
                    //window.parent.document.getElementById("tokenBalance").innerHTML = myBalance;
                    if (myBalance < 0) return;
                    Pac.showResult(param);
                }
                else
                    alert(error);
                break;

        }
    }
}

sendData("../jscript/pacman.aspx", "r=balance", parsePot);
egret.runEgret({ renderMode: "webgl", audioType: 0 });
