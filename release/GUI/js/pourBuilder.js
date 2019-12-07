    //this code will live in the js/pourBuilder.js file
    //set up sliders

    //make available step options draggable
    var setting = 1;
    var draggedItem = "grind";
    var currentOption = "waiting";
    var waterStatus = "unset";
    var settingsObject = {};
    var steps = 1;
    var stepCount = 1;


    updateEditor();
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
            onMove: function(evt) {
                //getInputs();
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
            spout.innerHTML = spoutForm;

            // replace el with newEL
            el1.parentNode.replaceChild(spout, el1);
            el1 = null;

        }
        if (draggedItem.id == "time") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var time = document.createElement('div');
            time.innerHTML = timeForm;
            // replace el with newEL
            el1.parentNode.replaceChild(time, el1);
            el1 = null;

        }
        if (draggedItem.id == "repeat") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var repeat = document.createElement('div');
            repeat.innerHTML = repeatForm;
            // replace el with newEL
            el1.parentNode.replaceChild(repeat, el1);
            el1 = null;

        }
        if (draggedItem.id == "stop") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var stop = document.createElement('div');
            stop.innerHTML = stopForm;
            // replace el with newEL
            el1.parentNode.replaceChild(stop, el1);
            el1 = null;

        }
        if (draggedItem == "grind" || draggedItem.id == "grind") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var grind = document.createElement('div');
            grind.innerHTML = grindForm;
            // replace el with newEL
            el1.parentNode.replaceChild(grind, el1);
            el1 = null;

        }
    }
    
    //getInputs(): grab entered values and add to the settingsObject, then display the values
    function getInputs(){
        var inputs = document.querySelectorAll('input,select');    
        for (var i = 0; i < inputs.length; i++) {
        settingsObject[inputs[i].id] = inputs[i].value;
        }
        console.log(settingsObject)
        console.log(draggedItem.id)
        document.getElementById("json").innerHTML = JSON.stringify(settingsObject, undefined, 2);
    }
    
    function addStepCount() {
        //add step count total at top right
        var ul = document.getElementById('stepCount');
        ul.innerHTML += '<liclass="list-group-item"> <a href="#" class="badge badge-success shadow" style="background-color: #7c75b2;color: white;">' + stepCount + '</a></li>';
        stepCount++;
    }

    function postStep(){
        $.ajax({
            url: 'inc/inc.updateRecipeStep.php',
            data: settingsObject,
            type: 'POST',
            success: function(response) {
                console.log("RESPONSE: " + response);
            }
            });
            //update display step count
            addStepCount();
    }
