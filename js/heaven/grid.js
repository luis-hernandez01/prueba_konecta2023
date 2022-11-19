// INGENIERO - FABIO GARCIA ALVAREZ ( 1077461284 )
(function($) {

    $.fn.grid = function(user_options)
    {
        return this.each(function() {
            $.addGrid(this, user_options);
        });

    };

    /********************/
    $.addGrid = function(obj, user_options) {

        var default_options = {
            width: "100%",
            height: 200,
            cols: {},
            selectionMode: "single",
            idName: "id",
            currentPage: 0,
            totalRecords: 0,
            recordPage: 20,
            totalPages: 0,
            rowHeaderWidth: 25
        };
        user_options = $.extend(default_options, user_options);


        var g = {
            //t:null,
            load: function()
            {
                //g.t=new Date().getTime();
                $(g.bTable).find("tr").remove();
                $(".pDiv").show();
                g.currentPage = $(g.fDiv).find(".page")[0].value;
                /*var xhr=post(this.url,"page=" + g.currentPage + "&recordpage=" + g.recordPage);
                 g.addData(xhr.responseText);
                 */
                if (this.url != "")
                {
                    $.ajax({
                        type: "GET",
                        url: this.url,
                        data: "page=" + g.currentPage + "&recordpage=" + g.recordPage,
                        success: function(data) {
                            $(".pDiv").hide();
                            g.addData(data);

                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            try {
                                if (p.onError)
                                    p.onError(XMLHttpRequest, textStatus, errorThrown);
                            } catch (e) {
                            }
                        }
                    });
                }
            },
            moveFirst: function() {
                $(g.fDiv).find(".page")[0].value = 1;
                g.load();
            },
            movePrev: function() {
                var c = $(g.fDiv).find(".page")[0].value;
                if (c > 1) {
                    $(g.fDiv).find(".page")[0].value = c - 1;
                    g.load();
                }
            },
            moveNext: function() {
                var c = $(g.fDiv).find(".page")[0].value;
                if (c < g.totalPages) {
                    $(g.fDiv).find(".page")[0].value = parseInt(c) + 1;
                    g.load();
                }
            },
            moveLast: function() {
                $(g.fDiv).find(".page")[0].value = g.totalPages;
                g.load();
            },
            inputPageChange: function(o) {
                g.load();
            },
            addData: function(data)
            {
                if (jQuery.trim(data) == "") {
                    return;
                }
                
                try {
                    data = jQuery.parseJSON(data);
                }
                catch (ex)
                {
                    alert('Se presentó un error desconocido al listar los datos');
                    return;
                }

                data = $.extend({rows: [], page: 0, total: 0}, data);
                g.totalRecords = data.total;
                g.currentPage = $(g.fDiv).find(".page")[0].value;
                g.totalPages = Math.ceil(data.total / g.recordPage);
                $(g.fDiv).find(".page_total").html(" de " + g.totalPages);

                var first = ((g.currentPage - 1) * g.recordPage) + 1;
                var last = first + g.recordPage - 1;
                if (last > g.totalRecords)
                    last = g.totalRecords;

                $(g.fDiv).find(".info").html(" Mostrando desde " + first + " a " + last + " de " + g.totalRecords);

                var x = 1;
                for (i in data.rows)
                {
                    var rw = data.rows[i];
                    var tr = g.addRow(data.rows[i]);
                    tr.className = (x++) % 2 == 0 ? "even-row" : "odd-row";
                    tr.setAttribute("id", 'tr_'+rw['id']);
                    tr.style.maxHeight = "20px";
                }

            },
            addRow: function(rw, position)
            {
                var tr = document.createElement("tr");
                tr.onclick = g.selectTr;

                var div = document.createElement("div");
                div.style.width = g.rowHeaderWidth + "px";

                var td = document.createElement('td');
                td.width = g.rowHeaderWidth + "px";
                td.appendChild(div);

                if (g.selectionMode != "none")
                {

                    var chk = document.createElement("input");
                    if (g.selectionMode == "single")
                    {
                        chk.type = "radio";
                        chk.name = g.idName;
                    }
                    else if (g.selectionMode == "multi")
                    {
                        chk.type = "checkbox";
                        chk.name = g.idName;
                    }

                    chk.value = rw[g.idField];

                    chk.onchange = g.selectRadio;
                    chk.setAttribute("selectable", "yes");
                    div.appendChild(chk);
                }
                td.className = "rowHeader";

                tr.appendChild(td);


                for (i in this.cols)
                {
                    var c = this.cols[i];
                    var td = document.createElement("td");
                    td.style.width = c.width + "px";

                    var div = document.createElement("div");
                    div.innerHTML = (rw[c.name] == undefined) ? "-" : rw[c.name];
                    div.setAttribute("name", c.name);
                    div.style.textAlign = c.align;
                    div.style.width = c.width + "px";
                    td.appendChild(div);
                    tr.appendChild(td);
                }
                var td = document.createElement("td"); //Columna de relleno
                td.innerHTML = "";
                tr.appendChild(td);

                //Determinar donde agregar la nueva fila 
                var pos = null;
                if (position == 0)
                {
                    pos = this.bTable.firstChild;
                }
                else if (typeof position == "object")
                {
                    pos = position;
                }
                this.bTable.insertBefore(tr, pos);
                return tr;
            },
            removeRow: function(rw) {
                $(rw).remove();
            },
            selectTr: function(e) {
                var tr = e.target;//.parentNode.parentNode;
                if (tr.tagName == "INPUT")
                    return;
                if (tr.tagName != "TR")
                    tr = tr.parentNode;
                if (tr.tagName != "TR")
                    tr = tr.parentNode;
                if (tr.tagName != "TR")
                    tr = tr.parentNode;
                if (tr.tagName != "TR")
                    tr = tr.parentNode;

                $(tr).find('input[selectable="yes"]').each(function(i, chk) {
                    chk.checked = !chk.checked;
                });
                g.selectRadio();

            },
            selectRadio: function() {
                $(g.bTable).find('input[selectable="yes"]').each(function(i, chk) {
                    //var radio=e.target;
                    var tr = chk.parentNode.parentNode.parentNode;
                    if (chk.checked == true) {
                        $(tr).addClass("selected");
                    } else {
                        $(tr).removeClass("selected");
                    }
                });
            },
            selectAllRow: function(e)
            {
                var chk = e.target;
                $(g.bTable).find('input[selectable="yes"]').attr("checked", chk.checked);
                g.selectRadio();
            },
            getSelectedCount: function()
            {
                //console.log(g.idName);
                return $(g.bTable).find("input[name=" + g.idName + "]:checked").length;
            },
            getSelectedValues: function(index)
            {
                if (g.getSelectedCount() == 0)
                {
                    return null;
                }
                else
                {
                    var v = $(g.bTable).find("input[name=" + g.idName + "]:checked");

                    if (index != undefined)
                    {
                        return (index >= v.length) ? null : v[index].value;
                    }
                    else
                    {
                        var r = new Array();
                        $(v).each(function(index, element) {
                            r[index] = element.value;
                        });
                        return r;
                    }

                }
            },
            getSelectedRow: function(index)
            {
                if (g.getSelectedCount() == 0)
                {
                    return null;
                }
                else
                {
                    var v = $(g.bTable).find("input[name=" + g.idName + "]:checked");

                    if (index != undefined)
                    {
                        return (index >= v.length) ? null : v[index].parentNode.parentNode.parentNode;
                    }
                    else
                    {
                        var r = new Array();
                        $(v).each(function(index, element) {
                            r[index] = element.parentNode.parentNode;
                        });
                        return r;
                    }
                }
            }
        };

        g = $.extend(user_options, g);

        g.gDiv = document.createElement('div'); //create global container
        g.hDiv = document.createElement('div'); //create header container
        g.bDiv = document.createElement('div'); //create body container
        g.fDiv = document.createElement('div'); //create footer container
        g.pDiv = document.createElement('div'); //create progress container

        g.gDiv.className = "gDiv";
        g.gDiv.style.width = g.width;


        //Crear encabezados
        g.hDiv.className = "hDiv";
        g.hTable = document.createElement('table');
        g.hTable.className = "hTable";
        g.hDiv.appendChild(g.hTable);



        //Fila de encabezado		
        var tr = document.createElement('tr');
        g.hTable.appendChild(tr);


        var th = document.createElement('th');
        th.style.width = g.rowHeaderWidth + "px";
        tr.appendChild(th);

        if (g.selectionMode == "multi") {
            var chk = document.createElement("input");
            chk.onchange = g.selectAllRow;
            chk.type = "checkbox";
            th.appendChild(chk);
            g.idName + "[]";
        }

        for (var i in g.cols)
        {
            var c = g.cols[i];
            var th = document.createElement('th');
            th.style.width = c.width + "px";

            var div = document.createElement('div');

            div.innerHTML = c.display;
            div.setAttribute("name", c.name);
            div.style.textAlign = c.align;
            div.style.width = c.width + "px";

            th.appendChild(div);
            tr.appendChild(th);
        }
        var th = document.createElement('th'); //Columna de relleno
        tr.appendChild(th);



        g.gDiv.appendChild(g.hDiv);

        //Crear cuerpo
        g.bTable = document.createElement("table");
        g.bTable.className = "bTable";
        g.bDiv.appendChild(g.bTable);

        g.pDiv.innerHTML = "Cargando...";
        g.pDiv.className = "pDiv";
        g.bDiv.appendChild(g.pDiv);

        g.gDiv.appendChild(g.bDiv);
        g.bDiv.style.height = g.height + "px";
        g.bDiv.style.overflow = "auto";
        g.bDiv.className = "bDiv";

        //Crear pie

        g.gDiv.appendChild(g.fDiv);
        g.fDiv.className = "fDiv";
        var fHTML = "<div class='pFirst pButton'></div>";
        fHTML += "<div class='pPrev pButton'></div>";
        fHTML += "<div class='btnseparator'></div>";

        fHTML += "<span class='current_page'>Pagina</span>";
        fHTML += "<input class='page' value='1'/>";
        fHTML += "<div class='btnseparator'></div>";
        fHTML += "<span class='page_total'></span>";

        fHTML += "<div class='btnseparator'></div>";
        fHTML += "<div class='pNext pButton'></div>";
        fHTML += "<div class='pLast pButton'></div>";

        fHTML += "<div class='btnseparator'></div>";
        fHTML += "<span class='info'></span>";
        g.fDiv.innerHTML = fHTML;

        $(g.fDiv).find(".pFirst")[0].onclick = g.moveFirst;
        $(g.fDiv).find(".pPrev")[0].onclick = g.movePrev;
        $(g.fDiv).find(".pNext")[0].onclick = g.moveNext;
        $(g.fDiv).find(".pLast")[0].onclick = g.moveLast;
        $(g.fDiv).find(".page")[0].onchange = g.inputPageChange;

        $(obj).append(g.gDiv);
        g.load();
        return g;
    };



})(jQuery);