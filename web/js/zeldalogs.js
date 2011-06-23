 $(document).ready(function(){
    $("#search_submit").click(function() { 
        if ($("#search").val() != '') {
            $("#search_form").submit(); 
        }
    });
}); 