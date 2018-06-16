<h1 class="bg-white content-heading border-bottom" style="text-align: center; margin-top: 50px;">Calendário</h1>
<div class="container theme-showcase">
    <div id="holder" class="row" ></div>
    <div>
        <h4>Legenda:</h4>
        <p><span style="background:#00008B">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Pendente</p>
        <p><span style="background:#006400">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Finalizada</p>
        <p><span style="background:#8B0000">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Atrasada</p>
    </div>

    <!-- Modal -->
    <div id="novaTarefa" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal-title">Nova Tarefa</h4>
                </div>
                <div class="modal-body">
                    <form id="nova_tarefa" action="#">
                        <div class="form-group" >
                            <label for="titulo">Título:</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required="true">
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição:</label>
                            <textarea class="form-control" id="descricao" name="descricao" style="resize: vertical" required="true"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="inicio">Ínicio:</label>
                            <input type="date" class="form-control" id="inicio" name="inicio" readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label for="fim">Fim:</label>
                            <input type="date" class="form-control" id="fim" name="fim" required="true">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="salvar">Salvar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>

        </div>
    </div>
</div>


<script type="text/tmpl" id="tmpl">
    {{ 
    var date = date || new Date(),
    month = date.getMonth(), 
    year = date.getFullYear(), 
    first = new Date(year, month, 1), 
    last = new Date(year, month + 1, 0),
    startingDay = first.getDay(), 
    thedate = new Date(year, month, 1 - startingDay),
    dayclass = lastmonthcss,
    today = new Date(),
    i, j; 
    if (mode === 'week') {
    thedate = new Date(date);
    thedate.setDate(date.getDate() - date.getDay());
    first = new Date(thedate);
    last = new Date(thedate);
    last.setDate(last.getDate()+6);
    } else if (mode === 'day') {
    thedate = new Date(date);
    first = new Date(thedate);
    last = new Date(thedate);
    last.setDate(thedate.getDate() + 1);
    }

    }}
    <table class="calendar-table table table-condensed table-tight">
    <thead>
    <tr>
    <td colspan="7" style="text-align: center">
    <table style="white-space: nowrap; width: 100%">
    <tr>
    <td style="text-align: left;">
    <span class="btn-group">
    <button class="js-cal-prev btn btn-default"><</button>
    <button class="js-cal-next btn btn-default">></button>
    </span>
    <button class="js-cal-option btn btn-default {{: first.toDateInt() <= today.toDateInt() && today.toDateInt() <= last.toDateInt() ? 'active':'' }}" data-date="{{: today.toISOString()}}" data-mode="month">{{: todayname }}</button>
    </td>
    <td>
    <span class="btn-group btn-group-lg">
    {{ if (mode !== 'day') { }}
    {{ if (mode === 'month') { }}<button class="js-cal-option btn btn-link" data-mode="year">{{: months[month] }}</button>{{ } }}
    {{ if (mode ==='week') { }}
    <button class="btn btn-link disabled">{{: shortMonths[first.getMonth()] }} {{: first.getDate() }} - {{: shortMonths[last.getMonth()] }} {{: last.getDate() }}</button>
    {{ } }}
    <button class="js-cal-years btn btn-link">{{: year}}</button> 
    {{ } else { }}
    <button class="btn btn-link disabled">{{: date.toDateString() }}</button> 
    {{ } }}
    </span>
    </td>
    <td style="text-align: right">
    <span class="btn-group">
    <button class="js-cal-option btn btn-default {{: mode==='year'? 'active':'' }}" data-mode="year">Ano</button>
    <button class="js-cal-option btn btn-default {{: mode==='month'? 'active':'' }}" data-mode="month">Mês</button>
    <button class="js-cal-option btn btn-default {{: mode==='week'? 'active':'' }}" data-mode="week">Semana</button>
    </span>
    </td>
    </tr>
    </table>

    </td>
    </tr>
    </thead>
    {{ if (mode ==='year') {
    month = 0;
    }}
    <tbody>
    {{ for (j = 0; j < 3; j++) { }}
    <tr>
    {{ for (i = 0; i < 4; i++) { }}
    <td class="calendar-month month-{{:month}} js-cal-option" data-date="{{: new Date(year, month, 1).toISOString() }}" data-mode="month">
    {{: months[month] }}
    {{ month++;}}
    </td>
    {{ } }}
    </tr>
    {{ } }}
    </tbody>
    {{ } }}
    {{ if (mode ==='month' || mode ==='week') { }}
    <thead>
    <tr class="c-weeks">
    {{ for (i = 0; i < 7; i++) { }}
    <th class="c-name">
    {{: days[i] }}
    </th>
    {{ } }}
    </tr>
    </thead>
    <tbody>
    {{ for (j = 0; j < 6 && (j < 1 || mode === 'month'); j++) { }}
    <tr>
    {{ for (i = 0; i < 7; i++) { }}
    {{ if (thedate > last) { dayclass = nextmonthcss; } else if (thedate >= first) { dayclass = thismonthcss; } }}
    <td name="calendar-day" class="calendar-day {{: dayclass }} {{: thedate.toDateCssClass() }} {{: date.toDateCssClass() === thedate.toDateCssClass() ? 'selected':'' }} {{: daycss[i] }} js-cal-option" data-date="{{: thedate.toISOString() }}">
    <div class="date">{{: thedate.getDate() }}</div>
    {{ thedate.setDate(thedate.getDate() + 1);}}
    </td>
    {{ } }}
    </tr>
    {{ } }}
    </tbody>
    {{ } }}
    {{ if (mode ==='day') { }}
    <tbody>
    <tr>
    <td colspan="7">
    <table class="table table-striped table-condensed table-tight-vert" >
    <thead>
    <tr>
    <th> </th>
    <th style="text-align: center; width: 100%">{{: days[date.getDay()] }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <th class="timetitle" >All Day</th>
    <td class="{{: date.toDateCssClass() }}">  </td>
    </tr>
    <tr>
    <th class="timetitle" >Before 6 AM</th>
    <td class="time-0-0"> </td>
    </tr>
    {{for (i = 6; i < 22; i++) { }}
    <tr>
    <th class="timetitle" >{{: i <= 12 ? i : i - 12 }} {{: i < 12 ? "AM" : "PM"}}</th>
    <td class="time-{{: i}}-0"> </td>
    </tr>
    <tr>
    <th class="timetitle" >{{: i <= 12 ? i : i - 12 }}:30 {{: i < 12 ? "AM" : "PM"}}</th>
    <td class="time-{{: i}}-30"> </td>
    </tr>
    {{ } }}
    <tr>
    <th class="timetitle" >After 10 PM</th>
    <td class="time-22-0"> </td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    {{ } }}
    </table>
</script>

<script>
    var $currentPopover = null;
    $(document).on('shown.bs.popover', function (ev) {
        var $target = $(ev.target);
        if ($currentPopover && ($currentPopover.get(0) != $target.get(0))) {
            $currentPopover.popover('toggle');
        }
        $currentPopover = $target;
    }).on('hidden.bs.popover', function (ev) {
        var $target = $(ev.target);
        if ($currentPopover && ($currentPopover.get(0) == $target.get(0))) {
            $currentPopover = null;
        }
    });
//quicktmpl is a simple template language I threw together a while ago; it is not remotely secure to xss and probably has plenty of bugs that I haven't considered, but it basically works
//the design is a function I read in a blog post by John Resig (http://ejohn.org/blog/javascript-micro-templating/) and it is intended to be loosely translateable to a more comprehensive template language like mustache easily
    $.extend({
        quicktmpl: function (template) {
            return new Function("obj", "var p=[],print=function(){p.push.apply(p,arguments);};with(obj){p.push('" + template.replace(/[\r\t\n]/g, " ").split("{{").join("\t").replace(/((^|\}\})[^\t]*)'/g, "$1\r").replace(/\t:(.*?)\}\}/g, "',$1,'").split("\t").join("');").split("}}").join("p.push('").split("\r").join("\\'") + "');}return p.join('');")
        }
    });
    $.extend(Date.prototype, {
        //provides a string that is _year_month_day, intended to be widely usable as a css class
        toDateCssClass: function () {
            return '_' + this.getFullYear() + '_' + (this.getMonth() + 1) + '_' + this.getDate();
        },
        //this generates a number useful for comparing two dates; 
        toDateInt: function () {
            return ((this.getFullYear() * 12) + this.getMonth()) * 32 + this.getDate();
        },
        toTimeString: function () {
            var hours = this.getHours(),
                    minutes = this.getMinutes(),
                    hour = (hours > 12) ? (hours - 12) : hours,
                    ampm = (hours >= 12) ? ' pm' : ' am';
            if (hours === 0 && minutes === 0) {
                return '';
            }
            if (minutes > 0) {
                return hour + ':' + minutes + ampm;
            }
            return hour + ampm;
        }
    });
    (function ($) {

        //t here is a function which gets passed an options object and returns a string of html. I am using quicktmpl to create it based on the template located over in the html block
        var t = $.quicktmpl($('#tmpl').get(0).innerHTML);
        function calendar($el, options) {
            //actions aren't currently in the template, but could be added easily...
            $el.on('click', '.js-cal-prev', function () {
                switch (options.mode) {
                    case 'year':
                        options.date.setFullYear(options.date.getFullYear() - 1);
                        break;
                    case 'month':
                        options.date.setMonth(options.date.getMonth() - 1);
                        break;
                    case 'week':
                        options.date.setDate(options.date.getDate() - 7);
                        break;
                    case 'day':
                        options.date.setDate(options.date.getDate() - 1);
                        break;
                }
                draw();
            }).on('click', '.js-cal-next', function () {
                switch (options.mode) {
                    case 'year':
                        options.date.setFullYear(options.date.getFullYear() + 1);
                        break;
                    case 'month':
                        options.date.setMonth(options.date.getMonth() + 1);
                        break;
                    case 'week':
                        options.date.setDate(options.date.getDate() + 7);
                        break;
                    case 'day':
                        options.date.setDate(options.date.getDate() + 1);
                        break;
                }
                draw();
            }).on('click', '.js-cal-option', function () {
                var $t = $(this), o = $t.data();
                if (o.date) {
                    o.date = new Date(o.date);
                }
                $.extend(options, o);
                draw();
            }).on('click', '.js-cal-years', function () {
                var $t = $(this),
                        haspop = $t.data('popover'),
                        s = '',
                        y = options.date.getFullYear() - 2,
                        l = y + 5;
                if (haspop) {
                    return true;
                }
                for (; y < l; y++) {
                    s += '<button type="button" class="btn btn-default btn-lg btn-block js-cal-option" data-date="' + (new Date(y, 1, 1)).toISOString() + '" data-mode="year">' + y + '</button>';
                }
                $t.popover({content: s, html: true, placement: 'auto top'}).popover('toggle');
                return false;
            }).on('click', '.event', function () {
                var $t = $(this),
                        index = +($t.attr('data-index')),
                        haspop = $t.data('popover'),
                        data, time;
                if (haspop || isNaN(index)) {
                    return true;
                }
                data = options.data[index];
                time = data.start.toTimeString();
                if (time && data.end) {
                    time = time + ' - ' + data.end.toTimeString();
                }
                var opcao = '</br></br>';
                if (data.situacao !== '2' && <?= $this->session->userdata('nivel') ?> === 2) {
                    opcao += '<div>\n\
                                <button type="button" class="btn btn-success" onclick="finalizar(' + data.id + ')">Finalizar</button>';
                }
                if (data.existente !== '1' && <?= $this->session->userdata('nivel') ?> === 2) {
                    var date = new Date(data.start);
                    var dia = date.getDate();
                    if (dia.toString().length == 1)
                        dia = "0" + dia;
                    var mes = date.getMonth() + 1;
                    if (mes.toString().length == 1)
                        mes = "0" + mes;
                    var ano = date.getFullYear();
                    var data_inicio = ano + "-" + mes + "-" + dia;

                    var date = new Date(data.end);
                    var dia = date.getDate();
                    if (dia.toString().length == 1)
                        dia = "0" + dia;
                    var mes = date.getMonth() + 1;
                    if (mes.toString().length == 1)
                        mes = "0" + mes;
                    var ano = date.getFullYear();
                    var data_fim = ano + "-" + mes + "-" + dia;

                    opcao += ' <button type="button" class="btn btn-info" onclick="editar(\'' + data.title + '\', \'' + data.text + '\', \'' + data_inicio + '\', \'' + data_fim + '\', \'' + data.id + '\')">Editar</button>\n\
                                <button type="button" class="btn btn-danger" onclick="excluir(' + data.id + ')">Excluir</button>\n\
                            </div>';
                }
                $t.data('popover', true);
                $t.popover({content: '<p><strong>' + time + '</strong></p>' + data.text + opcao, html: true, placement: 'auto left'}).popover('toggle');
                return false;
            }).on('click', '.selected', function () {
                    var $t = $(this), o = $t.data();
                    if (o.date) {
                        o.date = new Date(o.date);
                    }

                    var dia = o.date.getDate();
                    if (dia.toString().length == 1)
                        dia = "0" + dia;
                    var mes = o.date.getMonth() + 1;
                    if (mes.toString().length == 1)
                        mes = "0" + mes;
                    var ano = o.date.getFullYear();
                    var data = ano + "-" + mes + "-" + dia;

                    var now = new Date;
                    var dia = now.getDate();
                    if (dia.toString().length == 1)
                        dia = "0" + dia;
                    var mes = now.getMonth() + 1;
                    if (mes.toString().length == 1)
                        mes = "0" + mes;
                    var ano = now.getFullYear();
                    var data_atual = ano + "-" + mes + "-" + dia;

                    if (data >= data_atual) {
                        if (document.querySelector('#editar_tarefa')) {
                            document.getElementById("editar_tarefa").reset();
                            document.getElementById('editar_tarefa').id = 'nova_tarefa';
                            document.getElementById("salvar").setAttribute("onClick", "nova_tarefa();");
                        }
                        $('#novaTarefa').modal('show');
                        $('#modal-title').text('Nova tarefa');
                        document.getElementById("inicio").readOnly = true;
                        document.getElementById("inicio").value = data;
                    }
            });
            function monthAddEvent(index, event) {
                var $event = $('<div/>', {'class': 'event', text: event.title, title: event.title, 'data-index': index}),
                        e = new Date(event.start),
                        dateclass = e.toDateCssClass(),
                        day = $('.' + e.toDateCssClass()),
                        empty = $('<div/>', {'class': 'clear event', html: ' '}),
                        numbevents = 0,
                        time = event.start.toTimeString(),
                        endday = event.end && $('.' + event.end.toDateCssClass()).length > 0,
                        checkanyway = new Date(e.getFullYear(), e.getMonth(), e.getDate() + 40),
                        existing,
                        i;
                //$event.toggleClass('all-day', !!event.allDay);
                $event.toggleClass('pendente', event.situacao === '1');
                $event.toggleClass('finalizada', event.situacao === '2');
                $event.toggleClass('atrasada', event.situacao === '3');
                if (!!time) {
                    $event.html('<strong>' + time + '</strong> ' + $event.html());
                }
                if (!event.end) {
                    $event.addClass('begin end');
                    $('.' + event.start.toDateCssClass()).append($event);
                    return;
                }

                while (e <= event.end && (day.length || endday || options.date < checkanyway)) {
                    if (day.length) {
                        existing = day.find('.event').length;
                        numbevents = Math.max(numbevents, existing);
                        for (i = 0; i < numbevents - existing; i++) {
                            day.append(empty.clone());
                        }
                        day.append(
                                $event.
                                toggleClass('begin', dateclass === event.start.toDateCssClass()).
                                toggleClass('end', dateclass === event.end.toDateCssClass())
                                );
                        $event = $event.clone();
                        $event.html(' ');
                    }
                    e.setDate(e.getDate() + 1);
                    dateclass = e.toDateCssClass();
                    day = $('.' + dateclass);
                }
            }
            function yearAddEvents(events, year) {
                var counts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                $.each(events, function (i, v) {
                    if (v.start.getFullYear() === year) {
                        counts[v.start.getMonth()]++;
                    }
                });
                $.each(counts, function (i, v) {
                    if (v !== 0) {
                        $('.month-' + i).append('<span class="badge">' + v + '</span>');
                    }
                });
            }

            function draw() {
                $el.html(t(options));
                //potential optimization (untested), this object could be keyed into a dictionary on the dateclass string; the object would need to be reset and the first entry would have to be made here
                $('.' + (new Date()).toDateCssClass()).addClass('today');
                if (options.data && options.data.length) {
                    if (options.mode === 'year') {
                        yearAddEvents(options.data, options.date.getFullYear());
                    } else if (options.mode === 'month' || options.mode === 'week') {
                        $.each(options.data, monthAddEvent);
                    } else {
                        $.each(options.data, dayAddEvent);
                    }
                }
            }

            draw();
        }

        ;
        (function (defaults, $, window, document) {
            $.extend({
                calendar: function (options) {
                    return $.extend(defaults, options);
                }
            }).fn.extend({
                calendar: function (options) {
                    options = $.extend({}, defaults, options);
                    return $(this).each(function () {
                        var $this = $(this);
                        calendar($this, options);
                    });
                }
            });
        })({
            days: ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"],
            months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
            shortMonths: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            date: (new Date()),
            daycss: ["c-sunday", "", "", "", "", "", "c-saturday"],
            todayname: "Hoje",
            thismonthcss: "current",
            lastmonthcss: "outside",
            nextmonthcss: "outside",
            mode: "year",
            data: []
        }, jQuery, window, document);
    })(jQuery);
// Carrega os dados
    var data = [],
            date = new Date(),
            end;
<?php foreach ($eventos as $key => $value) : ?>
        end = new Date(<?= explode('-', $value['data_fim'])[0] ?>, <?= explode('-', $value['data_fim'])[1] ?> - 1, <?= explode('-', $value['data_fim'])[2] ?>);
        data.push({id: <?= $key ?>, title: '<?= $value['titulo'] ?>', start: new Date(<?= explode('-', $value['data_inicio'])[0] ?>, <?= explode('-', $value['data_inicio'])[1] ?> - 1, <?= explode('-', $value['data_inicio'])[2] ?>), end: end, text: '<?= $value['descricao'] ?>', situacao: '1', existente: '<?= $value['existente'] ?>'});
<?php endforeach; ?>
//Ordena eventos

    data.sort(function (a, b) {
        return (+a.start) - (+b.start);
    });
//Atualiza o calendário
    $('#holder').calendar({
        data: data
    });
</script>

<style>
    .calendar-day {
        width: 100px;
        min-width: 100px;
        max-width: 100px;
        height: 80px;
    }
    .calendar-table {
        margin: 0 auto;
        width: 700px;
    }
    .selected {
        background-color: #eee;
    }
    .outside .date {
        color: #ccc;
    }
    .timetitle {
        white-space: nowrap;
        text-align: right;
    }
    .event {
        border-top: 1px solid #b2dba1;
        border-bottom: 1px solid #b2dba1;
        background-image: linear-gradient(to bottom, #dff0d8 0px, #c8e5bc 100%);
        background-repeat: repeat-x;
        color: #3c763d;
        border-width: 1px;
        font-size: .75em;
        padding: 0 .75em;
        line-height: 2em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 1px;
    }
    .event.begin {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .event.end {
        margin-right: 2px;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .event.all-day {
        border-top: 1px solid #9acfea;
        border-bottom: 1px solid #9acfea;
        background-image: linear-gradient(to bottom, #d9edf7 0px, #b9def0 100%);
        background-repeat: repeat-x;
        color: #31708f;
        border-width: 1px;
    }

    .event.pendente {
        border-top: 1px solid #00008B;
        border-bottom: 1px solid #00008B;
        background-image: linear-gradient(to bottom, #00008B 0px, #00008B 100%);
        background-repeat: repeat-x;
        color: #ffffff;
        border-width: 1px;
    }

    .event.finalizada {
        border-top: 1px solid #006400;
        border-bottom: 1px solid #006400;
        background-image: linear-gradient(to bottom, #006400 0px, #006400 100%);
        background-repeat: repeat-x;
        color: #ffffff;
        border-width: 1px;
    }

    .event.atrasada {
        border-top: 1px solid #8B0000;
        border-bottom: 1px solid #8B0000;
        background-image: linear-gradient(to bottom, #8B0000 0px, #8B0000 100%);
        background-repeat: repeat-x;
        color: #ffffff;
        border-width: 1px;
    }

    .event.all-day.begin {
        border-left: 1px solid #9acfea;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .event.all-day.end {
        border-right: 1px solid #9acfea;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .event.clear {
        background: none;
        border: 1px solid transparent;
    }
    .table-tight > thead > tr > th,
    .table-tight > tbody > tr > th,
    .table-tight > tfoot > tr > th,
    .table-tight > thead > tr > td,
    .table-tight > tbody > tr > td,
    .table-tight > tfoot > tr > td {
        padding-left: 0;
        padding-right: 0;
    }
    .table-tight-vert > thead > tr > th,
    .table-tight-vert > tbody > tr > th,
    .table-tight-vert > tfoot > tr > th,
    .table-tight-vert > thead > tr > td,
    .table-tight-vert > tbody > tr > td,
    .table-tight-vert > tfoot > tr > td {
        padding-top: 0;
        padding-bottom: 0;
    }

    .popover{
        width: 100%; /* Max Width of the popover (depending on the container!) */
    }

</style>