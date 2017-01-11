<?php include("header.php"); ?>
    <div class="container container-fluid" id="mv-content">
        <?php
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }
        $num_rec_per_page=48;
        $start_from = ($page-1) * $num_rec_per_page;
        $pagination = "LIMIT $start_from, $num_rec_per_page";
        $Q = "SELECT * FROM `movielist` ORDER BY rel_year DESC, uploaded DESC, rating DESC $pagination";
        $mRes = $db->query($Q);
        while ($m = $mRes->fetch_object()) {
            displayThumb($m->imdb);
        }

        pegination($page);
        ?>
    </div>
    <div id="movieInfo" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //ajax movie details
        $('body').on("click", "a.mv-btn", function () {
            var pagerequest = $(this).attr('href');

            var request = $.ajax({
                url: pagerequest,
                method: "POST",
                dataType: "html"
            });

            request.done(function (msg) {
                $(".modal-body").html(msg);
                $('#movieInfo').modal('show');
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
            return false;
        });
		
		$('body').on("click", ".close", function () {
			$(".modal-body").empty();
		});
    </script>
<?php include("footer.php"); ?>