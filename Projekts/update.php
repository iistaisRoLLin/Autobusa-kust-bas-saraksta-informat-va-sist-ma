<!DOCTYPE html>
<html>
<head>
    <title>Update</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div id="busTimes"></div>

<script>
$(document).ready(function(){
    // Function to load bus times
    function loadBusTimes() {
        $.ajax({
            url: "get_bus_times.php", // PHP script to fetch bus times
            success: function(result){
                $("#busTimes").html(result);
            }
        });
    }

    // Load bus times initially
    loadBusTimes();

    // Refresh bus times every 15 seconds
    setInterval(function(){
        loadBusTimes();
    }, 15000); // 15 seconds in milliseconds
});
</script>

</body>
</html>