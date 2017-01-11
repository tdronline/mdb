<script type="text/javascript" language="javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" language="javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript">
    //expand Advanced search
    $('body').on("click", ".filter", function () {
        $('.filter-container').slideToggle();
    });

    //quick search
    $("#search").keyup(function () {
        $.ajaxSetup({cache: false});
        var ajax_load = "<img src='img/loader.gif' alt='loading...' />";
        var loadUrl = "search.php";
        if ($("#search").val() == '') {
            $("#suggest").fadeOut('fast');
            return;
        }
        $("#result").html(ajax_load);
        $.post(
            loadUrl,
            {s: $("#search").val()},
            function (responseText) {
                $("#suggest").slideDown('fast');
                $("#suggest").html(responseText);
            },
            "html"
        );
    });

    //advanced search
    $("#filter-movies").click(function () {
        $.ajaxSetup({cache: false});
        var ajax_load = "<img src='img/loader.gif' alt='loading...' />";
        var loadUrl = "ajax-search-results.php";
        $("#mv-content").html(ajax_load);
        $.post(
            loadUrl,
            {quality: $("#quality").val(), genre: $("#genre").val(), rating: $("#rating").val(), lang: $("#lang").val(), order: $("#order").val()},
            function (responseText) {
                $("#mv-content").fadeIn('fast');
                $("#mv-content").html(responseText);
            },
            "html"
        );
    });
</script>
</body>
</html>
