$(function(){
        $('<div><div id="image"></div></div>').attr('id', 'preloader').appendTo('body');

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
                    $('#preloader').remove();
                    $(this).dialog("close");
                    window.location.href=$('#delete-link').attr('href');
                }, 
                "Anuluj": function() { 
                    $('#preloader').remove();
                    $(this).dialog("close");
                } 
            }
        });

        // Dialog Link
        $('#delete-link').click(function(){
                $('<div></div>').attr('id','preloader').appendTo('body');
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
                    beforeSend: function(){
                        $('<div><div id="image"></div></div>').attr('id', 'preloader').appendTo('body').fadeTo("fast");
                    },
                    success: function(obj) {
                        $.each(obj,function(index,item){
                            $('p.lack_of_tags').remove();
                            $('.tags_box').append('<span class="tag"><span>'+item+'&nbsp;&nbsp;</span><a class="removing-tag" href="#" title="Removing tag">x</a></span>');                            
                        });
                        $('#tags_input_tagsinput span.tag').remove();
                    },
                    error: function() {
                        alert('Coś poszło nie tak! Powiedz Wachowi...');
                    },
                    complete: function() {            
                        $("#preloader #image").fadeOut(); // Usuwamy grafikę ładowania
                        $("#preloader").delay(350).fadeOut("fast", function(){
                            $(this).remove();
                        }); // Usuwamy diva przysłaniającego stronę
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
            $("#preloader").delay(350).fadeOut("fast", function(){
                $(this).remove();
            }); // Usuwamy diva przysłaniającego stronę
        });
        
        $('body').delegate('.removing-tag',"click",function(){
            var tag=$(this).parent().children('span').html();
            var data='tag='+tag;
            var url=$('#tags_remove_url').val();
            var html_tag=$(this).parent();
            $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    beforeSend: function(){  
                        $('<div><div id="image"></div></div>').attr('id', 'preloader').appendTo('body').fadeTo("fast");
                    },
                    success: function() {
                        html_tag.remove();
                    },
                    error: function() {alert('Coś poszło nie tak! Powiedz Wachowi...');
                    },
                    complete: function() {
                        $("#preloader #image").fadeOut(); // Usuwamy grafikę ładowania
                        $("#preloader").delay(350).fadeOut("fast", function(){
                            $(this).remove();
                        }); // Usuwamy diva przysłaniającego stronę
                    }
                    //beforeSend, error, success, complete - do wypełnienia
                });
        });
        
        
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        
        var calendar=$('#calendar').fullCalendar({
            header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
            },
            editable: true,
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                    var title = prompt('Nazwa wydarzenia:');
                    if (title) {
                            calendar.fullCalendar('renderEvent',
                                    {
                                            title: title,
                                            start: start,
                                            end: end,
                                            allDay: allDay
                                    },
                                    true // make the event "stick"
                            );
                    }
                    calendar.fullCalendar('unselect');
            },
            events: [
                    {
                            title: 'All Day Event',
                            start: new Date(y, m, 1)
                    },
                    {
                            title: 'Long Event',
                            start: new Date(y, m, d-5),
                            end: new Date(y, m, d-2)
                    },
                    {
                            id: 999,
                            title: 'Repeating Event',
                            start: new Date(y, m, d-3, 16, 0),
                            allDay: false
                    },
                    {
                            id: 999,
                            title: 'Repeating Event',
                            start: new Date(y, m, d+4, 16, 0),
                            allDay: false
                    },
                    {
                            title: 'Meeting',
                            start: new Date(y, m, d, 10, 30),
                            allDay: false
                    },
                    {
                            title: 'Lunch',
                            start: new Date(y, m, d, 12, 0),
                            end: new Date(y, m, d, 14, 0),
                            allDay: false
                    },
                    {
                            title: 'Birthday Party',
                            start: new Date(y, m, d+1, 19, 0),
                            end: new Date(y, m, d+1, 22, 30),
                            allDay: false
                    },
                    {
                            title: 'Click for Google',
                            start: new Date(y, m, 28),
                            end: new Date(y, m, 29),
                            url: 'http://google.com/'
                    }
            ],
            theme: true,
            firstDay: 1,
            monthNames:['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'],
            monthNamesShort:['sty.','lut','mar','kwi','maj','cze','lip','sie','wrz','paź','lis','gru'],
            dayNames: ['Niedziela','Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota'],
            dayNamesShort: ['nd', 'pn', 'wt', 'śr', 'czw', 'pt', 'sb'],
            columnFormat: {
                month: 'ddd',
                week: 'ddd d',
                day: ''
            },
            axisFormat: 'H:mm', 
            timeFormat: {
                '': 'H:mm', 
            agenda: 'H:mm{ - H:mm}'
            },
            buttonText: {
                today: 'Dzisiaj',
                day: 'dzień',
                week:'tydzień',
                month:'miesiąc'
            },
            titleFormat: {
                month: 'MMMM yyyy',                             // September 2009
                week: "d[ yyyy]{ '&#8212;'[ MMM] d MMMM yyyy}", // Sep 7 - 13 2009
                day: 'dddd, d MMMM yyyy'  
            }
        });
});