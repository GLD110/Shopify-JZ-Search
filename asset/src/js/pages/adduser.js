// bootstrap wizard//
$(".select21").select2({
    theme:"bootstrap",
    placeholder:"",
    width: '100%'
});
$('input[type="checkbox"].custom-checkbox, input[type="radio"].custom-radio').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue',
    increaseArea: '20%'
});
$("#commentForm").bootstrapValidator({
    fields: {
        first_name: {
            validators: {
                notEmpty: {
                    message: 'The first name is required'
                }
            },
            required: true,
            minlength: 3
        },
        last_name: {
            validators: {
                notEmpty: {
                    message: 'The last name is required'
                }
            },
            required: true,
            minlength: 3
        },
        password: {
            validators: {
                notEmpty: {
                    message: 'Password is required'
                },
                different: {
                    field: 'first_name,last_name',
                    message: 'Password should not match first or last name'
                }
            }
        },
        password_confirm: {
            validators: {
                notEmpty: {
                    message: 'Confirm Password is required'
                },
                identical: {
                    field: 'password'
                },
                different: {
                    field: 'first_name,last_name',
                    message: 'Confirm Password should match with password'
                }
            }
        },
        email: {
            validators: {
                notEmpty: {
                    message: 'The email address is required'
                },
                emailAddress: {
                    message: 'The input is not a valid email address'
                }
            }
        },
        bio: {
            validators: {
                notEmpty: {
                    message: 'Bio is required and cannot be empty'
                }
            },
            minlength: 20
        },

        gender: {
            validators: {
                notEmpty: {
                    message: 'Please select a gender'
                }
            }
        },
        activate: {
            validators: {
                notEmpty: {
                    message: 'Please check the checkbox to activate'
                }
            }
        },
        group: {
            validators:{
                notEmpty:{
                    message: 'You must select a group'
                }
            }
        }
    }
});

$('#activate').on('ifChanged', function(event){
    $('#commentForm').bootstrapValidator('revalidateField', $('#activate'));
});

$('#rootwizard').bootstrapWizard({
    'tabClass': 'nav nav-pills',
    'onNext': function(tab, navigation, index) {
        var $validator = $('#commentForm').data('bootstrapValidator').validate();
        return $validator.isValid();
    },
    onTabClick: function(tab, navigation, index) {
        return false;
    },
    onTabShow: function(tab, navigation, index) {
        var $total = navigation.find('li').length;
        var $current = index+1;
        var $percent = ($current/$total) * 100;

        // If it's the last tab then hide the last button and show the finish instead
        if($current >= $total) {
            $('#rootwizard').find('.pager .next').hide();
            $('#rootwizard').find('.pager .finish').show();
            $('#rootwizard').find('.pager .finish').removeClass('disabled');
        } else {
            $('#rootwizard').find('.pager .next').show();
            $('#rootwizard').find('.pager .finish').hide();
        }
        $('#rootwizard .finish').click(function() {
            var $validator = $('#commentForm').data('bootstrapValidator').validate();
            if ($validator.isValid()) {
                $('#myModal').modal('show');
                return $validator.isValid();
                $('#rootwizard').find("a[href='#tab1']").tab('show');
            }
        });

    }});
