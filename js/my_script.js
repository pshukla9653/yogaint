function showerrorpro(mydiv, erromsg) {
  $(mydiv).css({ border: '', color: '#222', background: '#f1f1f1' });
  // $("html, body").animate({ scrollTop: $(mydiv).offset().top - 150 }, 500);
  $(mydiv).focus();
  if (erromsg !== '') {
    $(mydiv).after('<span class="show-error-msg">' + erromsg + '</span>');
  }
}

function showsuccesspro(mydiv) {
  $(mydiv).css({ border: '1px solid #D7D7D7', background: '#e9eaec', color: '#222' });
  $('.show-error-msg').remove();
}

$(window).scroll(function() {
  var aTop = "100";
  if ($(this).scrollTop() >= aTop) {
      $('#top-link-block').addClass('affix');
      $('header').addClass('fixed');
  } else {
      $('#top-link-block').removeClass('affix');
      $('header').removeClass('fixed');
  }
});
var swiper = new Swiper(".mySwiper", {
  cssMode: true,
  autoplay: {
    delay: 4000,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  mousewheel: true,
  keyboard: true,
});


function ValidateEmail(email) {
  var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  return expr.test(email);
}
$(document).ready(function () {
  $('.cl_submit').click(function () {
    
    var name = $('.name').val();
    var age = $('.age').val();
    var gender = $('.gender').val();
    var nation = $('.nation').val();
    var email = $('.email').val();
    
    var count_code = $('.count_code').val();
    var phone = $('.phone').val();
    var check = $("input[type=checkbox]:checked");
    var interest = $(".interest").val();
    if (name === "") {
      alert('Name is required  ');
      showerrorpro(".name", '');
      return false;
    } else {
      showsuccesspro(".name");
    }
    if (age === "") {
      alert('Age is required  ');
      showerrorpro(".age", '');
      return false;
    } else {
      showsuccesspro(".age");
    }
    if (gender === "") {
      alert('Please select Gender  ');
      showerrorpro(".gender", '');
      return false;
    } else {
      showsuccesspro(".gender");
    }
    if (nation === "") {
      alert('Please select Nation ');
      showerrorpro(".nation", '');
      return false;
    } else {
      showsuccesspro(".nation");
    }
    if (!ValidateEmail(email)) {
      alert('Please enter valid email ID ');
      showerrorpro(".email", '');
      return false;
    } else {
      showsuccesspro(".email");
    }

    if (phone.length < 10) {
      alert('Please enter valid phone number ');
      showerrorpro(".phone", '');
      return false;
    } else {
      showsuccesspro(".phone");
    }
    if(check.length > 0){
      $(".sele").css({border:'1px solid #D7D7D7', background:'#e9eaec', color:'#222'});
  
      }else{
      alert('Please select date ');
      $(".sele").css({border:'1px solid #ff0000', background:'#ffdbdb', color:'#222'}); 
      $("#check").focus();
      return false;
      }


    if (interest === "") {
      alert('Please select School ');
      showerrorpro(".interest", '');
      return false;
    } else {
      showsuccesspro(".interest");
    }

    
  });

  
  $("nav ul li a").click(function() {

    Height = 0;
    var Schrol = $(this).attr('data-id');
    var Screen = "";
    Height = $("header").hasClass("fixed") ? 200 : 200;
    $('html, body').animate({
        scrollTop: $("." + Schrol + Screen).offset().top - Height
    }, 1000);
    $(".nav").toggleClass("mobile-nav");
    $('.menu-toggle').toggleClass("is-active");
});

});