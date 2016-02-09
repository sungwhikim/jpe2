/**
 * Created by sungwhikim on 2/8/16.
 */

function allCheckBoxes(ele) {
    $(ele).parent().parent().find('input[type="checkbox"]').prop('checked', true);
}

function noneCheckBoxes(ele) {
    $(ele).parent().parent().find('input[type="checkbox"]').prop('checked', false);
}