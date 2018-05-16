
 <div class="container">
        <div class="" style="margin-top:150px;">
            <!-- form: -->
            <section>
			
             
				
                <div class="row justify-content-center">
				
                  

                    <form id="defaultForm" method="post" class="col-md-6 card " action="<?php echo base_url('admin/loginpost'); ?>">
						<h2 class="h3-responsive pt-4 text-center">
                            <strong>Sign in</strong>
                        </h2>
						<hr>
						<?php $csrf = array(
										'name' => $this->security->get_csrf_token_name(),
										'hash' => $this->security->get_csrf_hash()
								); ?>
								<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
						<div class="form-group">
                            <label class="control-label">Email address</label>
                            <div class="">
                                <input type="text" class="form-control" name="email" />
                            </div>
                        </div>
						<div class="form-group">
                            <label class=" control-label">Password</label>
                            <div class="">
                                <input type="password" class="form-control" name="password" />
                            </div>
                        </div>
							<div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Sign up</button>
                                
                            </div>
                        </div>
                    </form>
                </div>
			
              
            </section>
            <!-- :form -->
        </div>
    </div>
<div class="clearfix">&nbsp;</div>
<script type="text/javascript">
$(document).ready(function() {
    
    $('#defaultForm').bootstrapValidator({
       fields: {
            email: {
                validators: {
					notEmpty: {
						message: 'Email is required'
					},
					regexp: {
					regexp: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
					message: 'Please enter a valid email address. For example johndoe@domain.com.'
					}
				}
            },
           
           password: {
               validators: {
					notEmpty: {
						message: 'Password is required'
					},
					stringLength: {
                        min: 6,
                        message: 'Password  must be at least 6 characters'
                    },
					regexp: {
					regexp:/^[ A-Za-z0-9_@.,/!;:}{@#&`~'"\\|=^?$%*)(_+-]*$/,
					message: 'Password wont allow <>[]'
					}
				}
            }
            
        }
    });

    // Validate the form manually
    $('#validateBtn').click(function() {
        $('#defaultForm').bootstrapValidator('validate');
    });

    $('#resetBtn').click(function() {
        $('#defaultForm').data('bootstrapValidator').resetForm(true);
    });
});
</script>