(function($) {
    $.fn.autocompletar2 = function(_url, options)
    {
        var t = this;
        var default_options = {
            params: "",
            form: null,
            inputId: null,
            width: null,
            height: 150,
            minLength: 1,
            onNew: null,
            newParams: null,
            onSelect: null,
            delay: 300,
            limit: 300,
            method: "get"
        };
        $.extend(this, default_options);
        $.extend(this, options);

        t.url = _url;

        var target = t.get(0);
        t.target = target;

        var f_values = "";



        var result = $(target).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: t.url,
                    type: t.method,
                    dataType: "json",
                    data: "limit=300&q=" + URLEncode(request.term) + "&" + t.params + "&" + f_values,
                    success: function(data) {
                        response($.map(data, function(item) {
                            item.label = item.text;
                            item.value = item.text;
                            return item;
                        }));
                    }
                });
            },
            minLength: t.minLength,
            delay: t.delay,
            select: function(event, ui) {
                $("#" + t.inputId).val(ui.item.id);
                if ($.isFunction(t.onSelect))
                {
                    t.onSelect(event, ui.item);
                }
            },
            change: function(event, ui)
            {
                if (!ui.item)
                {
                    if ($.isFunction(t.onNew))
                        t.onNew($(target).val(), t);
                    $(target).val("");
                    $("#" + t.inputId).val("");
                }
                else
                {
                    $("#" + t.inputId).val(ui.item.id);
                }
            },
            open: function() {
                $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
                $(".ui-autocomplete").css("max-height", t.height + "px");
                $(".ui-autocomplete").css("z-index", "10000");
                if (t.width != null)
                    $(".ui-autocomplete").css("max-width", t.width + "px");
            },
            close: function() {
                $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
            },
            search: function(event, ui)
            {
                if (t.form == null)
                {
                    f_values = "";
                }
                else
                {
                    f_values = $("#" + t.form).values();
                }

            }

        });
    }
})(jQuery);

/* ********************* ********************* ********************* ********************* ********************* */


(function($) {
    $.fn.values = function()
    {
        var s = "";
        var f = this.get(0);
        if (f) {
            $(f).find("input, textarea, select").each(function(index, e) {

                if (e.name)
                {
                    if (e.type == "textarea" && e.className == "mceAdvanced")
                    {
                        var t = tinyMCE.get(e.name).getContent();
                        s += e.name + "=" + URLEncode(t) + "&";
                    }
                    else if (e.type == "textarea" || e.type == "select-one" || e.type == "text" || e.type == "hidden" || e.type == "password")
                    {

                        if ($(e).hasClass("tinymce"))
                        {
                            var t = tinyMCE.get(e.name).getContent();
                            s += e.name + "=" + URLEncode(t) + "&";
                        }
                        else
                        {
                            if (e.value) {
                                s += e.name + "=" + URLEncode(e.value) + "&";
                            } else {
                                s += e.name + "=NULL&";
                            }
                        }
                    }
                    else if (e.type == "select-multiple")
                    {
                        var t = "";
                        t = $(e).val();
                        if (t == "")
                        {
                            s += e.name + "=NULL&";
                        }
                        else
                        {
                            s += e.name + "=" + URLEncode(t) + "&";
                        }

                    }
                    else if (e.type == "radio" || e.type == "checkbox")
                    {
                       if (e.checked){
                           s += e.name + "=" + URLEncode(e.value) + "&";
                       }
                    }
                    else if (e.value)
                    {

                        s += e.name + "=" + URLEncode(e.value) + "&";
                    }
                }
            });
        }

        if (s != "")
            s = s.substring(0, s.length - 1);
        return s;
    }
})(jQuery);