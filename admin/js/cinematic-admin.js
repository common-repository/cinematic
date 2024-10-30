(function () {
    new Vue({
        el: '#cinematicapp',
        data: {
            mode: 'sliders',// sliders | constructor | slideshow | library
            url: wpData.rest_url,
            imageUrl: wpData.image_url,
            restUrl: wpData.rest_url,
            nonce: wpData.nonce,
            sliders: [],
            id: null,
            title: null,
            cinematic: null,
            mediaLibrary: null,
            itemToPick: null,
            height: 0,
            dots: true,
            slideshow: false,
            speed: 0,
            slides: [],
            slideIndex: 0,
            slide: {
                zoom: 0,
                duration: 0,
                timing: '',
                items: []
            },
            library: [],
            isLoading: false,
            deleteConfirmationVisible: false,
            deleteAllConfirmationVisible: false,
            proVersionVisible: false,
        },
        methods: {
            //Runs slideshow
            run: function () {
                if(this.slides.length<2){
                    return;
                }
                this.mode = 'slideshow';

                var self = this;

                setTimeout(function () {
                    self.cinematic = new Cinematic(document.getElementById('slider'),
                        {
                            dots: self.dots,
                            speed: 1000 * (self.speed ? self.speed : 4.0)
                        });
                },
                    0);
            },
            //Opens slider list
            openSliders: function () {
                this.sliders = [];
                var self = this;
                var sliders = new wp.api.collections.Cinematic_slider();
                sliders.fetch(
                    {
                        success: function (model, response, options) {
                            for (var i = 0; i < model.models.length; i++) {
                                self.sliders.push({
                                    id: model.models[i].get('id'),
                                    title: model.models[i].get('title').rendered
                                })
                            }
                            self.mode = 'sliders';
                            self.id = null;
                        },
                        error: function (model, response, options) {
                        }
                    }
                );
            },
            //OPens library
            openLibrary: function () {
                this.$nextTick(function () {
                    this.mode = 'library';
                });
            },
            //Returns back to constructor
            back: function () {
                if (this.cinematic) {
                    this.cinematic.unbind();
                    this.cinematic = null;
                }
                this.$nextTick(function () {
                    this.mode = 'constructor';
                });
            },
            //Selects current slide
            setSlide: function (index) {
                this.slideIndex = index;
                this.slide = this.slides[index];
            },
            //Adds image
            addImage: function () {
                this.slide.items.push({
                    isText: false,
                    id: this.slide.items.length + 1,
                    url: null,
                    distance: null,
                    left: null,
                    top: null,
                    width: null,
                    height: null
                });
            },
            //Deletes item
            deleteItem: function (index) {
                this.slide.items.splice(index, 1);
            },
            //Moves items down
            moveDown: function (index) {
                var item = this.slide.items[index];
                this.slide.items.splice(index, 1);
                this.slide.items.splice(index + 1, 0, item);
            },
            //Moves item up
            moveUp: function (index) {
                var item = this.slide.items[index];
                this.slide.items.splice(index, 1);
                this.slide.items.splice(index - 1, 0, item);
            },
            //Copies object
            copy: function (o) {
                var v, key;
                var output = Array.isArray(o) ? [] : {};
                for (key in o) {
                    if (o.hasOwnProperty(key)) {
                        v = o[key];
                        output[key] = typeof v === "object" ? this.copy(v) : v;
                    }
                }
                return output;
            },
            //Creates slide from library
            addSlideFromLibrary: function (index) {
                var slide = this.copy(this.library[index]);

                if(slide.items == null){
                    this.proVersionVisible = true;
                    return;
                }

                this.isLoading = true;
                var urls = [];
                for (var i = 0; i < slide.items.length; i++) {
                    if (!slide.items[i].isText) {
                        urls.push(slide.items[i].url);
                    }
                }

                this.$http.post(this.restUrl + '/cinematic/v1/processMedia', { id: this.id, urls: urls },
                    {
                        headers: {
                            'X-WP-Nonce': this.nonce
                        }
                    }).then(response => {
                        var newUrls = response.body;
                        for (var i = 0; i < slide.items.length; i++) {
                            if (!slide.items[i].isText) {
                                slide.items[i].url = newUrls[i];
                            }
                        }
                        this.slides.push({
                            id: this.slides.length + 1,
                            zoom: 2,
                            duration: 5,
                            timing: 'ease-out',
                            items: slide.items
                        });
                        this.slideIndex = this.slides.length - 1;
                        this.slide = this.slides[this.slideIndex];
                        this.saveSlider();
                        this.mode = 'constructor';
                        this.isLoading = false;
                    }, response => {
                        // error callback
                        this.isLoading = false;
                    });
            },
            //Adds new slide
            addSlide: function () {
                this.slides.push({
                    id: this.slides.length + 1,
                    zoom: 2,
                    duration: 5,
                    timing: 'ease-out',
                    items: []
                });
                this.slideIndex = this.slides.length - 1;
                this.slide = this.slides[this.slideIndex];
            },
            //Deletes slide
            deleteSlide: function () {
                this.slides.splice(this.slideIndex, 1);
                if (this.slides.length > 0) {
                    this.slideIndex = 0;
                    this.slide = this.slides[0];
                } else {
                    this.slideIndex = null;
                    this.slide = null;
                }
            },
            //Moves slide to the right
            moveRight: function () {
                var slide = this.slides[this.slideIndex];
                this.slides.splice(this.slideIndex, 1);
                this.slides.splice(this.slideIndex + 1, 0, slide);
                this.slideIndex = this.slideIndex + 1;
            },
            //Moves slide to the left
            moveLeft: function () {
                var slide = this.slides[this.slideIndex];
                this.slides.splice(this.slideIndex, 1);
                this.slides.splice(this.slideIndex - 1, 0, slide);
                this.slideIndex = this.slideIndex - 1;
            },
            //Downloads configuration file
            downloadConfig: function () {
                var data = {
                    height: this.height,
                    dots: this.dots,
                    slideshow: this.slideshow,
                    speed: this.speed,
                    slides: this.slides
                };

                var jsonData = JSON.stringify(data);

                try {
                    var b = new Blob([jsonData], { type: "application/json;charset=utf-8" });
                    saveAs(b, "cinematic-" + this.title + ".json");
                } catch (e) {
                    window.open("data:application/json;charset=utf-8," + encodeURIComponent(jsonData), '_blank', '');
                }
            },
            //Uploads configuration file
            uploadConfig: function (event) {
                if (typeof window.FileReader !== 'function')
                    throw ("The file API isn't supported on this browser.");
                var input = event.target;
                if (!input)
                    throw ("The browser does not properly implement the event object");
                if (!input.files)
                    throw ("This browser does not support the 'files' property of the file input.");
                if (!input.files[0])
                    return undefined;
                var file = input.files[0];
                var fr = new FileReader();
                var self = this;

                fr.onload = function (event) {
                    var data = JSON.parse(event.target.result);
                    self.height = data.height;
                    self.dots = data.dots;
                    self.slideshow = data.slideshow;
                    self.speed = data.speed;

                    self.slides = data.slides;
                    if (self.slides.length > 0) {
                        self.slideIndex = 0;
                        self.slide = self.slides[0];
                    }
                    var input = window.document.getElementById('file-upload');
                    input.value = '';

                    if (!/safari/i.test(navigator.userAgent)) {
                        input.type = '';
                        input.type = 'file';
                    }
                };
                fr.readAsText(file);
            },
            //Opens slider
            openSlider: function (id) {
                var existing = new wp.api.models.Cinematic_slider({
                    id: id
                });
                var self = this;
                existing.fetch({
                    success: function (model, response, options) {
                        self.title = model.get('title').rendered;
                        var dataString = model.get('cinematic_settings');
                        var data = JSON.parse(dataString);
                        self.height = data.height;
                        self.dots = data.dots;
                        self.slideshow = data.slideshow;
                        self.speed = data.speed;
                        self.slides = data.slides;
                        self.slide = null;
                        if (self.slides.length > 0) {
                            self.slideIndex = 0;
                            self.slide = self.slides[0];
                        }
                        self.mode = 'constructor';
                        self.id = id;
                    },
                    error: function (model, response, options) {
                    }
                });
            },
            //Creates slider
            createSlider: function () {
                this.title = 'slider';
                this.height = '';
                this.dots = true;
                this.slideshow = false;
                this.speed = 4.0;
                this.slides = [];
                this.setSlide(0);
                var slider = new wp.api.models.Cinematic_slider({
                    title: this.title,
                    content: '',
                    cinematic_settings: '',
                    status: 'publish'
                });
                var self = this;
                slider.save().done(function (result) {
                    self.id = result.id;
                    var created = new wp.api.models.Cinematic_slider({
                        id: result.id
                    });
                    var data = {
                        height: self.height,
                        dots: self.dots,
                        slideshow: self.slideshow,
                        speed: self.speed,
                        slides: self.slides
                    };

                    var jsonData = JSON.stringify(data);
                    created.set({
                        content: self.getCode(),
                        cinematic_settings: jsonData
                    });
                    created.save();
                    self.mode = 'constructor';
                });
            },
            //Generates code for slider
            getCode: function () {
                var code = '    <div class=\"cinematic cinematic-inactive\" id=\"cinematicslider' + this.id + '\">\n';
                for (var i = 0; i < this.slides.length; i++) {
                    var slide = this.slides[i];
                    code += '        <figure><div data-height=\"' + (this.height ? this.height : 50) + '%\" data-zoom=\"' + (slide.zoom ? slide.zoom : 2) + '\" data-timing=\"' + (slide.timing ? slide.timing : 'ease-out') + '\" data-duration=\"' + (slide.duration ? slide.duration : 5) + '\">\n';
                    for (var j = 0; j < slide.items.length; j++) {
                        var item = slide.items[j];
                        code += '            <div ';
                        if (item.distance) {
                            code += 'data-distance=\"' + item.distance + '\" ';
                        }
                        if (item.left) {
                            code += 'data-left=\"' + item.left + '\" ';
                        }
                        if (item.top) {
                            code += 'data-top=\"' + item.top + '\" ';
                        }
                        if (item.width) {
                            code += 'data-width=\"' + item.width + '\" ';
                        }
                        if (item.height) {
                            code += 'data-height=\"' + item.height + '\" ';
                        }

                        if (item.isText) {
                            code += '>' + item.text + '<\/div>\n';
                        }
                        else {
                            code += '><img style=\"width:100%;height:100%\" src=\"' + item.url + '\"/><\/div>\n';
                        }
                    }
                    code += '        <\/div></figure>\n';
                }
                code +=
                    '    <\/div>\n' +
                    '    <script>\n' +
					'       document.addEventListener(\'DOMContentLoaded\',\n' +
					'           function () {\n' +
					'       	     new Cinematic(document.getElementById(\'cinematicslider' + this.id + '\'), { dots: ' + this.dots + ', speed: ' + (1000 * (this.speed ? this.speed : 4.0)) + ' });\n' +
					'            });\n' +
                    '    <\/script>\n';
                return code;
            },
            //Saves slider
            saveSlider: function () {
                var data = {
                    height: this.height,
                    dots: this.dots,
                    slideshow: this.slideshow,
                    speed: this.speed,
                    slides: this.slides
                };

                var jsonData = JSON.stringify(data);

                if (this.id != null) {
                    var existing = new wp.api.models.Cinematic_slider({
                        id: this.id
                    });
                    existing.set({
                        title: this.title,
                        content: this.getCode(),
                        cinematic_settings: jsonData
                    });
                    existing.save();
                }
            },
            //Shows delete confirmation
            showDeleteConfirmation: function () {
                this.deleteConfirmationVisible = true;
            },
            //Hides delete confirmation
            hideDeleteConfirmation: function () {
                this.deleteConfirmationVisible = false;
            },
            //Deletes slider
            deleteSlider: function () {
                if (this.id != null) {
                    var existing = new wp.api.models.Cinematic_slider({
                        id: this.id
                    });
                    var self = this;
                    existing.destroy().done(function () {
                        self.openSliders();
                        self.hideDeleteConfirmation();
                    });
                }
            },
            //Shows delete all confirmation
            showDeleteAllConfirmation: function () {
                this.deleteAllConfirmationVisible = true;
            },
            //Hides delete confirmation
            hideDeleteAllConfirmation: function () {
                this.deleteAllConfirmationVisible = false;
            },
            //Shows PRO version
            showProVersion: function () {
                this.proVersionVisible = true;
            },
            //Hides delete confirmation
            hideProVersion: function () {
                this.proVersionVisible = false;
            },
            //Deletes all data
            deleteAllData: function () {
                var self = this;
                this.$http.delete(this.restUrl + '/cinematic/v1/deleteAll',
                    {
                        headers: {
                            'X-WP-Nonce': this.nonce
                        }
                    }).then(response => {
                        self.openSliders();
                        self.hideDeleteAllConfirmation();
                    }, response => {
                    });
            },
            //Picks image from media library
            pickImage: function (item) {
                this.itemToPick = item;
                if (!this.mediaLibrary) {
                    this.mediaLibrary = wp.media({
                        title: 'Select Media',
                        multiple: false,
                        library: {
                            type: 'image',
                        }
                    });
                    var self = this;
                    this.mediaLibrary.on('close', function () {
                        var image = self.mediaLibrary.state().get('selection').single();
                        if (image) {
                            self.itemToPick.url = image.attributes.url;
                        }
                    });
                }
                this.mediaLibrary.open();
            },
            //Copies shortcode to clipboard
            copyShortcode: function () {
                navigator.clipboard.writeText('[cinematic id=\'' + this.id + '\']');
            }
        },
        mounted: function () {
            this.$nextTick(function () {
                //Loads library data
                for (var i = window.cinematicLibrary.length - 1; i >= 0; i--) {
                    this.library.push(window.cinematicLibrary[i]);
                }
                this.sliders = [];
                var self = this;
                wp.api.loadPromise.done(function () {
                    if (!wp.api.collections.Cinematic_slider) {
                        sessionStorage.clear();
                        location.reload();
                    }

                    var sliders = new wp.api.collections.Cinematic_slider();
                    sliders.fetch(
                        {
                            success: function (model) {
                                for (var i = 0; i < model.models.length; i++) {
                                    self.sliders.push({
                                        id: model.models[i].get('id'),
                                        title: model.models[i].get('title').rendered
                                    });
                                }
                            },
                            error: function () {
                            }
                        }
                    );
                });
            });
        }
    });
})();