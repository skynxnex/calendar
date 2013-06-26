/*jslint browser: true, nomen: true */
/*global _ */

Date.prototype.addHours = function (h) {
    this.setTime(this.getTime() + (h * 60 * 60 * 1000));
    return this;
}

// Simulates PHP's date function
Date.prototype.format=function(e){var t="";var n=Date.replaceChars;for(var r=0;r<e.length;r++){var i=e.charAt(r);if(r-1>=0&&e.charAt(r-1)=="\\"){t+=i}else if(n[i]){t+=n[i].call(this)}else if(i!="\\"){t+=i}}return t};Date.replaceChars={shortMonths:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],longMonths:["January","February","March","April","May","June","July","August","September","October","November","December"],shortDays:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],longDays:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],d:function(){return(this.getDate()<10?"0":"")+this.getDate()},D:function(){return Date.replaceChars.shortDays[this.getDay()]},j:function(){return this.getDate()},l:function(){return Date.replaceChars.longDays[this.getDay()]},N:function(){return this.getDay()+1},S:function(){return this.getDate()%10==1&&this.getDate()!=11?"st":this.getDate()%10==2&&this.getDate()!=12?"nd":this.getDate()%10==3&&this.getDate()!=13?"rd":"th"},w:function(){return this.getDay()},z:function(){var e=new Date(this.getFullYear(),0,1);return Math.ceil((this-e)/864e5)},W:function(){var e=new Date(this.getFullYear(),0,1);return Math.ceil(((this-e)/864e5+e.getDay()+1)/7)},F:function(){return Date.replaceChars.longMonths[this.getMonth()]},m:function(){return(this.getMonth()<9?"0":"")+(this.getMonth()+1)},M:function(){return Date.replaceChars.shortMonths[this.getMonth()]},n:function(){return this.getMonth()+1},t:function(){var e=new Date;return(new Date(e.getFullYear(),e.getMonth(),0)).getDate()},L:function(){var e=this.getFullYear();return e%400==0||e%100!=0&&e%4==0},o:function(){var e=new Date(this.valueOf());e.setDate(e.getDate()-(this.getDay()+6)%7+3);return e.getFullYear()},Y:function(){return this.getFullYear()},y:function(){return(""+this.getFullYear()).substr(2)},a:function(){return this.getHours()<12?"am":"pm"},A:function(){return this.getHours()<12?"AM":"PM"},B:function(){return Math.floor(((this.getUTCHours()+1)%24+this.getUTCMinutes()/60+this.getUTCSeconds()/3600)*1e3/24)},g:function(){return this.getHours()%12||12},G:function(){return this.getHours()},h:function(){return((this.getHours()%12||12)<10?"0":"")+(this.getHours()%12||12)},H:function(){return(this.getHours()<10?"0":"")+this.getHours()},i:function(){return(this.getMinutes()<10?"0":"")+this.getMinutes()},s:function(){return(this.getSeconds()<10?"0":"")+this.getSeconds()},u:function(){var e=this.getMilliseconds();return(e<10?"00":e<100?"0":"")+e},e:function(){return"Not Yet Supported"},I:function(){var e=null;for(var t=0;t<12;++t){var n=new Date(this.getFullYear(),t,1);var r=n.getTimezoneOffset();if(e===null)e=r;else if(r<e){e=r;break}else if(r>e)break}return this.getTimezoneOffset()==e|0},O:function(){return(-this.getTimezoneOffset()<0?"-":"+")+(Math.abs(this.getTimezoneOffset()/60)<10?"0":"")+Math.abs(this.getTimezoneOffset()/60)+"00"},P:function(){return(-this.getTimezoneOffset()<0?"-":"+")+(Math.abs(this.getTimezoneOffset()/60)<10?"0":"")+Math.abs(this.getTimezoneOffset()/60)+":00"},T:function(){var e=this.getMonth();this.setMonth(0);var t=this.toTimeString().replace(/^.+ \(?([^\)]+)\)?$/,"$1");this.setMonth(e);return t},Z:function(){return-this.getTimezoneOffset()*60},c:function(){return this.format("Y-m-d\\TH:i:sP")},r:function(){return this.toString()},U:function(){return this.getTime()/1e3}}

$(function () {
    var Event = Backbone.Model.extend();

    var Events = Backbone.Collection.extend({
        model: Event,
        url: 'events'
    });

    var EventsView = Backbone.View.extend({
        initialize: function () {
            _.bindAll(this);
            this.collection.fetch({async: false});
            this.render();
            this.addAll();
            this.listenTo(this.collection, 'add', this.addOne);
        },
        addOne: function (event) {
            this.$el.fullCalendar('renderEvent', event.toJSON());
        },
        render: function () {
            this.$el.fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay',
                    ignoreTimezone: false
                },
                buttonText: {
                    today: 'I dag',
                    month: 'Månad',
                    week: 'Vecka',
                    day: 'Dag'
                },
                selectable: true,
                editable: true,
                select: this.select,
                eventResize: this.resizeOrMove,
                eventDrop: this.resizeOrMove,
                eventClick: this.eventClick,
                aspectRatio: 2.2,
                ignoreTimezone: false,
                timeFormat: 'H(:mm)',
                axisFormat: 'H(:mm)'

            });
            this.$el.fullCalendar('changeView', 'agendaWeek');
        },
        eventClick: function () {

        },
        resizeOrMove: function (event) {
            console.log(event);
            this.collection.get(event.id).save({start: event.start.format("Y-m-d H:i"), end: event.end.format("Y-m-d H:i")});
        },
        select: function (startDate, endDate) {
            var eventView = new EventView({collection: this.collection, model: new Event({start: startDate, end: endDate})});
            eventView.render();
        },
        addAll: function () {
            this.$el.fullCalendar('addEventSource', this.collection.toJSON());
        }
    });

    var EventView = Backbone.View.extend({
        el: '#eventDialog',
        events: {
            "click #save_event": "save"
        },
        initialize: function () {
            _.bindAll(this);
        },
        render: function () {
            this.$el.modal({
                keyboard: true
            });
            var date = this.model.get('start');
            var startPicker = this.$('#startDate').datetimepicker({
                language: 'en',
                minuteStep: 30,
                pickSeconds: false,
                format: 'yyyy-MM-dd HH:mm'
            }).on('changeDate',function (ev) {
                    endPicker.setStartDate(ev.date);
                    endPicker.setDate(ev.date.addHours(1));
                }).data('datetimepicker');
            startPicker.setDate(date);
            var endPicker = this.$('#endDate').datetimepicker({
                language: 'en',
                pickSeconds: false,
                format: 'yyyy-MM-dd HH:mm'
            }).on('changeDate',function (ev) {
                    if (ev.date.valueOf() < startPicker.getDate().valueOf()) {
                        endPicker.setDate(startPicker.getDate());
                    }
                }).data('datetimepicker');
            endPicker.setDate(date.addHours(1));
            return this;
        },
        save: function (event) {
            event.preventDefault();
            if (this.$('#title').val().length <= 5) {
                this.$('#title').parent().addClass('error');
            }
            else {
                this.model.set({
                    'title': this.$('#title').val(),
                    'start': this.$('#startDate').find('input').val(),
                    'end': this.$('#endDate').find('input').val(),
                    'allDay': false
                });
                this.collection.create(this.model, {wait: true});
                this.close();
            }
        },
        close: function () {
            /* todo reset input values */
            this.$el.modal('hide');
        }
    });

    var events = new Events();
    new EventsView({el: $("#calendar"), "collection": events});
});