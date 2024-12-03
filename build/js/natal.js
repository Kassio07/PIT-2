//Click para nevar
$('#nevar-tema').click(function(){ 
  $(document).snowfall({flakeCount : 100, maxSpeed : 10});
  $('#pararnevar-tema').show();
  $('#nevar-tema').hide();
});

//Click para parar de nevar
$('#pararnevar-tema').click(function(){
  $(document).snowfall('clear');
  $('#pararnevar-tema').hide();
  $('#nevar-tema').show();
});