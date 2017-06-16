 // $(function(){
 //    $('.nav-toggle, .nav-close, .bg-overlay').click(function(){
 //        $('.bg-overlay, .nav-overlay').toggleClass('show');
 //    })
 // })

function toggleNav(){
    var bgOverlay = document.getElementsByClassName('bg-overlay'),
         navOverlay = document.getElementsByClassName('nav-overlay');

    bgOverlay[0].classList.toggle('show');
    navOverlay[0].classList.toggle('show');
}

var navToggle = document.getElementsByClassName('nav-toggle'),
    navClose = document.getElementsByClassName('nav-close'),
    bgOverlay = document.getElementsByClassName('bg-overlay');

navToggle[0].addEventListener("click", function(){ toggleNav() });
navClose[0].addEventListener("click", function(){ toggleNav() });
bgOverlay[0].addEventListener("click", function(){ toggleNav() });

/* Apply multiple Recaptcha's */
// function CaptchaCallback() {
//     jQuery('div.g-recaptcha').each(function(){
//         var key = jQuery(this).data('sitekey');
//         grecaptcha.render(jQuery(this)[0], {'sitekey' : key});
//     });
// };

    function recaptchaCallback() {
      $('#hiddenRecaptcha').valid();
    };

(function($){

    $('#registerform').validate();
    $('#loginform').validate();

    if (window.location.href.indexOf("success") > -1){
        $("input[name=retURL]").each(function(){
           $(this).val(window.location);
        });
    } else if ($('body').hasClass('page-product-support')){
        $("input[name=retURL]").each(function(){
            $(this).val(document.location.protocol +"//"+ document.location.hostname + document.location.pathname + '?success=true');
        });
    } else {
        $("input[name=retURL]").each(function(){
            $(this).val(window.location + '?success=true');
        });
    }


    $("#sf-form, #shipping-form, #library-form, #data-analysis-form").each(function(){
        $(this).validate({
            ignore: ".ignore",
            messages: {
               "hiddenRecaptcha": {
                    required: "Verify you are a human."
               },
               "g-recaptcha-hidden": {
                    required: "Verify you are a human."
               }
            },
            rules: {
                "hiddenRecaptcha": {
                     required: function() {
                         if(grecaptcha.getResponse() == '') {
                             return true;
                         } else {
                             return false;
                         }
                     }
                },
                "g-recaptcha-hidden": {
                     required: function() {
                         if(grecaptcha.getResponse() == '') {
                             return true;
                         } else {
                             return false;
                         }
                     }
                }
            }
        });
    });


    $("#registerform").validate({
        submitHandler: function(form){
             sfForm = $(form);

            $.ajax({
              type: "POST",
              url: "/wp-content/themes/SwiftBio/template-parts/salesforce-recaptcha.php",
              data: sfForm.serialize(),
              success: function(data) {
                if (data){
                    // show success message
                    sfForm.html('<div id="form-message">Thank you for your inquiry.  A representative from Swift Biosciences will be in contact with you shortly regarding your inquiry.</div>');
                    $('html, body').animate({
                        scrollTop: $("#form-message").offset().top
                    }, 0);
                } else {
                     sfForm.find('.g-recaptcha').before('<div id="form-message">ERROR: Please verify you are human.</div>');
                    grecaptcha.reset();
                }
              }
            })
        }
    });


/* Open PDF's in new window */
$('a[href$=".pdf"]').prop('target', '_blank');

    // Google Analytics Event on Social Sharing Links
    $('.social-share a').on('click', function() {
        var network = $(this).data('network');
        var href = $(this).attr('href');
        ga('send', 'social', {
            'socialNetwork': network,
            'socialAction': 'share',
            'socialTarget': window.location,
            'hitCallback': function(){
              //window.open(href, '_blank')
            }
        });
    });

    $('select.applications').select2({
        minimumResultsForSearch: Infinity,
        placeholder: "SELECT YOUR APPLICATION"
    });

    $('div:not(.woocommerce) > form select').select2();


    if(location.hash) {

        $(location.hash).addClass('open');
        var parent = $(location.hash).parents('.expand-collapse');
        $(parent).addClass('open');
        $(location.hash).find('i').toggleClass('fa-minus-circle');
        $('body').scrollTop($(location.hash).offset().top);
        setTimeout(function() {
            $('body').scrollTop($(location.hash).offset().top);
        }, 1500)
    }

    $('.expander, .site-footer nav>ul>li>a').click(function(){
        $(this).parent().toggleClass('open');
        $(this).find('i').toggleClass('fa-minus-circle');
        // if ($(this).parent('.main').lenth){
        //     location.hash = $(this).parent().attr('id');
        // }
         location.hash = $(this).parent().attr('id');
        return false;
    });

    $('.lightbox-video').magnificPopup({
      type:'iframe'
    });

    if ($(window).width() <= 1129){
        setTimeout(function() {
            $('div.woocommerce > form input[name="update_cart"]').prop("disabled",false);
        }, 1500)
    }

    //prevent quantities higher than 999
    $('.input-text.qty').on('keyup change', function(){
        var qty = $(this).val();
        if (qty > 999){
           var qty = 999
        }
        var id = $(this).data('product');
        $('a[data-product_id=' + id + ']').data('quantity', qty);
        $('div.woocommerce > form input[name="update_cart"]').prop("disabled",false);
    });



    $('#applications').on('change', function () {
      var url = $(this).val(); // get selected value
      if (url) { // require a URL
          window.location = url; // redirect
      }
      return false;
  });

// $('.parent-check input').click(function(){
//     var child = $(this).parent().next('.child-check');
//     child.toggleClass('active');
//     child.find('input').removeAttr('checked');
// });

$(".child-check input:checkbox").click(function(){
    // uncheck all siblings
    $(this).closest('.child-check').find('input:checkbox').not($(this)).removeAttr('checked');
});

$('#00N44000005ysA8').change(function(){
    if ($(this).val() == 'Other'){
        $('.other-text').toggle();
    }
});


})(jQuery);


