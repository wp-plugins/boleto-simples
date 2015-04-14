//Do the JS
jQuery(document).ready(function( $ ) {
  
  choiceValue();
  $('#donation_value').change(function() {
    choiceValue('#donation_value');
 });
//Post do boleto

// Attach a submit handler to the form
setTimeout(
  $( "#donation" ).submit(function( event ) {

  // Stop form from submitting normally
  event.preventDefault();

  // Get some values from elements on the page:
  var form = $( this ),
  term = form.find( $(this).data("id") ).val(),
  url = form.attr( "action" );

  // Send the data using post
  var posting = $.post( url, {boletosimples:{bank_billet:{id:$(this).data("id")}}} );

  // Put the results in a div
  posting.done(function( data ) {
      var content = $( data ).find( "#content" );
      $( "#result" ).empty().append( content );
    });
  }), 5000);
});

function choiceValue(obj)
{
  $ = jQuery;
  if($(obj).val() === 'more'){
     $('.custom_value').show();
   }else{
     $('.custom_value').hide();
   }
}