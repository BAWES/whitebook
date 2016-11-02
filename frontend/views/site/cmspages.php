<?php
use yii\helpers\Url;

$this->title = $title . ' | Whitebook';
?>


<link href="<?= Url::to("@web/css/bootstrap-select.min.css") ?>" rel="stylesheet">
<section id="inner_page_detials">
    <div class="top_sections_titles">
        <div class="container">
            <div class="title_main">
                <h1><?= $title ?></h1>
            </div>
            <div class="about_content_sec">
                <?= $content ?>
            </div>
        </div>
    </div>
</section>

<script>

// mobile hover menu start
    $(".mobile-menu .dropdown").click(function () {
            $(this).toggleClass('open');
        },
        function () {
            $(this).toggleClass('open');
        }
    );

// mobile hover menu end

</script>

<?php
$this->registerCss("
.about_content_sec{margin-bottom: 30px;font-family: 'OpenSansRegular' !important}
");
?>
<!--end footer sticky-->
