$( document ).ready(function() {
    $( function() {
        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term1 ) {
            return split( term1 ).pop();
        }

        $( "#multi_autocomplete" )

            // don't navigate away from the field on tab when selecting an item
            .on( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).autocomplete( "instance" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function( request, response ) {
                    $.getJSON( "autocomplete.php", {
                        term1: extractLast( request.term )
                    }, response );
                },
                search: function() {
                    // custom minLength
                    var term1 = extractLast( this.value );
                    if ( term1.length < 2 ) {
                        return false;
                    }
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
            });
        $( "#multi_autocomplete_disciplines1" )

            // don't navigate away from the field on tab when selecting an item
            .on( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).autocomplete( "instance" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function( request, response ) {
                    //Обработчик
                    $.getJSON( "autocompletes/discipline_learn_before/disc_learn_before_autocomplete.php", {
                        term1: extractLast( request.term )
                    }, response );
                },
                search: function() {
                    // custom minLength
                    var term1 = extractLast( this.value );
                    if ( term1.length < 2 ) {
                        return false;
                    }
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
            });
    });
});