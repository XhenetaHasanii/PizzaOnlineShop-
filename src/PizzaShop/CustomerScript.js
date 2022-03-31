
function process(test){
    "use strict";
    let jsonData = test;
    var objArray = JSON.parse(jsonData); //jsonData is a string and thus it must be converted into an Array of JSON Objects 
    
    for(let i=0;i<objArray.length;i++){ // obj is an array of json objects (every row from the table is a json object)
        setRadioStatus(objArray[i]);
    }
}

function setRadioStatus(obj){
    "use strict";
    switch (obj.Status) {
        case "1":
            document.getElementById('rBtn'+obj.id.toString()+'-1').checked = true;
            break;
        case "2":
            document.getElementById('rBtn'+obj.id.toString()+'-2').checked = true;
            break;
        case "3":
            document.getElementById('rBtn'+obj.id.toString()+'-3').checked = true;
            break;
        case "4":
            document.getElementById('rBtn'+obj.id.toString()+'-4').checked = true;
            break;
        case "5":
            document.getElementById('rBtn'+obj.id.toString()+'-5').checked = true;
            break;
        default:
            alert("Error - setRadioStatus: Status unknown.")
            break;
    }
    return;
}


   // request als globale Variable anlegen (haesslich, aber bequem)
   var request = new XMLHttpRequest(); 

   function requestData() { // fordert die Daten asynchron an
   request.open("GET", "CustomerStatus.php"); // URL für HTTP-GET
   request.onreadystatechange = processData; //Callback-Handler zuordnen
   request.send(null); // Request abschicken
   }



function processData() {
    if(request.readyState == 4) { // Uebertragung = DONE
       if (request.status == 200) {   // HTTP-Status = OK
         if(request.responseText != null) 
           process(request.responseText);// Daten verarbeiten
         else console.error ("Document is empty");        
       } 
       else console.error ("Transfer failed");
    } else ;          // Uebertragung laeuft noch
}

function customerScriptStart(){
    window.setInterval(() => {requestData()}, 2000);
}

