var TABLE = {};
var ARRAY = {};
var ACP = {};
var PASS = {};

TABLE.formwork = function(table) {
  var $tables = $(table);

  $tables.each(function () {
    var _table = $(this);

    //console.log(_table.find('thead tr th:nth-child(8)').html());
    //console.log(_table.find('tbody tr td:nth-child(8)').html());
    _table.find('thead tr').append($('<th class="edit">&nbsp;</th>'));
    //$('td:nth-child(2)').hide();
    _table.find('tbody tr').append($("<td class='edit'><input type='button' value='Klicka för att ändra' /></td>"))
 _table.find('thead tr th:nth-child(8)').hide();
 _table.find('tbody tr td:nth-child(8)').hide();
 //console.log(_table.find('tbody tr td:nth-child(8)').html());
  });
  
  $tables.find('.edit :button').on('click', function(e) {
    TABLE.editable(this);
    e.preventDefault();
  });
}



TABLE.editable = function(button) {
  var $button = $(button);
  var $row = $button.parents('tbody tr');
  var $cells = $row.children('td').not('.edit').slice(1);
  var $is = $row.children('td').eq(0).html();
  var $id = $row.children('td').eq(8).html();
  var $arrayrow = [];
  var $room = $row.children('th');
  
  if ($row.data('flag')) { // in edit mode, move back to table
    // cell methods
console.log('$is: ' + $is);
    ARRAY.save($is,$arrayrow);

    $cells.each(function () {
      var _cell = $(this);
      var res = $(".replaceme").val();
      console.log("$('.replaceme').val(): " + $(".replaceme").val());
      _cell.html(res);
      if(isNaN(res)){
        console.log('is not a number');
        _cell.html('1.0');
        ARRAY.save('1.0',$arrayrow);
      }else{
        console.log('is a number');
        
        ARRAY.save(res,$arrayrow);
      }
    });

    var roomres = $(".replaceroom").val();
    console.log("$('.replaceroom').val(): " + $(".replaceroom").val());
    $room.html(roomres);
    ARRAY.save(roomres, $arrayrow);

    $row.data('flag',false);
    $button.val('Klicka för att ändra');
    console.log($arrayrow);
console.log('Efter: is: '  +$arrayrow[0] + ', home: ' +$arrayrow[1]  + ', max: ' +$arrayrow[2]  + ', min: ' + $arrayrow[3]  + ', away: ' +$arrayrow[4]  + ', rund: ' +$arrayrow[5] + ', id: ' + $arrayrow[6] + 'room: '  +$arrayrow[7] );

  function makeajax($arrayrow){
        $.ajax({
            type: 'post',
            url: 'tableservice',
            data: { is: $arrayrow[0],
                    home: $arrayrow[1],
                    max: $arrayrow[2],
                    min: $arrayrow[3],
                    away: $arrayrow[4],
                    rund: $arrayrow[5],
                    id: $arrayrow[6],
                    room: $arrayrow[7],
                  },
            //dataType: Json,
            success: function(data){
                console.log(data + ': Ajax förfrågan uppfylldes.'); 
                window.location = 'temperatures';   
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Ajax förfrågan misslyckades: ' + textStatus + ', ' + errorThrown); 
                console.log(data) ;  
            },
        });
        console.log('Klickade för att spara: ' + $arrayrow);}
makeajax($arrayrow);

  } 
  else { // in table mode, move to edit mode 
    // cell methods
    ARRAY.save($is,$arrayrow);
console.log('$id: ' + $id);
    $cells.each(function() {
      var _cell = $(this);
//console.log('före: ' + _cell.html());
      _cell.data('text', _cell.html()).html('');
     
      var $input = $('<input type="text" class="replaceme" />')
        .val(_cell.data('text'))
        .width(_cell.width()-_cell.width()+30);  
          if(isNaN(_cell.data('text'))){
            console.log('is not a number');
            $input = $('<input type="text" class="replaceme" />')
              .val(_cell.data('1.0'))
              .width(_cell.width()-_cell.width()+30);
            _cell.append($input);
            ARRAY.save('1.0',$arrayrow);
          }else{
            _cell.append($input);
            ARRAY.save(_cell.data('text'),$arrayrow);
          }
    });

    console.log('före: ' +  $room.html());

    $room.data('text', $room.html()).html('');
    var $input = $('<input type="text" class="replaceroom" />')
        .val($room.data('text'))
        .width($room.width()-$room.width()+100); 
    $room.append($input);
    ARRAY.save($room.data('text'), $arrayrow);


console.log('Före: ' + $arrayrow);
    $row.data('flag', true);
    $button.val('Spara');

  }
}

//////////////////////////////////////////////////////////////////////////////////////


ARRAY.save = function(data, $array) {
  $array.push(data);
//console.log('Inne i save: ' + data);
};

///////////////////////////////////////////////////////////////////////////////////////

ACP.formwork = function(table) {
  var $tables = $(table);

  $tables.each(function () {
    var _table = $(this);

    _table.find('tbody').append($('<th class="edit">&nbsp;</th>'));
    _table.find('tbody').append($("<td class='edit'><input type='button' value='Klicka för att ändra' /></td>"))
  });
  
  $tables.find('.edit :button').on('click', function(e) {
    ACP.editable(this);
    e.preventDefault();
  });
}

ACP.editable = function(button) {
  var $button = $(button);
  var $row = $button.parents('tbody');

  var $area = $row.children().eq(0).children().eq(1);
  var $nrofrooms = $row.children().eq(1).children().eq(1);
  var $load = $row.children().eq(2).children().eq(1);
  var $percent = $row.children().eq(3).children().eq(1);
  var $percentlevel = $row.children().eq(4).children().eq(1);
  var $awayfrom = $row.children().eq(5).children().eq(1);
  var $awayto = $row.children().eq(6).children().eq(1);
  var $acpArray = [];


  if ($row.data('flag')) { // in edit mode, move back to table
    // cell methods

var areares = $( "#areaselect option:selected" ).text();
      $area.html(areares);
      ARRAY.save(areares,$acpArray);

var roomsres = $( "#roomselect option:selected" ).text();
      $nrofrooms.html(roomsres);
      ARRAY.save(roomsres,$acpArray);

var loadres = $( "#loadselect option:selected" ).text();
//console.log(loadres);
var loadtext;
if(loadres == 'JA' || loadres == 'ja'){
  loadtext = 1;
}else{
  loadtext = 0;
}
      $load.html(loadres);
      ARRAY.save(loadtext,$acpArray);


var percentres = $( "#percentselect option:selected" ).text();
//console.log(percentres);
var percenttext;
if(percentres == 'JA' || percentres == 'ja'){
  percenttext = 1;
}else{
  percenttext = 0;
}
      $percent.html(percentres);
      ARRAY.save(percenttext,$acpArray);

var levelres = $( "#levelselect option:selected" ).text();
      $percentlevel.html(levelres);
      ARRAY.save(levelres,$acpArray);

var fromres = $("#awayfrom").val();
      $awayfrom.html(fromres);

var tores = $("#awayto").val();
      $awayto.html(tores);

      ARRAY.save(fromres,$acpArray);
      ARRAY.save(tores,$acpArray);

      $row.data('flag',false);
    $button.val('Klicka för att ändra');
    console.log($acpArray);
console.log('Efter: area: ' + $acpArray[0]  + ', nrofrooms: ' + $acpArray[1] + ', load: ' +$acpArray[2]  + ', percent: ' + $acpArray[3]  + ', percentlevel: ' + $acpArray[4] + ', awayfrom: ' + $acpArray[5] + ', awayto: ' + $acpArray[6]);

  function makeajax($acpArray){
        $.ajax({
            type: 'post',
            url: 'acp/acpservice',
            data: { area: $acpArray[0],
                    nrofrooms: $acpArray[1],
                    load: $acpArray[2],
                    percent: $acpArray[3],
                    percentlevel: $acpArray[4],
                    awayfrom: $acpArray[5],
                    awayto: $acpArray[6]},
            //dataType: Json,
            success: function(data){
                console.log(data + ': Ajax förfrågan uppfylldes.'); 
                window.location = 'acp';   
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Ajax förfrågan misslyckades: ' + textStatus + ', ' + errorThrown); 
                console.log(data) ;  
            },
        });
        console.log('Klickade för att spara: ' + $acpArray);}
makeajax($acpArray);

  } 
  else { // in table mode, move to edit mode 
    // cell methods

        $area.data('text', $area.html()).html('');
        var $input = $('<select id="areaselect" name="area"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>')
        .val($area.data('text'))
        .width($area.width()-$area.width()+100);
        $area.append($input); 
        ARRAY.save($area.data('text'),$acpArray);

        $nrofrooms.data('text', $nrofrooms.html()).html('');
        var $input = $('<select id="roomselect" name="room"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option></select>')
        .val($nrofrooms.data('text'))
        .width($nrofrooms.width()-$nrofrooms.width()+100);
        $nrofrooms.append($input); 
        ARRAY.save($nrofrooms.data('text'),$acpArray);

        $load.data('text', $load.html()).html('');
        var $input = $('<select id="loadselect" name="load"><option value="NEJ">NEJ</option><option value="JA">JA</option></select>')
        .val($load.data('text'))
        .width($load.width()-$load.width()+100);
        $load.append($input); 
        ARRAY.save($load.data('text'),$acpArray);

        $percent.data('text', $percent.html()).html('');
        var $input = $('<select id="percentselect" name="percent"><option value="NEJ">NEJ</option><option value="JA">JA</option></select>')
        .val($percent.data('text'))
        .width($percent.width()-$percent.width()+100);
        console.log($percent.data('text'));
        $percent.append($input); 
        ARRAY.save($percent.data('text'),$acpArray);

        $percentlevel.data('text', $percentlevel.html()).html('');
        var $input = $('<select id="levelselect" name="level"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select>')
        .val($percentlevel.data('text'))
        .width($percentlevel.width()-$percentlevel.width()+100);
        $percentlevel.append($input); 
        ARRAY.save($percentlevel.data('text'),$acpArray);

        $awayfrom.data('text', $awayfrom.html()).html('');
        var $input = $('<input id = "awayfrom" type="text" name = "from" />')
        .val($awayfrom.data('text'))
        .width($awayfrom.width()-$awayfrom.width()+100);
        $awayfrom.append($input); 
        ARRAY.save($awayfrom.data('text'),$acpArray);

        $awayto.data('text', $awayto.html()).html('');
        var $input = $('<input id = "awayto" type="text" />')
        .val($awayto.data('text'))
        .width($awayto.width()-$awayto.width()+100);
        $awayto.append($input); 
        ARRAY.save($awayto.data('text'),$acpArray);

                console.log('Före: ' + $acpArray);

    $( "select#areaselect" ).selectmenu();
    $( "select#roomselect" ).selectmenu();
    $( "select#loadselect" ).selectmenu();
    $( "select#percentselect" ).selectmenu();
    $( "select#levelselect" ).selectmenu();

$(function() {
    $('#awayfrom').datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    showOn: 'both',
    dateFormat: "dd.mm.yy",
    dayNamesMin: [ "Sön", "Må", "Ti", "Ons", "Tors", "Fre", "Lör"],
    monthNames: [ "Januari", "Februari", "Mars", "April", "Maj", "Juni", "Juli", "Augusti", "September", "Oktober", "November", "December" ],
    showWeek: true,
    firstDay: 1,
    buttonText: 'Välj ett datum',
    buttonImage: 'themes/images/calendar.png',
    buttonImageOnly: true,
    numberOfMonths: 2,
    onClose: function( selectedDate ) {
        $( "#awayto" ).datepicker( "option", "minDate", selectedDate );
      }
  });

    $('#awayto').datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    showOn: 'both',
    dateFormat: "dd.mm.yy",
    dayNamesMin: [ "Sön", "Må", "Ti", "Ons", "Tors", "Fre", "Lör" ],
    monthNames: [ "Januari", "Februari", "Mars", "April", "Maj", "Juni", "Juli", "Augusti", "September", "Oktober", "November", "December" ],
    showWeek: true,
    firstDay: 1,
    onClose: function( selectedDate ) {
        $( "#awayfrom" ).datepicker( "option", "maxDate", selectedDate );
      },
    buttonText: 'Välj ett datum',
    buttonImage: 'themes/images/calendar.png',
    buttonImageOnly: true,
    numberOfMonths: 2,
  });
});

console.log('Före: area: ' + $acpArray[0]  + ', nrofrooms: ' +$acpArray[1] + ', load: ' +$acpArray[2]  + ', percent: ' +$acpArray[3]  + ', percentlevel: ' + $acpArray[4]  + ', awayfrom: ' +$acpArray[5]  + ', awayto: ' +$acpArray[6]);

    $row.data('flag', true);
    $button.val('Spara');

  }
}

/*//////////////////////////////////////////////////////////////////////////////////////////////////*/

PASS.formwork = function(table) {
  var $tables = $(table);

  $tables.each(function () {
    var _table = $(this);

    _table.find('thead tr').append($('<th class="edit">&nbsp;</th>'));
    _table.find('tbody tr').append($("<td class='edit'><input type='button' value='Klicka för att ändra' /></td>"))
    _table.find('thead tr th:nth-child(6)').hide();
    _table.find('tbody tr td:nth-child(6)').hide();
  });
  
  $tables.find('.edit :button').on('click', function(e) {
    PASS.editable(this);
    e.preventDefault();
  });
}

PASS.editable = function(button) {
  var $button = $(button);
  var $row = $button.parents('tbody tr');
  var $cells = $row.children('td').not('.edit');
  var $arrayrow = [];
  
  if ($row.data('flag')) { // in edit mode, move back to table

    $cells.each(function () {
      var _cell = $(this);
      var res = $(".replaceme").val();
      console.log("$('.replaceme').val(): " + $(".replaceme").val());
      _cell.html(res);
        ARRAY.save(res,$arrayrow);
    });

    $row.data('flag',false);
    $button.val('Klicka för att ändra');
    console.log($arrayrow);
console.log('Efter: akronym: '  +$arrayrow[0] + ', pass1: ' +$arrayrow[1]  + ', pass2: ' +$arrayrow[2]  + ', name: ' + $arrayrow[3]  + ', email: ' + $arrayrow[4] + ', id: ' +$arrayrow[5] );

  function makeajax($arrayrow){
        $.ajax({
            type: 'post',
            url: 'acp/passwordservice',
            data: { acronym: $arrayrow[0],
                    pass1: $arrayrow[1],
                    pass2: $arrayrow[2],
                    name: $arrayrow[3],
                    email: $arrayrow[4],
                    id: $arrayrow[5],
                  },
            //dataType: Json,
            success: function(data){
                console.log(data + ': Ajax förfrågan uppfylldes.'); 
                window.location = 'acp';   
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Ajax förfrågan misslyckades: ' + textStatus + ', ' + errorThrown); 
                console.log(data) ;  
            },
        });
        console.log('Klickade för att spara: ' + $arrayrow);}
makeajax($arrayrow);

  } 
  else { // in table mode, move to edit mode 
    // cell methods
    $cells.each(function() {
      var _cell = $(this);
console.log('före: ' + _cell.html());
      _cell.data('text', _cell.html()).html('');
     
      var $input = $('<input type="text" class="replaceme" />')
        .val(_cell.data('text'))
        .width(_cell.width()-_cell.width()+100);  
            _cell.append($input);
            ARRAY.save(_cell.data('text'),$arrayrow);
    });


console.log('Före: akronym: '  +$arrayrow[0] + ', pass1: ' +$arrayrow[1]  + ', pass2: ' +$arrayrow[2]  + ', name: ' + $arrayrow[3]  + ', email: ' + $arrayrow[4] + ', id: ' +$arrayrow[5] );

    $row.data('flag', true);
    $button.val('Spara');

  }
}

$(document).ready(function() {
  'use strict';
  TABLE.formwork('.fixedEdit');

  ACP.formwork('.fixedacp');

  PASS.formwork('.fixedlogin')

});