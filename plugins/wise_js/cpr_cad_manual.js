$(document).ready(function () {
  const Toast = Swal.mixin({
    toast: true,
    position: "top",
    showConfirmButton: false,
    timerProgressBar: true,
    timer: 6000,
  });

  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
  
  $('#emitente_cnpj').mask('00.000.000/0000-00', {reverse: false});
  $('#destinatario_cnpj').mask('00.000.000/0000-00', {reverse: false});
   
   

  $("#cod_nfe_input").keyup(function (e) {
    var tecla = e.keyCode ? e.keyCode : e.which;
    if (tecla == 13) {
      $("#numero").val($(this).val());
      $("#cod_nfe").removeClass("collapsed-card");
      $("#modelo").focus();
    }
  });

  
  $(".pula").keypress(function (e) {
    var tecla = e.keyCode ? e.keyCode : e.which;
    if (tecla == 13) {
      campo = $(".pula");
      indice = campo.index(this);
      e.preventDefault(e);
      if (campo[indice + 1] != null) {
        proximo = campo[indice + 1];
        proximo.focus();
      }
    }
  });

  function getFornecedorNomeAutocomplete(){
    $.ajax({
      url: "validar/validar.php?id=9",
      success: function(data) {
          console.log(data);
          $(function() {
              var obj = JSON.parse(data)
              console.log(obj);
              $("#emitente_nome").autocomplete({
                  source: [obj]
              });
          });
      },
    });
  }

  function getFornecedorcNPJAutocomplete(){
    $.ajax({
      url: "validar/validar.php?id=10",
      success: function(data) {
          console.log(data);
          $(function() {
              var obj = JSON.parse(data)
              console.log(obj);
              $("#emitente_cnpj").autocomplete({
                  source: [obj]
              });
          });
      },
  });
  }
  
  $("#fileUploadForm").validate({
    rules: {
      numero: {
        required: true,
      },
      serie: {
        required: true,
      },
      valor_total: {
        required: true,
      },
      dt_hr_emissao: {
        required: true,
      },
      dt_hr_entrada: {
        required: true,
      },
      destinatario_nome: {
        required: true,
      },
      emitente_cnpj: {
        required: true,
      },
      emitente_nome: {
        required: true,
      },
      emitente_escricao_estadual: {
        required: true,
      },
      emitente_uf: {
        required: true,
      },
    },
    messages: {
      numero: {
        required: "Campo Obrigatório!",
      },
      serie: {
        required: "Campo Obrigatório!",
      },
      valor_total: {
        required: "Campo Obrigatório!",
      },
      dt_hr_emissao: {
        required: "Campo Obrigatório!",
      },
       dt_hr_entrada: {
        required: "Campo Obrigatório!",
      },
      destinatario_nome: {
        required: "Campo Obrigatório!",
      },
      emitente_cnpj: {
        required: "Campo Obrigatório!",
      },
      emitente_nome: {
        required: "Campo Obrigatório!",
      },
      emitente_escricao_estadual: {
        required: "Campo Obrigatório!",
      },
      emitente_uf: {
        required: "Campo Obrigatório!",
      },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid");
    },submitHandler: function (form) {
    
       var form = $('#fileUploadForm')[0];
       var data = new FormData(form);
                   $.ajax({
                     type: "POST",
                     enctype: 'multipart/form-data',
                     url: "validar/validar.php?id=13",
                     beforeSend: function(){
                            
                     },
                     complete: function(){    
                             
                     },
                     data: data,
                     processData: false,
                     contentType: false,
                     cache: false,
                     timeout: 600000,
                     success: function (data) {
                       console.log(data);
                        if(data == "erro_nfe_existe"){
                          Toast.fire({
                            icon: 'error',
                            title: 'NF-e Já Cadastrado: Por favor, verifique se a NF-e já foi cadastrada para o mesmo fornecedor!'
                          })
                        }else
                        if(data == "erro_sem_item"){
                          Toast.fire({
                            icon: 'error',
                            title: 'Cadastro sem item: Por favor, selecione os itens informado na NF-e!'
                          })
                        }else
                        if(data == "erro_sem_arquivo"){
                          Toast.fire({
                            icon: 'error',
                            title: 'Cadastro sem arquivo: Por favor, faça o upload do arquivo NF-e!'
                          })
                        }else{
                          $("#cod_nfe").addClass("collapsed-card");
                          $("#input_nf_hide").hide();
                          $("#novocad").show();
                          Toast.fire({
                            icon: 'success',
                            title: 'Cadastro realizado com sucesso!!!'
                          })
                        }
                       getFornecedorNomeAutocomplete();
                       getFornecedorcNPJAutocomplete();

                      
                   }  /* FIM função success */
           });
       
       alert("Form successful submitted!");
      
     },
  });

  $("#inserir").click(function () {
    var item_forn_cod = $("#item_forn_cod").val();
    var item_forn_nome = $("#item_forn_nome").val();
    var item_qtd = $("#item_qtd").val();
    var item_forn_uni = $("#item_forn_uni").val();
    var item_wise = $("#item_wise").val();
    var valor_unit = $("#valor_unit").val();
    $.ajax({
      url: "validar/validar.php?id=8",
      type: "POST",
      data: {
        item_forn_cod: item_forn_cod,
        item_forn_nome: item_forn_nome,
        item_qtd: item_qtd,
        item_forn_uni: item_forn_uni,
        item_wise: item_wise,
        valor_unit: valor_unit,
      },
      success: function (data) {
        if (data == 1) {
          $.ajax({
            url: "tabelas/tb_cpr_cad_nfe.php",
            success: function (data) {
              $("#tabela").html(data);
            },
          });
          $("#item_forn_cod").val('').focus();
          $("#item_forn_nome").val('');
          $("#item_qtd").val('');
          $("#item_forn_uni").val('');
          $("#valor_unit").val('');
        } else {
          Toast.fire({
            icon: 'error',
            title: 'Cadastro de item faltando informações obrigatória!!'
          })
        }
      },
    });
  });

  $(document).on('click','.deletar_item',function(){
    var item = $(this).attr('id');
    $.ajax({
      url: "validar/validar.php?id=14",
      type: "POST",
      data: { id: item},
      success: function(data1) {
          console.log(data1);
          $.ajax({
            url: "tabelas/tb_cpr_cad_nfe.php",
            success: function (data) {
              $("#tabela").html(data);
            },
          });
      },
    });
  })

  getFornecedorNomeAutocomplete();
  getFornecedorcNPJAutocomplete();
  



  $("#loading1").hide();
});

