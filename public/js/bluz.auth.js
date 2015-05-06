/**
 * Created by yuklia on 04.03.15.
 */
define(['jquery'], function ($) {
    console.log('test');
    "use strict";
    $(function(){
        $('.provider').on('click', function(event){
            event.preventDefault();
            var provider = $(this).data('provider');
            $.ajax({
                type: "POST",
                url: "auth/auth",
                data: { provider: provider }
            });
        })
    })
});
