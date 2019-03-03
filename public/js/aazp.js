// aazp\aazpDemoBundle\Resources\public\js\file.js

// aazp = function(){
// var locale = "";
// return{
// initLocale : function(){
//       if(global.locale){
//         locale = global.locale;
//       }
//       else{
//         //Set a default locale if the user's one is not managed
//         console.error('The locale is missing, default locale will be set (fr_FR)');
//         locale = "en_UK";
//       }
// },
// getLocale : function(length){
//       if(length == 2){
//           return locale.split('_')[0];
//       }
//       return locale;
// },
// // initDatePicker : function(){
// //
//     // if($.datepicker.regional[aazp.getLocale(4)] != undefined ){
//         // $.datepicker.setDefaults( $.datepicker.regional[aazp.getLocale(4)] );
//     // }else if($.datepicker.regional[aazp.getLocale(2)] != undefined){
//         // $.datepicker.setDefaults( $.datepicker.regional[aazp.getLocale(2) ] );
//     // }else{
//         // $.datepicker.setDefaults( $.datepicker.regional['']);
//     // }
// //
//     // $('.aazpDatePicker').each(function(){
//         // var id_input=this.id.split('_datepicker')[0];
//         // var sfInput = $('#'+id_input)[0];
//         // if(! (sfInput)){
//             // console.error('An error has occurred while creating the datepicker');
//         // }
//         // $(this).datepicker({
//             // 'yearRange':$(this).data('yearrange'),
//             // 'changeMonth':$(this).data('changemonth'),
//             // 'changeYear':$(this).data('changeyear'),
//             // 'altField' : '#'+id_input,
//             // 'altFormat' : 'yy-mm-dd',
//             // 'minDate' : null,
//             // 'maxDate': null
//         // });
// //
//         // $(this).keyup(function(e) {
//             // if(e.keyCode == 8 || e.keyCode == 46) {
//                 // $.datepicker._clearDate(this);
//                 // $('#'+id_input)[0].value = '';
//             // }
//         // });
//         // var dateSf = $.datepicker.parseDate('yy-mm-dd',sfInput.value);
// //
//         // $(this).datepicker('setDate',dateSf);
//         // $(this).show();
//         // $(sfInput).hide();
//     // })
// // }
// }}()


var $PassengerHolder;
var $purchaseItemsHolder;
var $purchaseItemsHolder1;
var $purchaseItemsHolder2;
var $purchaseItemsHolder3;

var $addPassengerLink = $('<div class="btn-group pull-left"><a href="#" class="add_contact_link btn btn-default btn-md"><span class="glyphicon glyphicon-plus"></span></a></div>');
var $newPassengerLink = $('<div></div>').append($addPassengerLink);

// setup an "add a purchaseItem" link

var $addPurchaseItemLink = $('<div class="btn-group pull-left"><div class="col-md-12 col-sm-12"><a href="#" class="add_purchase_item_link btn btn-default btn-md"><span class="glyphicon glyphicon-plus"></span></a></div></div>');
var $newPurchaseItemLink = $('<div></div>').append($addPurchaseItemLink);

var $addPurchaseItemLink1 = $('<div class="btn-group pull-left"><div class="col-md-12 col-sm-12"><a href="#" class="add_purchase_item_link btn btn-default btn-md"><span class="glyphicon glyphicon-plus"></span></a></div></div>');
var $newPurchaseItemLink1 = $('<div></div>').append($addPurchaseItemLink1);

var $addPurchaseItemLink2 = $('<div class="btn-group pull-left"><div class="col-md-12 col-sm-12"><a href="#" class="add_purchase_item_link btn btn-default btn-md"><span class="glyphicon glyphicon-plus"></span></a></div></div>');
var $newPurchaseItemLink2 = $('<div></div>').append($addPurchaseItemLink2);

var $addPurchaseItemLink3 = $('<div class="btn-group pull-left"><div class="col-md-12 col-sm-12"><a href="#" class="add_purchase_item_link btn btn-default btn-md"><span class="glyphicon glyphicon-plus"></span></a></div></div>');
var $newPurchaseItemLink3 = $('<div></div>').append($addPurchaseItemLink3);


$(document).ready(function(){

	//2018-06-16 - DISCONNECTED THE JQUERY EVENT WHICH ADDS THE CREDIT CARD FEE TO THE PURCHASE ITEMS. 
//	$('input[name=purchase\\[paymentType\\]]').change(function( e ){
//		e.preventDefault();
//		// $( "form.payment" ).submit();
//		passengerPaymentMethodUpdateAction( $( "form.payment" ), function( response ) {
//			console.log(JSON.stringify(response));
//		});	
//		
//		$(document).ajaxStop(function() { calculateSum(); });
//	});
	//2018-06-16 - DISCONNECTED THE JQUERY EVENT WHICH ADDS THE CREDIT CARD FEE TO THE PURCHASE ITEMS. 

	//2018-06-16 - DISCONNECTED THE JQUERY EVENT WHICH ADDS THE CREDIT CARD FEE TO THE PURCHASE ITEMS. 
//	$('input[name=form\\[paymentType\\]]').change(function( e ){
//		e.preventDefault();
//		// $( "form.payment" ).submit();
//		passengerPaymentMethodUpdateAction( $( "form.payment" ), function( response ) {
//			console.log(JSON.stringify(response));
//		});	
//		
//		$(document).ajaxStop(function() { calculateSum(); });
//	});
	//2018-06-16 - DISCONNECTED THE JQUERY EVENT WHICH ADDS THE CREDIT CARD FEE TO THE PURCHASE ITEMS. 

	$( 'form.ajaxable' ).submit( function( e ) {
		e.preventDefault();

		postForm( $(this), function( response ) {
			console.log(JSON.stringify(response)); // Good tutorial stuff
		});
	// return false; // no please http://fuelyourcoding.com/jquery-events-stop-misusing-return-false/
	});

    // $('#booking_cancel').attr('type', 'button');  //This is so the Edit Booking form does not submit when we click on the 'Cancel' button which is type SubmitType::class (defined in the BookingType class).

    $('#modalWarning').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        $(this).find('.modal-title').text($(e.relatedTarget).data('modal-title'));
    });

    // $('#redeemWarning').on('show.bs.modal', function(e) {
    //     $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    // });
    //
    // $('#deleteWarning').on('show.bs.modal', function(e) {
    //     $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    // });
    //
    // $('#refundWarning').on('show.bs.modal', function(e) {
	//     $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	// });
    //
	$('.calc-item').on("keyup", function () {
	    calculateSum(this);
	});

	$('.calc-item').focusout(function () {
		formatMoney(this);
	});
	
	$('[data-toggle="tooltip"]').tooltip({ placement : 'top'}); 


	// $('#booking_flightdate').datetimepicker( {
		// format:'d-m-Y H:i',
		// //allowTimes:['6:00', '13:00', '14:00', '15:00','17:00', '17:05', '17:20', '19:00', '20:00'],
		// step: 10,
		// minDate: 0,
		// startDate: 0,
		// minTime: '7:00',
		// maxTime: '19:00',
		// defaultSelect: true,
		// yearStart: '2014'
	  // });

    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        // todayBtn: true,
    }).on("change", function (e) {
        $('#targetDateForm').submit();
    });

	$('#waitingListItem_waitingListItemDate').datetimepicker( {
		format:'d-m-Y',
		//allowTimes:['6:00', '13:00', '14:00', '15:00','17:00', '17:05', '17:20', '19:00', '20:00'],
		minDate: 0,
		startDate: 0,
		defaultSelect: true,
		timepicker: false,
		yearStart: '2014'
	  });

    // Get the ul that holds the collection of purchase items
    $purchaseItemsHolder = $('div.purchaseItems');
    // $purchaseItemsHolder.css('list-style-type', 'none');

    // add the "add a passenger" anchor and li to the passengers ul
    $purchaseItemsHolder.append($newPurchaseItemLink);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $purchaseItemsHolder.data('index', $purchaseItemsHolder.children().length);

    $addPurchaseItemLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new purchaseItem form (see next code block)
        createPurchaseItemAddLink($purchaseItemsHolder, $newPurchaseItemLink);
	});

    // add a delete link to all of the existing tag form li elements
    $purchaseItemsHolder.find('div.purchaseItem').each(function() {
        createPurchaseItemDeleteLink($(this));
    });

	$passengerHolder = $('div.passengers');

    // add the "add a passenger" anchor and li to the passengers ul
    $passengerHolder.append($newPassengerLink);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $passengerHolder.data('index', $passengerHolder.children().length);

    $addPassengerLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new passenger form (see next code block)
        createPassengerAddLink($passengerHolder, $newPassengerLink);
	});

    // add a delete link to all of the existing tag form li elements
    $passengerHolder.find('div.passenger').each(function() {
        createPassengerDeleteLink($(this));
    });
});

// function addPurchaseItemFormDeleteLink($tagFormLi) {
    // var $removeFormA = $('<div class="btn-group col-md-1"><a href="#" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></a></div>');
    // $tagFormLi.prepend($removeFormA);
// 
    // $removeFormA.on('click', function(e) {
        // // prevent the link from creating a "#" on the URL
        // e.preventDefault();
// 
        // // remove the li for the tag form
        // $tagFormLi.remove();
    // });
// }

// function addPurchaseItemForm($purchaseItemsHolder, $newPurchaseItemLink) {
// 
    // // Get the data-prototype explained earlier
    // var prototype = $purchaseItemsHolder.data('prototype');
// 
    // // get the new index
    // var index = $purchaseItemsHolder.data('index');
// 
    // // Replace '__name__' in the prototype's HTML to
    // // instead be a number based on how many items we have
    // var newForm = prototype.replace(/__name__/g, index);
// 
    // // increase the index with one for the next item
    // $purchaseItemsHolder.data('index', index + 1);
// 
    // // Display the form in the page in an li, before the "Add a purchase item" link li
    // var $newFormLi = $('<li class="purchaseItem"></li>').append(newForm);
// 
    // $newPurchaseItemLink.before($newFormLi);
//     
    // addPurchaseItemFormDeleteLink($newFormLi);
// }

function createPassengerDeleteLink($newPassengerHolder) {
    var $newPassengerDeleteLink = $('<div class="btn-group col-md-1 col-xs-1"><a href="#" class="btn btn-default btn-md"><span class="glyphicon glyphicon-remove"></span></a></div>');
    
    $newPassengerHolder.append($newPassengerDeleteLink);

    $newPassengerDeleteLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $newPassengerHolder.remove();
    });
    
    return $newPassengerDeleteLink;
}

function createPassengerAddLink($passengerHolder, $newPassengerAddLink) {

    // Get the data-prototype explained earlier
    var prototype = $passengerHolder.data('prototype');

    // get the new index
    var index = $passengerHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // var newPassengerPrototype = prototype.replace(/__LABEL__/g, 'Order Item ' + index);
    var newPassengerPrototype = prototype.replace(/__name__/g, '' + index);

    // increase the index with one for the next item
    $passengerHolder.data('index', index+1);

    var $newPassengerHolder = $('<div class="passenger row no-gutter col-md-12"></div>').append(newPassengerPrototype);
    $newPassengerAddLink.before($newPassengerHolder);
    
    createPassengerDeleteLink($newPassengerHolder);
}

function createPurchaseItemDeleteLink($newPurchaseItemsHolder) {
    var $newPurchaseItemDeleteLink = $('<div class="btn-group col-md-1 col-xs-1"><a href="#" class="btn btn-default btn-md"><span class="glyphicon glyphicon-remove"></span></a></div>');
    
    $newPurchaseItemsHolder.append($newPurchaseItemDeleteLink);

    $newPurchaseItemDeleteLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $newPurchaseItemsHolder.remove();
        calculateSum(this);
    });
    
    return $newPurchaseItemDeleteLink;
}

function createPurchaseItemAddLink($purchaseItemsHolder, $newPurchaseItemAddLink) {

    // Get the data-prototype explained earlier
    var prototype = $purchaseItemsHolder.data('prototype');

    // get the new index
    var index = $purchaseItemsHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // var newPurchaseItemPrototype = prototype.replace(/__LABEL__/g, 'Order Item ' + index);
    var newPurchaseItemPrototype = prototype.replace(/__name__/g, '' + index);

    // increase the index with one for the next item
    $purchaseItemsHolder.data('index', index+1);

    var $newPurchaseItemsHolder = $('<div class="row nopadding purchaseItem col-md-12 col-sm-12"></div>').append(newPurchaseItemPrototype);
    $newPurchaseItemAddLink.before($newPurchaseItemsHolder);
    
    createPurchaseItemDeleteLink($newPurchaseItemsHolder);
}

function formatMoney(element) {

        var thisVal = $(element).val();
        if (isNaN(thisVal) || thisVal.length === 0) {
            thisVal = 0;
        } else {
            thisVal = parseFloat(thisVal);
        }
        $(element).val(thisVal.toFixed(2));
}

function calculateSum() {
    var sum = 0;
    var payment_sum = 0;
    // var $this = $(element);

    // Process each element with the same class
    $(".calc-item").each(function () {
        var thisVal = $(this).val();

        // Count invalid entries as 0
        if (isNaN(thisVal) || thisVal.length === 0) {
            thisVal = 0;
        } else {
            thisVal = parseFloat(thisVal);
        }

        sum += thisVal;
    });
    
    $(".calc-item-payment").each(function () {
//        var thisVal = $(this).text();
        var thisVal = $(this).attr( "payment-total" );

        // Count invalid entries as 0
        if (isNaN(thisVal) || thisVal.length === 0) {
            thisVal = 0;
        } else {
            thisVal = parseFloat(thisVal);
        }

        payment_sum += thisVal;
    });

	$('#form_balanceAmount').html((sum - payment_sum).toFixed(2));
	$('#purchase_paymentAmount').val((sum - payment_sum).toFixed(2));
	$('#form_paymentAmount').val((sum - payment_sum).toFixed(2));
}

function calculateSum_ORIG(element) {
    var sum = 0;
    var payment_sum = 0;
    var $this = $(element);

    // Process each element with the same class
    $(".calc-item").each(function () {
        var thisVal = $(this).val();

        // Count invalid entries as 0
        if (isNaN(thisVal) || thisVal.length === 0) {
            thisVal = 0;
        } else {
            thisVal = parseFloat(thisVal);
        }

        sum += thisVal;
    });
    
    $(".calc-item-payment").each(function () {
        var thisVal = $(this).text();

        // Count invalid entries as 0
        if (isNaN(thisVal) || thisVal.length === 0) {
            thisVal = 0;
        } else {
            thisVal = parseFloat(thisVal);
        }

        payment_sum += thisVal;
    });

	$('#form_balanceAmount').html((sum - payment_sum).toFixed(2));
	$('#form_paymentAmount').val((sum - payment_sum).toFixed(2));
}

function bookingPaymentSummaryUpdatePurchaseItem(selectEl)
{
	selectIdParts = selectEl.id.split('_');
	
	productPriceId = 'product_price_' + selectIdParts[2] +'_'+ selectIdParts[5];
	//EG: form_passengers_1_purchase_purchaseItems_1_amount
	amountId = selectIdParts[0] +'_'+ selectIdParts[1] +'_'+ selectIdParts[2] +'_'+ selectIdParts[3] +'_'+ selectIdParts[4] +'_'+ selectIdParts[5] +'_'+ 'amount';
	
	$.ajax({
	    type        : 'GET',
	    url         : '/booking/product/cost/' + selectEl.value,
	    data        : selectEl.value,
	})
	.done(function(response){
		$('#'+amountId).val(response);
		$('#'+productPriceId).html(response);
	})
	.fail(function(jqXHR, textStatus, errorThrown){
    	alert('Error HERE: ' + errorThrown);
	});

	$(document).ajaxStop(function() { calculateSum(); });
}

function updatePurchase(selectEl) {
	
	selectIdParts = selectEl.id.split('_');
	// alert(selectIdParts[0]);
	
	productPriceId = 'product_price_' + selectIdParts[2];
	amountId = selectIdParts[0] +'_'+ selectIdParts[1] +'_'+ selectIdParts[2] +'_'+ 'amount';

  $.ajax({
    type        : 'GET',
    url         : '/booking/product/cost/' + selectEl.value,
    data        : selectEl.value,
  })
  .done(function(response){
    $('#'+amountId).val(response);
    $('#'+productPriceId).html(response);
  })
  .fail(function(jqXHR, textStatus, errorThrown){
    alert('Error : ' + errorThrown);
  });

	$(document).ajaxStop(function() { calculateSum(); });
}

function bookingSummaryUpdatePurchase(selectEl) {
	//form_purchases_1_purchaseItems_1_product
	selectIdParts = selectEl.id.split('_');
	
	productPriceId = 'product_price_' + selectIdParts[2] +'_'+ selectIdParts[4];
	//form_purchases_1_purchaseItems_1_amount
	amountId = selectIdParts[0] +'_'+ selectIdParts[1] +'_'+ selectIdParts[2] +'_'+ selectIdParts[3] +'_'+ selectIdParts[4] +'_'+ 'amount';

  $.ajax({
    type        : 'GET',
    url         : '/product/cost/' + selectEl.value,
    data        : selectEl.value,
  })
  .done(function(response){
    $('#'+amountId).val(response);
    $('#'+productPriceId).html(response);
  })
  .fail(function(jqXHR, textStatus, errorThrown){
    alert('Error : ' + errorThrown);
  });

	$(document).ajaxStop(function() { calculateSum(); });
}

function passengerPaymentMethodUpdateAction($form, callback)
{	
  var values = {};
  $.each( $form.serializeArray(), function(i, field)
  {
    values[field.name] = field.value;
  });

  $.ajax({
    type        : $form.attr( 'method' ),
    url         : $form.attr( 'action' ),
    data        : values,
    success     : function(data) {
	  console.log(data.error);
      callback( data );
    },
    error: function(jqXHR, textStatus, errorThrown) {
 	  console.log(textStatus, errorThrown);
	}
  })
  .done(function(response){
    $('#ajaxreplace').html(response);                 
  })
  .fail(function(jqXHR, textStatus, errorThrown)
  {
	  $('#ajaxError .modal-body p').html(jqXHR.responseText);
	  $('#ajaxError').modal('show');
//    alert('Error : ' + errorThrown + ' ' + jqXHR.responseText );
  });
}

function postForm( $form, callback ){
 
  /*
   * Get all form values
   */
  var values = {};
  $.each( $form.serializeArray(), function(i, field) {
    values[field.name] = field.value;
  });
 
  /*
   * Throw the form values to the server!
   */
  $.ajax({
    type        : $form.attr( 'method' ),
    url         : $form.attr( 'action' ),
    data        : values,
    success     : function(data) {
      callback( data );
    }
  })
  .done(function(response){
    template = response;
    // alert(template);
    $('#your_div').html(template); //Change the html of the div with the id = "your_div"                        
  })
  .fail(function(jqXHR, textStatus, errorThrown){
    alert('Error : ' + errorThrown);
  });
}

