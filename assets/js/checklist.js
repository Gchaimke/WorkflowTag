var allTextLines = [];
var toggle;
var len = "";
var chArray = [];


$(document).ready(function () {
    /** Future server get data
     *  $.ajax({
         type: "GET",
         url: "./data/flex2.csv",
         dataType: "text",
         success: function (data) { processData(data); }
     }); 
     */
    processData(csv);
    toggle = true;
    setProgress(localStorage.getItem("progress"));
    $("#project").append(GetURLParameter('pr'));
    $("#sn").append(GetURLParameter('sn'));

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    $("#date").append(dd + "/" + mm + "/" + yyyy);
});

function GetURLParameter(sParam) {
    //?pr=Flex2&sn=FL-0105-001
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}

function getQCCode(id) {
    var code = prompt("Please enter QC Code", "QC Check");
    id = "#" + id;
    if (code == "1") {
        //$(id).attr("disabled", true);
        $(id).prop('checked', true);
        toggleOne();
    } else {
        //$(id).removeClass();
        //$(id).addClass("btn btn-danger");
        $(id).prop('checked', false);
        toggleOne();
    }
}

function saveData() {
    localStorage.setItem("chArray", JSON.stringify(chArray));
    chArray = JSON.parse(localStorage.getItem("chArray"));

    //console.log(chArray);
    localStorage.setItem("progress", len);
}

function updateProgress() {
    var checked = $("input:checkbox:checked").length;
    len = 100 / (allTextLines.length - 1) * checked
    $("#progress-bar").width(len + "%");
    $("#progress-bar").attr("aria-valuenow", len);
}

function setProgress(p) {
    $("#progress-bar").width(p + "%");
    $("#progress-bar").attr("aria-valuenow", p);
}

function toggleOne(item, index, arr) {
    if (event.target.id != "") {
        id = event.target.id.split("_");
    } else {
        id = 0;
    }

    if ($(event.target).is(":checked") || !toggle) {
        chArray[id[1]] = 1;
        chArray[index] = 1;
    } else {
        chArray[id[1]] = 0;
        chArray[index] = 0;
    }
    updateProgress();
    saveData();
}

function toggleAllCheckboxs() {
    $("[id^=check]").prop('checked', toggle);
    if (toggle) {
        toggle = false;
    } else {
        toggle = true;
    }
    chArray.forEach(toggleOne);
}

function processData(allText) {
    allTextLines = allText.split(/\r\n|\n/);
    var headers = allTextLines[0].split(',');
    var table = "";
    var prefix = "1.0";
    var verify = "";
    var checked = "";
    var onClick = "";
    if (JSON.parse(localStorage.getItem("chArray")) == null) {
        chArray = new Array(allTextLines.length);
    } else {
        chArray = JSON.parse(localStorage.getItem("chArray"));
    }
    table = '<table   class="table"> \
            <thead class="thead-dark"><tr>\
            <th scope="col" onclick="saveData()">#</th>\
            <th id="result"  scope="col">'+ headers[0] + '</th>\
            <th scope="col" onclick="toggleAllCheckboxs()">'+ headers[1] + '</th>\
            </tr></thead><tbody>';
    for (var i = 1; i < allTextLines.length; i++) {
        checked = "";
        if (chArray[i])
            checked = "Checked";
        if (i >= 10)
            prefix = '1.'
        var data = allTextLines[i].split(',');
        if (data[1] == "QC") {
            onClick = ' onclick="getQCCode(this.id)"';
        } else {
            onClick = ' onclick="toggleOne()"';
        }
        verify = '<div class="checkbox">\
                    <input type="checkbox"  id="check_'+ i + '" name="check' + i + '" ' +
            onClick + ' ' + checked + '></div>';
        table += '<tr><th scope="row">' + prefix + i + '</th>';
        table += '<td class="description">' + data[0] + '</td>';
        table += '<td onclick="restore()">' + verify + '</td></tr>';
    }
    table += '</tbody></table>';
    $(workTable).append(table);
}

function centerLoginBox() {
    var ua = navigator.userAgent.toLowerCase();
    var isAndroid = ua.indexOf("android") > -1; // Detect Android devices
    if (isAndroid) {
        //window.orientation is different for iOS and Android
        if (window.orientation == 0 || window.orientation == 180) { //Landscape Mode
            $('#loginbox').css('margin-top', '20%');
        }
        else if (window.orientation == 90 || window.orientation == -90) { //Portrait Mode
            $('#loginbox').css('margin-top', '40%');
        }
    }
    else {
        if (window.orientation == 90 || window.orientation == -90) { //Landscape Mode
            $('#loginbox').css('margin-top', '20%');
        }
        else if (window.orientation == 0 || window.orientation == 180) { //Portrait Mode
            $('#loginbox').css('margin-top', '40%');
        }
    }
}

$("#save").click(function () {
    $.post("save_pdf", {
        pdf: 'Production/' + GetURLParameter('pr') + '/' + GetURLParameter('sn') + '/' + GetURLParameter('sn') + '.pdf'
    }).done(function (o) {
        console.log('pdf created in Projects folder on server.');
    });
});



var csv = `Description,Verify
Create the folders with "Easy Folder Creation" tool,Verify
Mount metal Spacers (use 10 M3X8 + loc222),Verify
Prepare TIMER (use 4 standoff + 4 screws M3X8 SEMS PH). Mount TIMER (use 4 screws M3X6).,Verify
Break the part of AD6665T1 (Screw 4 spacers with 4 M3[DO1403006] + flat washer M4). Mount AD6665T1 (use 4 screws M3X6),Verify
Mount 2 tie-wraps holders (use 2 screws M4X10 flat + flat washer + M4 Nyloc nut),Verify
Mount 2 tie-wraps holders (use 2 screws M3X10 flat + flat washer+ M3 Nyloc nut),Verify
Mount 2 power supply’s (use 8 M3X6 +loc222) 15v – left upper corner 12v – left down corner,Verify
Screw 2 ground screws M4X16+nut,Verify
Mount DC/DC filter (Use 2 screws M3X8 SEMS),Verify
Check plastic before assembling.,Verify
Use a Nail file to make a hole of 2mm.,Verify
Insert the Dongle mini-DP before mounting plastic base.,Verify
Mount the plastic base to the metal holder (BASE) (use 4 screws M4X20 with 4 flat washer). Insert the screws with the washer through the protective buffers and Base plastic.To capture the screws with the metal holder: use 4 flat washers and 4 M4 Nyloc nut.,Verify
Add more screws to secure plastic base (use 2 M4x12 flat head +2 flat washer+2 M4 Nyloc nut),Verify
Mount the AD6526T2 (use 4 screws M3X8 SEMS),Verify
Perform a connectivity test to the push button cable. Insert cable through the plastic base. ,Verify
Insert the Pedal cable through the plastic base.,Verify
Check if the red dot on connector is on same line with red dot on plastic and looks straight.,Verify
Insert the Pedal pins to the connector according to the following order: Red | Black | White | Green,Verify
Mount the FAN to the plastic base (use 4 M3X30 flat + washer + M3 Nyloc nut),Verify
Insert 5 ground cables (use M4 Flat washer and M4 Nyloc nut to secure the cables).,Verify
Insert the AC power cables to power supply units.,Verify
Mount the Schurter to the plastic base (Use 2 screw M3X16 + flat washer + Nyloc nut). Add 2 fuse 10A to the Schurter.,Verify
Check AD6583T1 (Tool input) in an external system: STANT: left – 1 right – 2 that data reaches 4080 . CONTRUST: left – 1 right – 2 that data reaches 4080 | BALOON left and right data reaches not more than 700,Verify
Mount AD6583T1 with his own Nuts.,Verify
Connect the NUC power cable to the output of 15V power supply.,Verify
Connect PEDAL cable to AD6665T1 (Pedal connector) card.,Verify
Connect the AD6583T1 flat cable to AD6665T1 (Tools connector) card.,Verify
Mount the Wi-Fi card into the NUC and connect the 2 antennas to the Wi-Fi card.,Verify
Connect the memory to the NUC.,Verify
Mount SSD M.2 to the NUC.,Verify
Connect NUC power cable from the PSU 15v. ,Verify
Connect power push button cable to the NUC and put hot glue on power button connector.,Verify
Mount the motherboard (NUC) to the base (use 4 Screws M3X8 SEMS.),Verify
Stick the 2 antennas to the back panel.,Verify
Connect power cable from PSU 12V to DC/DC converter.,Verify
Insert the FISCHER cable through the plastic base and via ground cable ring. Connect the FISCHER cable to the TIMER connector J2.,Verify
Connect USB cable from the TIMER connector J1 to NUC USB.,Verify
Connect FISCHER power to DC/DC converter out  ,Verify
Connect FAN cable to the NUC connector.,Verify
Use 4 tie-wraps to close cables.,Verify
Mount the USB cable to the rear panel (use 2 screws M3X8PH). Connect USB cable to the NUC.,Verify
Connect USB cable from NUC to AD6526T2 card (USB) and to AD6665T1 card (J10: USB).,Verify
Use hot glue to secure the connectors.,Verify
Paste the serial label inside the FLEX (right from NUC),Verify
Scan the serial numbers of:  Dc converter 15v | Dc converter 12v| NUC | SSD M.2| Store them in production folder dependence of FLEX serial number,Verify
Screw M4X12 round head with plastic washer 16FWRT006032,Verify
Mount 2 metal brackets to the upper panel (use 4 screws M3X6FH + loc222),Verify
Mount the joysticks to the upper panel with its own screws.,Verify
Check all buttons of the Lexan if they are performed correctly and paste the LEXAN to upper panel.,Verify
Paste the 3DS label to the upper panel.,Verify
Screw the 2 joysticks head (use 2 screws 4.40X3/8PH + loc222).,Verify
Add CYBERBOND RL67 over the bottom two ports of AD6583T1 and screw tow cylinders  ,Verify
Connect joystick cable to AD6665T1 card (connector JOYSTICK).,Verify
Connect Lexan cable to AD6665T1 card (connector PANEL).,Verify
Stick windows key and serial to the bottom.,Verify
Install last Windows Image with Acronis,Verify
Activate windows license,Verify
Take a photo of the inside view and store to the production folder dependence of FLEX serial number.,Verify
Add 2 screws M4X12PH + Plastic Washer to the FLEX Base.,Verify
QA/QC CHECK,QC
Stick Lexan,Verify
Screw 2 M4X12PH to close upper panel,Verify
Screw 4 M4X12 flat + loc222 to close upper panel,Verify
Stick the 2 labels of the joysticks to  the right joystick and left joystick.,Verify
FOD,Verify
The FLEX QC test include checking display port and USB cable.,Verify
Take 6 photos of the FLEX after assembly: Upper panel | Front side | Left side | Right side | Rear side | Buttom. ,Verify
Store to the production folder dependence of FLEX serial number.,Verify
QA/QC CHECK,QC`