
    $(document).ready(function() {
        $(
            'input#defaultconfig'
        ).maxlength({
            alwaysShow: true,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger"
        });

        $(
            'input#thresholdconfig'
        ).maxlength({
            threshold: 20,
            alwaysShow: true,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger"

        });
        $(".display-no").hide();
        $(
            'input#moreoptions'
        ).maxlength({
            alwaysShow: true,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger"
        });

        $(
            'input#alloptions'
        ).maxlength({
            alwaysShow: true,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger",
            separator: ' chars out of ',
            preText: 'You typed ',
            postText: ' chars.',
            validate: true
        });


        $(
            'textarea#textarea'
        ).maxlength({
            alwaysShow: true
        });

        $('input#placement')
            .maxlength({
                alwaysShow: true,
                placement: 'bottom'
            });

    });
  $('#card').card({
      container: $('.card-wrapper')
  });

    