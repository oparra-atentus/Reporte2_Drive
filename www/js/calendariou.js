MES=null,Date.prototype.toISOString||!function(){function e(e){var a=String(e);return 1===a.length&&(a="0"+a),a}Date.prototype.toISOString=function(){return this.getUTCFullYear()+"-"+e(this.getUTCMonth()+1)+"-"+e(this.getUTCDate())+"T"+e(this.getUTCHours())+":"+e(this.getUTCMinutes())+":"+e(this.getUTCSeconds())+"."+String((this.getUTCMilliseconds()/1e3).toFixed(3)).slice(2,5)+"Z"}}(),function(e){var a=function(e){this.init(e)};a.NombresAbreviadosDeDias=["lun","mar","mié","jue","vie","sáb","dom"],a.NombresDeMeses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"],a.NombresAbreviadosDeMeses=["ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic"],a.prototype={constructor:a,init:function(e){if(this.input=e,e instanceof Array)this.date=new Date(this.parseISO8601ToTimestamp(e[0]+"-"+this._pad(e[1]+1)+"-"+this._pad(e[2])));else if("object"==typeof this.input&&this.input instanceof Date)this.date=e;else if("string"==typeof this.input){var i=/^(\d{4})-(\d{2})-(\d{2})T(\d{2})\:(\d{2})\:(\d{2})$/,t=i.exec(this.input);if(t){var n=parseInt(t[1],10),s=parseInt(t[2],10)-1,o=parseInt(t[3],10),c=parseInt(t[4],10),r=parseInt(t[5],10),h=parseInt(t[6],10);this.date=new Date(n,s,o,c,r,h)}else this.date=new Date(this.input)}else this.date=new Date(this.input);this.dia=this.date.getDate(),this.mes=this.date.getMonth(),this.ano=this.date.getFullYear(),this.hora=this.date.getHours(),this.minuto=this.date.getMinutes(),this.segundo=this.date.getSeconds(),this.nombreDeMes=a.NombresDeMeses[this.mes],this.nombreAbreviadoDeMes=a.NombresAbreviadosDeMeses[this.mes],this.diaDeSemana=0===this.date.getDay()?7:this.date.getDay()},esMayorQue:function(e){return this.date.getTime()>e.date.getTime()},esMayorOIgualQue:function(e){return this.date.getTime()>=e.date.getTime()},esMenorQue:function(e){return this.date.getTime()<e.date.getTime()},esMenorOIgualQue:function(e){return this.date.getTime()<=e.date.getTime()},esIgualQue:function(e){return this.date.getTime()===e.date.getTime()},toLocalString:function(){return this.date.getFullYear()+"-"+this._pad(this.date.getMonth()+1)+"-"+this._pad(this.date.getDate())+"T"+this._pad(this.date.getHours())+":"+this._pad(this.date.getMinutes())+":"+this._pad(this.date.getSeconds())+"."+String((this.date.getMilliseconds()/1e3).toFixed(3)).slice(2,5)},aLas:function(e){var i=null;switch(e){case"0 horas":i=new a(this.format("yyyy-mm-dd")+"T00:00:00");break;case"24 horas":i=new a(this.format("yyyy-mm-dd")+"T24:00:00")}return i},format:function(e){var a=null;switch(e){case"yyyy-mm-dd":a=this.toLocalString().slice(0,10);break;case"hh:mm":a=this.toLocalString().slice(11,16);break;case"yyyy-mm-ddThh:mm:ss":var i=this.toLocalString();a=i.slice(0,10)+"T"+i.slice(11,19);break;case"yyyy-mm-ddThh:mm:ssZ":var i=this.toLocalString();a=i.slice(0,10)+"T"+i.slice(11,19)+"Z"}return a},parseISO8601ToTimestamp:function(e){var a,i,t=0,n=[1,4,5,6,7,10,11];if(i=/^(\d{4}|[+\-]\d{6})(?:-(\d{2})(?:-(\d{2}))?)?(?:T(\d{2}):(\d{2})(?::(\d{2})(?:\.(\d{3}))?)?(?:(Z)|([+\-])(\d{2})(?::(\d{2}))?)?)?$/.exec(e)){for(var s,o=0;s=n[o];++o)i[s]=+i[s]||0;i[2]=(+i[2]||1)-1,i[3]=+i[3]||1,"Z"!==i[8]&&void 0!==i[9]&&(t=60*i[10]+i[11],"+"===i[9]&&(t=0-t)),a=Date.UTC(i[1],i[2],i[3],i[4],i[5]+t,i[6],i[7])}else a=NaN;return a},_pad:function(e){return 10>e?"0"+e:e}};var i=function(e,a){a=a||{},this.init(e,a)};i.prototype={constructor:i,init:function(i,t){var n=this;this.fecha=i,this.$el=e('<div class="selector"></div>'),this.fechaMinima=t.fechaMinima,this.fechaMaxima=t.fechaMaxima,this.$botonPrevio=e('<div class="boton previo">&#x25c0;</div>'),this.$botonSiguiente=e('<div class="boton siguiente">&#x25b6;</div>'),this.$botonHoy=e('<div class="boton actual">Hoy</div>'),this.$botonPrevio.on("click",function(){n.setFecha(new a(new Date(n.fecha.ano,n.fecha.mes-1,n.fecha.dia)).aLas("0 horas"),t.especial)}),this.$botonSiguiente.on("click",function(){n.setFecha(new a(new Date(n.fecha.ano,n.fecha.mes+1,n.fecha.dia)).aLas("0 horas"),t.especial)}),this.$botonHoy.on("click",function(){var e=new a(new Date),i=new a(new Date(e.ano,e.mes,1));n.setFecha(i.aLas("0 horas"),t.especial)}),this.$el.append(this.$botonPrevio,this.$botonHoy,this.$botonSiguiente),n._habilitaODeshabilitaBotones(t.especial),this.$el.on("calendariou:selector:cambio",function(){n._habilitaODeshabilitaBotones(t.especial)})},setFecha:function(e,i){if(!i){if(this.fechaMinima){var t=new a(new Date(this.fechaMinima.ano,this.fechaMinima.mes,1)).aLas("0 horas");if(e.esMenorQue(t))return void this.$el.trigger("calendariou:selector:error",{mensaje:"Fecha está fuera de rango (límite inferior)",parametros:{fecha:e.format("yyyy-mm-ddThh:mm:ss"),fechaMinima:this.fechaMinima.format("yyyy-mm-ddThh:mm:ss")}})}if(this.fechaMaxima){var n=this.fechaMaxima.aLas("24 horas");if(e.esMayorOIgualQue(n))return void this.$el.trigger("calendariou:selector:error",{mensaje:"Fecha está fuera de rango (límite superior)",parametros:{fecha:e.format("yyyy-mm-ddThh:mm:ss"),fechaMaxima:this.fechaMaxima.format("yyyy-mm-ddThh:mm:ss")}})}}this.fecha=e,this.$el.trigger("calendariou:selector:cambio")},el:function(){return this.$el},_habilitaODeshabilitaBotones:function(e){if(this.fechaMinima){var i=new a(new Date(this.fecha.ano,this.fecha.mes,1));i.aLas("0 horas").esMenorOIgualQue(this.fechaMinima)?this.$botonPrevio.addClass("desactivado"):this.$botonPrevio.removeClass("desactivado")}if(!e&&this.fechaMaxima){var t=new a(new Date(this.fecha.ano,this.fecha.mes+1,0));t.aLas("24 horas").esMayorOIgualQue(this.fechaMaxima)?this.$botonSiguiente.addClass("desactivado"):this.$botonSiguiente.removeClass("desactivado")}}};var t=function(e){e||(e={}),this.init(e)};t.prototype={constructor:t,init:function(i){this.fechaInicio=i.fechaInicio?new a(i.fechaInicio):null,this.fechaTermino=i.fechaTermino?new a(i.fechaTermino):null,this.$el=e('<div class="wrapper"><div class="fecha inicio"></div><div class="separador">al</div><div class="fecha termino"></div></div>'),this.update()},el:function(){return this.$el},update:function(){var i=this.$el.find(".fecha.inicio"),t=null!=e("#dialog-calendario")[0]?!0:!1;if(this.fechaInicio){var n=e('<div class="dia">'+this.fechaInicio.dia+"</div>"),s=e('<div class="mes-y-ano">'+this.fechaInicio.nombreAbreviadoDeMes+" "+this.fechaInicio.ano+"</div>"),o=e('<div id="hI"class="hora inicio"></div>');i.empty().append(n,s).show();var c=e('<div class="hora inicio">'+this.fechaInicio.format("hh:mm")+"</div>");1==t?i.append(o):i.append(c)}else i.hide().empty();var r=this.$el.find(".separador"),h=this.$el.find(".fecha.termino");if(this.fechaTermino){if(0===this.fechaTermino.hora&&0===this.fechaTermino.minuto&&0===this.fechaTermino.segundo)var d=new a(new Date(this.fechaTermino.ano,this.fechaTermino.mes,this.fechaTermino.dia-1)),l=d.dia,m=d.nombreAbreviadoDeMes,f=d.ano,u="24:00";else var l=this.fechaTermino.dia,m=this.fechaTermino.nombreAbreviadoDeMes,f=this.fechaTermino.ano,u=this.fechaTermino.format("hh:mm");var n=e('<div class="dia">'+l+"</div>"),s=e('<div class="mes-y-ano">'+m+" "+f+"</div>"),v=e('<div id="hT"class="hora termino"></div>');h.empty().append(n,s).show(),r.show();var p=e('<div class="hora termino">'+u+"</div>");1==t?h.append(v):h.append(p)}else r.hide(),h.hide().empty()},set:function(e,i){switch(e){case"fechaInicio":this.fechaInicio=null!==i?new a(i):null,this.update(),this.$el.trigger("calendariou:seleccion:cambio");break;case"fechaTermino":if(null===i?this.fechaTermino=this.fechaInicio:this.fechaTermino=new a(i),null!==this.fechaTermino&&this.fechaTermino.esMenorQue(this.fechaInicio)){var t=this.fechaInicio;this.fechaInicio=this.fechaTermino,this.fechaTermino=t}this.update(),this.$el.trigger("calendariou:seleccion:cambio")}},get:function(e){var a=null;switch(e){case"fechaInicio":a=this.fechaInicio;break;case"fechaTermino":a=this.fechaTermino}return a}};var n=function(e,a){a=a||{},this.init(e,a)};n.prototype={constructor:n,init:function(a,i){1==i.actualizar||(this.$el=e('<div class="wrapper"></div>'),this.permiteSeleccionar=i.permiteSeleccionar,this.seleccionaIntervalo=i.seleccionaIntervalo,this.fechaMinima=i.fechaMinima,this.fechaMaxima=i.fechaMaxima,this.estaSeleccionandoPrimerParametro=!0,this.generar(a,i.especial))},el:function(){return this.$el},generar:function(i,t){for(var n=this,s=i.ano,o=i.mes,c=(i.dia,new Date(s,o+1,0).getDate()),r=e("<table><thead></thead><tbody></tbody></table>"),h=e("<tr></tr>"),d=0;d<a.NombresAbreviadosDeDias.length;d++)h.append("<th>"+a.NombresAbreviadosDeDias[d]+"</th>");e("thead",r).append(h);var l=1,m=new Date(s,o,l),f=new a(m),u=f.diaDeSemana;u>1&&(l=-u+2);for(var v,p=e("tbody",r),g=l,y=new a(new Date);c>=g;){v=e("<tr></tr>");for(var w=1;7>=w;w++){var T=new Date(s,o,g),b=new a(T),M=b.format("yyyy-mm-dd"),I=["dia"];y.ano===b.ano&&y.mes===b.mes&&y.dia===b.dia&&I.push("actual"),b.mes!==o&&I.push("de-otro-mes"),t||(this.fechaMinima&&b.esMenorQue(this.fechaMinima.aLas("0 horas"))&&I.push("fuera-de-rango"),this.fechaMaxima&&b.esMayorOIgualQue(this.fechaMaxima)&&I.push("fuera-de-rango")),v.append('<td><div class="'+I.join(" ")+'" id ="'+M+'" data-fecha="'+M+'">'+b.dia+"</div></td>"),g++}p.append(v)}if(this.permiteSeleccionar){var D=".dia:not(.de-otro-mes):not(.fuera-de-rango)",C=function(){var a=r.find(D);n.estaSeleccionandoPrimerParametro?(a.removeClass("seleccionado"),n.$el.trigger("calendariou:mesCalendario:seleccionaFechaInicio",e(this).data("fecha")),n.seleccionaIntervalo&&(n.estaSeleccionandoPrimerParametro=!1)):(n.$el.trigger("calendariou:mesCalendario:seleccionaFechaTermino",e(this).data("fecha")),n.estaSeleccionandoPrimerParametro=!0)};r.find(D).on("click",C)}var $=e('<div class="titulo">'+i.nombreDeMes+" "+i.ano+"</div>"),S=e('<div class="contenido"></div>');S.append(r),this.$el.empty().append($).append(S),this.$el.trigger("calendariou:mesCalendario:generarReady")},marcarIntervalo:function(i,t){if(null!==i){var n=this.$el.find(".contenido table .dia");n.removeClass("seleccionado"),n.each(function(n){var s=e(this),o=new a(s.data("fecha")+"T00:00:00"),c=i.aLas("0 horas");if(null===t)o.esIgualQue(c)&&s.addClass("seleccionado");else{var r=t;(o.esMayorQue(c)||o.esIgualQue(c))&&o.esMenorQue(r)&&s.addClass("seleccionado")}})}}};var s=function(e,a){a||(a={}),this.init(e,a)};s.prototype={constructor:s,init:function(s,o){var c=this;if(this.$el=e(s),this.$calendariou=e('<div class="calendariou"><div class="mes-calendario"></div><div class="navegacion"></div><div class="seleccion"></div></div>'),this.$el.empty().append(this.$calendariou),o.fechaCalendario)var r=new a(o.fechaCalendario);else var r=new a(new Date);o.especial?this.fechaCalendario=new a(new Date(r.ano,MES,1)).aLas("0 horas"):this.fechaCalendario=new a(new Date(r.ano,r.mes,1)).aLas("0 horas"),o.fechaMaxima?this.fechaMaxima=new a(o.fechaMaxima):this.fechaMaxima=new a(new Date),o.fechaMinima?this.fechaMinima=new a(o.fechaMinima):this.fechaMinima=null;var h=o.seleccion||{},d=void 0!==h.activa?h.activa:!0,l=void 0!==h.intervalo?h.intervalo:!0;this.seleccion=new t({fechaInicio:o.fechaInicio||null,fechaTermino:o.fechaTermino||null,especial:o.mantenimiento||!1}),this.$calendariou.find(".seleccion").append(this.seleccion.el()),this.seleccion.el().on("calendariou:seleccion:cambio",function(){c.mesCalendario.marcarIntervalo(c.seleccion.get("fechaInicio"),c.seleccion.get("fechaTermino"))});var m={permiteSeleccionar:d,seleccionaIntervalo:l,fechaMinima:this.fechaMinima,fechaMaxima:this.fechaMaxima,especial:o.mantenimiento||!1};this.mesCalendario=new n(this.fechaCalendario,m),this.$calendariou.find(".mes-calendario").append(this.mesCalendario.el()),this.mesCalendario.el().on("calendariou:mesCalendario:seleccionaFechaInicio",function(e,a){c.actualizarSeleccion({fechaInicio:a+"T00:00:00",fechaTermino:a+"T24:00:00"})}),this.mesCalendario.el().on("calendariou:mesCalendario:seleccionaFechaTermino",function(e,i){var t=new a(i+"T00:00:00"),n=new a(i+"T24:00:00"),s=c.seleccion.get("fechaInicio"),o=null;if(t.esMenorQue(s)){var r=s;s=t,o=new a(r.format("yyyy-mm-dd")+"T24:00:00")}else o=n;var h=new a(new Date(s.ano,s.mes+3,s.dia));h.esMenorQue(o)?c.$el.trigger("calendariou:warning",{mensaje:"No es posible seleccionar un intervalo mayor a tres meses.",parametros:{fechaInicio:s.format("yyyy-mm-ddThh:mm:ss"),fechaTermino:o.format("yyyy-mm-ddThh:mm:ss")}}):c.actualizarSeleccion({fechaInicio:s.format("yyyy-mm-ddThh:mm:ss"),fechaTermino:o.format("yyyy-mm-ddThh:mm:ss")})}),this.selector=new i(this.fechaCalendario,{fechaMinima:this.fechaMinima,fechaMaxima:this.fechaMaxima,especial:o.mantenimiento||!1}),this.$calendariou.find(".navegacion").append(this.selector.el()),this.selector.el().on("calendariou:selector:cambio",function(){c.mesCalendario.generar(c.selector.fecha,o.mantenimiento),c.mesCalendario.el().trigger("calendariou:mesCalendario:cambiaMes",{ano:c.selector.fecha.ano,mes:c.selector.fecha.mes+1})}),this.seleccion.el().trigger("calendariou:seleccion:cambio"),this.mesCalendario.el().on("calendariou:mesCalendario:generarReady",function(){c.mesCalendario.marcarIntervalo(c.seleccion.get("fechaInicio"),c.seleccion.get("fechaTermino"))})},actualizarSeleccion:function(e){void 0!==e.fechaInicio&&this.seleccion.set("fechaInicio",e.fechaInicio),void 0!==e.fechaTermino&&this.seleccion.set("fechaTermino",e.fechaTermino)}},e.fn.calendariou=function(i){return this.each(function(){var t=new a(new Date(i.fechaInicio)).aLas("0 horas");new a(new Date(i.fechaTermino)).aLas("0 horas");MES=t.mes;var n=e(this),o=n.data("calendariou");o?(delete o,e(".boton previo").off("click"),e(".boton actual").off("click"),e(".boton previo").off("click"),o=new s(this,i),n.data("calendariou",o)):(o=new s(this,i),n.data("calendariou",o))})},e.fn.calendariou.Constructor=s,window.Reporte2=window.Reporte2||{},window.Reporte2.Calendariou={},window.Reporte2.Calendariou.Calendariou=s,window.Reporte2.Calendariou.Fecha=a,window.Reporte2.Calendariou.mes=n}(window.jQuery);