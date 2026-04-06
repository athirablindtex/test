<?php include 'inc/header.php'; ?>  
<?php include 'inc/sidebar.php'; ?> 
<div class="main-panel">
         <div class="content">
            <div class="page-inner">
               <h4 class="page-title">User Profile</h4>
               <div class="row">
                  <div class="col-md-8">
                     <div class="card card-with-nav">
               <div class="card-body">
                           <div class="row mt-3">
                              <div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Name" value="Hizrian">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Name" value="hello@example.com">
                                 </div>
                              </div><div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Password</label>
                                    <input type="email" class="form-control" name="email" placeholder="Name" value="hello@example.com">
                                 </div>
                              </div>
                           </div>
                           
                           
                           
                           <div class="text-right mt-3 mb-3">
                              <button class="btn btn-primary">Save</button>
                              
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="card card-profile card-secondary">
                        <div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
                           <div class="profile-picture">
                              <div class="avatar avatar-xl">
                                 <img src="assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
                              </div>
                           </div>
                        </div>
                        <div class="card-body">
                           <div class="user-profile text-center">
                              <div class="name">Hizrian, 19</div>
                              <div class="job">Frontend Developer</div>
                              <div class="desc">A man who hates loneliness</div>
                              
                              <div class="view-profile">
                                 <a href="#" class="btn btn-secondary btn-block">Log Out</a>
                              </div>
                           </div>
                        </div>
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
         
      </div>
<?php include 'inc/footer.php'; ?>
<script >
   $(document).ready(function() {
      $('#basic-datatables').DataTable({
      });
   
      $('#multi-filter-select').DataTable( {
         "pageLength": 5,
         initComplete: function () {
            this.api().columns().every( function () {
               var column = this;
               var select = $('<select class="form-control"><option value=""></option></select>')
               .appendTo( $(column.footer()).empty() )
               .on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex(
                     $(this).val()
                     );
   
                  column
                  .search( val ? '^'+val+'$' : '', true, false )
                  .draw();
               } );
   
               column.data().unique().sort().each( function ( d, j ) {
                  select.append( '<option value="'+d+'">'+d+'</option>' )
               } );
            } );
         }
      });
   
   });


   function addSubItem(e){
        var a = 1;
   $('.show-sub-item-form-wrpr').toggleClass('d-none');

   $('.show-sub-item-form').find('ul').html('<li> <div class="row row-1"><div class="col-md-8"> <div class="form-group mb-2 p-0"> <input id="Name" type="text" class="form-control" placeholder="Sub Item Name '+ a +'"> </div></div><div class="col-md-3 first-sub-price"> <div class="form-group p-0"> <input id="Name" type="text" class="form-control price-box" placeholder="Price"> </div></div><div class="col-md-1"> <div class="form-group mb-2 p-0"> <button type="button" onclick="addSubItem2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button> </div></div></div><div class="row add-more-items"><div class="col-12 col-new"><ul class="list-unstyled"></ul></div></div></li><li class="set-2"></li>');
$('.price-column').addClass('d-none');

  
   }



var count = 1;
   function addSubItem2(e){




      //cache the lis since we need to use it again
var $lis = $('.show-sub-item-form ul li');
//check if there are more than 5 elements
if ($lis.length > 0) {





   count++;




 $('.item-price-col').addClass('d-none');

     $('.show-sub-item-form').find('ul li.set-2').append('<div style="margin-left:'+count+5 +'px">  <div class="row bg-gray  row-1 row-2"><div class="col-md-8"> <div class="form-group p-0 mb-2 "> <input id="Name" type="text" class="form-control" placeholder="Sub Item Name '+ count +'"> </div></div><div class="col-md-3 item-price-col"> <div class="form-group mb-2 p-0"> <input id="Name" type="text" class="form-control price-box" placeholder="Price"> </div></div><div class="col-md-1"> <div class="form-group mb-2 p-0 btn-col"> <button type="button" onclick="addSubItem2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button> </div></div></div></div>');
$('.first-sub-price').addClass('d-none');


$(e).parent().html('<button type="button" onclick="removeSubItem2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-minus"></i></button>');

// .find('.price-box').removeClass('d-none')




}


//style="margin-left:'+count+5 +'px"


   }

   function removeSubItem2(e){



      $('.show-sub-item-form  li').each(function() {
  var $this = $(this);
  if ($this.find('div').length > 1) { //if looking for direct descendants then do .children('div').length
    $(e).parent().parent().parent().parent().addClass('d-none');
  }
});







   
   }

</script>