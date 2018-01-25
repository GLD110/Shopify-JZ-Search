/**
 * Created by user on 27/7/16.
 */
$(document).ready(function () {
    //Square blue color scheme for iCheck
    $('input[type="checkbox"].square-blue').iCheck({
        checkboxClass: 'icheckbox_square-blue'
    });

    $("#authentication").bootstrapValidator({
        fields: {
            email1: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Password is required'
                    }

                }
            }
        }
    });
    $("#reset_pw").bootstrapValidator({
        fields: {
            username2: {
                validators: {
                    notEmpty: {
                        message: 'A registered email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            }
        }
    });

    $("#register_here").bootstrapValidator({
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'First name is required'
                    }
                },
                required: true,
                minlength: 3
            },
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'Last name is required'
                    }
                },
                required: true,
                minlength: 3
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
            email_confirm: {
                validators: {
                    notEmpty: {
                        message: 'The confirm email address is required'
                    },
                    identical: {
                        field: 'email',
                        message: 'Entered email is not matching with your email'
                    }
                }
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
            }
        }
    });

});
