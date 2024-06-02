function loadBusTimes() {
    $.ajax({
        url: "get_bus_times.php", // PHP script to fetch bus times
        success: function(result){
            $("#busTimes").html(result);
        }
    });
}