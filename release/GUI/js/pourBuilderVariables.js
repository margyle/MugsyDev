//form for water options
var waterForm = '<div id="editorActionable">Settings' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Total Milliliters:</label>' +
'<input type="number" class="form-control col-sm-4" step="5.00" value="50">'+
'</div>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Flow Rate:</label>' +
'<input type="number" class="form-control col-sm-4" step="0.10" value="4.00">'+
'</div>' +
'<hr>' +
'</div>';

//form for cone options
var coneForm = '<div id="editorActionable">Direction:' +
'<div class="form-group row" style="padding-left:15px; padding-top: 10px">' +
'<div class="form-check form-check-inline">' +
'<input class="form-check-input" type="radio" name="coneDirectionCW" id="Clockwise" value="CW">' +
'<label class="form-check-label" for="coneDirectionCW">Clockwise</label>' +
'</div>' +
'<div class="form-check form-check-inline">' +
'<input class="form-check-input" type="radio" name="coneDirectionCC" id="Clockwise" value="CC">' +
'<label class="form-check-label" for="coneDirectionCC">Counter Clockwise</label>' +
'</div>' +
'</div>' +
'<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
'<label class="col-sm-4 col-form-label">Distance:</label>' +
'<select class="form-control col-sm-4" id="waterWeightMl">' +
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
