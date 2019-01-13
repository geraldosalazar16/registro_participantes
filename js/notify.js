


  function notify_success(titulo, texto) {
      new PNotify({
          title: titulo,
          text: texto,
          type: 'success',
          nonblock: {
              nonblock: true,
              nonblock_opacity: .2
          },
          delay: 2500
      });
  }

  function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
      var pair = vars[i].split("=");
      if (pair[0] == variable) {
        return pair[1];
      }
    } 
    //alert('Query Variable ' + variable + ' not found');
  }