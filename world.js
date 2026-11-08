// main functions

function click_lookupCountry() {
    var search = "";
    if (jQuery("#country")[0].reportValidity()) {
        search = jQuery("#country").val();
    }
    else {
        return;
    }

    jQuery.ajax("world.php?country=" + search + "&lookup=country").done(
        function(result) {
            jQuery("#result").html(result);
        }
    ).fail(
        function(result) { alert("Could not request database !"); }
    );
}

function click_lookupCity() {
    var search = "";
    if (jQuery("#country")[0].reportValidity()) {
        search = jQuery("#country").val();
    }
    else {
        return;
    }

    jQuery.ajax("world.php?country=" + search + "&lookup=city").done(
        function(result) {
            jQuery("#result").html(result);
        }
    ).fail(
        function(result) { alert("Could not request database !"); }
    );
}

// document ready

var LAST_LOOKUP = null;

jQuery(document).ready(function () {
    jQuery("#lookup-country").on("click", function(event) {
        LAST_LOOKUP = jQuery("#lookup-country")[0];
        click_lookupCountry();
    });
    jQuery("#lookup-city").on("click", function(event) {
        LAST_LOOKUP = jQuery("#lookup-city")[0];
        click_lookupCity();
    });
    
    LAST_LOOKUP = jQuery("#lookup-country")[0];
    jQuery("#country").on("keydown", function(event) {
        if (event.key === "Enter") {
            LAST_LOOKUP.click() 
        }
    })
})