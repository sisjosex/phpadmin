var BASE_URL;
var master_template = {};
var modules = {};
var language = {};
var moduleManager;
var modalIndex = 100;
var sidebar;

master_template.label = '<label/>';
master_template.hidden = '<input type="hidden"/>';
master_template.text = '<input type="text"/>';
master_template.textarea = '<textarea/>';
master_template.editor = '<textarea/>';
master_template.date = '<input type="text"/>';
master_template.datetime = '<input type="datetime"/>';
master_template.map = '<div>' +
                        '<input type="text" class="search full-width" placeholder="' + lang('Address') + '"/>' +
                        '<input type="hidden" class="lat" />' +
                        '<input type="hidden" class="lon" />' +
                        '<div class="map" /></div>';
master_template.email = '<input type="email"/>';
master_template.password = '<input type="password"/>';
master_template.upload = '<div class="dropzone">' +
                            '<div class="dz-default dz-message">' +
                                '<span>' + lang('Drop files here to upload') + '</span>' +
                            '</div>' +
                         '</div>' +
                        '<input type="hidden" />';
master_template.mask = '<input type="text"/>';
master_template.dropdown = '<select/>';
master_template.multiple = '<select multiple="multiple"/>';
master_template.composite = '<select multiple="composite"/>';
master_template.daterange = '<input type="text"/>';

master_template.submit = '<input class="btn btn-primary btn-large btn-block" type="submit"/>';
master_template.button = '<a class="btn btn-primary btn-large btn-block"/>';
master_template.field = '<div class="control-group"/>';
master_template.field_footer = '<div class="control-group center"/>';
master_template.form = '<form autocomplete="off"></form>';
master_template.modal = '<div class="modal">' +
    '<div class="modal-box">' +
    '<div class="modal-container">' +
    '<div class="modal-border">' +
    '<div class="modal-header"><h1 class="title"></h1></div>' +
    '<div class="modal-content"></div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';

master_template.grid = '<div class="grid-container"><table class="grid" cellpadding="0" cellspacing="0"/></div>';
master_template.grid_inline = '<table class="grid" cellpadding="0" cellspacing="0"/>';
master_template.row = '<tr />';
master_template.module = '<div class="module"/>';
master_template.thead = '<thead/>';
master_template.tbody = '<tbody/>';
master_template.tfoot = '<tfoot/>';
master_template.td = '<td/>';
master_template.th = '<th/>';
master_template.tr = '<tr/>';
master_template.action = '<button class="btn btn-primary btn-large btn-block"><i class="icon "></i>Delete</button>';
master_template.external = '<div class="external"/>';
master_template.message = '<div class="message"><h3 class="title"></h3></div>';
master_template.tabs_container = '<div />';
master_template.tabs = '<ul class="tabs" />';
master_template.tab = '<li class="tab_content"><a class="title"></a></li>';
master_template.tab_content = '<div class="tab" />';
master_template.field_group = '<div class="field-group"><h3 class="title"></h3></div>';
master_template.field_column = '<div class="field-column"/>';
master_template.sidebar_menu = '<ul class="menu"><li class="group"><a class="text"></a></li></ul>';
master_template.sidebar_row = '<li class="menu"><a class="text"></a></li>';
master_template.sidebar_icon = '<i class="arrow icon fa fa-chevron-right"></i>';
master_template.sidebar_arrow = '<i class="arrow icon fa fa-chevron-right"></i>';
master_template.toolbar = '<div class="toolbar"/>';
master_template.a = '<a/>';
master_template.inline = '<div/>';
master_template.h1 = '<h1/>';
master_template.h2 = '<h2/>';
master_template.h3 = '<h3/>';
master_template.h4 = '<h4/>';
master_template.h5 = '<h5/>';
master_template.div = '<div/>';
master_template.radio = '<div/>';
master_template.checkbox = '<div/>';
master_template.pagination = '<div/>';

var widgets = {};
var containers = {};

var validation = {};
$.validator.setDefaults({ ignore: '' });

validation['default'] = {
    rules: {},
    messages: {},
    errorClass: 'help-inline',
    errorElement: 'span',
    //validHandler: function(e, validator) {},
    highlight: function (element) { // hightlight error inputs
        $(element)
            .closest('.help-inline').removeClass('ok'); // display OK icon
        $(element)
            .closest('.control-group').removeClass('success').addClass('error'); // set error class to the control group
    },

    unhighlight: function (element) { // revert the change dony by hightlight
        $(element)
            .closest('.control-group').removeClass('error'); // set error class to the control group
    },

    success: function (label) {
        label
            .addClass('valid').addClass('help-inline ok') // mark the current input as valid and display OK icon
            .closest('.control-group').removeClass('error').addClass('success'); // set success class to the control group

        return false;
    },
    submitHandler: function (form) {
        $(form).find('.alert-success').show();
        $(form).find('.alert-error').hide();

        return false;
    },
    invalidHandler: function (event, validator) { //display error alert on form submit
        $(event.currentTarget).find('.alert-success').hide();
        $(event.currentTarget).find('.alert-error').show();

        return false;
    }
};

validation.login = {
    rules: {},
    messages: {},
    errorClass: 'help-inline',
    errorElement: 'div',
    //validHandler: function(e, validator) {},
    highlight: function (element) { // hightlight error inputs
        $(element)
            .closest('.help-inline').removeClass('ok'); // display OK icon
        $(element)
            .closest('.control-group').removeClass('success').addClass('error'); // set error class to the control group
    },

    unhighlight: function (element) { // revert the change dony by hightlight
        $(element)
            .closest('.control-group').removeClass('error'); // set error class to the control group
    },

    success: function (label) {
        label
            .addClass('valid').addClass('help-inline ok') // mark the current input as valid and display OK icon
            .closest('.control-group').removeClass('error').addClass('success'); // set success class to the control group
    },
    submitHandler: function (form) {
        $(form).find('.alert-success').show();
        $(form).find('.alert-error').hide();
    },
    invalidHandler: function (event, validator) { //display error alert on form submit
        $(event.currentTarget).find('.alert-success').hide();
        $(event.currentTarget).find('.alert-error').show();
    }
};

var datepickerConfig = {
    format: 'YYYY-MM-DD',
    separator: '-',
    language: 'auto',
    startOfWeek: 'sunday',// or monday
    getValue: function()
    {
        return this.value;
    },
    setValue: function(s)
    {
        this.value = s;
    },
    startDate: false,
    endDate: new Date(),
    minDays: 0,
    maxDays: 0,
    showShortcuts: true,
    time: {
        enabled: false
    },
    shortcuts:
    {
        //'prev-days': [1,3,5,7],
        'next-days': [3,5,7],
        //'prev' : ['week','month','year'],
        'next' : ['week','month','year']
    },
    customShortcuts : [],
    inline:false,
    container: 'body',
    alwaysOpen:false,
    singleDate:false,
    batchMode:false
};

var datepickerConfigSingle = {
    format: 'YYYY-MM-DD',
    autoClose: true,
    singleDate : true,
    showShortcuts: false
};

widgets.daterange = function (element, field, model) {

    var self = this;

    this.init = function () {

        element.dateRangePicker(datepickerConfig);

        return this;
    };

    this.reset = function() {

    };

    return this;
};

widgets.date = function (element, field, model) {

    var self = this;

    this.init = function () {

        element.dateRangePicker(datepickerConfigSingle);

        return this;
    };

    this.reset = function() {

    };

    this.setValue = function(value) {

    };

    return this;
};

widgets.upload = function (element, field, model, module) {

    var self = this;

    this.dropzone = undefined;

    this.field_key = "";

    this.firstTime = true;

    this.value = '';

    this.allValues = {};

    this.init = function () {

        if( self.dropzone === undefined ) {

            var field_id = field.id ? field.id : field.key;
            self.field_key = field.id ? field.id : model.table_name + '_' + field_id;
            var field_name = field.id ? field.id : model.table_name + '[' + field_id + ']';

            $(element).dropzone({
                paramName: field.key,
                url: field.settings.url,
                addRemoveLinks:true,
                maxFiles:1,
                init: function() {

                    /*this.on("addedfile", function(file) {});*/

                    this.on("removedfile", function(file) {

                        $('#' + self.field_key).val('');
                    });

                    this.on("sending", function(file, xhr, formData) {

                        self.xhr = xhr;

                        fileName = self.xhr.response;

                        $('#' + self.field_key).val(fileName);

                        formData.append('name', fileName);
                    });

                    this.on("complete", function(file) {

                        if(file.status === 'error') {

                        } else {

                            fileName = self.xhr.response;

                            $('#' + self.field_key).val(fileName);

                            //$('#' + self.field_key).val(file.name);
                        }
                    });

                    if(self.dropzone === undefined) {
                        self.dropzone = this;
                    }

                    return this;
                },
                sending: function() {

                    self.dropzone.options.params = self.getParams();
                }
            });

            return this;

        }

        return this;
    };

    this.reset = function() {

        self.dropzone.removeAllFiles(true);
    };


    this.setValue = function(value) {

        self.dropzone.options.params = self.getParams();

        self.dropzone.removeAllFiles(true);

        $('#' + self.field_key).val(value);

        var params = self.getParams();

        params['field'] = field.key;
        params['value'] = value;

        $.post(field.settings.url, params, function(data) {

            self.dropzone.removeAllFiles(true);

            if(data) {

                $.each(data, function(key,value){

                    var mockFile = { name: value.name, size: value.size, type: 'image' };

                    self.dropzone.addFile(mockFile, true);

                    self.dropzone.options.thumbnail.call(self.dropzone, mockFile, BASE_URL + '../helpers/timthumb.php?width=48&height=48&src=' + field.settings.download + value.name);

                });

            } else {

                for(var i in self.dropzone.files) {

                    self.dropzone.removeFile( files[i] );
                }
            }

        });
    };


    self.getParams = function() {

        var array = module.containers.form.element.serializeArray();
        var params = {};

        var result =  new FormData();

        for(var i in array) {
            var obj = array[i];

            params[obj.name.replace(model.table_name, '').replace('[', '').replace(']', '')] = obj.value;

            //result.append(model.table_name + '[' + obj.name.replace(model.table_name, '').replace('[', '').replace(']', '') + ']', obj.value);

            //result += model.table_name + '[' + obj.name.replace(model.table_name, '').replace('[', '').replace(']', '') + ']' + '=' + obj.value + '&';
        }

        //return module.containers.form.element.serializeArray();

        //return new FormData(module.containers.form.element);

        return params;
    };

    return this;
};


widgets.map = function (element, field, model) {

    var self = this;

    this.map = undefined;
    self.geocoder = undefined;

    this.field_key = "";

    this.initAfterShow = true;

    this.value = '';

    this.markers = [];

    this.fields = {};

    this.init = function () {

        if( self.map === undefined ) {

            var field_id = field.id ? field.id : field.key;
            self.field_key = field.id ? field.id : model.table_name + '_' + field_id;
            var field_name = field.id ? field.id : model.table_name + '[' + field_id + ']';

            self.fields.latitude = element.find('.lat');
            self.fields.longitude = element.find('.lon');

            self.fields.latitude.attr('name', model.table_name + '[' + field.settings.lat_key + ']')
            self.fields.longitude.attr('name', model.table_name + '[' + field.settings.lon_key + ']')

            setTimeout(self.initDelayed, 200);

        }

        return this;
    };

    this.initDelayed = function() {

        var field_id = field.id ? field.id : field.key;
        self.field_key = field.id ? field.id : model.table_name + '_' + field_id;
        var field_name = field.id ? field.id : model.table_name + '[' + field_id + ']';

        self.map = new google.maps.Map(element.find('.map')[0], {
            center: new google.maps.LatLng(field.settings.center.latitude, field.settings.center.longitude),
            zoom: field.settings.zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        self.geocoder = new google.maps.Geocoder();

        element.find('.search').unbind('change').on('change', function(){

            self.geocodeAddress($(this).val());

            return false;
        });

        element.find('.search').keypress(function(e){

            if(e.which == 13){

                self.geocodeAddress($(this).val());

                return false;
            }
        });

        google.maps.event.addListener(self.map, 'click', function(e) {

            self.removeAllMarkers();

            self.addMarker(e.latLng);
        });

        self.setValue(self.value);

        return this;
    };

    this.geocodeAddress = function(address) {

        self.value = '';

        if(self.map != undefined) {

            self.removeAllMarkers();

            self.geocoder.geocode( { 'address': address}, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                    var latLong = results[0].geometry.location;

                    self.map.setCenter(latLong);
                    self.map.setZoom(field.settings.zoom);

                    self.addMarker(latLong);

                } else {

                    alert( lang('Address not found') );
                }
            });
        }
    };

    this.reset = function() {

        self.value = '';

        self.removeAllMarkers();

        if(self.map != undefined) {
            self.map.setCenter(new google.maps.LatLng(field.settings.center.latitude, field.settings.center.longitude));
            self.map.setZoom(field.settings.zoom);
        }
    };


    this.setValue = function(value) {

        this.value = value;

        self.removeAllMarkers();

        if(self.map != undefined) {

            google.maps.event.trigger(self.map, 'resize');

            if(value && value.latitude && (self.value.latitude !== field.settings.center.latitude && self.value.longitude !== field.settings.center.longitude)) {

                var latLong = new google.maps.LatLng(self.value.latitude, self.value.longitude);
                self.map.setCenter(latLong);
                self.map.setZoom(field.settings.zoom);
                self.addMarker(latLong);

            } else {

                var latLong = new google.maps.LatLng(field.settings.center.latitude, field.settings.center.longitude);
                self.map.setCenter(latLong);
                self.map.setZoom(field.settings.zoom);
                self.addMarker(latLong);
            }
        }
    }

    this.addMarker = function(latLong) {

        self.fields.latitude.val(latLong.lat());
        self.fields.longitude.val(latLong.lng());

        var marker = new google.maps.Marker({
            map: self.map,
            //position: new google.maps.LatLng(lat, lng),
            title: "move this marker",
            //icon: image,
            //shadow: shadow,
            //shape: shape
            position: latLong,
            animation:google.maps.Animation.DROP,
            draggable:true
        });

        google.maps.event.addListener(marker, 'dragend', function(evt){
            self.fields.latitude.val(evt.latLng.lat());
            self.fields.longitude.val(evt.latLng.lng());
        });

        self.markers.push(marker);
    };

    this.removeAllMarkers = function() {

        for(var i in self.markers) {
            self.markers[i].setMap(null);
        }

        self.markers = [];
    };

    return this;
};


widgets.editor = function (element, field, model) {

    var self = this;

    this.editor = undefined;

    this.field_key = "";

    this.firstTime = true;

    this.init = function () {

        if( self.editor === undefined ) {

            var field_id = field.id ? field.id : field.key;
            self.field_key = field.id ? field.id : model.table_name + '_' + field_id;
            var field_name = field.id ? field.id : model.table_name + '[' + field_id + ']';

            self.editor = element.editable(
                {
                    inlineMode: false,
                    borderColor: '#ececec',
                    width: '484px',
                    imageUploadURL: field.settings.url,
                    imageUploadParams: {field: field.key}
                }
            );

            return this;

        }

        return this;
    };

    this.reset = function() {

        $(element).editable("setHTML", "", false);
    };


    this.setValue = function(value) {

        $(element).editable("setHTML", value, false);
    }

    return this;
};


widgets.pagination = function (element, settings) {

    var self = this;

    this.pagination = undefined;

    this.firstTime = true;

    this.init = function () {

        if( self.pagination === undefined ) {

            self.settings = {};
            //self.settings.items = settings.total;
            self.settings.itemsOnPage = settings.per_page;
            self.settings.cssStyle = 'light-theme';
            self.settings.onPageClick = function(pageNumber, event) {

                settings.onChange(pageNumber);

                return false;
            };

            element.pagination(self.settings);

            return this;

        }

        return this;
    };


    this.setValue = function(settings) {

        element.pagination('updateItems', settings.total);
    };

    return this;
};

widgets.tabs = function (element) {

    var self = this;

    this.init = function () {

        element.idTabs();

        return this;
    };

    this.reset = function() {

    };

    return this;
};

containers.form = function (model, container, module) {

    this.element = buildFromTemplate(model, 'form', container.form.settings);

    this.fields = {};
    this.buttons = {};
    this.widgets = {};

    var self = this;

    this.init = function () {

        self.validation_params = {rules: {}, messages: {}};

        if (container.form.settings.validation) {

            self.validation_params = validation[container.form.settings.validation];

        } else {

            self.validation_params = validation['default'];
        }

        var field_column = buildFromTemplate(model, 'field_column');
        var field_group = buildFromTemplate(model, 'field_group');
        var current_column = '';
        var current_group = '';
        var field_container = '';
        var field;

        var tab_header = buildFromTemplate('', 'tabs_container');

        self.element.append(tab_header);

        current_tab = buildFromTemplate(model, 'tab_content');
        current_tab.attr('id', model.table_name + '_' + 'tab_default');

        tab_header.append(current_tab);

        self.tabs = [];

        //self.tabs.push({id: model.table_name + '_' + 'tab_default', title: ''});

        for (var key in model.field_group.form) {

            var tab_changed = false;

            field = model.fields[model.field_group.form[key]];

            var field_id = field.id ? field.id : field.key;
            var field_key = field.id ? field.id : model.table_name + '_' + field_id;
            var field_name = field.id ? field.id : model.table_name + '[' + field_id + ']';

            if (field.tab) {

                tab_changed = true;

                current_tab = buildFromTemplate(model, 'tab_content');
                current_tab.attr('id', model.table_name + '_' + 'tab_' + field_id);

                //self.element.append(current_tab);

                self.tabs.push({id: model.table_name + '_' + 'tab_' + field_id, title: field.tab});

                tab_header.append(current_tab);
            }

            if (!field) {
                continue;
            }

            var field_element = buildFromTemplate(model, field.type, field, field.key);

            if (!field_element) {
                continue;
            }

            field_container = buildFromTemplate(model, 'field');

            if (field.type === 'hidden' || (field.form_template)) {

                if (field.type == 'hidden') {

                    field_element.attr('name', field_name);

                    self.validation_params = initFormField(self.validation_params, field, field_element, {field_id: field_id, field_name: field_name, field_key: field_key});

                    self.fields[field_id] = field_element;

                } else if (field.form_template) {

                    field_element.find(field_key);

                    var field_tmp = field_element.find('#' + field_key);

                    self.validation_params = initFormField(self.validation_params, field, field_tmp, {field_id: field_id, field_name: field_name, field_key: field_key});

                    self.fields[field_id] = field_tmp;
                }

            } else {

                if (field.label !== false) {

                    var label_element = buildFromTemplate(model, 'label', field);

                    label_element.text(field.name);

                    label_element.attr('for', field_key);

                    field_container.append(label_element);
                }

                self.validation_params = initFormField(self.validation_params, field, field_element, {field_id: field_id, field_name: field_name, field_key: field_key});

                self.fields[field_id] = field_element;
            }

            if (field.type === 'hidden') {

                self.element.append(field_element);

            } else {

                field_container.append(field_element);
            }

            if (field.group) {

                if ((current_column != field.group) || tab_changed) {

                    field_column = buildFromTemplate(model, 'field_column');

                    current_tab.append(field_column);

                    current_column = field.group;
                }

                if (field.field_group) {

                    if ((current_group != field.field_group) || tab_changed) {

                        field_group = buildFromTemplate(model, 'field_group');

                        field_group.find('.title').text(field.field_group);

                        current_group = field.field_group;
                    }

                    field_group.append(field_container);

                    field_column.append(field_group);

                } else {

                    field_column.append(field_container);
                }

            } else if (field.field_group) {

                if ((current_group != field.field_group) || tab_changed) {

                    field_group = buildFromTemplate(model, 'field_group');

                    field_group.find('.title').text(field.field_group);

                    current_tab.append(field_group);

                    current_group = field.field_group;
                }

                field_group.append(field_container);

            } else {

                if (field.type === 'hidden') {

                    current_tab.append(field_element);

                } else {

                    current_tab.append(field_container);
                }
            }
        }

        if(self.tabs.length > 1) {

            var tab_container = buildFromTemplate('', 'tabs');

            for(var i in self.tabs) {

                var tab = self.tabs[i];

                var tab_element = buildFromTemplate('', 'tab');

                tab_element.find('.title').attr('href', '#' + tab.id);
                tab_element.find('.title').text(tab.title);

                tab_container.append(tab_element);
            }

            tab_header.prepend(tab_container);

            self.widgets.tabs = new widgets['tabs'](tab_header);

            self.widgets.tabs.init();
        }

        field_container = buildFromTemplate('', 'field_footer');
        field_container.addClass('modal-footer');

        if (!container.form.settings || (container.form.settings && !container.form.settings.buttons)) {

            var btnSubmit = buildFromTemplate(model, 'submit', {});
            var btnCancel = buildFromTemplate(model, 'button', {});

            btnSubmit.attr('value', lang('Send'));
            btnCancel.text(lang('Cancel'));

            btnSubmit.attr('url', model.base_url + model.table_name + '/save');
            btnSubmit.on('click', self.saveCustom);

            self.buttons.submit = btnSubmit;
            self.buttons.cancel = btnCancel;

            field_container.append(btnSubmit).append(btnCancel);

        } else {

            for (var i_ in container.form.settings.buttons) {

                var button = container.form.settings.buttons[i_];

                var btn = buildFromTemplate(model, 'button', {});
                btn.text(button.value);
                btn.attr('url', button.url);
                field_container.append(btn);

                btn.on('click', self.saveCustom);

                self.buttons[button.key] = btn;
            }
        }

        for (var i in self.fields) {
            var element = self.fields[i];
            field = model.fields[i];

            if (field.key_depending) {

                element.on('change', self.loadDepends);
            }

            if (field.value) {

                element.val(field.value);

            } else if (field.values) {

                var value;
                var label;
                var option;

                if (field.type == 'radio') {

                    element.find('*').remove();
                    element.html('');

                    for (var j in field.values) {

                        value = field.values[j];
                        label = $('<label></label>');
                        option = $('<input type="radio"/>');

                        option.attr('value', value.id);
                        option.attr('name', element.attr('name'));
                        option.attr('id', element.attr('id'));
                        label.text(value.name);
                        label.attr('for', value.name);

                        label.prepend(option);
                        element.append(label);
                    }
                } else if (field.type == 'checkbox') {

                    element.find('*').remove();
                    element.html('');

                    for (var j_ in field.values) {

                        value = field.values[j_];
                        label = $('<label></label>');
                        option = $('<input type="checkbox"/>');

                        option.attr('value', value.id);
                        option.attr('name', element.attr('name'));
                        option.attr('id', element.attr('id'));
                        label.text(value.name);
                        label.attr('for', value.name);

                        label.prepend(option);
                        element.append(label);
                    }
                }
            }

            if(widgets[field.type] !== undefined) {

                    self.widgets[i] = new widgets[field.type](element, field, model, module);

                    if( self.widgets[i].initAfterShow === undefined ) {

                        self.widgets[i].init();
                    }
            }
        }

        self.element.append(field_container);

        self.element.validate(self.validation_params);

        return self;
    };

    this.loadDepends = function (event) {

        var element = $(event.target);

        var key = element.attr('name');
        key = key.replace(model.table_name, '').replace('[', '').replace(']', '').replace('[', '').replace(']', '');

        var data = self.element.serializeArray();
        data.push({name: 'key', value: key});

        ajax({
            url: BASE_URL + model.base_url + model.table_name + '/loadDepends',
            data: data,
            success: function (response) {

                if (response.status) {

                    if (response.status === 'success') {

                        self.loadData(response, true);

                        element.trigger('changed');
                    }
                }
            }
        });
    };

    this.saveCustom = function (urlOvewrite, callbackAfter) {

        var url = $(this).attr('url');

        if(urlOvewrite && !urlOvewrite.target) {
            url = urlOvewrite;
        }

        if (self.element.valid()) {

            ajax(
                {
                    url: BASE_URL + url,
                    data: self.element.serializeArray(),
                    success: function (response) {

                        if (response.status) {

                            if (response.status === 'success') {

                                if (response.reload) {

                                    window.location.href = response.reload;

                                } else {

                                    self.callbackAfterSend(response);

                                    $(this).trigger('changed');

                                    $(urlOvewrite.target).trigger('changed');


                                    return callbackAfter ? callbackAfter() : '';
                                }

                            } else {

                                alert(response.message);
                            }

                        } else {

                            alert(lang('There was a server problem in server: ' + response));
                        }
                    }
                }
            );
        }

        return false;
    };

    this.loadData = function (response, reset, module) {

        if (reset === undefined)
            resetForm(self.element, self);

        for (var i in self.fields) {

            var element = self.fields[i];

            if (response[model.table_name][i] !== undefined) {

                if (element.is('input') || element.is('textarea')) {

                    element.val(response[model.table_name][i]);

                    var field = model.fields[i];

                    if(self.widgets[i] !== undefined) {
                        self.widgets[i].setValue( response[model.table_name][i], response[model.table_name] );
                    }

                } else if (model.fields[i].type == 'dropdown' || model.fields[i].type == 'multiple' || model.fields[i].type == 'composite') {

                    element.find('*').remove();
                    element.html('');

                    for (var j in response[model.table_name].dropdown[i]) {

                        var value = response[model.table_name].dropdown[i][j];
                        var option = $('<option/>');

                        option.attr('value', value.id);
                        option.text(value.name);

                        if (value.selected || response[model.table_name][i] == value.id) {

                            option.attr('selected', 'selected');
                        }

                        element.append(option);
                    }

                } else if (model.fields[i].type == 'inline') {

                    if (module && module.inline) {

                        var listTmp = [];
                        for(var k in response[model.table_name].inline[i]) {
                            listTmp.push(response[model.table_name].inline[i][k]);
                        }

                        module.inline[i].containers.grid.list = listTmp;
                        module.inline[i].containers.grid.refreshOnly();
                    }

                } else if(self.widgets[i] != undefined) {

                    self.widgets[i].setValue( response[model.table_name][i], response[model.table_name] );
                }
            }
        }
    };

    this.initAfterShow = function() {

        for(var i in self.widgets) {
            self.widgets[i].init();
        }
    };

    this.callbackAfterSend = function (response) {


    };

    this.remoteLoad = function (url) {

        ajax({
            url: BASE_URL + model.base_url + model.table_name + '/getNew',
            success: function (response) {

                self.loadData(response);
            }
        });
    };

    return this;
};


containers.grid = function (model, container, module) {

    this.element = buildFromTemplate(model, 'grid', container.grid.settings);

    this.grid = this.element.find('.grid');

    this.element.hide();

    var self = this;

    self.widgets = {};

    self.data = {per_page: 10, current_page: 1};

    self.actions = {};

    this.init = function () {

        if (module.containers.form && module.containers.modal) {

            resetForm(module.containers.form.element, module.containers.form);

            module.containers.modal.element.hide();

            module.containers.modal.element.find('.modal-content').append(module.containers.form.element);

            module.containers.form.callbackAfterSend = function (response) {

                module.containers.modal.fadeOut(200);
                self.refresh();
            };

            module.containers.form.buttons.cancel.on('click', function () {

                module.containers.modal.fadeOut(200);

                return false;
            });

            module.containers.modal.element.on('click', function (event) {

                if (event.target == module.containers.modal.element[0]) {
                    module.containers.modal.fadeOut(200);
                }
            });
        }

        self.header = buildFromTemplate(model, 'thead');
        self.header_row = buildFromTemplate(model, 'tr');

        var header_th;
        self.total_columns = 0;
        for (var i in model.field_group.grid) {

            var key = model.field_group.grid[i];
            var field = model.fields[key];

            if (!field) {
                continue;
            }

            header_th = buildFromTemplate(model, 'th');

            header_th.text(field.name);

            self.header_row.append(header_th);

            self.total_columns ++;
        }

        if (container.grid.settings && container.grid.settings.actions) {

            header_th = buildFromTemplate(model, 'th');
            header_th.addClass('actions');
            header_th.text('');
            self.header_row.append(header_th);

            self.total_columns ++;
        }

        $(self.header).append(self.header_row);
        self.grid.append(self.header);

        self.tbody = buildFromTemplate(model, 'tbody');
        self.grid.append(self.tbody);

        self.external = buildFromTemplate(model, 'external');
        $(module.settings.container).append(self.external);

        self.message = buildFromTemplate(model, 'message');

        if( !$.isEmptyObject(model.pagination ) ) {

            self.tfoot = buildFromTemplate(model, 'tfoot');
            self.grid.append(self.tfoot);

            self.pagination = buildFromTemplate(model, 'pagination');

            self.data.per_page = model.pagination.per_page;

            model.pagination.onChange = function(page) {

                self.data.current_page = page;

                self.refresh();
            };

            self.widgets.pagination = new widgets.pagination(self.pagination, model.pagination).init();

            var td = buildFromTemplate(model, 'td');

            td.append(self.pagination);

            td.attr('colspan', self.total_columns);

            self.tfoot.append(td);
        }

        return this;
    };

    this.refresh = function () {

        var data = {};

        if(self.pagination !== undefined) {
            data = self.data;
        }

        ajax({
            url: BASE_URL + model.base_url + model.table_name + '/getList',
            data: data,
            success: function (response) {

                self.loadData(response);
            },
            beforeSend: function () {

            }
        });
    };

    this.loadData = function (data) {

        self.refreshWidgets(data);

        self.tbody.find('*').remove();
        self.tbody.html('');

        for (var i in data.list) {

            var row = data.list[i];
            var tr = buildFromTemplate(model, 'tr');
            var row_id = row[model.table_id];
            var td;
            tr.attr('id', row_id);

            for (var j in model.field_group.grid) {

                column = model.field_group.grid[j];

                if (!model.fields[column]) {
                    continue;
                }

                td = buildFromTemplate(model, 'td');

                if (row[column])
                    switch (model.fields[column].type) {

                        case 'email':
                        {

                            var link = buildFromTemplate('', 'a');
                            link.attr('href', 'mailto:' + row[column]);
                            link.text(row[column]);

                            td.append(link);

                            break;
                        }
                        case 'upload': {

                            var link = $('<img/>')
                            link.attr('src', BASE_URL + '../helpers/timthumb.php?width=48&height=48&src=' + model.fields[column].settings.download + row[column]);
                            link.css('width', 100);
                            link.css('height', 'auto');
                            link.text(row[column]);

                            td.append(link);

                            break;
                        }
                        default:
                        {

                            td.text(row[column]);
                            break;
                        }
                    }

                tr.append(td);
            }

            if (container.grid.settings && container.grid.settings.actions) {
                td = buildFromTemplate(model, 'td');

                td.addClass('actions');

                for (var k in container.grid.settings.actions) {
                    var action = container.grid.settings.actions[k];
                    var btn = buildFromTemplate(model, 'action');
                    btn.attr('action', action.type);
                    btn.attr('rel', row_id);

                    if (action.type == 'edit') {

                        btn.text(lang('Edit'));
                        btn.on('click', self.actionButton);

                    } else if (action.type == 'delete') {

                        btn.text(lang('Delete'));
                        btn.on('click', self.actionButton);

                    } else if (action.type == 'module') {

                        btn.attr('module', action.module);
                        btn.attr('url', action.url);
                        btn.text(action.text);
                        btn.on('click', self.actionButton);

                    } else {

                        btn.attr('url', action.url);
                        btn.attr('key', action.key);
                        btn.text(action.text);
                        btn.on('click', self.actionButton);

                        if(action.key) {
                            self.actions[action.key] = action;
                        }
                    }

                    td.append(btn);
                }

                tr.append(td);
            }

            self.tbody.append(tr);
        }
    };

    this.refreshWidgets = function(data) {

        for(var i in self.widgets) {

           self.widgets[i].setValue(data);
        }
    };

    this.actionButton = function(event) {

        self.actionRow( $(this).attr('action'), $(this).attr('rel'), $(this).attr('url'), $(this).attr('module'), $(this).attr('key') );
    };

    this.actionRow = function (type, id, url, module_name, key) {

        for (var i in module.inline) {
            module.inline[i].containers.grid.list = [];
            module.inline[i].containers.grid.refreshOnly();
        }

        if (!url) {

            if (type === 'new') {

                module.containers.form.element.find('.grid-container').show();
                module.containers.modal.element.find('.modal-content').append(module.containers.form.element);

                resetForm(module.containers.form.element, module.containers.form);

                module.containers.modal.element.find('.modal-header .title').text(lang('New ' + model.table_name));

                url = model.base_url + model.table_name + '/getNew';

            } else if (type === 'edit') {

                module.containers.form.element.find('.grid-container').show();
                module.containers.modal.element.find('.modal-content').append(module.containers.form.element);

                resetForm(module.containers.form.element, module.containers.form);

                module.containers.modal.element.find('.modal-header .title').text(lang('Edit ' + model.table_name));

                url = model.base_url + model.table_name + '/getEdit';

            } else if (type === 'delete') {

                url = model.base_url + model.table_name + '/delete';

            } else if (type === 'add') {

                if(self.actions[key]) {

                    url = self.actions[key].model.table_name + '/getNew';


                    var action = self.actions[key];

                    var moduleConfig = {
                        model: action.model,
                        settings: [],
                        containers: {
                            modal: {},
                            form: {
                                settings: {}
                            }
                        }
                    };

                    self.moduleLoaded = new Module(moduleConfig).init();

                    self.moduleLoaded.containers.modal.element.find('.modal-header .title').text(action.text);

                    self.moduleLoaded.containers.modal.element.find('.modal-content').append(self.moduleLoaded.containers.form.element);

                    //$(moduleLoaded.settings.container).append(self.moduleLoaded.element);

                    self.moduleLoaded.containers.modal.fadeIn(200);

                    self.moduleLoaded.containers.form.callbackAfterSend = function (response) {

                        self.moduleLoaded.containers.modal.fadeOut(200);

                        self.refresh();
                    };

                    self.moduleLoaded.containers.modal.element.on('click', function (event) {

                        if (event.target == self.moduleLoaded.containers.modal.element[0]) {
                            self.moduleLoaded.containers.modal.fadeOut(200);
                        }
                    });

                    self.moduleLoaded.containers.form.buttons.cancel.on('click', function () {

                        self.moduleLoaded.containers.modal.fadeOut(200);

                        return false;
                    });

                    self.moduleLoaded.inline = {};

                    $(module.settings.container).find('.dinamic-modal').remove();

                    $(module.settings.container).append(self.moduleLoaded.containers.modal.element);

                    $(module.settings.container).show();

                    self.moduleLoaded.containers.modal.fadeIn(200);

                    self.moduleLoaded.containers.modal.element.addClass('dinamic-modal');
                }
            }
        }

        if (type === 'delete') {

            if (!confirm(lang('Are you sure that want to delete this record?'))) {
                return false;
            }

        } else if (type === 'new' || type === 'edit') {

            module.containers.modal.fadeIn(200);
            module.containers.modal.element.scrollTop(0);
            module.containers.modal.element.css('z-index', modalIndex++);
        }

        var data = {};
        data[model.table_id] = id;

        ajax({
            url: BASE_URL + url,
            data: data,
            success: function (response) {

                if (response.message) {

                    module.containers.modal.element.find('.modal-content').html('');

                    self.message.find('.title').text(response.message);

                    module.containers.modal.element.find('.modal-content').append(self.message);
                }

                if (type === 'new') {

                    module.containers.form.loadData(response);

                    module.containers.form.initAfterShow();

                } else if (type === 'edit') {


                    module.containers.form.loadData(response, '', module);

                    module.containers.form.initAfterShow();

                } else if (type === 'delete') {

                    self.refresh();

                } else if (type === 'load') {

                    self.external.find('*').remove();
                    self.external.html('');
                    self.external.append(response);
                    self.external.find('.grid-container').show();

                    self.element.hide();
                    self.external.show();

                } else if (type === 'module') {

                    var moduleLoaded = new Module(response[module_name]).init();

                    moduleManager.modules[module_name] = moduleLoaded;

                    moduleLoaded.containers.modal.element.find('.modal-header .title').text(response[module_name].containers.form.settings.title);

                    moduleLoaded.containers.modal.element.find('.modal-content').append(moduleLoaded.containers.form.element);

                    $(moduleLoaded.settings.container).append(moduleLoaded.element);

                    moduleLoaded.containers.modal.fadeIn(200);

                    moduleLoaded.containers.form.callbackAfterSend = function (response) {

                        moduleLoaded.containers.modal.fadeOut(200);
                    };

                    moduleLoaded.containers.modal.element.on('click', function (event) {

                        if (event.target == moduleLoaded.containers.modal.element[0]) {
                            moduleLoaded.containers.modal.fadeOut(200);
                        }
                    });

                    moduleLoaded.inline = {};

                } else if (type === 'add') {

                    var action = self.actions[key];

                    if(action.default) {
                        var value;
                        for (var i in action.default) {
                            value = action.default[i];

                            if(value === 'row_id') {
                                value = id;
                            }

                            self.moduleLoaded.containers.modal.element.find('#' + action.model.table_name + '_' + i).val( value );
                        }
                    }
                }
            },
            beforeSend: function () {

            }
        });
    };

    this.show = function () {

        self.external.hide();

        self.element.show();

        self.grid.show();

        self.grid.find('.grid').show();

        self.refresh();
    };

    this.cancel = function (id) {

        if (module.containers.form && module.containers.modal) {

            module.containers.modal.fadeOut(200);
        }
    };

    return this;
};


containers.inline = function (model, container, module, parentModule, parentKey) {

    this.element = buildFromTemplate(model, 'inline', container.grid.settings);

    this.grid = buildFromTemplate(model, 'grid_inline', container.grid.settings);

    this.list = [];

    this.element.append(this.grid);

    var self = this;

    this.init = function () {

        if (module.containers.form && module.containers.modal) {

            resetForm(module.containers.form.element, module.containers.form);

            module.containers.modal.element.hide();

            module.containers.modal.element.find('.modal-content').append(module.containers.form.element);

            module.containers.form.callbackAfterSend = function (response) {

                module.containers.modal.fadeOut(200);
                return false;
            };

            module.containers.form.buttons.submit.unbind('click');

            module.containers.form.buttons.submit.on('click', function (response) {

                if (module.containers.form.element.valid()) {

                    var obj = {};

                    for (var i in module.containers.form.fields) {
                        obj[i] = module.containers.form.fields[i].val();
                    }

                    self.list.push(obj);

                    self.loadData(self, model, parentModule, parentKey);

                    self.cancel();
                }

                return false;
            });

            module.containers.form.buttons.cancel.on('click', function () {

                module.containers.modal.fadeOut(200);

                return false;
            });

            module.containers.modal.element.on('click', function (event) {

                if (event.target == module.containers.modal.element[0]) {
                    module.containers.modal.fadeOut(200);
                }
            });
        }

        self.header = buildFromTemplate(model, 'thead');
        self.header_row = buildFromTemplate(model, 'tr');

        var header_th;

        for (var i in model.field_group.grid) {

            var key = model.field_group.grid[i];
            var field = model.fields[key];

            if (!field) {
                continue;
            }

            header_th = buildFromTemplate(model, 'th');

            header_th.text(field.name);

            self.header_row.append(header_th);
        }

        header_th = buildFromTemplate(model, 'th');
        header_th.addClass('actions');
        header_th.text('');
        self.header_row.append(header_th);

        $(self.header).append(self.header_row);
        self.grid.append(self.header);

        self.tbody = buildFromTemplate(model, 'tbody');
        self.grid.append(self.tbody);

        self.message = buildFromTemplate(model, 'message');

        return this;
    };

    this.refreshOnly = function () {

        self.loadData(self, model, parentModule, parentKey);
    };

    this.loadData = function (grid, model, parentModule, parentKey, td) {

        grid.tbody.find('*').remove();
        grid.tbody.html('');

        var fieldParent = parentModule.model.fields[parentKey];
        var field_parent_id = fieldParent.id ? fieldParent.id : fieldParent.key;

        var field_element;

        for (var i in grid.list) {

            var row = grid.list[i];
            var tr = buildFromTemplate(model, 'tr');
            tr.attr('id', i);

            for (var j in model.field_group.grid) {

                column = model.field_group.grid[j];

                td = buildFromTemplate(model, 'td');


                switch (model.fields[column].type) {

                    case 'email':
                    {

                        var link = buildFromTemplate('', 'a');
                        link.attr('href', 'mailto:' + row[column]);
                        link.text(row[column]);

                        td.append(link);

                        break;
                    }
                    case 'upload': {

                        var link = $('<img/>')
                        link.attr('src', BASE_URL + '../helpers/timthumb.php?width=48&height=48&src=' + model.fields[column].settings.download + row[column]);
                        link.css('width', 100);
                        link.css('height', 'auto');
                        link.text(row[column]);

                        td.append(link);

                        break;
                    }
                    default:
                    {

                        td.text(row[column]);
                        break;
                    }
                }

                tr.append(td);
            }

            if (row.deleted) {
                field_element = buildFromTemplate(model, 'hidden', fieldParent, fieldParent.key);
                field_element.removeAttr('id');
                field_element.attr('name', parentModule.model.table_name + '[' + field_parent_id + ']' + '[' + i + '][deleted]');
                field_element.val('1');

                td.append(field_element);

                tr.addClass('deleted');
            }

            for (j in model.field_group.form) {

                column = model.field_group.form[j];
                var field = model.fields[column];

                if (field.type === "inline") {
                    self.buildDepends(module.inline[column], field.model, model, column, td, parentModule.model.table_name + '[' + field_parent_id + ']' + '[' + i + ']');
                } else if(field.type === "composite") {

                    field_id = model.fields[column].id ? model.fields[column].id : model.fields[column].key;

                    for( var k in row[column] ) {

                        field_element = buildFromTemplate(model, 'hidden', fieldParent, fieldParent.key);
                        field_element.removeAttr('id');

                        field_element.attr('name', parentModule.model.table_name + '[' + field_parent_id + ']' + '[' + i + '][' + field_id + '][]');
                        field_element.val(row[column][k]);

                        td.append(field_element);
                    }
                } else {

                        field_id = model.fields[column].id ? model.fields[column].id : model.fields[column].key;

                    if( field_id !== undefined ) {

                        field_element = buildFromTemplate(model, 'hidden', fieldParent, fieldParent.key);
                        field_element.removeAttr('id');

                        field_element.attr('name', parentModule.model.table_name + '[' + field_parent_id + ']' + '[' + i + '][' + field_id + ']');
                        field_element.val(row[column]);

                        td.append(field_element);
                    }
                }
            }

            field_element = buildFromTemplate(model, 'hidden', fieldParent, fieldParent.key);
            field_element.removeAttr('id');
            field_element.attr('name', parentModule.model.table_name + '[' + field_parent_id + ']' + '[' + (i) + '][' + parentModule.model.table_name + '_id]');
            field_element.val(parentModule.containers.form.fields.id.val());
            td.append(field_element);

            td = buildFromTemplate(model, 'td');
            td.addClass('actions');

            var btn;

            if (row.id) {
                btn = buildFromTemplate(model, 'button');
                btn.text(lang('Edit'));
                btn.attr('rel', row.id);
                btn.attr('action', 'edit');
                btn.on('click', self.actionButton);
                td.append(btn);
            }

            if (!row.deleted) {
                btn = buildFromTemplate(model, 'button');
                btn.text(lang('Delete'));
                btn.attr('rel', i);
                btn.attr('action', 'delete');
                btn.on('click', self.actionButton);
                td.append(btn);
            }

            tr.append(td);

            grid.tbody.append(tr);
        }
    };

    this.buildDepends = function (module, model, parentModel, parentKey, td, starting_element) {

        var fieldParent = parentModel.fields[parentKey];
        var field_parent_id = fieldParent.id ? fieldParent.id : fieldParent.key;

        for (var i in module.containers.grid.list) {

            var row = module.containers.grid.list[i];

            for (var j in model.field_group.form) {

                column = model.field_group.form[j];
                var field = model.fields[column];

                var field_id = model.fields[column].id ? model.fields[column].id : model.fields[column].key;

                var field_element = buildFromTemplate(model, 'hidden', fieldParent, fieldParent.key);
                field_element.removeAttr('id');

                field_element.attr('name', starting_element + '[' + field_parent_id + ']' + '[' + i + '][' + field_id + ']');
                field_element.val(row[column]);

                td.append(field_element);
            }
        }
    };

    this.actionButton = function(event) {

        self.actionRow( $(this).attr('action'), $(this).attr('rel'), $(this).attr('url'), $(this).attr('module') );
    };

    this.actionRow = function (type, id, url) {

        /*Resettings inline components*/
        for (var i in module.inline) {
            module.inline[i].containers.grid.list = [];
            module.inline[i].containers.grid.refreshOnly();
        }

        module.containers.form.element.find('.grid').show();

        module.containers.modal.element.find('.modal-header .title').text(lang('New ' + model.table_name));

        module.containers.modal.element.find('.modal-content').append(module.containers.form.element);

        if (type === 'new') {

            module.containers.modal.element.find('.modal-header .title').text(lang('New ' + model.table_name));
            module.containers.modal.element.scrollTop(0);

            for(var key in module.containers.form.fields) {
                module.containers.form.fields[key].val('');
            }

            resetForm(module.containers.form.element, module.containers.form);

            ajax({
                url: BASE_URL + model.base_url + model.table_name + '/getNew',
                success: function(result) {
                    module.containers.form.loadData(result);
                }
            });

            module.containers.modal.fadeIn(200);

            module.containers.modal.element.scrollTop(0);
            $(window).scrollTop(0);

            module.containers.modal.element.css('z-index', modalIndex++);

            module.containers.form.buttons.submit.unbind('click').on('click', function(){

                if ( module.containers.form.element.valid() ) {

                    var obj = {};

                    for (var i in module.containers.form.fields) {

                        if(model.fields[i].type === 'composite') {

                            obj[i] = [];

                            module.containers.form.fields[i].find('option').each(function(){

                                if(this.selected === true) {
                                    obj[i].push(this.value);
                                }
                            });

                        } else if(model.fields[i].type === 'upload') {

                            obj[i] = module.containers.form.fields[i][1].value;

                        } else {

                            obj[i] = module.containers.form.fields[i].val();
                        }
                    }

                    self.list.push(obj);

                    self.loadData(self, model, parentModule, parentKey);

                    self.cancel();
                }
            });

        } else if (type === 'delete') {

            self.list[id].deleted = true;

            self.refreshOnly();

        } else if (type === 'edit') {

            module.containers.modal.element.find('.modal-header .title').text(lang('Edit ' + model.table_name));
            module.containers.modal.element.scrollTop(0);
            $(window).scrollTop(0);

            module.containers.modal.fadeIn(200);
            module.containers.modal.element.scrollTop(0);
            module.containers.modal.element.css('z-index', modalIndex++);

            var data = {};

            data[model.table_id] = id;

            ajax({
                url: BASE_URL + model.base_url + model.table_name + '/getEdit',
                data: data,
                success: function (response) {

                    module.containers.form.loadData(response, true, module);
                }
            });

            module.containers.form.buttons.submit.unbind('click').on('click', function(){

                if (module.containers.form.element.valid()) {

                    ajax({
                        url: BASE_URL + model.base_url + model.table_name + '/save',
                        data: module.containers.form.element.serializeArray(),
                        success: function (response) {

                            var obj = {};

                            if( response.status === 'success' && response[model.table_name] ) {

                                var id = response[model.table_name].id;
                                for(var index in self.list) {

                                    if(self.list[index].id === id) {

                                        for(var j in self.list[index]) {
                                            self.list[index][j] = response[model.table_name][j];
                                        }

                                        break;
                                    }
                                }
                            }

                            parentModule.inline[parentKey].containers.grid.refreshOnly();

                            self.cancel();
                        }
                    });
                }

                return false;
            });

            self.refreshOnly();
        }

        return false;
    };

    this.show = function () {

        self.element.show();

        self.refresh();
    };

    this.cancel = function () {

        module.containers.modal.fadeOut(200);
    };

    return this;
};

containers.modal = function (model, container, module) {

    this.element = buildFromTemplate(model, 'modal', container.settings);

    var self = this;

    this.init = function () {

        return this;
    };

    this.fadeIn = function (milliseconds) {

        //self.element.css('opacity', 0);
        self.element.css('display', 'block');
        self.element.addClass('shown');
        //self.element.css('opacity', 1);
    };

    this.fadeOut = function (milliseconds) {

        self.element.removeClass('shown');
        self.element.css('display', 'none');
    };

    return this;
};

containers.toolbar = function (model, container, module) {

    this.element = buildFromTemplate(model, 'toolbar', container.grid.settings);

    var self = this;

    this.fields = {};

    this.init = function () {

        if (container.toolbar.settings && container.toolbar.settings.actions) {

            for (var i in container.toolbar.settings.actions) {

                var config = container.toolbar.settings.actions[i];
                var txt;

                if (config.type == 'button') {

                    var btn = buildFromTemplate('', 'button');

                    btn.text(config.title);
                    btn.attr('action', config.action);

                    if (config.action === 'new') {

                        btn.on('click', module.containers.grid.actionButton);
                    }

                    self.element.append(btn);

                } else if (config.type === 'text') {

                    txt = buildFromTemplate('', config.template);

                    txt.text(config.title);
                    self.element.append(txt);

                } else if (config.type === 'break') {

                    txt = buildFromTemplate('', 'div');
                    self.element.append(txt);
                }
            }

            module.containers.grid.element.prepend(self.element);
        }

        self.form = buildFromTemplate(model, 'form');
        var contFields = 0;
        for(var i in model.fields) {

            var field = model.fields[i];

            if( field.filtrable ) {

                var field_element = buildFromTemplate(model, field.type, field, field.key);

                field_element.attr('name', model.table_name + '.' + field.key);

                if (field.attr) {

                    for (var j in field.attr) {

                        field_element.attr(field.attr[j].name, field.attr[j].value);
                    }
                }

                field_element.addClass('search');

                self.form.append(field_element);

                self.fields[field.key] = field_element;

                contFields ++;
            }
        }

        if(contFields > 0) {

            self.submit = buildFromTemplate(model, 'submit');
            self.submit.val( lang('Filter') );

            self.form.append(self.submit);

            self.submit.on('click', function(){

                var values = self.form.serializeArray();

                var values_array = {};

                for (var i in values) {

                    values_array[values[i].name] = values[i].value;
                }

                module.containers.grid.data.filters = values_array;

                module.containers.grid.refresh();

                return false;
            });

            ajax({
                url: BASE_URL + model.base_url + model.table_name + '/getNew',
                success: function(response) {

                    for(var i in self.fields) {

                        var element = self.fields[i];

                        if (model.fields[i].type == 'dropdown' || model.fields[i].type == 'multiple' || model.fields[i].type == 'composite') {

                            element.find('*').remove();
                            element.html('');

                            for (var j in response[model.table_name].dropdown[i]) {

                                var value = response[model.table_name].dropdown[i][j];
                                var option = $('<option/>');

                                option.attr('value', value.id);
                                option.text(value.name);

                                if (value.selected || response[model.table_name][i] == value.id) {

                                    option.attr('selected', 'selected');
                                }

                                element.append(option);
                            }

                        }
                    }
                }
            });
        }

        self.element.append(self.form);

        return this;
    };

    return this;
};

var Module = function (module) {

    this.settings = module.settings;
    this.element = buildFromTemplate(module.model, 'module', module.settings);
    this.element.attr('id', module.model.table_name + '_container');
    this.containers = {};
    this.model = module.model;

    var self = this;

    this.init = function () {

        for (var container_name in module.containers) {

            this.appendContainer(container_name, new containers[container_name](module.model, module.containers, self).init());
        }

        return this;
    };

    this.appendContainer = function (container_name, container) {

        if (container_name != 'toolbar') {
            this.element.append(container.element);
        }

        this.containers[container_name] = container;
    };
};

function initFormField(validation_params, field, element, settings) {

    if(field.type === 'upload') {
        element = $(element[1]);
    }

    element.attr('id', settings.field_key);
    element.attr('name', settings.field_name);

    if (field.attr) {

        for (var j in field.attr) {

            element.attr(field.attr[j].name, field.attr[j].value);
        }
    }

    if (field.type == 'dropdown') {

        if (element.is('select') && field.values) {

            for (var i in field.values) {
                var value = field.values[i];
                var option = $('<option/>');
                option.attr('value', value.id);
                option.text(value.name);

                element.append(option);
            }
        }
    }

    if (field.type == 'composite' || field.type == 'multiple') {

        element.attr('name', settings.field_name + '[]');

        if (field.validation && field.type != 'hidden') {
            validation_params.rules[settings.field_name + '[]'] = field.validation.rules;
            validation_params.messages[settings.field_name + '[]'] = field.validation.messages;
        }

    } else {

        if (field.validation) {

            validation_params.rules[settings.field_name] = field.validation.rules;
            validation_params.messages[settings.field_name] = field.validation.messages;
        }
    }

    return validation_params;
}

function buildFromTemplate(model, type, settings, id) {

    var element;

    if ((settings && settings.template) || (settings && settings.form_template)) {

        str = settings.template ? settings.template : (settings.form_template ? settings.form_template : '');

        element = $(str);

    } else {

        if (master_template[type]) {

            element = $(master_template[type]);

            if (type != 'label' && type != 'field' && type != 'tr' && type != 'td' && type != 'th' && type != 'thead' && type != 'tbody' && type != 'tfoot' && type != 'button' && type != '<') {

                if (model) {

                    //element.attr('id', model.table_name + '_' + (id ? id : type));

                    if(type == 'grid') {
                        element.addClass(model.table_name);
                    }
                }
            }

        } else {

            return false;
        }
    }

    return element;
}

function lang(txt) {
    return language[txt] ? language[txt] : txt;
}

function resetForm(form, form_container) {
    form[0].reset();

    if(form_container)
    for(var i in form_container.widgets) {
        form_container.widgets[i].reset();
    }

    form.find('.help-inline').remove();
}

function ajax(settings) {

    jQuery.ajax(
        {
            url: settings.url,
            type: 'POST',
            data: settings.data,
            async: settings.async ? settings.async : true,
            cache: false,
            beforeSend: function () {
                return settings.beforeSend ? settings.beforeSend() : '';
            },
            success: function (response) {

                if (response.logged && response.logged === false) {
                    location.reload();
                    return;
                }

                return settings.success ? settings.success(response) : '';
            },
            error: function () {

                settings.success('', true);
            }
        }
    );
}


var ModuleManager = function () {

    this.modules = {};

    var self = this;

    this.init = function (modules) {

        for (var i in modules) {

            var module = new Module(modules[i]).init();

            this.appendModule(i, module, module.settings);

            module.inline = {};

            initInlineModules(module, module.settings);
        }

        if (sidebar && sidebar.length > 0) {

            var sidebarContainer = $('#sidebar');

            for (i in sidebar) {

                var row = sidebar[i];

                var sidebarRow;

                if( !row.type ) {

                    sidebarRow = buildFromTemplate('', 'sidebar_row');

                } else if(row.type === 'menu') {

                    sidebarRow = buildFromTemplate('', 'sidebar_menu');
                }

                var sidebarArrow = buildFromTemplate('', 'sidebar_arrow');

                sidebarRow.find('.text').append(sidebarArrow);

                sidebarRow.find('.text').text(row.title);
                sidebarRow.attr('rel', row.module);
                sidebarRow.find('.text').attr('rel', row.module).find('i').attr('rel', row.module);

                if(row.url) {
                    sidebarRow.attr('url', row.url);
                    sidebarRow.find('.text').attr('url', row.url).find('i').attr('url', row.url);
                }

                sidebarContainer.append(sidebarRow);

                sidebarRow.bind('click', self.onRowSeleted);
            }
        }

        frameworkInit();
    };

    this.onRowSeleted = function (event) {

        var itemSelected = $(event.target);

        self.sidebar_current_module = itemSelected.attr('rel');

        $('#sidebar .menu').removeClass('active');

        $('#page .grid-container, .external').hide();

        itemSelected.parent().addClass('active');
        itemSelected.addClass('active');

        var url = itemSelected.attr('url');

        if($.trim(url) === '') {

            moduleManager.modules[self.sidebar_current_module].containers.grid.show();
            moduleManager.modules[self.sidebar_current_module].containers.grid.external.hide();

        } else {

            ajax({
                url: BASE_URL + url,
                success: function (response) {

                    var module = $(response);

                    $('#' + self.sidebar_current_module + ' *').remove();
                    $('#' + self.sidebar_current_module).html(module);

                    module.show();
                }
            });
        }

        return false;
    };

    this.appendModule = function (i, module, settings) {

        $(settings.container).append(module.element);

        this.modules[modules[i].model.table_name] = module;
    };
};

function initInlineModules(module, settings) {

    module.inline = {};

    if (module.containers.form) {

        for (var i in module.model.fields) {

            var field = module.model.fields[i];

            if (field.type)
                if (field.type === 'inline') {

                    var moduleInline = {
                        model: field.model,
                        settings: {container: settings.container},
                        containers: {
                            form: {settings: {validation: 'default'}},
                            modal: {},
                            grid: {},
                            toolbar: {
                                settings: {
                                    actions: [
                                        {
                                            type: 'button',
                                            title: lang(lang('New ' + field.model.table_name)),
                                            action: 'new'
                                        }
                                    ]
                                }
                            }
                        }
                    };

                    moduleInline.containers.form = new containers.form(field.model, moduleInline.containers, moduleInline, module, i).init();
                    moduleInline.containers.modal = new containers.modal(field.model, moduleInline.containers, moduleInline, module, i).init();
                    moduleInline.containers.grid = new containers.inline(field.model, moduleInline.containers, moduleInline, module, i).init();
                    moduleInline.containers.toolbar = new containers.toolbar(field.model, moduleInline.containers, moduleInline, module, i).init();

                    module.containers.form.fields[i].append(moduleInline.containers.toolbar.element);
                    module.containers.form.fields[i].append(moduleInline.containers.grid.element);

                    $(settings.container).append(moduleInline.containers.modal.element);

                    module.inline[i] = moduleInline;

                    initInlineModules(moduleInline, settings);
                }
        }
    }
}


$(function () {

    moduleManager = new ModuleManager();
    moduleManager.init(modules);
});