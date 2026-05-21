	<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">Powered by
							<span class="blue bolder"><a href="https://dotweb.in/" target="_blank">Dotweb.in</a></span> &copy; <?php echo date("Y");?>
						</span>
					</div>
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		

		<!-- <![endif]-->

		<!--[if IE]>
<script src="../../assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='../../assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="../../assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="../../assets/js/excanvas.min.js"></script>
		<![endif]-->
		 <script src="../../assets/js/jquery-ui.custom.min.js"></script>
		<!--<script src="../../assets/js/jquery.ui.touch-punch.min.js"></script> -->
		<script src="../../assets/js/chosen.jquery.min.js"></script>
		<!-- <script src="../../assets/js/spinbox.min.js"></script> -->
		<!-- <script src="../../assets/js/bootstrap-datepicker.min.js"></script>
		<script src="../../assets/js/bootstrap-timepicker.min.js"></script> -->
		<script src="../../assets/js/moment.min.js"></script>
		<!-- <script src="../../assets/js/daterangepicker.min.js"></script>
		<script src="../../assets/js/bootstrap-datetimepicker.min.js"></script>
		<script src="../../assets/js/bootstrap-colorpicker.min.js"></script>
		<script src="../../assets/js/jquery.knob.min.js"></script>
		<script src="../../assets/js/autosize.min.js"></script>
		<script src="../../assets/js/jquery.inputlimiter.min.js"></script>
		<script src="../../assets/js/jquery.maskedinput.min.js"></script>
		<script src="../../assets/js/bootstrap-tag.min.js"></script> -->


<!-- page specific plugin scripts -->
		<!-- <script src="../../assets/js/jquery.dataTables.min.js"></script> -->
		<script src="../../assets/js/jquery.dataTables.bootstrap.min.js"></script>
		<!-- <script src="../../assets/js/dataTables.buttons.min.js"></script>
		<script src="../../assets/js/buttons.flash.min.js"></script>
		<script src="../../assets/js/buttons.html5.min.js"></script>
		<script src="../../assets/js/buttons.print.min.js"></script>
		<script src="../../assets/js/buttons.colVis.min.js"></script>
		<script src="../../assets/js/dataTables.select.min.js"></script> -->

		
		<!-- <script src="../../assets/js/jquery.colorbox.min.js"></script> -->

		<!-- ace scripts -->
		<script src="../../assets/js/ace-elements.min.js"></script>
		<script src="../../assets/js/ace.min.js"></script>

<script type="text/javascript">
			// jQuery(function($) {
			// //datepicker plugin
			// 	//link
			// 	$('.date-picker').datepicker({
			// 		autoclose: true,
			// 		startDate: new Date(),
			// 		todayHighlight: true
			// 	})
			// 	//show datepicker when clicking on the icon
			// 	.next().on(ace.click_event, function(){
			// 		$(this).prev().focus();
			// 	});
			
			// 	//or change it into a date range picker
			// 	$('.input-daterange').datepicker({autoclose:true});		
			// 	//to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
			// 	$('input[name=date-range-picker]').daterangepicker({
			// 		'applyClass' : 'btn-sm btn-success',
			// 		'cancelClass' : 'btn-sm btn-default',
			// 		locale: {
			// 			applyLabel: 'Apply',
			// 			cancelLabel: 'Cancel',
			// 		}
			// 	})
			// 	.prev().on(ace.click_event, function(){
			// 		$(this).next().focus();
			// 	});
			// });
		</script>
		<script type="text/javascript">
			jQuery(function($) {
				if(!ace.vars['touch']) {
					$('.chosen-select').chosen({allow_single_deselect:true}); 
					//resize the chosen on window resize
			
					$(window)
					.off('resize.chosen')
					.on('resize.chosen', function() {
						$('.chosen-select').each(function() {
							 var $this = $(this);
							 $this.next().css({'width': $this.parent().width()});
						})
					}).trigger('resize.chosen');
					//resize chosen on sidebar collapse/expand
					$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
						if(event_name != 'sidebar_collapsed') return;
						$('.chosen-select').each(function() {
							 var $this = $(this);
							 $this.next().css({'width': $this.parent().width()});
						})
					});
				}

				});
			</script>
			<script type="text/javascript">
			// jQuery(function($) {
			// var $overflow = '';
			// var colorbox_params = {
			// 	rel: 'colorbox',
			// 	reposition:true,
			// 	scalePhotos:true,
			// 	scrolling:false,
			// 	previous:'<i class="ace-icon fa fa-arrow-left"></i>',
			// 	next:'<i class="ace-icon fa fa-arrow-right"></i>',
			// 	close:'&times;',
			// 	current:'{current} of {total}',
			// 	maxWidth:'100%',
			// 	maxHeight:'100%',
			// 	onOpen:function(){
			// 		$overflow = document.body.style.overflow;
			// 		document.body.style.overflow = 'hidden';
			// 	},
			// 	onClosed:function(){
			// 		document.body.style.overflow = $overflow;
			// 	},
			// 	onComplete:function(){
			// 		$.colorbox.resize();
			// 	}
			// };

			// $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
			// $("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");//let's add a custom loading icon
			
			
			// $(document).one('ajaxloadstart.page', function(e) {
			// 	$('#colorbox, #cboxOverlay').remove();
		 //   });
		//})
				</script>
		<!--<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-tags">Date</label>
										<div class="col-sm-9">
											<div class="input-group">
												<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy">
												<span class="input-group-addon">
													<i class="fa fa-calendar bigger-110"></i>
												</span>
											</div>
										</div>
									</div>-->