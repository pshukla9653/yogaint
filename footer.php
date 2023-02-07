    <!-- =============== 1.9 Footer Area Start ====================-->
    <footer>
      <p>Copyright &copy; 2022 All Rights Reserved.</p>
    </footer>
    <!-- <div class="popup">
    
    </div> -->
    
    <script src="https://newyearpackage.co.in/js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.3/dist/index.bundle.min.js"></script>
    <script src="js/my_script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.js"></script> 
    <script>
      $(document).ready(function(){
if($('.brands_slider').length)
     {
         var brandsSlider = $('.brands_slider');
         brandsSlider.owlCarousel(
         {
             loop:true,
             autoplay:true,
             autoplayTimeout:5000,
             nav:false,
             dots:false,
             autoWidth:true,
             items:8,
             margin:42
         });
         if($('.brands_prev').length)
         {
             var prev = $('.brands_prev');
             prev.on('click', function()
             {
                 brandsSlider.trigger('prev.owl.carousel');
             });
         }
         if($('.brands_next').length)
         {
             var next = $('.brands_next');
             next.on('click', function()
             {
                 brandsSlider.trigger('next.owl.carousel');
             });
         }
     }
 });
      $("#interest_id").change(function(){
        var interest_id = $(this).val();
        
        if(interest_id){
            $.ajax({
                type:'POST',
                url:'ascript/',
                data:'interest_id='+interest_id,
                dataType: 'json',
                success:function(rep){
                  console.log(rep);
                  if(rep.date1 == 0){
                    $('#msg1').html('<span class="text-danger" style="margin:0px !important;">No Seats available</span>');
                    $("#date_1").prop("disabled", true);
                  }
                  else{
                    $('#msg1').html('<span class="text-success" style="margin:0px !important;">'+ rep.date1 +' Seats available</span>');
                    $("#date_1").prop("disabled", false);
                  }
                  //two
                  if(rep.date2 == 0){
                    $('#msg2').html('<span class="text-danger" style="margin:0px !important;">No Seats available</span>');
                    $("#date_2").prop("disabled", true);
                  }
                  else{
                    $('#msg2').html('<span class="text-success" style="margin:0px !important;">'+ rep.date2 +' Seats available</span>');
                    $("#date_2").prop("disabled", false);
                  }
                  //three
                  if(rep.date3 == 0){
                    $('#msg3').html('<span class="text-danger" style="margin:0px !important;">No Seats available</span>');
                    $("#date_3").prop("disabled", true);
                  }
                  else{
                    $('#msg3').html('<span class="text-success" style="margin:0px !important;">'+ rep.date3 +' Seats available</span>');
                    $("#date_3").prop("disabled", false);
                  }
                  // four
                  if(rep.date4 == 0){
                    $('#msg4').html('<span class="text-danger" style="margin:0px !important;">No Seats available</span>');
                    $("#date_4").prop("disabled", true);
                  }
                  else{
                    $('#msg4').html('<span class="text-success" style="margin:0px !important;">'+ rep.date4 +' Seats available</span>');
                    $("#date_4").prop("disabled", false);
                  }
                  // five
                  if(rep.date5 == 0){
                    $('#msg5').html('<span class="text-danger" style="margin:0px !important;">No Seats available</span>');
                    $("#date_5").prop("disabled", true);
                  }
                  else{
                    $('#msg5').html('<span class="text-success" style="margin:0px !important;">'+ rep.date5 +' Seats available</span>');
                    $("#date_5").prop("disabled", false);
                  }
                  //six
                  if(rep.date6 == 0){
                    $('#msg6').html('<span class="text-danger" style="margin:0px !important;">No Seats available</span>');
                    $("#date_6").prop("disabled", true);
                  }
                  else{
                    $('#msg6').html('<span class="text-success" style="margin:0px !important;">'+ rep.date6 +' Seats available</span>');
                    $("#date_6").prop("disabled", false);
                  }
                  //seven
                  if(rep.date7 == 0){
                    $('#msg7').html('<span class="text-danger" style="margin:0px !important;">No Seats available</span>');
                    $("#date_7").prop("disabled", true);
                  }
                  else{
                    $('#msg7').html('<span class="text-success" style="margin:0px !important;">'+ rep.date7 +' Seats available</span>');
                    $("#date_7").prop("disabled", false);
                  }
                }
				
            }); 
        }
        else{
                    $('#msg1').html('');
                    $("#date_1").prop("disabled", false);
                    //two
                    $('#msg2').html('');
                    $("#date_2").prop("disabled", false);
                    //three
                    $('#msg3').html('');
                    $("#date_3").prop("disabled", false);
                    //
                    $('#msg4').html('');
                    $("#date_4").prop("disabled", false);
                    //five
                    $('#msg5').html('');
                    $("#date_5").prop("disabled", false);
                    //six
                    $('#msg6').html('');
                    $("#date_6").prop("disabled", false);
                    //seven
                    $('#msg7').html('');
                    $("#date_7").prop("disabled", false);
        }
    });
    </script>          
    <script>
    // Set the date we're counting down to
    var countDownDate = new Date("Feb 28, 2023 23:59:00").getTime();
    // Update the count down every 1 second
    var x = setInterval(function() {
      // Get today's date and time
      var now = new Date().getTime();
      // Find the distance between now and the count down date
      var distance = countDownDate - now;
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      // Output the result in an element with id="demo"
      document.getElementById("demo").innerHTML = days + "d |" + hours + "h |" +
        minutes + "m |" + seconds + "s ";
      // document.getElementById("demo").innerHTML = 0 + "d |" + 0 + "h |" +
      //   0 + "m |" + 0 + "s ";
      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "EXPIRED";
      }
    }, 1000);
    </script>
  </body>
</html>