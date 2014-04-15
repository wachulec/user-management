$(function(){


        //treeview for inner menus
        $("#browser").treeview({
                toggle: function() {
                        console.log("%s was toggled.", $(this).find(">span").text());
                }
        });

        // menu superfish
        //$('#navigationTop').superfish();

        // tags
        $("#tags_input").tagsInput();

        // dataTable
        var uTable = $('#example').dataTable( {
                "sScrollY": 200,
                "bJQueryUI": true,
                "sPaginationType": "full_numbers"
        } );
        $(window).bind('resize', function () {
                uTable.fnAdjustColumnSizing();
        } );


        // Accordion
        $("#accordion").accordion({ header: "h3" });

        // Accordion2
        $("#accordion2").accordion({ header: "h3" });

        // Tabs
        $('#tabs').tabs();


        // Dialog			
        $('#dialog').dialog({
                autoOpen: false,
                width: 600,
                buttons: {
                        "Ok": function() { 
                                $(this).dialog("close"); 
                        }, 
                        "Cancel": function() { 
                                $(this).dialog("close"); 
                        } 
                }
        });

        // Dialog Link
        $('#dialog_link').click(function(){
                $('#dialog').dialog('open');
                return false;
        });

        // Datepicker
        $('#datepicker').datepicker({
                inline: true
        });

        // Slider
        $('#slider').slider({
                range: true,
                values: [17, 67]
        });

        // Progressbar
        $("#progressbar").progressbar({
                value: 20 
        });

        //hover states on the static widgets
        $('#dialog_link, ul#icons li').hover(
                function() { $(this).addClass('ui-state-hover'); }, 
                function() { $(this).removeClass('ui-state-hover'); }
        );

        $('.icons').hover(function(){
            $(this).addClass('ui-state-hover');
        },function(){
            $(this).removeClass('ui-state-hover');
        });

});