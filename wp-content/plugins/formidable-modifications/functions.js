// JavaScript Document
jQuery(document).ready(function() {

    //wrap frm_submit
    /*jQuery(".frm_forms form fieldset").append("<div class='sticky-wrapper'></div>");
    jQuery(".frm_forms form .frm_submit").appendTo(".sticky-wrapper");
    jQuery(".frm_forms form .frm_submit").addClass("stuck");

    var waypoints = jQuery('.sticky-wrapper').waypoint(function(direction) {
        if (direction == "up") {
            jQuery(".frm_forms form .frm_submit").addClass("stuck");
        } else {
            jQuery(".frm_forms form .frm_submit").removeClass("stuck");
        }
    }, {
        offset: 'bottom-in-view'
    })*/

    //added by cloudred on May 1,2015
    //modifyng formidable form to show append the "other field" with the html char "&#8627;" arrow for better visual recognition

    var arrow = '<span class="other-arrow">&#8627;</span>';
    if (jQuery('.frm_other_input')) {
        //prepend the arrow
        jQuery(".frm_other_container select").after(function() {
            return arrow;
        });
    }
    jQuery(".frm_other_container select").change(function() {
        //if other has been selected, show the arrow
        if (jQuery(this).val() == "Other") {
            jQuery(this).parent().find(".other-arrow").css('display', 'inline-block');
        } else {
            jQuery(this).parent().find(".other-arrow").css('display', 'none');
        }
    });

    //kep track of number of words entered in textreas where limits are set
    //update word counter fields
    function evalDom() {
        jQuery(".frm_form_field.enforcecount").each(function() {
            //last classname is the word count allowed
            var word_limit = jQuery(this).attr('class').split(' ').pop();
            if (!jQuery(this).find(".frm_description span").length) {
                jQuery(this).find(".frm_description").append(jQuery("<span>"));
                jQuery(this).find(".frm_description").find("span").addClass("word_count");
            }
            jQuery(this).find("textarea, input").keyup(function() {
                var words = jQuery(this).val().split(' ');
                var words_remaining = Number(word_limit - words.length) + 1;
                jQuery(this).parent().find(".word_count").html(" You have <strong>" + words_remaining + "</strong> words remaining.");
				if (words_remaining <= 0) {
                    jQuery(this).parent().find(".word_count").addClass("limit_reached");
                    jQuery(this).parent().find(".word_count").removeClass("limit_ok");
                    //jQuery(this).parent().find(".word_count").html(" You are over the limit by <strong>" + words_remaining + "</strong>");
					//limit to word count, remove any other characteres typed after that.
					this.value = this.value.substr(0,this.value.length-1);

                } else {
                  
                    jQuery(this).parent().find(".word_count").removeClass("limit_reached");
                    jQuery(this).parent().find(".word_count").addClass("limit_ok");

                }
            });
            //trigger on load
            jQuery(this).find("textarea, input").keyup();
        });
    }


    evalDom();

});