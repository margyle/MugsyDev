//form for water options
var waterForm = '<div id="editorActionable"><b>Water Settings</b><hr>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Total Milliliters:</label>' +
'<input type="number" class="form-control col-sm-4" step="5.00" value="50" id="waterWeight" name="waterWeight">'+
'</div>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Flow Rate:</label>' +
'<input type="number" class="form-control col-sm-4" step="0.10" value="4.00" id="flowRate" name="flowrate">'+
'</div>' +
'<hr>' +
'</div>';

//form for cone options
var coneForm = '<div id="editorActionable"><b>Direction:</b><hr>' +
'<div class="form-group row" style="padding-left:15px; padding-top: 10px">' +
'<div class="form-check form-check-inline">' +
'<input class="form-check-input" type="radio" name="coneDirection" id="coneDirection" value="CW">' +
'<label class="form-check-label" for="coneDirectionCW">Clockwise</label>' +
'</div>' +
'<div class="form-check form-check-inline">' +
'<input class="form-check-input" type="radio" name="coneDirection" id="coneDirection" value="CC">' +
'<label class="form-check-label" for="coneDirectionCC">Counter Clockwise</label>' +
'</div>' +
'</div>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Distance:</label>' +
'<select class="form-control col-sm-4" id="coneDistance" name="coneDistance">' +
'<option value="45">45° </option>' +
'<option value="90">90° Quarter Turn</option>' +
'<option value="135">135° </option>' +
'<option value="180">180° Half Turn</option>' +
'<option value="225">225°</option>' +
'<option value="270">270°</option>' +
'<option value="315">315°</option>' +
'<option value="360">360° Complete Turn</option>' +
'</select>' +
'</div>' +
'<hr></div>';

//form for spout options
var spoutForm = '<div id="editorActionable"><b>Spout Movement:</b><hr>' +
'<div class="form-group row col-sm-8" style="padding-left:15px; padding-top: 10px">' +
//'<label for="range">Start Position</label>'+
'<label for="startPos">Start Position:</label>'+
'<input type="range" class="custom-range" min="1" max="180" id="startPos">'+
'<br><hr>'+
'<label for="endPos">End Position:</label>'+
'<input type="range" class="custom-range" min="1" max="180" id="endPos">'+
'</div>'
'<hr></div>';

//form for time options
var timeForm = '<div id="editorActionable"><b>Step Timer:</b><hr>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Total Seconds:</label>' +
'<input type="number" class="form-control col-sm-4" step="1.00" value="30" id="totalSeconds" name="totalSeconds">'+
'</div>' +
'<hr></div>';

//form for step repeat options
var repeatForm =  '<div id="editorActionable"><b>Step Timer:</b><hr>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Total Seconds:</label>' +
'<input type="number" class="form-control col-sm-4" step="1.00" value="30" id="totalSeconds" name="totalSeconds">'+
'</div>' +
'<hr></div>';


//form for stop options
var stopForm = '<div id="editorActionable"><b>Pause Timer:</b><hr>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Total Seconds:</label>' +
'<input type="number" class="form-control col-sm-4" step="1.00" value="15" id="pauseSeconds" name="pauseSeconds">'+
'</div>' +
'<hr></div>';

//form for grind options
var grindForm = '<div id="editorActionable"><b>Grind Settings</b><hr>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Total Milligrams:</label>' +
'<input type="number" class="form-control col-sm-4" step="1.00" value="30" id="coffeeWeight" name="coffeeWeight">'+
'</div>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Grind Size:</label>' +
'<input type="number" class="form-control col-sm-4" step="1" value="7" id="grindSize" min ="1" max="10" name="grindSize">'+
'</div>' +
'<hr>' +
'</div>';



