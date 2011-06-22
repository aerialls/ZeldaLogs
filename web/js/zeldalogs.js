 $(document).ready(function(){
    $("#search_submit").click(function() { 
        if ($("#search").value() != '') {
            $("#search_form").submit(); 
        }
    });
}); 