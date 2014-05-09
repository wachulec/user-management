$(function(){


        //treeview for inner menus
        $("#browser").treeview({
                toggle: function() {
                        console.log("%s was toggled.", $(this).find(">span").text());
                }
        });
        

        // menu superfish
        $('#navigationTop').superfish();

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
        $('#del-dialog').dialog({
                autoOpen: false,
                width: 300,
                buttons: {
                        "Ok": function() { 
                                $('#preloader').css('display', 'none');
                                $(this).dialog("close");
                                window.location.href=$('#delete-link').attr('href');
                        }, 
                        "Anuluj": function() { 
                                $('#preloader').css('display', 'none');
                                $(this).dialog("close");
                        } 
                }
        });

        // Dialog Link
        $('#delete-link').click(function(){
                $('#preloader').css('display', 'block');
                $('#del-dialog').dialog('open');
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
                            $('.tags_box').append('<span class="tag"><span>'+item+'&nbsp;&nbsp;</span><a class="removing-tag" href="#" title="Removing tag">x</a></span>');                            
                        });
                        $('#tags_input_tagsinput span.tag').remove();
                        $('.removing-tag').click(function(){
                            var tag=$(this).parent().children('span').html();
                            var data='tag='+tag;
                            var url=$('#tags_remove_url').val();
                            var html_tag=$(this).parent();
                            $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: data,
                                    success: function() {
                                        html_tag.remove();
                                    },
                                    error: function() {alert('Coś poszło nie tak! Powiedz Wachowi...');
                                    },
                                    complete: function() {
                                    }
                                    //beforeSend, error, success, complete - do wypełnienia
                                });
                        });
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
        });
        
        
        /*ikonka ładowania strony*/
        $(window).load(function() { // Czekamy na załadowanie całej zawartości strony
            $("#preloader #image").fadeOut(); // Usuwamy grafikę ładowania
            $("#preloader").delay(350).fadeOut("fast"); // Usuwamy diva przysłaniającego stronę
        });
        
        $('.removing-tag').click(function(){
            var tag=$(this).parent().children('span').html();
            var data='tag='+tag;
            var url=$('#tags_remove_url').val();
            var html_tag=$(this).parent();
            $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function() {
                        html_tag.remove();
                    },
                    error: function() {alert('Coś poszło nie tak! Powiedz Wachowi...');
                    },
                    complete: function() {
                    }
                    //beforeSend, error, success, complete - do wypełnienia
                });
        });
});