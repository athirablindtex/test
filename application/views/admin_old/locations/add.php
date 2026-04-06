<style>
 
    .containers
    {
        //position: absolute;
        top: 10%; left: 10%; right: 0; bottom: 0;
    }
    .action
    {
        width: 400px;
        height: 30px;
        margin: 10px 0;
    }
    .cropped>img
    {
        margin-right: 10px;
    }
    .imageBox
    {
        position: relative;

        width: 507px;
        height: 373px;
        border:1px solid #aaa;
        background: #fff;
        overflow: hidden;
        background-repeat: no-repeat;
        cursor:move;
    }

    .imageBox .thumbBox
    {
        position: absolute;
        top: 7%;

        width: 427px;
        height: 292px;
        margin-top: 15px;
        margin-left: 40px;
        box-sizing: border-box;
        border: 1px solid rgb(102, 102, 102);
        box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.5);
        background: none repeat scroll 0% 0% transparent;
    }

    .imageBox .spinner
    {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        text-align: center;
        line-height: 400px;
        background: rgba(0,0,0,0.7);
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      <?php echo @$page; ?>
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('admin/'.@$controller); ?>"><?php echo @$module; ?></a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo @$page; ?></h3>
            </div>
              <?php if($this->session->flashdata('info')!=''){ ?>
                <div class="alert alert-info">
  <strong>Info!</strong> <?php echo $this->session->flashdata('info'); ?>
</div>
<?php } ?>
<?php if(validation_errors()){ ?>
       
        <div class="alert alert-danger">
  <strong>Error!</strong>  <?php echo validation_errors(); ?>
</div>
        <?php } ?>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="" method="post" enctype="multipart/form-data">
              <div class="box-body">
             
               <div class="col-lg-6">
        <div class="form-group">
			<label>Name</label>
          <input class="form-control" type="text" name="name" placeholder="Name" value="<?php echo @$res->name!=''?@$res->name:$this->input->post('name'); ?>" required>
       </div>
        </div>
      <div class="col-lg-6">
        <div class="form-group">
			<label>Username</label>
          <input class="form-control" type="text" name="username" placeholder="E-mail" value="<?php echo @$res->username!=''?@$res->username:$this->input->post('username'); ?>" required>
       </div>
        </div>
       
      
     <div class="col-lg-12">
        <div class="form-group">
			<label>Password</label>
          <input class="form-control" type="password" name="password" placeholder="Password" <?= @$this->uri->segment(4) ? '' : 'required'; ?>>
       </div>
        </div>
          
         
         
          
        
         
        
         
           
        
          
        
                             <input type="hidden" name="active" value="<?php echo @$this->uri->segment(4) ? @$res->active : 1 ?>">
                        <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>"> 
              
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

       
          <!-- /.box -->

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <script type="text/javascript">
    window.onload = function () {
        var options =
                {
                    imageBox: '.imageBox',
                    thumbBox: '.thumbBox',
                    spinner: '.spinner',
                    imgSrc: 'avatar.png'
                }
        var cropper;
        document.querySelector('#file').addEventListener('change', function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                options.imgSrc = e.target.result;
                cropper = new cropbox(options);
            }
            reader.readAsDataURL(this.files[0]);
            this.files = [];
        })
        document.querySelector('#btnCrop').addEventListener('click', function () {
            var img = cropper.getDataURL();
            $('#cropped_image').val(img);

            document.querySelector('.cropped').innerHTML = '<img src="' + img + '">';
        })
        document.querySelector('#btnZoomIn').addEventListener('click', function () {
            cropper.zoomIn();
        })
        document.querySelector('#btnZoomOut').addEventListener('click', function () {
            cropper.zoomOut();
        })
    };

    'use strict';
    var cropbox = function (options) {
        var el = document.querySelector(options.imageBox),
                obj =
                {
                    state: {},
                    ratio: 1,
                    options: options,
                    imageBox: el,
                    thumbBox: el.querySelector(options.thumbBox),
                    spinner: el.querySelector(options.spinner),
                    image: new Image(),
                    getDataURL: function ()
                    {
                        var width = this.thumbBox.clientWidth,
                                height = this.thumbBox.clientHeight,
                                canvas = document.createElement("canvas"),
                                dim = el.style.backgroundPosition.split(' '),
                                size = el.style.backgroundSize.split(' '),
                                dx = parseInt(dim[0]) - el.clientWidth / 2 + width / 2,
                                dy = parseInt(dim[1]) - el.clientHeight / 2 + height / 2,
                                dw = parseInt(size[0]),
                                dh = parseInt(size[1]),
                                sh = parseInt(this.image.height),
                                sw = parseInt(this.image.width);

                        canvas.width = width;
                        canvas.height = height;
                        var context = canvas.getContext("2d");
                        context.drawImage(this.image, 0, 0, sw, sh, dx, dy, dw, dh);
                        var imageData = canvas.toDataURL('image/png');
                        return imageData;
                    },
                    getBlob: function ()
                    {
                        var imageData = this.getDataURL();
                        var b64 = imageData.replace('data:image/png;base64,', '');
                        var binary = atob(b64);
                        var array = [];
                        for (var i = 0; i < binary.length; i++) {
                            array.push(binary.charCodeAt(i));
                        }
                        return  new Blob([new Uint8Array(array)], {type: 'image/png'});
                    },
                    zoomIn: function ()
                    {
                        this.ratio *= 1.1;
                        setBackground();
                    },
                    zoomOut: function ()
                    {
                        this.ratio *= 0.9;
                        setBackground();
                    }
                },
        attachEvent = function (node, event, cb)
        {
            if (node.attachEvent)
                node.attachEvent('on' + event, cb);
            else if (node.addEventListener)
                node.addEventListener(event, cb);
        },
                detachEvent = function (node, event, cb)
                {
                    if (node.detachEvent) {
                        node.detachEvent('on' + event, cb);
                    }
                    else if (node.removeEventListener) {
                        node.removeEventListener(event, render);
                    }
                },
                stopEvent = function (e) {
                    if (window.event)
                        e.cancelBubble = true;
                    else
                        e.stopImmediatePropagation();
                },
                setBackground = function ()
                {
                    var w = parseInt(obj.image.width) * obj.ratio;
                    var h = parseInt(obj.image.height) * obj.ratio;
                    var pw = (el.clientWidth - w) / 2;
                    var ph = (el.clientHeight - h) / 2;
                    el.setAttribute('style',
                            'background-image: url(' + obj.image.src + '); ' +
                            'background-size: ' + w + 'px ' + h + 'px; ' +
                            'background-position: ' + pw + 'px ' + ph + 'px; ' +
                            'background-repeat: no-repeat');
                },
                imgMouseDown = function (e)
                {
                    stopEvent(e);
                    obj.state.dragable = true;
                    obj.state.mouseX = e.clientX;
                    obj.state.mouseY = e.clientY;
                },
                imgMouseMove = function (e)
                {
                    stopEvent(e);
                    if (obj.state.dragable)
                    {
                        var x = e.clientX - obj.state.mouseX;
                        var y = e.clientY - obj.state.mouseY;
                        var bg = el.style.backgroundPosition.split(' ');
                        var bgX = x + parseInt(bg[0]);
                        var bgY = y + parseInt(bg[1]);
                        el.style.backgroundPosition = bgX + 'px ' + bgY + 'px';
                        obj.state.mouseX = e.clientX;
                        obj.state.mouseY = e.clientY;
                    }
                },
                imgMouseUp = function (e)
                {
                    stopEvent(e);
                    obj.state.dragable = false;
                },
                zoomImage = function (e)
                {
                    var evt = window.event || e;
                    var delta = evt.detail ? evt.detail * (-120) : evt.wheelDelta;
                    delta > -120 ? obj.ratio *= 1.1 : obj.ratio *= 0.9;
                    setBackground();
                }

        obj.spinner.style.display = 'block';
        obj.image.onload = function () {
            obj.spinner.style.display = 'none';
            setBackground();
            attachEvent(el, 'mousedown', imgMouseDown);
            attachEvent(el, 'mousemove', imgMouseMove);
            attachEvent(document.body, 'mouseup', imgMouseUp);
            var mousewheel = (/Firefox/i.test(navigator.userAgent)) ? 'DOMMouseScroll' : 'mousewheel';
            attachEvent(el, mousewheel, zoomImage);
        };
        obj.image.src = options.imgSrc;
        attachEvent(el, 'DOMNodeRemoved', function () {
            detachEvent(document.body, 'DOMNodeRemoved', imgMouseUp)
        });
        return obj;
    };

</script>
    <!-- elRTE -->
<script type="text/javascript" charset="utf-8">
			$().ready(function() {
				var opts = {
					cssClass : 'el-rte',
					lang     : 'en',
					allowSource : 1,  // allow user to view source
					height   : 450,   // height of text area
					toolbar  : 'maxi',   // Your options here are 'tiny', 'compact', 'normal', 'complete', 'maxi', or 'custom'
					cssfiles : ['<?php echo base_url(); ?>assets/js/elrte/css/elrte-inner.css'],
					// elFinder
					fmAllow  : 1,
					fmOpen : function(callback) {
						$('<div id="myelfinder" />').elfinder({
							url : '../connectors/php/connector.php', // elFinder configuration file.
							lang : 'en',
							dialog : { width : 900, modal : true, title : 'Files' }, // Open in dialog window
							closeOnEditorCallback : true, // Close after file select
							editorCallback : callback     // Pass callback to file manager
						})
					}
					//end of elFinder
				}
				$('#editor').elrte(opts); // id of textarea you want rich edit on
			})
		</script>
        <!--<script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
            });
        </script>-->
        <script type="text/javascript">
  $(function () {
    $('#datetimepicker1').datepicker({
      viewMode: 'years'
    });
	 $('#datetimepicker2').datepicker({
      viewMode: 'years'
    });
  });
 </script>
  <script type="text/javascript">
      $(function () {
      		$('#reservation').daterangepicker();
			<?php if((@$res->from_date=='') && (@$res->to_date=='')){ ?>
			$('#reservation').val('');
			<?php } ?>
			<?php if((@$res->from_date=='0000-00-00') && (@$res->to_date=='0000-00-00')){ ?>
			$('#reservation').val('');
			<?php } ?>
       });
	   $('#datepicker').datepicker({
      autoclose: true
    });
    </script>
<!-- elRTE -->