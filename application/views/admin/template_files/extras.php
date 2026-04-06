<?php include 'inc/header.php'; ?>  
<?php include 'inc/sidebar.php'; ?> 
<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title">Extras</h4>
            <div class="btn-group btn-group-page-header ml-auto">
               <button type="button" class="btn btn-success btn-rounded"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
               <i class="fa fa-plus mr-2"></i> New Extras
               </button>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="collapse show" id="collapseExample" style="">
                  <div class="card">
                     <div class="card-header">
                        <div class="card-title">New extra item</div>
                     </div>
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-12 col-main">
                              <div class="input-group">
                                 <input id="Name" type="text" class="form-control" placeholder="Name">
                                 <select class="form-control" id="formGroupDefaultSelect" style="height: 40px;">
                                    <option>Value Type</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                 </select>
                                 <input id="Name" type="text" class="form-control price-box" placeholder="Value">
                                 <span class="btn-box"> 
                                 <button type="button" onclick="addMainExtra(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button>
                           </span>
                           <div   class=" d-block mt-2 w-100 small-btn">
                         <a style="cursor: pointer;" class="text-primary" onclick="addSubItem(this)">Add Sub</a>
                         </div>
                              </div>
                               
                           </div>
                       
                           <div class="col-md-12 show-sub-item-form-wrpr d-none">
                              <div class="row">
                                 <div class="col-md-12">
                                    <hr/>
                                    <div class="card-title mb-3">Add Sub</div>
                                 </div>
                                 <div class="col-12 show-sub-item-form">
                                    <ul class="list-unstyled"></ul>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12 clearfix mt-3  d-flex align-items-center justify-content-center justify-content-lg-center">
                              <button type="button" id="addRowButton" class="btn btn-primary mx-auto">Add</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card">
                  <!--           <div class="card-header">
                     <div class="d-flex align-items-center">
                        <h4 class="card-title">Products</h4>
                        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
                        <i class="fa fa-plus"></i>
                        Add Company
                        </button>
                     </div>
                     </div> -->
                  <div class="card-body">
                     <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover" >
                           <thead>
                              <tr>
                                 <th>ID</th>
                                 <th>Name</th>
                                 <th>Type</th>
                                 <th>Unit (%/Unit)</th>
                                 <th>Price</th>
                                 <th style="width: 10%">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>2252</td>
                                 <td>Curtain Gold</td>
                                 <td>Type 1</td>
                                 <td>100 (Unit)</td>
                                 <td>200</td>
                                 <td>
                                    <div class="form-button-action">
                                       <!--  <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                          <i class="fa fa-edit"></i>
                                          </button> -->
                                       <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                       <i class="fa fa-times"></i>
                                       </button>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                     <hr/>
                     <nav aria-label="Page navigation example">
                        <ul class="pagination mt-4">
                           <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                           <li class="page-item active"><a class="page-link" href="#">1</a></li>
                           <li class="page-item"><a class="page-link" href="#">2</a></li>
                           <li class="page-item"><a class="page-link" href="#">3</a></li>
                           <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                     </nav>
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
   
   
   // function addSubItem(e){
   //      var a = 1;
   // $('.show-sub-item-form-wrpr').toggleClass('d-none');
   
   // $('.show-sub-item-form').find('ul').html('<li> <div class="row row-1"><div class="col-md-8"> <div class="form-group mb-2 p-0"> <input id="Name" type="text" class="form-control" placeholder="Sub Item Name '+ a +'"> </div></div><div class="col-md-3 first-sub-price"> <div class="form-group p-0"> <input id="Name" type="text" class="form-control price-box" placeholder="Price"> </div></div><div class="col-md-1"> <div class="form-group mb-2 p-0"> <button type="button" onclick="addSubItem2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button> </div></div></div><div class="row add-more-items"><div class="col-12 col-new"><ul class="list-unstyled"></ul></div></div></li><li class="set-2"></li>');
   // $('.price-column').addClass('d-none');
   
   
   // }
   
   
   
   // var count = 1;
   // function addSubItem2(e){
   
   
   
   
   //    //cache the lis since we need to use it again
   // var $lis = $('.show-sub-item-form ul li');
   // //check if there are more than 5 elements
   // if ($lis.length > 0) {
   
   
   
   
   
   // count++;
   
   
   
   
   // $('.item-price-col').addClass('d-none');
   
   //   $('.show-sub-item-form').find('ul li.set-2').append('<div style="margin-left:'+count+5 +'px">  <div class="row bg-gray  row-1 row-2"><div class="col-md-8"> <div class="form-group p-0 mb-2 "> <input id="Name" type="text" class="form-control" placeholder="Sub Item Name '+ count +'"> </div></div><div class="col-md-3 item-price-col"> <div class="form-group mb-2 p-0"> <input id="Name" type="text" class="form-control price-box" placeholder="Price"> </div></div><div class="col-md-1"> <div class="form-group mb-2 p-0 btn-col"> <button type="button" onclick="addSubItem2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button> </div></div></div></div>');
   // $('.first-sub-price').addClass('d-none');
   
   
   // $(e).parent().html('<button type="button" onclick="removeSubItem2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-minus"></i></button>');
   
   // // .find('.price-box').removeClass('d-none')
   
   
   
   
   // }
   
   
   // //style="margin-left:'+count+5 +'px"
   
   
   // }
   
   // function removeSubItem2(e){
   
   
   
   //    $('.show-sub-item-form  li').each(function() {
   // var $this = $(this);
   // if ($this.find('div').length > 1) { //if looking for direct descendants then do .children('div').length
   //  $(e).parent().parent().parent().parent().addClass('d-none');
   // }
   // });
   
   
   
   
   
   
   
   
   // }
    var htmlt = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control" id="formGroupDefaultSelect" style="height: 40px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span>   <div   class=" d-block mt-2 w-100 small-btn"><a style="cursor: pointer;" class="text-primary" onclick="addSubItem(this)">Add Sub</a></div></div>';



  var htmlt2 = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control" id="formGroupDefaultSelect" style="height: 41px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra2(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2"><a style="cursor: pointer;" class="text-primary" onclick="addSubItem2(this)">Add Sub</a></div>     ';


    var htmlt3 = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control" id="formGroupDefaultSelect" style="height: 41px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2"><a style="cursor: pointer;" class="text-primary" onclick="addSubItem2(this)">Add Sub</a>   <a style="cursor: pointer;" class="text-primary ml-3" onclick="removeSubItem2(this)">Remove Sub</a></div>     ';







   function addMainExtra(e){
     


$(e).parents('.col-main').append(htmlt);
$(e).parents('.input-group').find('.price-box').addClass('d-none');

$(e).parents('.input-group').find('.btn-box').html('<button onclick="removeMainItem(this)" type="button" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-minus"></i></button>');


   }


   function removeMainItem(e){
$(e).parents('.input-group').removeClass('mb-3 mt-3');
$(e).parents('.input-group').html('');





   }



var count = 1;
 function  addSubItem(e){
   count++;
$(e).parents('.input-group').find('.price-box').addClass('d-none');


//

$(e).parents('.input-group').append('<div style="background:#eee; margin-left:'+count+'5px" class="w-100 sub-wr p-3 mt-2">'+htmlt2+'</div>');

$(e).parents('.input-group').find('.small-btn').html('<a style="cursor: pointer;" class="text-primary"  onclick="removeSubItem(this)">Remove Sub</a>');


 }


function removeSubItem(e){


$(e).parents('.input-group').find('.sub-wr').removeClass('p-3');
$(e).parents('.input-group').find('.sub-wr').html('');

$(e).parents('.input-group').find('.small-btn').html('<a style="cursor: pointer;" class="text-primary"  onclick="addSubItem(this)">Add Sub</a>');
}



  function  addSubItem2(e){
    count++;
$(e).parents('.sub-wr').append('<div style=" margin-left:'+count+'5px" class="w-100 sub-wr p-3 mt-2"><hr>'+htmlt3+'</div>');

 }



 function removeSubItem2(e){


$(e).parent().parent().parent().remove();

}



   function addMainExtra2(e){
    
 $(e).parents('.input-group').find('.price-box').addClass('d-none');


 $(e).html('<i class="fa fa-minus"></i>');
  $(e).attr('onclick', 'removeExtra2(this)');
$(e).parents('.sub-wr').append('<div class="mt-2">'+htmlt3+'</div>');
 }


    function removeExtra2(e){
    
$(e).parent().parent().remove();
 }

</script>