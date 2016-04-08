<?php $this->title = 'Contact us | Whitebook'; ?>
<!-- coniner start -->
<section id="inner_pages_sections">
    <div class="container">
        <div class="title_main">
            <h1>Contact & Faq's</h1>
        </div>
        <div class="contact_us">
            <div class="col-md-4 paddingleft0">
                <div class="contact_us_sec">
                    <h3 class="inner_title">Ask a Question</h3>
                    <div data-example-id="basic-forms" class="bs-example">
                        <form id="form1" name="form1" method="post">
                            <div class="form-group">

                                <input type="text" placeholder="Sender Name *" id="username" name="username" class="form-control">
                                <div id="chkname" class="error"></div>
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Sender Email *" id="useremail" name="useremail" class="form-control">
                                <div id="chkemail" class="error"></div>
                            </div><div class="form-group">
                                <textarea placeholder="Message *" rows="3" id="usermessgae" name="usermessgae" class="form-control"></textarea>
                                <div id="chkmessage" class="error"></div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default" type="button" id="send" name="send" title="Send Email">Send Email</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-7">
            <div class="contact_center_sec">
                <h3 class="inner_title">Find an Answer</h3>
                <ul class="inner_contents">
                    <li>
                        <div class="left_books">
                            WHAT IS <br/>
                            My Whitebook ?
                        </div>
                        <div class="left_books_lis">
                            <ul>
                                <?php foreach ($faq as $f) { ?>
                                    <li>
                                        <p><a class="show_content" href="javascript:void(0);"><?= ucfirst($f['question']) ?></a></p>
                                        <div class="toogle_botom"><?= ucfirst($f['answer']) ?>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>
</section>



<!-- continer end -->


<script>
    jQuery(document).ready(function () {

        jQuery(".dropdown").hover(
                function () {
                    jQuery('.dropdown-menu', this).stop(true, true).slideDown("fast");
                    jQuery(this).toggleClass('open');
                },
                function () {
                    jQuery('.dropdown-menu', this).stop(true, true).slideUp("fast");
                    jQuery(this).toggleClass('open');
                }
        );


        jQuery(".show_content").click(function () {
            jQuery(this).parent().next('.toogle_botom').toggle();
        });

// Window load event used just in case window height is dependant upon images
        if (jQuery(window).width() > 991) {

            jQuery(window).bind("load", function () {

                var footerHeight = 0,
                        footerTop = 0,
                        jQueryfooter = jQuery("#footer_sections");

                positionFooter();

                function positionFooter() {

                    footerHeight = jQueryfooter.height();
                    jQueryTop = (jQuery(window).scrollTop() + jQuery(window).height() - footerHeight) + "px";

                    if ((jQuery(document.body).height() + footerHeight) < jQuery(window).height()) {
                        jQueryfooter.css({
                            position: "absolute"
                        })
                    } else {
                        jQueryfooter.css({
                            position: "static"
                        }).animate({
                            bottom: footerTop
                        })
                    }

                }

                jQuery(window)
                        .scroll(positionFooter)
                        .resize(positionFooter)

            });
        }
<!--end footer sticky-->

    });
</script>




<script type="text/javascript">
    jQuery('#send').on('click', function () {
        jQuery('#chkname,#chkemail,#chkmessage').html('');
        var i = 0;
        var j = 0;
        if (jQuery('#username').val() == '')
        {
            jQuery('#chkname').html('Enter name!');
            i = 1;
        } else
        {
            jQuery('#chkname').html('');
            i = 0;
        }

        if (validateEmail(jQuery('#useremail').val())) {
            jQuery('#chkemail').html('');
            i = 0;
            j = 0;
        }
        else
        {
            jQuery('#chkemail').html('Enter valid email address!');
            i = 1;
            j = 1;
        }
        if (jQuery('#usermessgae').val() == '')
        {
            jQuery('#chkmessage').html('Enter message!');
            return false;
            i = 1;
            j = 1;
        } else
        {
            jQuery('#chkmessage').html('');
            var msg = jQuery('#usermessgae').val();
            i = 0;
        }
        if (i == 1)
        {
            return false;
        }
        if ((i == 0) && (j == 0))
        {
            var csrfToken = jQuery('meta[name="csrf-token"]').attr("content");
            jQuery.ajax({
                type: 'POST',
                url: "<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/contact'); ?>",
                //type: 'post',
                data: {username: jQuery("#username").val(), useremail: jQuery("#useremail").val(), msg: jQuery("#usermessgae").val(), csrf: csrfToken},
                success: function (data) {
                    if (data == 1)
                    {
                        jQuery('#login_success').modal('show');
                        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span style="margin-top: 5px; width: 320px; float: left; text-align: left;">Contact enquiry information send successfully!</span>');
                        window.setTimeout(function () {
                            location.reload()
                        }, 2000)
                    }
                }
            });

            return true;
        }

    });


    /*		function validateEmail(mail)   
     {  
     if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(form1.useremail.value))  
     {  
     return (1);  
     }  
     
     return (0);  
     }  
     */
    /*function validateEmail(email) {
     var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
     return re.test(email);
     }*/
</script> 



