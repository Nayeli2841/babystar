<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Guarderías BabyStar - Guarderia muy cerca de ti</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="asset/css/style.css" rel="stylesheet">
    <link href="asset/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="asset/css/font-awesome.css" rel="stylesheet">
    <link href="asset/css/build.css" rel="stylesheet">

    <script src="asset/js/jquery.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/scripts.js"></script>
    <script src="asset/js/bootstrap-select.js"></script>
    <script type="text/javascript">
      function changeReferBy(curValue)
      {
        if(curValue == 'other')
        {
          $('#refer_to_other').fadeIn();
        }
        else
        {
          $('#refer_to_other').fadeOut();          
        }
      }

      function changebranch(curValue)
      {
        if(curValue == 'other')
        {
          $('#branch_office_other').fadeIn();
        }
        else
        {
          $('#branch_office_other').fadeOut();          
        }
      }



      $( document ).ready(function() {
        // Handler for .ready() called.
      });

      function validateEmail(email) {
          var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
          return re.test(email);
      }

      function validation()
      {
      	var name 			= $.trim($('#parent_name').val());
      	var child_name 		= $.trim($('#child_name').val());
      	var years 			= $.trim($('#years').val());
      	var months 			= $.trim($('#months').val());
      	var days 			= $.trim($('#days').val());
      	var branch_office 	= $.trim($('#branch_office').val());
        var branch_office_other  = $.trim($('#branch_office_other').val());
      	var start_time 		= $.trim($('#start_time').val());
      	var end_time 		= $.trim($('#end_time').val());
      	var email 			= $.trim($('#email').val());
      	var phone 			= $.trim($('#phone').val());
      	var refer_to 		= $.trim($('#refer_to').val());
        var refer_to_other = $.trim($('#refer_to_other').val());
        var other_service = $.trim($('#other_service').val());

        // populate services
        var services = [];
        $("input:checkbox[class=services]:checked").each(function()
        {
            var service = $(this).val();
            services.push(service);
        });

        if(other_service != '')
          services.push(other_service);
  
      	var check = true;

      	if(name == '')
      	{
      		$('#parent_name').focus();
      		$('#parent_name').addClass('has-error');
      		check = false;
      	}

      	if(child_name == '')
      	{
      		$('#child_name').addClass('has-error');
          if(check)
            $('#child_name').focus();
      		check = false;
      	}

      	if(years == '' && months == '' && days == '')
      	{
      		$('#age_div').addClass('has-error');
          if(check)
            $('#years').focus();
      		check = false;
      	}

      	if(branch_office == '')
      	{
      		$('#branch_office').addClass('has-error');
          if(check)
            $('#branch_office').focus();
      		check = false;
      	}
        else if(branch_office == 'other' && branch_office_other == '')
        {
          $('#branch_office_other').focus();
          $('#branch_office_other').addClass('has-error');
          if(check)
            $('#branch_office_other').focus();
          check = false;
        }

      	if(start_time == '')
      	{
      		$('#start_time').addClass('has-error');
          if(check)
            $('#start_time').focus();
      		check = false;
      	}

      	if(end_time == '')
      	{
      		$('#end_time').addClass('has-error');
          if(check)
            $('#end_time').focus();
      		check = false;
      	}

      	if(email == '')
      	{
      		$('#email').addClass('has-error');
          if(check)
            $('#email').focus();
      		check = false;
      	}
        else
        {
          if(!validateEmail(email))
          {
            $('#email').addClass('has-error');
            if(check)
              $('#email').focus();
            check = false;            
          }
        }

      	if(phone == '')
      	{
      		$('#phone').addClass('has-error');
          if(check)  
            $('#phone').focus();
      		check = false;
      	}

      	if(refer_to == '')
      	{
      		$('#refer_to').addClass('has-error');
          if(check)  
            $('#refer_to').focus();
      		check = false;
      	}
      	else if(refer_to == 'other' && refer_to_other == '')
        {
          $('#refer_to_other').addClass('has-error');
          if(check)  
            $('#refer_to_other').focus();
          check = false;
        }




        if(check)
        {
          $.ajax({
              type: 'post',
              url: 'api/query',
              dataType : "JSON",
              data: {parent_name:name, child_name:child_name, years:years, months:months, days:days, branch_office:branch_office, branch_office_other:branch_office_other, start_time:start_time, end_time:end_time, phone:phone, email:email, refer_by:refer_to, refery_by_other: refer_to_other, services:services},
              beforeSend:function(){

              },
              success:function(data){
                showMsg('#jobmsg', 'Query deleted successfully.', 'green');
                getQueries();
              },
              error:function(jqxhr){
              }
            });
        }

      }  
    </script>

<script>
  
  function getUserData() {
	FB.api('/me?fields=name,email', function(response) {
    console.log(response);
//    $('#status').append('<a href="" onclick="FB.logout();">Logout</a> ');
    $('#parent_name').val(response.name);
    $('#email').val(response.email);
	});
}
 
window.fbAsyncInit = function() {
	//SDK loaded, initialize it
	FB.init({
		appId      : '793643427414904',
		xfbml      : true,
		version    : 'v2.4'
	});
 
	//check user session and refresh it
	FB.getLoginStatus(function(response) {
		if (response.status === 'connected') {
			//user is authorized
			document.getElementById('loginBtn').style.display = 'none';
			getUserData();
		} else {
			//user is not authorized
		}
	});
};
 
//load the JavaScript SDK
(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = document.location.protocol +"//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
 
//add event listener to login button
function checkLoginState(){
	//do the login
	FB.login(function(response) {
    console.log(response);
		if (response.authResponse) {
			//user just authorized your app
			document.getElementById('loginBtn').style.display = 'none';
			getUserData();
		}
	}, {scope: 'email,public_profile', return_scopes: true});
}
 
</script>




  </head>
  <body class="body_bg">

    <div class="container ">
	<div class="row">
		<div class="col-md-8 center_div">
			<form class="form-horizontal" role="form">
        <div class="form-group">
          <img src="asset/images/logo.gif" class="form_logo"> 
        </div>

        <div class="form-group">
           
          <label for="name" class="col-sm-4 control-label">

          </label>
          <div class="col-sm-4">
          <a class="btn btn-primary" id="loginBtn" onClick="checkLoginState();"><i class="fa fa-facebook"></i> Fill information using Facebook</a>
          </div>
        

        </div>
          <div id="status"></div>



				<div class="form-group">
					 
					<label for="name" class="col-sm-4 control-label">
						Name
					</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="parent_name" onkeyup="$(this).removeClass('has-error');">
					</div>
				</div>

				<div class="form-group">
					 
					<label for="childName" class="col-sm-4 control-label">
						Name your child
					</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="child_name" onkeyup="$(this).removeClass('has-error');">
					</div>
				</div>

					<div class="form-group" id="age_div">
					 
					<label for="age" class="col-sm-4 control-label">
						Age
					</label>
					<div class="col-sm-2">
<!-- 					<label for="age" class="col-sm-2 control-label">
						Years
					</label> -->
					<select class="selectpicker" data-width="100%" id="years" onchange="$('#age_div').removeClass('has-error');">
               <option value="">Years</option>
   						 <option value="0">0</option>
   						 <option value="1">1</option>
   						 <option value="2">2</option>
   						 <option value="3">3</option>
  					</select>
  					</div>
  					<div class="col-sm-2">
<!--   					<label for="age" class="col-sm-2 control-label">
						Months
					</label> -->
					<select class="selectpicker" id="months"  data-width="100%" onchange="$('#age_div').removeClass('has-error');">
               <option value="">Months</option>
   						 <option value="0">0</option>
   						 <option value="1">1</option>
   						 <option value="2">2</option>
   						 <option value="3">3</option>
   						 <option value="4">4</option>
   						 <option value="5">5</option>
   						 <option value="6">6</option>
   						 <option value="7">7</option>
   						 <option value="8">8</option>
   						 <option value="9">9</option>
   						 <option value="10">10</option>
   						 <option value="11">eleven</option>
  					</select>
  					</div>
  					<div class="col-sm-2">
<!--   					<label for="age" class="col-sm-2 control-label">
						Days
					</label> -->
  					<select class="selectpicker" id="days" data-width="100%" onchange="$('#age_div').removeClass('has-error');">
               <option value="">Days</option>
   						 <option value="0">0</option>
   						 <option value="1">1</option>
   						 <option value="2">2</option>
   						 <option value="3">3</option>
   						 <option value="4">4</option>
   						 <option value="5">5</option>
   						 <option value="6">6</option>
   						 <option value="7">7</option>
   						 <option value="8">8</option>
   						 <option value="9">9</option>
   						 <option value="10">10</option>
   						 <option value="11">eleven</option>
   						 <option value="12">12</option>
   						 <option value="13">13</option>
   						 <option value="14">14</option>
   						 <option value="15">fifteen</option>
   						 <option value="16">16</option>
   						 <option value="17">17</option>
   						 <option value="18">18</option>
   						 <option value="19">19</option>
   						 <option value="20">20</option>
   						 <option value="21">21</option>
   						 <option value="22">22</option>
   						 <option value="23">23</option>
   						 <option value="24">24</option>
   						 <option value="25">25</option>
   						 <option value="26">26</option>
   						 <option value="27">27</option>
   						 <option value="28">28</option>
   						 <option value="29">29</option>
   						 <option value="30">30</option>
  					</select>
  					</div>
				</div>
				
				<div class="form-group">
					 
					<label for="branch_office" class="col-sm-4 control-label">
						Branch Office
					</label>
					<div class="col-sm-6">
						<select class="selectpicker" id="branch_office" onchange="changebranch(this.value);">
   						 <option value="Escandon">Escandon</option>
   						 <option value="San Jeronimo">San Jeronimo</option>
   						 <option value="San Angel">San Angel</option>
   						 <option value="other">Testify in area - specify</option>
  					</select>
            <br><br>
  					<input type="text" class="form-control" id="branch_office_other" placeholder="Other" style="display:none;" onkeyup="$(this).removeClass('has-error');">
					</div>
				</div>				

					<div class="form-group">
					 
					<label for="hours" class="col-sm-4 control-label">
						Required Hours
					</label>
					<div class="col-sm-5">
					<label for="start_time" class="col-sm-6 control-label" style="text-align:left; width:60px;">
						From
					</label>
					<select class="selectpicker" id="start_time" data-width="25%">
   						 <option value="7">07:00 hrs</option>
   						 <option value="8">08:00 hrs</option>
   						 <option value="9">09:00 hrs</option>
   						 <option value="10">10:00 hrs</option>
   						 <option value="11">11:00 hrs</option>
   						 <option value="12">12:00 hrs</option>
   						 <option value="13">13:00 hrs</option>
   						 <option value="14">14:00 hrs</option>
   						 <option value="15">15:00 hrs</option>
   						 <option value="16">16:00 hrs</option>
   						 <option value="17">17:00 hrs</option>
   						 <option value="18">18:00 hrs</option>
  					</select>
            <b>To</b>
               
               <select class="selectpicker" id="end_time" data-width="25%">
               <option value="8">08:00 hrs</option>
               <option value="9">09:00 hrs</option>
               <option value="10">10:00 hrs</option>
               <option value="11">11:00 hrs</option>
               <option value="12">12:00 hrs</option>
               <option value="13">13:00 hrs</option>
               <option value="14">14:00 hrs</option>
               <option value="15">15:00 hrs</option>
               <option value="16">16:00 hrs</option>
               <option value="17">17:00 hrs</option>
               <option value="18">18:00 hrs</option>
               <option value="19">19:00 hrs</option>
            </select>
  					</div>
<!-- 					<div class="col-sm-3">
  					<label for="end_time" class="col-sm-2 control-label">
						at
					</label>
  					<select class="selectpicker" id="end_time">
   						 <option value="8">08:00 hrs</option>
   						 <option value="9">09:00 hrs</option>
   						 <option value="10">10:00 hrs</option>
   						 <option value="11">11:00 hrs</option>
   						 <option value="12">12:00 hrs</option>
   						 <option value="13">13:00 hrs</option>
   						 <option value="14">14:00 hrs</option>
   						 <option value="15">15:00 hrs</option>
   						 <option value="16">16:00 hrs</option>
   						 <option value="17">17:00 hrs</option>
   						 <option value="18">18:00 hrs</option>
  						 <option value="19">19:00 hrs</option>
  					</select>
  					</div> -->
				</div>

				<div class="form-group">
					 
					<label for="inputEmail3" class="col-sm-4 control-label">
						Email
					</label>
					<div class="col-sm-6">
						<input type="email" class="form-control" id="email" onkeyup="$(this).removeClass('has-error');">
					</div>
				</div>
				<div class="form-group">
					 
					<label for="phone" class="col-sm-4 control-label">
						Phone
					</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="phone" onkeyup="$(this).removeClass('has-error');">
					</div>
				</div>

				<div class="form-group">
					 
					<label for="services" class="col-sm-4 control-label">
						What services interest you 
					</label>
					<div class="col-sm-6">
          <div class="checkbox checkbox-primary">
              <input id="checkbox1" class="services" type="checkbox" value="Web Cameras">
              <label for="checkbox1">
                  Web Cameras
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox2" class="services" type="checkbox" value="Early Stimulation">
              <label for="checkbox2">
                  Early Stimulation
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox3" class="services" type="checkbox" value="English">
              <label for="checkbox3">
                  English
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox4" type="checkbox" value="Kindergarten">
              <label for="checkbox4">
                  Kindergarten
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox5" class="services" type="checkbox" value="Nursery Express">
              <label for="checkbox5">
                  Nursery Express
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox6" class="services" type="checkbox">
              <label for="checkbox6">
                  Infants
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox7" class="services" type="checkbox">
              <label for="checkbox7">
                  Maternal
              </label>
          </div>

<!-- 					<label for="other_services" class="col-sm-2 control-label">
						Other 
					</label> -->
						<input type="text" class="form-control" id="other_service" placeholder="other" onkeyup="$(this).removeClass('has-error');">
					</div>
				</div>

				<div class="form-group">
					 
					<label for="branch_office" class="col-sm-4 control-label">
						How did he find out about us
					</label>
					<div class="col-sm-6">
						<select class="selectpicker" id="refer_to" onChange="changeReferBy(this.value);">
   						 <option value="recommendation">Recommendation</option>
   						 <option value="google">Google</option>
   						 <option value="bing">Bing</option>
   						 <option value="youtube">Youtube</option>
   						 <option value="facebook">Facebook</option>
   						 <option value="external advertising">External advertising</option>
   						 <option value="other">Other - specify</option>
  					</select>
            <br><br>
  					<input type="text" style="display:none; margin-top:15px;" class="form-control" id="refer_to_other" placeholder="other" onkeyup="$(this).removeClass('has-error');">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-10">
						 
						<button type="button" class="btn btn-primary" onClick="validation();">
							Download Brochure
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

   
  </body>
</html>