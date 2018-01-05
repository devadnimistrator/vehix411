				
    		<!-- bootstrap-daterangepicker -->
    		<script src="assets/js/moment/moment.min.js?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>"></script>
    		<script src="assets/js/datepicker/daterangepicker.js?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>"></script>
    		
    		<!-- iCheck -->
    		<script src="assets/plugins/iCheck/icheck.min.js?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>"></script>
    		
    		<!-- validator -->
				<script src="assets/plugins/validator.min.js?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>"></script>
				
				<!-- validator -->
				<script>
					// initialize the validator function
					validator.message.date = 'not a real date';
				
					// validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
					$('form').on('blur', 'input[required], input.optional, select.required', validator.checkField).on('change', 'select.required', validator.checkField).on('keypress', 'input[required][pattern]', validator.keypress);
				
					$('.multi.required').on('keyup blur', 'input', function() {
						validator.checkField.apply($(this).siblings().last()[0]);
					});
				
					$('form.validateform').submit(function(e) {
						e.preventDefault();
						var submit = true;
				
						// evaluate the form using generic validaing
						if (!validator.checkAll($(this))) {
							submit = false;
						}
				
						if (submit)
							this.submit();
				
						return false;
					}); 
				</script>
				<!-- /validator -->

				<!-- Custom Theme Scripts -->
				<script src="assets/js/custom.js?v=<?php echo ASSETS_CUSTOM_VERSION; ?>"></script>
				
				<!-- footer content -->
				<footer>
					
				</footer>
				<!-- /footer content -->
			</div>
		</div>
	</body>
</html>