jQuery(function($) {
    if($(".page-id-5").length)
    {
        setTimeout(function () {
            $(".logo-overlay").addClass('ani-show');
        }, 800);
    }
    /*
    setTimeout(function () {
        $(".paws-background").addClass('ani-show');
    }, 200);
    */
    setTimeout(function () {
        $(".logo").addClass('ani-show');
    }, 200);
    /*
    setTimeout(function () {
        $(".black-paw-1").addClass('ani-show');
    }, 800);
    setTimeout(function () {
        $(".black-paw-2").addClass('ani-show');
    }, 1000);
    /*
    $(".fees-wrapper .checkbox").click(function() {
        var fee = $(this).val();
        var label = $(this).data('label');
        if($(this).is(':checked')) {
           updateTotal_add(fee);
        } else {
           updateTotal_minus(fee);
        }
        updateDetails();
    });
    /*
    $("#cred_form_22_1_1_form_submit_1").click(function() {
        // check if payment option selected
        var submit_form = 1;
        var payment = $(".checkbox:checked").val();
        alert(payment);
        if(typeof(payment) === "undefined") {
            submit_form = 0;
            $(".cred-form").submit(function(e){
                e.preventDefault();
                alert("Please select a payment option.");
            });
        }
        if(submit_form == 1) {
            alert(submit_form);
            //$("#cred_form_22_1_1_form_submit_1").unbind('submit').submit();
        }
    });

    $("#cred_form_22_1_1").submit(function(e){
        e.preventDefault();
        var payment = $(".checkbox:checked").val();
        alert(payment);
        $("#cred_form_22_1_1").bind('submit').submit();
    });
     */
    $('.top').click(function(event){
        $('html, body').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
    var waypoint = new Waypoint({
        element: document.getElementById('header'),
        handler: function() {
            $(".top").toggleClass('show');
        },
        offset: -500
    });
});

function updateTotal_add(fee)
{
    var $ = jQuery;
    //get current total
    var total = $(".raw-total").val();
    //add fee onto total
    total = (parseInt(total) + parseInt(fee));
    // update total fields
    $(".raw-total").val(total);
    $("#cred_form_22_1_1_member-amount-paid").val(total);
    $(".total-to-pay-wrapper span").html(total+".00");
}
function updateTotal_minus(fee)
{
    var $ = jQuery;
    var total = $(".raw-total").val();
    //minus fee from total
    total = (parseInt(total) - parseInt(fee));
    // update total fields
    $(".raw-total").val(total);
    $("#cred_form_22_1_1_member-amount-paid").val(total);
    $(".total-to-pay-wrapper span").html(total+".00");
}
function updateDetails() {
    var $ = jQuery;
    var str = "";
    $(".fees-wrapper .checkbox").each(function () {
        if($(this).is(':checked')) {
            var label = $(this).data('label');
            str += label;
            str += ",";
        }
    });
    $(".membership-details").val(str);
}