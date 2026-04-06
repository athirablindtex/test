"use strict";
function tfna_alert_danger(title="",message=""){
        $.notify({
            // options
            message: message, 
            title: title
        },{
            // settings
            type: 'danger',
            placement: {
                from: "top",
                align: "center",
            },
            timer: 1000,
        });
    }

    function tfna_alert_success(title="",message=""){
        $.notify({
            // options
            message: message, 
            title: title
        },{
            // settings
            type: 'success',
            placement: {
                from: "top",
                align: "center",
            },
            timer: 1000,
        });
    }    


    function tfna_alert_info(title="",message=""){
        $.notify({
            // options
            message: message, 
            title: title
        },{
            // settings
            type: 'info',
            placement: {
                from: "top",
                align: "center",
            },
            timer: 1000,
        });
    }    