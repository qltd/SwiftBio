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

$(document).ready(function(){

    $('select').select2({
        minimumResultsForSearch: Infinity,
        placeholder: "SELECT YOUR APPLICATION"
    });

    $('.expander').click(function(){
        $(this).parent().toggleClass('open');
        $(this).find('i').toggleClass('fa-minus-circle');
        return false;
    });

    $('.lightbox-video').magnificPopup({
  type:'iframe',
});


    $('#applications').on('change', function () {
      var url = $(this).val(); // get selected value
      if (url) { // require a URL
          window.location = url; // redirect
      }
      return false;
  });

});


