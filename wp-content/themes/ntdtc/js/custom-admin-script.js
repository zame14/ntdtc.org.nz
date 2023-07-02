jQuery(function($) {
    $(".approve").click(function() {
        var id = $(this).data('id');
        //$("#approve-member .spinner").html("<img src='/wp-content/plugins/wp-views/embedded/res/img/ajax-loader3.svg' />").hide().fadeIn();
        $.ajax({
            url: ajaxurl + "?action=ajax&call=approveMember&member_id=" + id,
            cache: false,
            success: function (response) {
                window.location.href = response;
            }
        });
    });
    $(".approve-class-enrolment").click(function() {
        var id = $(this).data('id');
        //$("#approve-member .spinner").html("<img src='/wp-content/plugins/wp-views/embedded/res/img/ajax-loader3.svg' />").hide().fadeIn();
        $.ajax({
            url: ajaxurl + "?action=ajax&call=approveClassEnrolment&enrolment_id=" + id,
            cache: false,
            success: function (response) {
                window.location.href = response;
            }
        });
    });
});