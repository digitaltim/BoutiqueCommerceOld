//var requestURL = 'https://hello/incld/model/designerPO.json.php';
//var mainEl = document.getElementById("itemForm"); // todo why doesn't this work?
var designerSelectId = 'designerId';
var selectedItemId; // to use in fns
var designerItems = new Array(); // array of objects returned by json query
var itemFormId = 'itemForm';
var itemSelectId = 'itemSelect';
var colorSelectId = 'colorSelect';
var sizeSelectId = 'sizeSelect';
var qtyInputId = 'qtyInput';
var costInputId = 'costInput';

function processDesignerSelect(el) {
    removeItemSelect();
    if(el.options[el.selectedIndex].value != 0) {
        createHiddenInput('designerName', 'designerName', el.options[el.selectedIndex].text, document.getElementById(itemFormId));
        callGetDesignerItems(el.options[el.selectedIndex].value);
    }
}

function callGetDesignerItems(designerId) {
    sendRequest(requestURL+'?action=getDesignerItems&designerId='+designerId, buildItems);
}

function processItemSelect(el) {
    removeColorSelect();
    selectedItemId = el.options[el.selectedIndex].value;
    if(selectedItemId != 0) {
        // separate style number and name
        var itemTextArr = el.options[el.selectedIndex].text.split(" :: ");
        createHiddenInput('styleNumber', 'styleNumber', itemTextArr[0], document.getElementById(itemFormId));
        createHiddenInput('itemName', 'itemName', itemTextArr[1], document.getElementById(itemFormId));
        sendRequest(requestURL + '?action=getItemColors&itemId=' + selectedItemId, createColorSelect);
    }
}

function processColorSelect(el, itemId) {
    if (document.getElementById(sizeSelectId)) {
        removeSizeSelect();
    }
    else {
        removeQtyTextbox();
    }
    if (el.options[el.selectedIndex].value != 0) {
        createHiddenInput('colorName', 'colorName', el.options[el.selectedIndex].text, document.getElementById(itemFormId));
        sendRequest(requestURL + '?action=getItemSizes&itemId=' + itemId, createSizeSelect);
    }
}

function processSizeSelect(el) {
    removeQtyTextbox();
    if (el.options[el.selectedIndex].value != 0) {
        createHiddenInput('sizeName', 'sizeName', el.options[el.selectedIndex].text, document.getElementById(itemFormId));
        createQtyTextbox();
    }
}

function buildItems(request) {
    designerItems = JSON.parse(request.responseText);
    createItemSelect(designerItems, itemFormId);
}

/**
 * NOT CURRENTLY IN USE possible datalist
 * @param items
 * @param appendToElementId
 */
function createItemSelectDL(items, appendToElementId) {
    var options = new Array();
    //var initOption = new SelectOption('0', 'select item');
    //options.push(initOption);
    for (var i = 0; i < items.length; i++) {
        var seasonText = items[i].season;
        if (seasonText == 'null' || seasonText == null) {
            seasonText = '';
        }
        var optionText = items[i].style_number+' :: '+items[i].name+' :: '+seasonText;
        var option = new SelectOption(items[i].id, optionText);
        options.push(option);
    }
    var attributes = new Array();
    var onChangeAttr = new Attribute("onchange", "processItemSelect(document.getElementById(this.id));");
    attributes.push(onChangeAttr);
    var listId = "itemList";
    var dlAttr = new Attribute("list", listId);
    attributes.push(dlAttr);
    createDatalistInput(listId, "itemInput", "itemInput", options, document.getElementById(appendToElementId), attributes);
    document.getElementById("itemInput").focus();
}

function createItemSelect(items, appendToElementId) {
    var options = new Array();
    var initOption = new SelectOption('0', 'select item');
    options.push(initOption);
    for (var i = 0; i < items.length; i++) {
        var seasonText = items[i].season;
        if (seasonText == null) {
            seasonText = 'N/A';
        }
        var optionText = items[i].style_number+' :: '+items[i].name+' :: '+seasonText;
        var option = new SelectOption(items[i].id, optionText);
        options.push(option);
    }
    var attributes = new Array();
    var onChangeAttr = new Attribute("onchange", "processItemSelect(document.getElementById(this.id));");
    attributes.push(onChangeAttr);
    createSelect(itemSelectId, itemSelectId, options, document.getElementById(appendToElementId), attributes);
    document.getElementById(itemSelectId).focus();
}

function createColorSelect(request) {
    var json = JSON.parse(request.responseText);
    var options = new Array();
    var initOption = new SelectOption('0', 'select color');
    options.push(initOption);
    var itemId;
    for (var i = 0; i < json.length; i++) {
        itemId = json[i].item_id;
        var selected;
        if (json.length == 1) {
            selected = true;
        }
        else {
            selected = false;
        }
        var option = new SelectOption(json[i].id, json[i].color, selected);
        options.push(option);
    }
    var attributes = new Array();
    var onChangeAttr = new Attribute("onchange", "processColorSelect(document.getElementById(this.id), "+itemId+");");
    attributes.push(onChangeAttr);
    createSelect(colorSelectId, colorSelectId, options, document.getElementById(itemFormId), attributes);
    document.getElementById(colorSelectId).focus();
    // if only 1 option it gets selected and processed
    if (selected) {
        processColorSelect(document.getElementById(colorSelectId), itemId);
    }
}

function createSizeSelect(request) {
    var json = JSON.parse(request.responseText);
    if (json.length == 0) {
        createQtyTextbox();
        return;
    }
    var options = new Array();
    var initOption = new SelectOption('0', 'select size');
    options.push(initOption);
    for (var i = 0; i < json.length; i++) {
        var selected = (i == 0) ? true : false;
        var option = new SelectOption(json[i].id, json[i].size, selected);
        options.push(option);
    }
    var attributes = new Array();
    var onChangeAttr = new Attribute("onchange", "processSizeSelect(document.getElementById(this.id));");
    attributes.push(onChangeAttr);
    createSelect(sizeSelectId, sizeSelectId, options, document.getElementById("itemForm"), attributes);
    document.getElementById(sizeSelectId).focus();
    processSizeSelect(document.getElementById(sizeSelectId));
}

function createQtyTextbox() {
    var attributes = new Array();
    attributes.push(new Attribute("type", "number"));
    attributes.push(new Attribute("min", 1));
    attributes.push(new Attribute("max", 99));
    attributes.push(new Attribute("placeholder", 'qty'));
    attributes.push(new Attribute("value", 1));
    createInput(qtyInputId, qtyInputId, document.getElementById("itemForm"), attributes);
    document.getElementById(qtyInputId).focus();
    createCostTextbox();
}

function getItemCost() {
    for (var i = 0; i < designerItems.length; i++) {
        if (designerItems[i].id == selectedItemId) {
            //console.log(JSON.stringify(designerItems));
            return designerItems[i].cost;
        }
    }
}

function createCostTextbox() {
    var defaultValue = getItemCost();
    var attributes = new Array();
    attributes.push(new Attribute("type", "number"));
    attributes.push(new Attribute("min", .01));
    attributes.push(new Attribute("max", 9999));
    attributes.push(new Attribute("step", ".01"));
    attributes.push(new Attribute("placeholder", 'cost'));
    attributes.push(new Attribute("value", defaultValue));
    createInput(costInputId, costInputId, document.getElementById("itemForm"), attributes);
    document.getElementById(costInputId).focus();
    createItemSubmit();
}

function createItemSubmit() {
    var attributes = new Array();
    attributes.push(new Attribute("type", "submit"));
    attributes.push(new Attribute("value", 'Add Item'));
    attributes.push(new Attribute("onclick", "if(!validateItemForm()){return false;}"));
    createInput(itemSubId, itemSubId, document.getElementById("itemForm"), attributes);
    document.getElementById(itemSubId).focus();
}

function validateItemForm() {
    var valid = true;
    var qtyEl = document.getElementById(qtyInputId);
    var qty = qtyEl.value;
    if (qty == '' || qty == 0) {
        qtyEl.className = 'errorField';
        qtyEl.focus();
        valid = false;
    }
    else if (qtyEl.className == 'errorField') {
        qtyEl.className = '';
    }
    var costEl = document.getElementById(costInputId);
    var cost = costEl.value;
    if (cost == '' || cost == 0) {
        costEl.className = 'errorField';
        costEl.focus();
        valid = false;
    }
    else if (costEl.className == 'errorField') {
        costEl.className = '';
    }
    return valid;
}

function removeItemSelect() {
    var itemSelEl = document.getElementById(itemSelectId);
    if (itemSelEl) {
        if (itemSelEl.value != '0') {
            /*
             if (!confirm('remove items?')) {
             return false;
             }
             */
            removeColorSelect();
            removeSizeSelect();
        }
        document.getElementById("itemForm").removeChild(itemSelEl);
    }
}

function removeColorSelect() {
    var itemColorSelEl = document.getElementById(colorSelectId);
    if (itemColorSelEl) {
        document.getElementById("itemForm").removeChild(itemColorSelEl);
        if (document.getElementById(sizeSelectId)) {
            removeSizeSelect();
        }
        else {
            removeQtyTextbox();
        }
    }
}

function removeSizeSelect() {
    var itemSizeSelEl = document.getElementById(sizeSelectId);
    if (itemSizeSelEl) {
        document.getElementById("itemForm").removeChild(itemSizeSelEl);
        removeQtyTextbox();
    }
}

function removeQtyTextbox() {
    var qtyTbEl = document.getElementById(qtyInputId);
    if (qtyTbEl) {
        document.getElementById("itemForm").removeChild(qtyTbEl);
    }
    removeCostTextbox();
}

function removeCostTextbox() {
    var costTbEl = document.getElementById(costInputId);
    if (costTbEl) {
        document.getElementById("itemForm").removeChild(costTbEl);
    }
    removeItemSubmit();
}

function removeItemSubmit() {
    var subEl = document.getElementById(itemSubId);
    if (subEl) {
        document.getElementById("itemForm").removeChild(subEl);
    }
}

// called to add a new item select box and skip designer select
function addItem(designerId) {
    callGetDesignerItems(designerId);
}