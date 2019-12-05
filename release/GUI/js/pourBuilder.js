   
    //make available step options draggable
    var setting = 1;
    var draggedItem; 
    var currentOption = "waiting";
    $(document).ready(function() {
        
        Sortable.create(availableOptions, {
            animation: 100,
            group: {
                name: "list-1",
                pull: "clone",
                revertClone: false,
                put: "false"
            },
            draggable: '.list-group-item',
            handle: '.list-group-item',
            sort: false,
            filter: '.sortable-disabled',
            onMove: function (evt) {
            // if (evt.item > 0) {
            //     return false;
            // }
        }
            //chosenClass: 'active',
        });

        //make sticky list to recieve step options
        Sortable.create(stepStickyList, {
            group: 'list-1',
            handle: '.list-group-item',
            onAdd(evt) {
                draggedItem = evt.item; 
                //console.log(draggedItem.id);
                updateEditor();
                setting++

            }
        });
    });
    //function to delete last step option
    function deleteItem() {
        var count = document.getElementById('stepStickyList').getElementsByTagName("li");
        var i = count.length
        var ulElem = document.getElementById('stepStickyList')
        ulElem.removeChild(ulElem.childNodes[i])
        setting--;
        //mainDrag();
    }

    //update editor for dragged option type
    function updateEditor() {
        console.log("Editor Type: " + draggedItem.id);

        if (draggedItem.id == "water") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var water = document.createElement('span');
            water.innerHTML = '<div id=editorActionable">'+
            '<div class="form-group row" style="padding-left:0px; padding-top: 0px">'+
            '<label class="col-sm-4 col-form-label">Total Milliliters:</label>'+
            '<select class="form-control col-sm-4" id="waterWeightMl">'+
            '<option>50</option>'+
            '<option>10</option>'+
            '</select>'+
            '</div>'+
            '<div class="form-group row" style="padding-left:0px; padding-top: 0px">'+
            '<label class="col-sm-4 col-form-label">Flow Rate:</label>'+
            '<select class="form-control col-sm-4" id="flowRateMl">'+
            '<option>5</option>'+
            '<option>10</option>'+
            '</select>'+
            '</div>'+
            '<hr>'+
            '</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(water, el1);
        
        }
        if (draggedItem.id == "cone") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var cone = document.createElement('span');
            cone.innerHTML = '<div id="editorActionable">'+
            '<div class="form-check">'+
            '<input class="form-check-input" type="checkbox" value="" id="coneClockwise">'+
            '<label class="form-check-label" for="defaultCheck1">Clockwise</label>'+
            '</div>'+
            '<div class="form-check" style="padding-bottom:10px;">'+
            '<input class="form-check-input" type="checkbox" value="" id="coneCounterClockwise">'+
            '<label class="form-check-label" for="defaultCheck1">Counter Clockwise</label>'+
            '</div>'+
            '<hr>'+
            '</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(cone, el1);

        }
        if (draggedItem.id == "spout") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var spout = document.createElement('span');
            spout.innerHTML = '<div id="editorActionable">Updated form for spout settings</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(spout, el1);

        }
        if (draggedItem.id == "time") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var time = document.createElement('span');
            time.innerHTML = '<div id="editorActionable">Updated form for time settings</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(time, el1);

        }
        if (draggedItem.id == "repeat") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var repeat = document.createElement('span');
            repeat.innerHTML = '<div id="editorActionable">Updated form for repeat settings</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(repeat, el1);

        }
        if (draggedItem.id == "stop") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var stop = document.createElement('span');
            stop.innerHTML = '<div id="editorActionable">Updated form for stop settings</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(stop, el1);

        }
        if (draggedItem.id == "grind") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var grind = document.createElement('span');
            grind.innerHTML = '<div id="editorActionable">Updated form for grind settings</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(grind, el1);

        }
    }
