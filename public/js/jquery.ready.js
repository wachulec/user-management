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
        
        $('#dialog_link').click(function(e){
            var str='';
            var data='';
            var url=$('#tags_update_url').val();
            $('#tags_input_tagsinput .tag').each(function(index, item){
                str+=$(item).children().html().substring(0,$(item).children().html().indexOf('&nbsp;'))+'.';
            });
            if(str.length!=0){
                data='data='+str.substring(0,str.length-1);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(obj) {
                        $.each(obj,function(index,item){
                            $('p.lack_of_tags').remove();
                            $('.tags_box').append('<span class="tag"><span>'+item+'&nbsp;&nbsp;</span></span>');                            
                        });
                        $('#tags_input_tagsinput span.tag').remove();
                        //alert('Operacja dodawania tagów zakończona pomyślnie! Odśwież przeglądarkę!');
                    },
                    error: function() {
                        alert('Coś poszło nie tak! Powiedz Wachowi...');
                    },
                    complete: function() {
                    }
                    //beforeSend, error, success, complete - do wypełnienia
                });
            }else{
                alert("Nie podano nazwy!");
            }
            //1. wysłanie danych do skryptu
            //2. odebranie danych z powrotem i wpisanie ich do diva z tagami
            //3. wyczyszczenie tags_input_tagsinput z tagów
            //console.log(data.substring(0,data.length-1));
        });

});