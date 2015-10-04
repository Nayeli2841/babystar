var sortBy = '';
var sortOrder = '';
var server = window.location.hostname;
var webUrl = location.protocol + "//"+server+"/romeo_interiors/"
if(server == 'localhost')
{
  var apiUrl = location.protocol + "//"+server+"/romeo_interiors/api/";
}
else
  var apiUrl = location.protocol + "//"+server+"/romeo_interiors/api/";


function showMsg(id, msg, type)
{
    $(id).html(msg).addClass(type).slideDown('fast').delay(2500).slideUp(1000,function(){$(id).removeClass(type)}); 
}

function login()
{
    var email = $.trim($('#email').val());
    var password = $.trim($('#password').val());    
    var check = true;

    if(email == '')
    {
        $('#email').focus();
        $('#email').addClass('error-class');
        check = false;
    }

    if(password == '')
    {
        $('#password').focus();
        $('#password').addClass('error-class');
        check = false;
    }

    if(check)
    {
        $('#spinner').show();
        $.ajax({
          type: 'POST',
          url: apiUrl + 'login',
          dataType : "JSON",
          data: { username: email, password: password },
          beforeSend:function(){

          },
          success:function(data){
            $('#spinner').hide();
            if(data.status == 'success')
            {
                showMsg('#msg', 'Successfully Logged In. Redirecting ...', 'green')
                window.location = 'project_categories.php';
            }
          },
          error:function(jqxhr){
            $('#spinner').hide();            
            showMsg('#msg', 'Wrong credentials. Try Again', 'red')
          }
        });

    }
}

function logout()
{
    $.ajax({
      type: 'GET',
      url: apiUrl + 'logout',
      dataType : "JSON",
      data: {},
      beforeSend:function(){

      },
      success:function(data){
        window.location = 'login.php';
      },
      error:function(jqxhr){
      }
    });
}


function sortbyFunc(sort, sortbystr, module, status)
{
  sortBy = sortbystr;
  obj = '.'+sortbystr;
  if(sort == 'all')
  {
    sortOrder = 'asc';
    $('.fa-sort').show();
    $('.fa-sort-asc').hide();
    $('.fa-sort-desc').hide();
    $(obj+'all').hide();
    $(obj+'desc').hide();
    $(obj+'asc').show();
  }
  else if(sort == 'asc')
  {
    sortOrder = 'desc';
    $('.fa-sort').show();
    $(obj+'all').hide();
    $(obj+'asc').hide();
    $(obj+'desc').show();
  }
  else if(sort == 'desc')
  {
    sortOrder = 'asc';
    $('.fa-sort').show();
    $(obj+'all').hide();
    $(obj+'desc').hide();
    $(obj+'asc').show();
  }

  if(module == 'vendors')
    getAllVendors(status)
  else if(module == 'events')
    getAllEvents(status)
  else if(module == 'deals')
    getDeals(status, 1);
  else if(module == 'promo')
    getPromoVendors(status , 1);
  else if(module == 'subscriber')
    getSubscribers(status);  
}

function removeElm(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

function adminData()
{
  $.ajax({
           type: "GET",
           url: apiUrl + 'admindata',
           dataType : "JSON",
           beforeSend:function(){


           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {

                $('#name').val(data.data.name);
                $('#email').val(data.data.username);
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });
}

function updateAdminData()
{
  var name = $('#name').val();
  var email = $('#email').val();

  var check = true;

   if(name == '')
     {
         $('#name').focus();
         $('#name').addClass('error-class');
         check = false;
     }
     else if(email == '')
     {
         $('#email').focus();
         $('#email').addClass('error-class');
         check = false;
     }

     if(check)
     {
        $.ajax({
           type: "POST",
           url: apiUrl + 'editadmindata',
           dataType : "JSON",
           data:{name:name,email:email},
           beforeSend:function(){


           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {
                showMsg('#msg', 'Profile updated successfully.', 'green');
                adminData();
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });
     }
}

function confirmPassword()
{
  var password = $('#password').val();
  var confirm_password = $('#confirm_password').val();
  var check = true;

  if(password == '')
     {
         $('#password').focus();
         $('#password').addClass('error-class');
         check = false;
     }
     else if(confirm_password == '')
     {
         $('#confirm_password').focus();
         $('#confirm_password').addClass('error-class');
         check = false;
     }

     if(password == confirm_password)
     {
        check = true;
        $.ajax({
           type: "POST",
           url: apiUrl + 'editadminpassword',
           dataType : "JSON",
           data:{password:password},
           beforeSend:function(){

           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {
              showMsg('#msg', 'Password updated successfully.', 'green');
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });
     }
     else
     {
         $('#password').focus();
         $('#password').addClass('error-class');
         $('#confirm_password').focus();
         $('#confirm_password').addClass('error-class');

         check = false;
     }


}

function forgotPassword()
{
    var email = $('#email').val();

    if(email == '')
    {
      $('#email').focus();
      $('#email').addClass('error-class');
      check = false;
    }

    $.ajax({
           type: "GET",
           url: apiUrl + 'forgotpassword',
           dataType : "JSON",
           data:{email:email},
           beforeSend:function(){

           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {
              showMsg('#msg', 'Email have been sent.', 'green');
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });

}

function resetPassword()
{
  var password = $('#password').val();
  var confirmPassword = $('#confirmpassword').val();
  var email = $('#email').val();
  var code = $('#code').val();
  var check = true;

  if(password == '')
  {
    $('#password').focus();
    $('#password').addClass('error-class');
    check = false;
  }
  if(confirmPassword == '')
  {
    $('#confirmpassword').focus();
    $('#confirmpassword').addClass('error-class');
    check = false;
  }
  else if(password != confirmPassword)
  {
    $('#password').focus();
    $('#password').addClass('error-class');
    $('#confirmpassword').focus();
    $('#confirmpassword').addClass('error-class');
    check = false;
  }
  else
  {
    $.ajax({
           type: "GET",
           url: apiUrl + 'resetpassword',
           dataType : "JSON",
           data:{email:email,code:code,password:password},
           beforeSend:function(){

           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {
              showMsg('#msg', 'Password updated successfully.', 'green');
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });
  }
}


/********************** Romeo Interiors Functions**************************/
function getTestimonials()
{
  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'testimonials',
      dataType : "JSON",
      data: {},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){
        var html = '';
        var options = '';
        if(data.data.length > 0)
        {       

            $.each(data.data, function( index, value ) {
              // if(value.status == 0)
              //   var status = '<i class="fa fa-times-circle"></i> ';
              // else
              //   var status = '<i class="fa fa-check-circle"></i> ';

                //options += '<option value="'+value.id+'">'+value.name+' </option>';
                html += '<tr>\
                            <td>'+value.testimonial+'</td>\
                            <td>'+value.client_name+'</td>\
                            <td>'+value.company_name+'</td>\
                            <td><img width="150" height="75" src="../data/testimonial/'+value.path+' "></td>\
                            <td><a href="javascript:void(0);" data-toggle="modal"  onclick="getSingleTestimonial('+value.id+');" data-target="#addtestimonial">Edit</a> | <a href="javascript:void(0);" onclick="deleteTestimonial('+value.id+');">Delete</a></td>\
                         </tr>';

            });            
        }
        else
        { 
            html += '<tr>\
                        <td colspan="4" align="center">Testimonials not found</td>\
                     </tr>';            
        }



        $('#testimonialbody').html(html);
       // $('#cat_id').append(options);

      },
      error:function(jqxhr){
      }
    });
}


function deleteTestimonial(id)
{
    $.ajax({
      type: 'POST',
      url: apiUrl + 'deletetestimonial',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },
      success:function(data){
        showMsg('#jobmsg', 'Testimonial deleted successfully.', 'green');
        getTestimonials();
      },
      error:function(jqxhr){
      }
    });
}

function getSingleTestimonial(id)
{
    $('#testimonial_id').val(id);

    testimonialReset();  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'singletestimonial',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },

      success:function(data){
        
        $('#testimonial_id').val(data.data.id);
        $('#client_name').val(data.data.client_name);
        $('#company_name').val(data.data.company_name);
        $('#testimonial').val(data.data.testimonial);
        $('#path').val(data.data.path);
        $('#temp_pic').attr('src', data.data.web_url);
        $('#mode').html('Edit');

   //var editor =     $('#testimonial').data("wysihtml5").editor

// editor.setValue(data.data[0].testimonial, true);

      },
      error:function(jqxhr){
      }
    });

}

function addUpdateTestimonial()
{
    var id            = $.trim($('#testimonial_id').val());
    var testimonial   = $.trim($('#testimonial').val());
    var client_name   = $.trim($('#client_name').val());
    var company_name  = $.trim($('#company_name').val());
    var path          = $.trim($('#path').val());
    var check         = true;

    if(testimonial == '')
    {
        $('#testimonial').focus();
        $('#testimonial').addClass('error-class');
        check = false;
    }
    if(client_name == '')
    {
        $('#client_name').focus();
        $('#client_name').addClass('error-class');
        check = false;
    }
    if(company_name == '')
    {
        $('#company_name').focus();
        $('#company_name').addClass('error-class');
        check = false;
    }
  if(path == '')
    {
        $('#image').focus();
        $('#image').addClass('error-class');
        check = false;
    }

    if(check)
    {
         if(id == '' || (typeof id == 'undefined'))
          {    
              $('#spinner').show();      
              $.ajax({
                type: 'POST',
                url: apiUrl + 'testimonial',
                dataType : "JSON",
                data: {testimonial:testimonial, client_name:client_name, company_name:company_name, path:path},
                beforeSend:function(){

                },
                success:function(data){
                $('#spinner').hide();   
                $('#addtestimonial').modal('hide');
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Testimonial added successfully.', 'green');                    
                      getTestimonials();
                      $('#addtestimonial').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#msg', 'Testimonial already exists with this name.', 'red');
                }
              });
            }
            else
            {
              var dataObj = {id:id, testimonial:testimonial,client_name:client_name, company_name:company_name, path:path};

                $('#spinner').show();      
              
              $.ajax({
                type: 'POST',
                url: apiUrl + 'edittestimonial',
                dataType : "JSON",
                data: dataObj,
                beforeSend:function(){

                },
                success:function(data){
                $('#addtestimonial').modal('hide');  
                $('#spinner').hide();      
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Testimonial updated successfully.', 'green');                    
                      getTestimonials();                
                      $('#addtestimonial').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#jobmsg', 'Testimonial already exists.', 'red');
                }
              });
            }
    }
}

function showAddTestimonialPopup()
{
    testimonialReset();
}

function testimonialReset()
{
    $('#testimonial_id').val('');
    $('#client_name').val('');
    $('#company_name').val('');
    $('#testimonial').val('');
    $('#path').val('');
    $('#temp_pic').attr('src', '../admin/images/logoplaceholder.png');
    $('#image').val('');
    $('#testimonial').removeClass('error-class');
    $('#client_name').removeClass('error-class');
    $('#company_name').removeClass('error-class');
    $('#path').removeClass('error-class');
    $('#temp_pic').removeClass('error-class');
    $('#image').removeClass('error-class');

}


function getProjectCategories()
{
  // if(edit)
  //   sync = false;
  // else
  //   sync = true;
    $.ajax({
      type: 'GET',
      url: apiUrl + 'projectcategories',
      dataType : "JSON",
      data: {},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){
        var html = '';
        var options = '';
        if(data.data.length > 0)
        {        

            $.each(data.data, function( index, value ) {
              // if(value.status == 0)
              //   var status = '<i class="fa fa-times-circle"></i> ';
              // else
              //   var status = '<i class="fa fa-check-circle"></i> ';

                //options += '<option value="'+value.id+'">'+value.name+' </option>';
                html += '<tr>\
                            <td>'+value.name+'</td>\
                            <td><a href="javascript:void(0);" data-toggle="modal"  onclick="getSingleProjectCategory('+value.id+');" data-target="#addprojectcat">Edit</a> | <a href="javascript:void(0);" onclick="deleteProjectCategory('+value.id+');">Delete</a></td>\
                         </tr>';

            });            
        }
        else
        { 
            html += '<tr>\
                        <td colspan="2" align="center">Project Categories not found</td>\
                     </tr>';            
        }



        $('#project_categoriesbody').html(html);
       // $('#cat_id').append(options);

      },
      error:function(jqxhr){
      }
    });
}


function deleteProjectCategory(id)
{
    $.ajax({
      type: 'POST',
      url: apiUrl + 'deleteprojectcategory',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },
      success:function(data){
        showMsg('#jobmsg', 'Project Category deleted successfully.', 'green');
        getProjectCategories();
      },
      error:function(jqxhr){
      }
    });
}

function getSingleProjectCategory(id)
{
    $('#project_cat_id').val(id);

    projectCategoryReset();  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'projectcategory',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },

      success:function(data){
        
        $('#project_cat_id').val(data.data.id);
        $('#cat_name').val(data.data.name);
        $('#mode').html('Edit');

      },
      error:function(jqxhr){
      }
    });

}

function addUpdateProjectCategory()
{
    var id            = $.trim($('#project_cat_id').val());
    var name          = $.trim($('#cat_name').val());
    var check         = true;

    if(name == '')
    {
        $('#cat_name').focus();
        $('#cat_name').addClass('error-class');
        check = false;
    }
  

    if(check)
    {
         if(id == '' || (typeof id == 'undefined'))
          {    
              $('#spinner').show();      
              $.ajax({
                type: 'POST',
                url: apiUrl + 'projectcategory',
                dataType : "JSON",
                data: {name:name},
                beforeSend:function(){

                },
                success:function(data){
                $('#spinner').hide();   
                $('#addprojectcat').modal('hide');
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Project Category added successfully.', 'green');                    
                      getProjectCategories();
                      $('#addprojectcat').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#msg', 'Project Category already exists with this name.', 'red');
                }
              });
            }
            else
            {
              var dataObj = {id:id, name:name};

                $('#spinner').show();      
              
              $.ajax({
                type: 'POST',
                url: apiUrl + 'editprojectcategory',
                dataType : "JSON",
                data: dataObj,
                beforeSend:function(){

                },
                success:function(data){
                $('#addprojectcat').modal('hide');  
                $('#spinner').hide();      
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Project Category updated successfully.', 'green');                    
                      getProjectCategories();                
                      $('#addprojectcat').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#jobmsg', 'Project Category already exists.', 'red');
                }
              });
            }
    }
}

function showAddProjectCategoryPopup()
{
    projectCategoryReset();
}

function projectCategoryReset()
{
    $('#project_cat_id').val('');
    $('#cat_name').val('');
    $('#cat_name').removeClass('error-class');
    
}


function getQueries(page)
{
    curpage = page;
    if(page > 0)
    page -= 1;

    $.ajax({
      type: 'GET',
      url: apiUrl + 'queries',
      dataType : "JSON",
      data: {},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){
        var html = '';
        var options = '';
        if(data.data.length > 0)
        {       

            $.each(data.data, function( index, value ) {
             
                html += '<tr>\
                            <td>'+value.name+'</td>\
                            <td><a href="mailto:'+value.email+'">'+value.email+'</a></td>\
                            <td>'+value.phone+'</td>\
                            <td>'+value.message+'</td>\
                            <td>  <a href="javascript:void(0);" onclick="deleteQuery('+value.id+');">Delete</a></td>\
                         </tr>';

            });            
        }
        else
        { 
            html += '<tr>\
                        <td colspan="5" align="center">Queries not found</td>\
                     </tr>';            
        }



        $('#queriesbody').html(html);
       // $('#cat_id').append(options);

       $('#pagination').bootpag({
            total: data.total_pages,          // total pages
            page: page,            // default page
            maxVisible: 5,     // visible pagination
            leaps: true         // next/prev leaps through maxVisible
        }).on("page", function(event, num){
          getQueries(num);
           });

      },
      error:function(jqxhr){
      }
    });
}


function deleteQuery(id)
{
    $.ajax({
      type: 'POST',
      url: apiUrl + 'deletequery',
      dataType : "JSON",
      data: {id:id},
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

function getSingleQuery(id)
{
    $('#query_id').val(id);

    queryReset();  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'singlequery',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },

      success:function(data){
        
        $('#query_id').val(data.data.id);
        $('#query_name').val(data.data.name);
        $('#query_email').val(data.data.email);
        $('#query_phone').val(data.data.phone);
        $('#message').val(data.data.message);
        $('#mode').html('Edit');

      },
      error:function(jqxhr){
      }
    });

}

function addUpdateQuery()
{
    var id            = $.trim($('#query_id').val());
    var name          = $.trim($('#query_name').val());
    var email         = $.trim($('#query_email').val());
    var phone         = $.trim($('#query_phone').val());
    var message       = $.trim($('#message').val());
    var check         = true;

    if(name == '')
    {
        $('#query_name').focus();
        $('#query_name').addClass('error-class');
        check = false;
    }
    if(email == '')
    {
        $('#query_email').focus();
        $('#query_email').addClass('error-class');
        check = false;
    }
    if(phone == '')
    {
        $('#query_phone').focus();
        $('#query_phone').addClass('error-class');
        check = false;
    }
    if(message == '')
    {
        $('#message').focus();
        $('#message').addClass('error-class');
        check = false;
    }
  

    if(check)
    {
         if(id == '' || (typeof id == 'undefined'))
          {    
              $('#spinner').show();      
              $.ajax({
                type: 'POST',
                url: apiUrl + 'query',
                dataType : "JSON",
                data: {name:name, email:email , phone:phone, message:message },
                beforeSend:function(){

                },
                success:function(data){
                $('#spinner').hide();   
                $('#addquery').modal('hide');
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Query added successfully.', 'green');                    
                      getQueries();
                      $('#addquery').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#msg', 'Query already exists with this name.', 'red');
                }
              });
            }
            else
            {
              var dataObj = {id:id, name:name, email:email, phone:phone, message:message};

                $('#spinner').show();      
              
              $.ajax({
                type: 'POST',
                url: apiUrl + 'editquery',
                dataType : "JSON",
                data: dataObj,
                beforeSend:function(){

                },
                success:function(data){
                $('#addquery').modal('hide');  
                $('#spinner').hide();      
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Query updated successfully.', 'green');                    
                      getQueries();                
                      $('#addquery').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#jobmsg', 'Query already exists.', 'red');
                }
              });
            }
    }
}

function showAddQueryPopup()
{
    queryReset();
}

function queryReset()
{
    $('#query_id').val('');
    $('#query_name').val('');
    $('#query_email').val('');
    $('#query_phone').val('');
    $('#message').val('');
    $('#query_name').removeClass('error-class');
    $('#query_email').removeClass('error-class');
    $('#query_phone').removeClass('error-class');
    $('#message').removeClass('error-class');
    
}



function getTeams()
{
  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'teams',
      dataType : "JSON",
      data: {},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){
        var html = '';
        var options = '';
        if(data.data.length > 0)
        {        

            $.each(data.data, function( index, value ) {
             
                html += '<tr>\
                            <td>'+value.name+'</td>\
                            <td>'+value.designation+'</td>\
                            <td><img width="150" height="75" src="../data/team/'+value.path+' "></td>\
                            <td>'+value.sort_order+'</td>\
                            <td><a href="javascript:void(0);" data-toggle="modal"  onclick="getSingleTeam('+value.id+');" data-target="#addteam">Edit</a> | <a href="javascript:void(0);" onclick="deleteTeam('+value.id+');">Delete</a></td>\
                         </tr>';

            });            
        }
        else
        { 
            html += '<tr>\
                        <td colspan="9" align="center">Teams not found</td>\
                     </tr>';            
        }



        $('#teamsbody').html(html);
       // $('#cat_id').append(options);

      },
      error:function(jqxhr){
      }
    });
}


function deleteTeam(id)
{
    $.ajax({
      type: 'POST',
      url: apiUrl + 'deleteteam',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },
      success:function(data){
        showMsg('#jobmsg', 'Team deleted successfully.', 'green');
        getTeams();
      },
      error:function(jqxhr){
      }
    });
}

function getSingleTeam(id)
{
    $('#team_id').val(id);

    teamReset();  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'singleteam',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },

      success:function(data){
        var myimage = '../data/team/'+path;
        $('#team_id').val(data.data.id);
        $('#name').val(data.data.name);
        $('#designation').val(data.data.designation);
        $('#bio').val(data.data.bio);

        $('#path').val(data.data.path);
        $('#temp_pic').attr('src', data.data.web_url);
        $('#sort_order').val(data.data.sort_order);
        $('#facebook').val(data.data.facebook);
        $('#twitter').val(data.data.twitter);
        $('#google').val(data.data.google);
        $('#skype').val(data.data.skype);

        $('#mode').html('Edit');

      },
      error:function(jqxhr){
      }
    });

}

function addUpdateTeam()
{
    var id                = $.trim($('#team_id').val());
    var name              = $.trim($('#name').val());
    var designation       = $.trim($('#designation').val());
    var bio               = $.trim($('#bio').val());
    var path              = $.trim($('#path').val());
    var sort_order        = $.trim($('#sort_order').val());
    var facebook          = $.trim($('#facebook').val());
    var twitter           = $.trim($('#twitter').val());
    var google            = $.trim($('#google').val());
    var skype             = $.trim($('#skype').val());
    var check             = true;

    if(name == '')
    {
        $('#name').focus();
        $('#name').addClass('error-class');
        check = false;
    }
    if(designation == '')
    {
        $('#designation').focus();
        $('#designation').addClass('error-class');
        check = false;
    }
    if(bio == '')
    {
        $('#bio').focus();
        $('#bio').addClass('error-class');
        check = false;
    }
    if(path == '')
    {
        $('#image').focus();
        $('#image').addClass('error-class');
        check = false;
    }
    if(sort_order == '')
    {
        $('#sort_order').focus();
        $('#sort_order').addClass('error-class');
        check = false;
    }
  

    if(check)
    {
         if(id == '' || (typeof id == 'undefined'))
          {    
              $('#spinner').show();      
              $.ajax({
                type: 'POST',
                url: apiUrl + 'team',
                dataType : "JSON",
                data: {name:name, designation:designation , bio:bio, path:path, sort_order:sort_order, facebook:facebook, twitter:twitter, google:google, skype:skype },
                beforeSend:function(){

                },
                success:function(data){
                $('#spinner').hide();   
                $('#addteam').modal('hide');
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Team added successfully.', 'green');                    
                      getTeams();
                      $('#addteam').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#msg', 'Team already exists with this name.', 'red');
                }
              });
            }
            else
            {
              var dataObj = {id:id, name:name, designation:designation , bio:bio, path:path, sort_order:sort_order, facebook:facebook, twitter:twitter, google:google, skype:skype};

                $('#spinner').show();      
              
              $.ajax({
                type: 'POST',
                url: apiUrl + 'editteam',
                dataType : "JSON",
                data: dataObj,
                beforeSend:function(){

                },
                success:function(data){
                $('#addteam').modal('hide');  
                $('#spinner').hide();      
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Team updated successfully.', 'green');                    
                      getTeams();                
                      $('#addteam').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#jobmsg', 'Team already exists.', 'red');
                }
              });
            }
    }
}

function showAddTeamPopup()
{
    teamReset();
}

function teamReset()
{
    $('#team_id').val('');
    $('#name').val('');
    $('#designation').val('');
    $('#bio').val('');
    $('#path').val('');
    $('#temp_pic').attr('src', '../admin/images/logoplaceholder.png');
    $('#image').val('');
    $('#sort_order').val('');
    $('#facebook').val('');
    $('#twitter').val('');
    $('#google').val('');
    $('#skype').val('');

    $('#name').removeClass('error-class');
    $('#designation').removeClass('error-class');
    $('#bio').removeClass('error-class');
    $('#path').removeClass('error-class');
    $('#temp_pic').removeClass('error-class');
    $('#sort_order').removeClass('error-class');
    $('#image').removeClass('error-class');
    $('#facebook').removeClass('error-class');
    $('#twitter').removeClass('error-class');
    $('#google').removeClass('error-class');
    $('#skype').removeClass('error-class');


    
}


function getProjects()
{

    $.ajax({
      type: 'GET',
      url: apiUrl + 'projects',
      dataType : "JSON",
      data: {},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){

        var html = '';
        var options = '';
        if(data.data.length > 0)
        {        

            $.each(data.data, function( index, value ) {
              var project = '<tr>';
              if(value.cat_name != '' && value.cat_name != 'null' && typeof data.data.cat_name != 'object')
              {
                project += '<td>'+value.cat_name+'</td>';
              }
              else
              {
                project += '<td></td>';
              }
              if(value.name != '' && value.name != 'null' && typeof data.data.name != 'object')
              {
                project += '<td>'+value.name+'</td>';
              }
              else
              {
                project += '<td></td>';
              }
              if(value.location != '' && value.location != 'null' && typeof data.data.location != 'object')
              {
                project += '<td>'+value.location+'</td>';
              }
              else
              {
                project += '<td></td>';
              }
              if(value.client != '' && value.client != 'null' && typeof data.data.client != 'object')
              {
                project += '<td>'+value.client+'</td>';
              }
              else
              {
                project += '<td></td>';
              }
              if(value.consultant != '' && value.consultant != 'null'  && value.consultant != null && typeof data.data.consultant != 'object')
              {
                project += '<td>'+value.consultant+'</td>';
              }
              else
              {
                project += '<td></td>';
              }
              if(value.contractor != '' && value.contractor != 'null' && value.contractor != null && typeof data.data.contractor != 'object')
              {
                project += '<td>'+value.contractor+'</td>';
              }
              else
              {
                project += '<td></td>';
              }
              if(value.description != '' && value.description != 'null' && typeof data.data.description != 'object')
              {
                project += '<td>'+value.description+'</td>';
              }
              else
              {
                project += '<td></td>';
              }
              project += '<td><a href="javascript:void(0);" data-toggle="modal"  onclick="getSingleProject('+value.id+');" data-target="#addproject">Edit</a> | <a href="javascript:void(0);" onclick="deleteProject('+value.id+');">Delete</a></td>';
              //project += '<td><a href="javascript:void(0);" data-toggle="modal"  onclick="getSingleProject('+value.id+');" data-target="#addproject">Edit</a> | <a href="javascript:void(0);" onclick="deleteProject('+value.id+');">Delete</a></td>\
               //          </tr>';

              //getSingleProjectCategory('value.cat_id');

              // if(value.status == 0)
              //   var status = '<i class="fa fa-times-circle"></i> ';
              // else
              //   var status = '<i class="fa fa-check-circle"></i> ';

                //options += '<option value="'+value.id+'">'+value.name+' </option>';
                // html += '<tr>\
                //             <td>'+value.cat_name+'</td>\
                //             <td>'+value.name+'</td>\
                //             <td>'+value.location+'</td>\
                //             <td>'+value.client+'</td>\
                //             <td>'+value.consultant+'</td>\
                //             <td>'+value.contractor+'</td>\
                //             <td>'+value.description+'</td>\
                //             <td><a href="javascript:void(0);" data-toggle="modal"  onclick="getSingleProject('+value.id+');" data-target="#addproject">Edit</a> | <a href="javascript:void(0);" onclick="deleteProject('+value.id+');">Delete</a></td>\
                //          </tr>';

                html += project;

            });            
        }
        else
        { 
            html += '<tr>\
                        <td colspan="8" align="center">Projects not found</td>\
                     </tr>';            
        }



        $('#projectbody').html(html);
       // $('#cat_id').append(options);

      },
      error:function(jqxhr){
      }
    });
}

function addVideo()
{

  var html = '<div class="container">\
  <div class="row clearfix">\
    <div class="col-md-10 column">\
      <form class="form-horizontal" role="form" onsubmit="return false;">\
        <div class="form-group">\
           <label for="inputEmail3" class="col-sm-2 control-label"></label>\
          <div class="col-sm-4">\
            <input type="text" class="form-control videos" id="video">\
            <a href="javascript:void(0);" onclick="$(this).parent().parent().remove()" style="float:right;">Delete</a>\
          </div>\
        </div>\
      </form>\
    </div>\
  </div>\
</div>';
  $('#video_div').append(html)
}


function deleteProject(id)
{
    $.ajax({
      type: 'POST',
      url: apiUrl + 'deleteproject',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },
      success:function(data){
        showMsg('#jobmsg', 'Project deleted successfully.', 'green');
        getProjects();
      },
      error:function(jqxhr){
      }
    });
}

function getSingleProject(id)
{
    $('#project_id').val(id);

    projectReset();  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'singleproject',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },

      success:function(data){
        
        $('#project_id').val(data.data.id);
        $('#cat_id').val(data.data.cat_id);
        $('#name').val(data.data.name);
        //$('#value').val(data.data.value);
        $('#year').val(data.data.year_executed);
        $('#location').val(data.data.location);
        $('#client').val(data.data.client);
        $('#consultant').val(data.data.consultant);
        $('#contractor').val(data.data.contractor);
        //$('#heading').val(data.data.heading);
        $('#description').val(data.data.description);
        $('#mode').html('Edit');

        var html = '';

         $(data.data.videos).each(function(ind, val){
                       var video = val;
                       if(ind == 0)
                       {
                          $('#video').val(video['embed_code']);
                       }
                       else
                       {
                         html += '<div class="container">\
                                      <div class="row clearfix">\
                                        <div class="col-md-10 column">\
                                          <form class="form-horizontal" role="form" onsubmit="return false;">\
                                            <div class="form-group">\
                                               <label for="inputEmail3" class="col-sm-2 control-label"></label>\
                                              <div class="col-sm-4">\
                                                <input type="text" class="form-control videos" id="video" value='+video['embed_code']+'>\
                                                <a href="javascript:void(0);" onclick="$(this).parent().parent().remove()" style="float:right;">Delete</a>\
                                              </div>\
                                            </div>\
                                          </form>\
                                        </div>\
                                      </div>\
                                    </div>';
                          //console.log(video);
                      }
                       }); 
                    
                      $('#video_div').html(html);

          var images_html = '';

         $(data.data.images).each(function(ind, val){
            console.log(val)
                       if(ind == 0)
                       {
                          $('#temp_pic1').attr('src', val.web_url);
                          $('#path1').val(val.path);
                       }
                       else
                       {
                          globalImageCounter++;
                           images_html = '<div class="container">\
                          <div class="row clearfix">\
                            <div class="col-md-10 column">\
                              <form class="form-horizontal" role="form" onsubmit="return false;">\
                                <div class="form-group">\
                                   <label for="inputEmail3" class="col-sm-2 control-label">Image</label>\
                                  <div class="col-sm-5">\
                                          <a style="float:right;" onclick="$(this).parent().parent().remove()" href="javascript:void(0);">Delete</a>\
                                <input type="file" name="image" id="image'+globalImageCounter+'" data-url="../api/project_upload" class="file-pos">\
                                <img src="'+val.web_url+'" id="temp_pic'+globalImageCounter+'" width="80" height="80">\
                                <img src="images/spinner.gif" id="image_spinner'+globalImageCounter+'" style="display:none;">\
                                <input type="hidden" id="path'+globalImageCounter+'" value="'+val.path+'" class="images">\
                                  </div>\
                                </div>\
                              </form>\
                            </div>\
                          </div>\
                        </div>';

                        $('#image_div').append(images_html);
                        $('#image' + globalImageCounter).fileupload({
                                dataType: 'json',
                                done: function (e, data) {
                                  $('#image_spinner' + globalImageCounter).hide();          
                               $('#path' + globalImageCounter).val(data.result.file_name);
                               $('#temp_pic' + globalImageCounter).attr('src',data.result.web_url);
                                },
                                send: function (e, data) {
                                  $('#image_spinner' + globalImageCounter).show();
                                }
                            });

                      }


                                            
  
                       }); 



      },
      error:function(jqxhr){
      }
    });

}

function addUpdateProject()
{
    var id            = $.trim($('#project_id').val());
    var cat_id        = $.trim($('#cat_id').val());
    var name          = $.trim($('#name').val());
    var location      = $.trim($('#location').val());
    var client        = $.trim($('#client').val());
    var consultant    = $.trim($('#consultant').val());
    var contractor    = $.trim($('#contractor').val());
    var value         = $.trim($('#value').val());
    var heading       = $.trim($('#heading').val());
    var year_executed = $.trim($('#year').val());
    var description   = $.trim($('#description').val());
    var path1   = $.trim($('#path1').val());

    var check         = true;

    if(cat_id == '')
    {
        $('#cat_id').focus();
        $('#cat_id').addClass('error-class');
        check = false;
    }
    if(name == '')
    {
        $('#name').focus();
        $('#name').addClass('error-class');
        check = false;
    }

    // if(heading == '')
    // {
    //     $('#heading').focus();
    //     $('#heading').addClass('error-class');
    //     check = false;
    // }

    if(path1 == '')
    {
        $('#image1').focus();
        $('#image1').addClass('error-class');
        check = false;
    }
  
    var images = [];
  if($('.images').length > 0)
  {
    $(".images").each(function(ind, val){
                       var image = $(val).val()
                       if(image != '')
                      {
         images.push(image);

     }

 // $('#image_div').append(html);

 //        $('#image1').fileupload({
 //        dataType: 'json',
 //        done: function (e, data) {
 //          $('#image_spinner').hide();          
 //       $('#path1').val(data.result.file_name);
 //       $('#temp_pic1').attr('src',data.result.web_url);
 //        },
 //        send: function (e, data) {
 //          $('#image_spinner').show();
 //        }
 //    }); 
    }); 
  }


  // $.ajax({
  //               type: 'POST',
  //               url: apiUrl + 'projectimage',
  //               dataType : "JSON",
  //               data: { images:images},
  //               beforeSend:function(){

  //               },
  //               success:function(data){
  //               $('#spinner').hide();   
  //               //$('#addproject').modal('hide');
  //                 if(data.status == 'success')
  //                 {
  //                     //showMsg('#jobmsg', 'Project added successfully.', 'green');                    
  //                     //getProjects();
  //                     //$('#addproject').modal('hide');
  //                 }
  //               },
  //               error:function(jqxhr){
  //                 //$('#spinner').hide();      
  //                 //showMsg('#msg', 'Project already exists with this name.', 'red');
  //               }
  //             });

  var videos = [];
  if($('.videos').length > 0)
  {
    $(".videos").each(function(ind, val){
                       var video = $(val).val()
                       if(video != '')
                      {
         videos.push(video);

     }
    }); 
  }

    if(check)
    {
         if(id == '' || (typeof id == 'undefined'))
          {    
              $('#spinner').show();      
              $.ajax({
                type: 'POST',
                url: apiUrl + 'project',
                dataType : "JSON",
                data: {cat_id:cat_id,name:name, location:location,value:value, client:client, consultant:consultant,contractor:contractor,year_executed:year_executed, heading:heading, description:description, videos:videos, images:images},
                beforeSend:function(){

                },
                success:function(data){
                $('#spinner').hide();   
                $('#addproject').modal('hide');
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Project added successfully.', 'green');                    
                      getProjects();
                      $('#addproject').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#msg', 'Project already exists with this name.', 'red');
                }
              });

            }
            else
            {
              var dataObj = {id:id, cat_id:cat_id,name:name, location:location,value:value, client:client, consultant:consultant,contractor:contractor,year_executed:year_executed, heading:heading, description:description, videos:videos, images:images};

                $('#spinner').show();      
              
              $.ajax({
                type: 'POST',
                url: apiUrl + 'editproject',
                dataType : "JSON",
                data: dataObj,
                beforeSend:function(){

                },
                success:function(data){
                $('#addproject').modal('hide');  
                $('#spinner').hide();      
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Project updated successfully.', 'green');                    
                      getProjects();                
                      $('#addproject').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#jobmsg', 'Project already exists.', 'red');
                }
              });
            }
    }
}

var globalImageCounter = 1;
function addImage()
{
   globalImageCounter++;
    var html = '<div class="container">\
  <div class="row clearfix">\
    <div class="col-md-10 column">\
      <form class="form-horizontal" role="form" onsubmit="return false;">\
        <div class="form-group">\
           <label for="inputEmail3" class="col-sm-2 control-label">Image</label>\
          <div class="col-sm-5">\
                  <a style="float:right;" onclick="$(this).parent().parent().remove()" href="javascript:void(0);">Delete</a>\
        <input type="file" name="image" id="image'+globalImageCounter+'" data-url="../api/project_upload" class="file-pos">\
        <img src="images/logoplaceholder.png" id="temp_pic'+globalImageCounter+'" width="80" height="80">\
        <img src="images/spinner.gif" id="image_spinner'+globalImageCounter+'" style="display:none;">\
        <input type="hidden" value="" id="path'+globalImageCounter+'" class="images">\
          </div>\
        </div>\
      </form>\
    </div>\
  </div>\
</div>';

    $('#image_div').append(html);



    $('#image' + globalImageCounter).fileupload({
        dataType: 'json',
        done: function (e, data) {
          $('#image_spinner' + globalImageCounter).hide();          
       $('#path' + globalImageCounter).val(data.result.file_name);
       $('#temp_pic' + globalImageCounter).attr('src',data.result.web_url);
        },
        send: function (e, data) {
          $('#image_spinner' + globalImageCounter).show();
        }
    });

}

function showAddProjectPopup()
{
    projectReset();
}

function projectReset()
{
    var globalImageCounter = 1;

    $('#project_id').val('');
    $('#name').val('');
    $('#location').val('');
    $('#value').val('');
    $('#client').val('');
    $('#heading').val('');
    $('#description').val('');
    $('#video').val('');
    $('#image_div').html('');
    $('#path1').val('');
    $('#consultant').val('');
    $('#contractor').val('');
    $('#year').val('');
    $('#temp_pic1').attr('src', 'images/logoplaceholder.png');    

    $('#cat_id').removeClass('error-class');
    $('#name').removeClass('error-class');
    $('#location').removeClass('error-class');
    $('#value').removeClass('error-class');
    $('#client').removeClass('error-class');
    $('#heading').removeClass('error-class');
    $('#description').removeClass('error-class');
    $('#consultant').removeClass('error-class');
    $('#contractor').removeClass('error-class');
    $('#year').removeClass('error-class');

}


function getNews()
{
    $.ajax({
      type: 'GET',
      url: apiUrl + 'news',
      dataType : "JSON",
      data: {},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){
        var html = '';
        var options = '';
        if(data.data.length > 0)
        {        
          //<td><img width="150" height="75" src="../data/news/'+value.path+' "></td>\

            $.each(data.data, function( index, value ) {
             
                html += '<tr>\
                            <td>'+value.heading+'</td>\
                            <td>'+value.description+'</td>\
                            <td>'+value.archive+'</td>\
                            <td>'+value.date_created+'</td>\
                            <td><a href="javascript:void(0);" data-toggle="modal"  onclick="getSingleNews('+value.id+');" data-target="#addnews">Edit</a> | <a href="javascript:void(0);" onclick="deleteNews('+value.id+');">Delete</a></td>\
                         </tr>';

            });            
        }
        else
        { 
            html += '<tr>\
                        <td colspan="7" align="center">News not found</td>\
                     </tr>';            
        }



        $('#newsbody').html(html);

      },
      error:function(jqxhr){
      }
    });
}


function deleteNews(id)
{
    $.ajax({
      type: 'POST',
      url: apiUrl + 'deletenews',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },
      success:function(data){
        showMsg('#jobmsg', 'News deleted successfully.', 'green');
        getNews();
      },
      error:function(jqxhr){
      }
    });
}

function getSingleNews(id)
{
    $('#news_id').val(id);

    newsReset();  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'singlenews',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },

      success:function(data){
        $('#news_id').val(data.data.id);
        $('#heading').val(data.data.heading);
        $('#description').val(data.data.description);
        //$('#path').val(data.data.path);
        //$('#temp_pic').attr('src', data.data.web_url);

        $('#archive_yes').prop('checked', false);
        $('#archive_no').prop('checked', false);        

        if(data.data.archive == 'yes')
          $('#archive_yes').prop('checked', true);
        else
          $('#archive_no').prop('checked', true);


        $('#mode').html('Edit');

      },
      error:function(jqxhr){
      }
    });

}

function addUpdateNews()
{
    var id                = $.trim($('#news_id').val());
    var heading           = $.trim($('#heading').val());
    var description       = $.trim($('#description').val());
    var path              = $.trim($('#path').val());
    var archive           = $.trim($("input[type='radio'][name='archive']:checked").val());
    var check             = true;

    if(heading == '')
    {
        $('#heading').focus();
        $('#heading').addClass('error-class');
        check = false;
    }
    if(description == '')
    {
        $('#description').focus();
        $('#description').addClass('error-class');
        check = false;
    }

    if(archive == '')
    {
        $('#archive').focus();
        $('#archive').addClass('error-class');
        check = false;
    }
  

    if(check)
    {
         if(id == '' || (typeof id == 'undefined'))
          {    
              $('#spinner').show();      
              $.ajax({
                type: 'POST',
                url: apiUrl + 'news',
                dataType : "JSON",
                data: {heading:heading, description:description , path:path, archive:archive },
                beforeSend:function(){

                },
                success:function(data){
                $('#spinner').hide();   
                $('#addnews').modal('hide');
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'News added successfully.', 'green');                    
                      getNews();
                      $('#addnews').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#msg', 'News already exists with this name.', 'red');
                }
              });
            }
            else
            {
              var dataObj = {id:id, heading:heading, description:description , path:path, archive:archive };

                $('#spinner').show();      
              
              $.ajax({
                type: 'POST',
                url: apiUrl + 'editnews',
                dataType : "JSON",
                data: dataObj,
                beforeSend:function(){

                },
                success:function(data){
                $('#addnews').modal('hide');  
                $('#spinner').hide();      
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'News updated successfully.', 'green');                    
                      getNews();                
                      $('#addnews').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#jobmsg', 'News already exists.', 'red');
                }
              });
            }
    }
}

function showAddNewsPopup()
{
    newsReset();
}

function newsReset()
{
    $('#news_id').val('');
    $('#heading').val('');
    $('#description').val('');
    $('#path').val('');
    $('#temp_pic').attr('src', '../admin/images/logoplaceholder.png');
    $('#image').val('');
    $('#archive_no').prop('checked', true);
    

    $('#heading').removeClass('error-class');
    $('#description').removeClass('error-class');
    $('#path').removeClass('error-class');
    $('#archive').removeClass('error-class');
    $('#temp_pic').removeClass('error-class');
    $('#image').removeClass('error-class');
    
}

function getServices()
{
    $.ajax({
      type: 'GET',
      url: apiUrl + 'services',
      dataType : "JSON",
      data: {},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){
        var html = '';
        var options = '';
        if(data.data.length > 0)
        {        

            $.each(data.data, function( index, value ) {
             
                html += '<tr>\
                            <td>'+value.name+'</td>\
                            <td>'+value.slug+'</td>\
                            <td>'+value.description+'</td>\
                            <td>'+value.archive+'</td>\
                            <td><a href="javascript:void(0);" data-toggle="modal"  onclick="getSingleService('+value.id+');" data-target="#addservices">Edit</a> | <a href="javascript:void(0);" onclick="deleteService('+value.id+');">Delete</a></td>\
                         </tr>';

            });            
        }
        else
        { 
            html += '<tr>\
                        <td colspan="5" align="center">Services not found</td>\
                     </tr>';            
        }



        $('#servicesbody').html(html);

      },
      error:function(jqxhr){
      }
    });
}


function deleteService(id)
{
    $.ajax({
      type: 'POST',
      url: apiUrl + 'deleteservices',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },
      success:function(data){
        showMsg('#jobmsg', 'Service deleted successfully.', 'green');
        getServices();
      },
      error:function(jqxhr){
      }
    });
}

function getSingleService(id)
{
    $('#services_id').val(id);

    servicesReset();  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'singleservice',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },

      success:function(data){
        $('#services_id').val(data.data.id);
        $('#name').val(data.data.name);
        $('#description').val(data.data.description);
        $('#slug').val(data.data.slug);
        $('#archive_yes').prop('checked', false);
        $('#archive_no').prop('checked', false);        

        if(data.data.archive == 'yes')
          $('#archive_yes').prop('checked', true);
        else
          $('#archive_no').prop('checked', true);
        

        $('#mode').html('Edit');

        var images_html = '';

         $(data.data.images).each(function(ind, val){
            console.log(val)
                       if(ind == 0)
                       {
                          $('#services_temp_pic1').attr('src', val.web_url);
                          $('#services_path1').val(val.path);
                       }
                       else
                       {
                          globalImageCounter++;
                           images_html = '<div class="container">\
                          <div class="row clearfix">\
                            <div class="col-md-10 column">\
                              <form class="form-horizontal" role="form" onsubmit="return false;">\
                                <div class="form-group">\
                                   <label for="inputEmail3" class="col-sm-2 control-label">Image</label>\
                                  <div class="col-sm-5">\
                                          <a style="float:right;" onclick="$(this).parent().parent().remove()" href="javascript:void(0);">Delete</a>\
                                <input type="file" name="image" id="services_image'+globalImageCounter+'" data-url="../api/service_upload" class="file-pos">\
                                <img src="'+val.web_url+'" id="services_temp_pic'+globalImageCounter+'" width="80" height="80">\
                                <img src="images/spinner.gif" id="services_image_spinner'+globalImageCounter+'" style="display:none;">\
                                <input type="hidden" id="services_path'+globalImageCounter+'" value="'+val.path+'" class="services_images">\
                                  </div>\
                                </div>\
                              </form>\
                            </div>\
                          </div>\
                        </div>';

                        $('#services_image_div').append(images_html);
                        $('#services_image' + globalImageCounter).fileupload({
                                dataType: 'json',
                                done: function (e, data) {
                                  $('#services_image_spinner' + globalImageCounter).hide();          
                               $('#services_path' + globalImageCounter).val(data.result.file_name);
                               $('#services_temp_pic' + globalImageCounter).attr('src',data.result.web_url);
                                },
                                send: function (e, data) {
                                  $('#services_image_spinner' + globalImageCounter).show();
                                }
                            });
                       }
                 });

      },
      error:function(jqxhr){
      }
    });

}

function addUpdateServices()
{
    var id                = $.trim($('#services_id').val());
    var name              = $.trim($('#name').val());
    var description       = $.trim($('#description').val());
    var slug              = $.trim($('#slug').val());
    var archive           = $.trim($("input[type='radio'][name='archive']:checked").val());
    var path              = $.trim($('#services_path1').val());
    var check             = true;

    if(name == '')
    {
        $('#name').focus();
        $('#name').addClass('error-class');
        check = false;
    }
    if(description == '')
    {
        $('#description').focus();
        $('#description').addClass('error-class');
        check = false;
    }
    if(slug == '')
    {
        $('#slug').focus();
        $('#slug').addClass('error-class');
        check = false;
    }
    if(archive == '')
    {
        $('#archive').focus();
        $('#archive').addClass('error-class');
        check = false;
    }
     if(path == '')
    {
        $('#services_image1').focus();
        $('#services_image1').addClass('error-class');
        check = false;
    }
  
   var images = [];
  if($('.services_images').length > 0)
  {
    $(".services_images").each(function(ind, val){
                       var image = $(val).val()
                       if(image != '')
                      {
                         images.push(image);
                      }
     });
  }



//start

    if(check)
    {
         if(id == '' || (typeof id == 'undefined'))
          {    
              $('#spinner').show();      
              $.ajax({
                type: 'POST',
                url: apiUrl + 'services',
                dataType : "JSON",
                data: {name:name, description:description , slug:slug, archive:archive, images:images },
                beforeSend:function(){

                },
                success:function(data){
                $('#spinner').hide();   
                $('#addservices').modal('hide');
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Service added successfully.', 'green');                    
                      getServices();
                      $('#addservices').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#msg', 'Service already exists with this name.', 'red');
                }
              });
          }
            else
            {
              var dataObj = {id:id, name:name, description:description , slug:slug, archive:archive, images:images };

                $('#spinner').show();      
              
              $.ajax({
                type: 'POST',
                url: apiUrl + 'editservices',
                dataType : "JSON",
                data: dataObj,
                beforeSend:function(){

                },
                success:function(data){
                $('#addservices').modal('hide');  
                $('#spinner').hide();      
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Service updated successfully.', 'green');                    
                      getServices();                
                      $('#addservices').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#jobmsg', 'Service already exists.', 'red');
                }
               });
          } // end else
     } // end check
//end

 }
    

function showAddServicesPopup()
{
    servicesReset();
}

function servicesReset()
{
    $('#services_id').val('');
    $('#name').val('');
    $('#description').val('');
    $('#slug').val('');
    $('#archive_no').prop('checked', true);
    

    $('#name').removeClass('error-class');
    $('#description').removeClass('error-class');
    $('#slug').removeClass('error-class');
    $('#archive').removeClass('error-class');
    
}

function addServicesImage()
{
   globalImageCounter++;
    var html = '<div class="container">\
  <div class="row clearfix">\
    <div class="col-md-10 column">\
      <form class="form-horizontal" role="form" onsubmit="return false;">\
        <div class="form-group">\
           <label for="inputEmail3" class="col-sm-2 control-label">Image</label>\
          <div class="col-sm-5">\
                  <a style="float:right;" onclick="$(this).parent().parent().remove()" href="javascript:void(0);">Delete</a>\
        <input type="file" name="image" id="services_image'+globalImageCounter+'" data-url="../api/service_upload" class="file-pos">\
        <img src="images/logoplaceholder.png" id="services_temp_pic'+globalImageCounter+'" width="80" height="80">\
        <img src="images/spinner.gif" id="services_image_spinner'+globalImageCounter+'" style="display:none;">\
        <input type="hidden" value="" id="services_path'+globalImageCounter+'" class="services_images">\
          </div>\
        </div>\
      </form>\
    </div>\
  </div>\
</div>';

    $('#services_image_div').append(html);



    $('#services_image' + globalImageCounter).fileupload({
        dataType: 'json',
        done: function (e, data) {
          $('#services_image_spinner' + globalImageCounter).hide();          
       $('#services_path' + globalImageCounter).val(data.result.file_name);
       $('#services_temp_pic' + globalImageCounter).attr('src',data.result.web_url);
        },
        send: function (e, data) {
          $('#services_image_spinner' + globalImageCounter).show();
        }
    });

}


function a()
{
    var name            = $.trim($('#contact_name').val());
    var email           = $.trim($('#contact_email').val());
    var phone           = $.trim($('#contact_phone').val());
    var message         = $.trim($('#contact_message').val());
    var check           = true;

    if(name == '')
    {
        $('#contact_name').focus();
        $('#contact_name').addClass("error_input");
        check = false;
    }
    if(email == '')
    {
        $('#contact_email').focus();
        $('#contact_email').addClass("error_input");
        check = false;
    }
    if(phone == '')
    {
        $('#contact_phone').focus();
        $('#contact_phone').addClass("error_input");
        check = false;
    }
    if(message == '')
    {
        $('#contact_message').focus();
        $('#contact_message').addClass("error_input");
        check = false;
    }

    if(check)
    {
      if(check)
    {    
              $('#spinner').show();      
              $.ajax({
                type: 'POST',
                url: apiUrl + 'query',
                dataType : "JSON",
                data: {name:name, email:email , phone:phone, message:message },
                beforeSend:function(){

                },
                success:function(data){
                $('#spinner').hide();   
                  if(data.status == 'success')
                  {
                      //$('#mail_success').html('Your message has been sent successfully.').show().fadeOut(1000);
                      var name            = $('#contact_name').val('');
                      var email           = $('#contact_email').val('');
                      var phone           = $('#contact_phone').val('');
                      var message         = $('#contact_message').val('');
                      showMsg('#mail_success', 'Your message has been sent successfully.','green');                    
                    $('input, textarea').removeClass("error_input");
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();    
                  //$('#mail_fail').html('Sorry, error occured this time sending your message.').show();  
                  showMsg('#mail_fail', 'Sorry, error occured this time sending your message.','red');
                }
              });
    }

}}

function getClients()
{

    $.ajax({
      type: 'GET',
      url: apiUrl + 'clients',
      dataType : "JSON",
      data: {},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){

        var html = '';
        var options = '';
        if(data.data.length > 0)
        {        

          

            $.each(data.data, function( index, value ) {
              //getSingleProjectCategory('value.cat_id');

              // if(value.status == 0)
              //   var status = '<i class="fa fa-times-circle"></i> ';
              // else
              //   var status = '<i class="fa fa-check-circle"></i> ';

                //options += '<option value="'+value.id+'">'+value.name+' </option>';
                html += '<tr>\
                            <td>'+value.name+'</td>\
                            <td><img width="150" height="75" src="../data/client/'+value.path+' "></td>\
                            <td>'+value.project_name+'</td>\
                            <td>'+value.description+'</td>\
                            <td><a href="javascript:void(0);" data-toggle="modal"  onclick="getSingleClient('+value.id+');" data-target="#addclient">Edit</a> | <a href="javascript:void(0);" onclick="deleteClient('+value.id+');">Delete</a></td>\
                         </tr>';

            });            
        }
        else
        { 
            html += '<tr>\
                        <td colspan="5" align="center">Clients not found</td>\
                     </tr>';            
        }



        $('#clientsbody').html(html);
       // $('#cat_id').append(options);

      },
      error:function(jqxhr){
      }
    });
}


function deleteClient(id)
{
    $.ajax({
      type: 'POST',
      url: apiUrl + 'deleteclient',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },
      success:function(data){
        showMsg('#jobmsg', 'Client deleted successfully.', 'green');
        getClients();
      },
      error:function(jqxhr){
      }
    });
}

function getSingleClient(id)
{
    $('#client_id').val(id);

    clientReset();  
    $.ajax({
      type: 'GET',
      url: apiUrl + 'client',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },

      success:function(data){
        
        $('#client_id').val(data.data.id);
        $('#project_id').val(data.data.project_id);
        $('#client_name').val(data.data.name);
        $('#path').val(data.data.path);
        $('#temp_pic').attr('src', data.data.web_url);
        $('#description').val(data.data.description);
        
        $('#mode').html('Edit');                                            
  



      },
      error:function(jqxhr){
      }
    });

}


function addUpdateClient()
{
    var id            = $.trim($('#client_id').val());
    var name          = $.trim($('#client_name').val());
    var project_id    = $.trim($('#project_id').val());
    var path          = $.trim($('#path').val());
    var description   = $.trim($('#description').val());
    var check         = true;

   
    if(name == '')
    {
        $('#client_name').focus();
        $('#client_name').addClass('error-class');
        check = false;
    }

  if(path == '')
    {
        $('#image').focus();
        $('#image').addClass('error-class');
        check = false;
    }
    // if(description == '')
    // {
    //     $('#description').focus();
    //     $('#description').addClass('error-class');
    //     check = false;
    // }
    // if(project_id == '0')
    // {
    //     $('#project_id').focus();
    //     $('#project_id').addClass('error-class');
    //     check = false;
    // }

    if(check)
    {
         if(id == '' || (typeof id == 'undefined'))
          {    
              $('#spinner').show();      
              $.ajax({
                type: 'POST',
                url: apiUrl + 'client',
                dataType : "JSON",
                data: {name:name, project_id:project_id, path:path, description:description},
                beforeSend:function(){

                },
                success:function(data){
                $('#spinner').hide();   
                $('#addclient').modal('hide');
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Client added successfully.', 'green');                    
                      getClients();
                      $('#addclient').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#msg', 'Client already exists with this name.', 'red');
                }
              });
            }
            else
            {
              var dataObj = {id:id, name:name, project_id:project_id, path:path, description:description};

                $('#spinner').show();      
              
              $.ajax({
                type: 'POST',
                url: apiUrl + 'editclient',
                dataType : "JSON",
                data: dataObj,
                beforeSend:function(){

                },
                success:function(data){
                $('#addclient').modal('hide');  
                $('#spinner').hide();      
                  if(data.status == 'success')
                  {
                      showMsg('#jobmsg', 'Client updated successfully.', 'green');                    
                      getClients();                
                      $('#addclient').modal('hide');
                  }
                },
                error:function(jqxhr){
                  $('#spinner').hide();      
                  showMsg('#jobmsg', 'Client already exists.', 'red');
                }
              });
            }
    }
}

function showAddClientPopup()
{
    clientReset();
}

function clientReset()
{
    $('#client_id').val('');
    $('#client_name').val('');
    $('#path').val('');
    $('#temp_pic').attr('src', '../admin/images/logoplaceholder.png');
    $('#image').val('');
    $('#description').val('');

    $('#client_name').removeClass('error-class');
    $('#project_id').removeClass('error-class');
    $('#path').removeClass('error-class');
    $('#temp_pic').removeClass('error-class');
    $('#image').removeClass('error-class');
    $('#description').removeClass('error-class');

}

function getCategories()
{
 $.ajax({
   type: 'GET',
   dataType:"JSON",
   url: apiUrl + 'projectcategories',
   data: { },
   beforeSend:function(){

   },
   success:function(data){
    {
     var options = '';
     $.each(data.data, function( index, value ) {
      options += '<option value="'+value.id+'">'+value.name+' </option>';
     });
     $('#cat_id').html(options);
    
 }
}
});
}

  function getProjectNames()
{
 $.ajax({
   type: 'GET',
   dataType:"JSON",
   url: apiUrl + 'projects',
   data: { },
   beforeSend:function(){

   },
   success:function(data){
    {
     var options = '<option value="0">Select Project</option>';

     $.each(data.data, function( index, value ) {
      options += '<option value="'+value.id+'">'+value.name+' </option>';
     });
     $('#project_id').html(options);
    
 }
}
});
}

function getFooterNews()
{
      $.ajax({
      type: 'GET',
      url: apiUrl + 'news',
      dataType : "JSON",
      data: {limit:5},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){
        var html = '';
        if(data.data.length > 0)
        {   
          //while(data.data.length < 2)     
          //{
            $.each(data.data, function( index, value ) {

                html += '<li><a href="news">'+value.heading+'</a></li>';

            });  
          //}
                   
        }
        else
        { 
          $('.widget_recent_post').hide();
//            html += '<li><a href="#">New not Found</a></li>';            
        }



        $('#footernews').html(html);

      },
      error:function(jqxhr){
      }
    });
}


function showProjectDetail(id)
{
  // jssor_slider1_starter('slider1_container');  
  // $('.popup').fadeIn();


        var init_html = ' <!-- Loading Screen -->\
          <div u="loading" style="position: absolute; top: 0px; left: 0px;">\
            <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;\
                background-color: #000; top: 0px; left: 0px;width: 100%;height:100%;"> </div>\
            <div style="position: absolute; display: block; background: url(img/loading.gif) no-repeat center center;\
                top: 0px; left: 0px;width: 100%;height:100%;"> </div>\
          </div>\
          <!-- Slides Container -->\
          <div id = "images" u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 720px; height: 480px;\
            overflow: hidden;">\
            <!-- <div> <img u="image" src="{{web_url}}asset/img/travel/01.jpg"> <img u="thumb" src="{{web_url}}asset/img/travel/thumb-01.jpg"> </div> -->\
          </div>\
          <!--#region Thumbnail Navigator Skin Begin -->\
          <!-- Help: http://www.jssor.com/development/slider-with-thumbnail-navigator-jquery.html -->\
          <!-- thumbnail navigator container -->\
          <div u="thumbnavigator" class="jssort07" style="width: 720px; height: 100px; left: 0px; bottom: 0px;">\
            <!-- Thumbnail Item Skin Begin -->\
            <div u="slides" style="cursor: default;">\
              <div u="prototype" class="p">\
                <div u="thumbnailtemplate" class="i"></div>\
                <div class="o"></div>\
              </div>\
            </div>\
            <!-- Thumbnail Item Skin End -->\
            <!--#region Arrow Navigator Skin Begin -->\
            <!-- Help: http://www.jssor.com/development/slider-with-arrow-navigator-jquery.html -->\
            <!-- Arrow Left -->\
            <span u="arrowleft" class="jssora11l" style="top: 123px; left: 8px;"> </span>\
            <!-- Arrow Right -->\
            <span u="arrowright" class="jssora11r" style="top: 123px; right: 8px;"> </span>\
            <!--#endregion Arrow Navigator Skin End -->\
          </div>\
          <!--#endregion Thumbnail Navigator Skin End -->';
          $('#slider1_container').html(init_html);


  $.ajax({
      type: 'GET',
      url: apiUrl + 'singleproject',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },
      success:function(data){

      if(data.data.name == '')
      {
        $('#popup_title').hide(); 
      }
      else
      {
        $('#popup_title').html(data.data.name);           

      }
      // if(data.data.date_created == '')
      // {
      //   $('#date_div').hide(); 
      // }
      // else
      // {
      //   $('#date').html(data.data.date_created);           

      // }
      if(data.data.year_executed == '' || data.data.year_executed == 'null' || typeof data.data.year_executed == 'object')
      {
        $('#year_div').hide(); 
      }
      else
      {
        $('#year').html(data.data.year_executed);           

      }
      if(data.data.location == '' || data.data.location == 'null' || typeof data.data.location == 'object')
      {
        $('#location_div').hide(); 
      }
      else
      {
        $('#location').html(data.data.location);           

      }
      if(data.data.client == '' || data.data.client == 'null' || typeof data.data.client == 'object')
      {
        $('#client_div').hide(); 
      }
      else
      {
        $('#client').html(data.data.client);           

      }
      if(data.data.consultant == '' || data.data.consultant == 'null' || typeof data.data.consultant == 'object')
      {
        $('#consultant_div').hide(); 
      }
      else
      {
        $('#consultant').html(data.data.consultant);           

      }
      if(data.data.contractor == '' || data.data.contractor == 'null' || typeof data.data.contractor == 'object')
      {
        $('#contractor_div').hide(); 
      }
      else
      {
        $('#contractor').html(data.data.contractor);           

      }
      if(data.data.cat_name == '' || data.data.cat_name == 'null' || typeof data.data.cat_name == 'object')
      {
        $('#category_div').hide(); 
      }
      else
      {
        $('#category').html(data.data.cat_name);           

      }
      // if(data.data.value == '')
      // {
      //   $('#value_div').hide(); 
      // }
      // else
      // {
      //   $('#value').html(data.data.value);           

      // }
      // if(data.data.heading == '')
      // {
      //   $('#heading').hide(); 
      // }
      // else
      // {
      //   $('#heading').html(data.data.heading);           

      // }
      if(data.data.description == '' || data.data.description == 'null' || typeof data.data.description == 'object')
      {
        $('#desc_div').hide();
        $('#description').hide(); 
      }
      else
      {
        $('#desc_div').show();
        $('#description').html(data.data.description);           

      }
      var image = data.data.images;
      if(image.length != '')
      {
        //var html = '<div> <img u="image" src="{{web_url}}asset/img/travel/01.jpg"> <img u="thumb" src="{{web_url}}asset/img/travel/thumb-01.jpg"> </div>';
        var html = '';

        $.each(data.data.images, function( index, value ) {
                
                  html += '<div> <img data-bla="dd" u="image" src="'+webUrl+'Repo/timthumb.php?src='+value.web_url+'&h=480&w=720&zc=0">\
                  <img u="thumb" src= "'+webUrl+'Repo/timthumb.php?src='+value.web_url+'&h=80&w=800&zc=0" > </div>';

              });   



          $('#images').html(html);
          $('.popup').fadeIn();
          jssor_slider1_starter('slider1_container');

          
      }
      else
      {

      }

      },
      error:function(jqxhr){
      }
    });


}

function showPopup()
{
  jssor_slider1_starter('slider1_container');  
  $('.popup').fadeIn();
}