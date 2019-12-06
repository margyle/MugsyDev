
var setting = 1;
var draggedItem;
var currentOption = "waiting";
var waterStatus = "unset";

$(document).ready(function () {
//make available step options draggable
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

    if (draggedItem.id == "water" && waterStatus == "unset") {
        //console.log(setting == 1);
        var el1 = document.querySelector('#editorActionable');
        var water = document.createElement('div');
        water.innerHTML = waterForm;
        // replace el with newEL
        el1.parentNode.replaceChild(water, el1);
        waterStatus = "set";
        el1 = null;

    }
    if (draggedItem.id == "cone") {
        //console.log(setting == 1);
        var el1 = document.querySelector('#editorActionable');
        var cone = document.createElement('div');
        cone.innerHTML = coneForm;
        // replace el with newEL
        el1.parentNode.replaceChild(cone, el1);
        el1 = null;

    }
    if (draggedItem.id == "spout") {
        //console.log(setting == 1);
        var el1 = document.querySelector('#editorActionable');
        var spout = document.createElement('div');
        spout.innerHTML = '<div id="editorActionable">Spout Movement:' +
            '<div class="form-group row col-sm-8" style="padding-left:15px; padding-top: 10px">' +
            //'<label for="range">Start Position</label>'+
            '<label for="startPos">Start Position:</label>' +
            '<input type="range" class="custom-range" min="1" max="180" id="startPos">' +
            '<br><hr>' +
            '<label for="endPos">End Position:</label>' +
            '<input type="range" class="custom-range" min="1" max="180" id="endPos">' +
            '</div>';
        // '<div class="col-sm-8">' +            
        // 'Range Slider' +
        // '</div>';
        // replace el with newEL
        el1.parentNode.replaceChild(spout, el1);
        el1 = null;

    }
    if (draggedItem.id == "time") {
        //console.log(setting == 1);
        var el1 = document.querySelector('#editorActionable');
        var time = document.createElement('div');
        time.innerHTML = '<div id="editorActionable">Updated form for time settings</div>';
        // replace el with newEL
        el1.parentNode.replaceChild(time, el1);
        el1 = null;

    }
    if (draggedItem.id == "repeat") {
        //console.log(setting == 1);
        var el1 = document.querySelector('#editorActionable');
        var repeat = document.createElement('div');
        repeat.innerHTML = '<div id="editorActionable">Updated form for repeat settings</div>';
        // replace el with newEL
        el1.parentNode.replaceChild(repeat, el1);
        el1 = null;

    }
    if (draggedItem.id == "stop") {
        //console.log(setting == 1);
        var el1 = document.querySelector('#editorActionable');
        var stop = document.createElement('div');
        stop.innerHTML = '<div id="editorActionable">Updated form for stop settings</div>';
        // replace el with newEL
        el1.parentNode.replaceChild(stop, el1);
        el1 = null;

    }
    if (draggedItem.id == "grind") {
        //console.log(setting == 1);
        var el1 = document.querySelector('#editorActionable');
        var grind = document.createElement('div');
        grind.innerHTML = '<div id="editorActionable">Updated form for grind settings</div>';
        // replace el with newEL
        el1.parentNode.replaceChild(grind, el1);
        el1 = null;

    }
}
