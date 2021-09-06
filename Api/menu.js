function list_menu(id){
    $.ajax({
        type : "post",
        url : "menu.php",
        data : {
            id : id
        },
      success : function(event){
          var json = event
          $("#makanan").html(json[id].nama_makanan);
          $("#harga").html(json[id].harga);
          $("#jenis").html(json[id].jenis);
      }  
    });
}