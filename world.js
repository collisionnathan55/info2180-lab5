// main functions

function click_lookupBtn() {
    var search = "";
    if (jQuery("#country")[0].reportValidity()) {
        search = jQuery("#country").val();
    }
    else {
        return;
    }

    jQuery.ajax("world.php?country=" + search).done(
        function(result) {
            jQuery("#result").html(result);
        }
    ).fail(
        function(result) { alert("Could not request database !"); }
    );
}

// document ready

jQuery(document).ready(function () {
    jQuery("#lookup").on("click", (event) => click_lookupBtn());
    jQuery("#country").on("keyup", function(event) {
        if (event.key === "Enter") {
            jQuery("#lookup")[0].click() 
        }
    })
})