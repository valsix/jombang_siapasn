var mySkins = [
    //'skin-blue',
    //'skin-black',
    'skin-red',
    'skin-yellow',
    'skin-purple',
    'skin-green',
    //'skin-blue-light',
    //'skin-black-light',
    'skin-red-light',
    'skin-yellow-light',
    'skin-purple-light',
    'skin-green-light'
  ];
  
  var tmp = get('skin');
  if (tmp && $.inArray(tmp, mySkins))
      changeSkin(tmp)
  
  function get(name) {
    if(typeof (localStorage.getItem(name)) !== 'undefined')
      localStorage.setItem(name, "skin-purple");

    if (typeof (Storage) !== 'undefined') {
	  //return 'skin-blue';
      return localStorage.getItem(name)
    } else {
      window.alert('Please use a modern browser to properly view this template!')
    }
  }
  
  function store(name, val) {
    if (typeof (Storage) !== 'undefined') {
      localStorage.setItem(name, val)
    } else {
      window.alert('Please use a modern browser to properly view this template!')
    }
  }
  
  function changeSkin(cls) {
    $.each(mySkins, function (i) {
      $('.ubah-color-warna').removeClass(mySkins[i])
    })
	
	$('.ubah-color-warna').addClass(cls)
    store('skin', cls)
    return false
  }