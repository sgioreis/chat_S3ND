// Responsável por limpar localStorage, finalizar e redirecionar
function finalizado() {
    $("#finalizado").show();
    localStorage.removeItem('token');
    localStorage.removeItem('id_usuario');
    localStorage.removeItem('id_atendimento');
    localStorage.removeItem('atendente');
    setTimeout(() => { window.location.href = 'index.html'; }, 3000);
}

// Recebendo e listando as mensagens
function getMensagens() {

    //console.log("/atendimento/get_mensagens.php?id_atendimento=" + localStorage.getItem('id_atendimento') + "&token=" + localStorage.getItem('token') + "&id_usuario=" + localStorage.getItem('id_usuario'))
    $.ajax({
        url: "/atendimento/get_mensagens.php?id_atendimento=" + localStorage.getItem('id_atendimento') + "&token=" + localStorage.getItem('token') + "&id_usuario=" + localStorage.getItem('id_usuario')
    }).done(function(response) {

        let html = "";
        let conteudo = JSON.parse(response);
        //console.log(conteudo.finalizado)
        if (conteudo.finalizado == 1) {
            finalizado();
        }

        if (conteudo.mensagens) {
            // cada iteração irá adicionar os bolões de mensagens
            conteudo.mensagens.map((value, index) => {
                html += "<div class='col-12'><p class='msg msg-" + (value.atendente == '1' ? 'spt' : 'usu') + "'>" + value.conteudo + "<br><small>" + value.data_envio + "</small></p></div>"
            });
        }

        $("#mensagem").html(html);

    });
}

// Enviando mensagens
$("#form-msg").on("submit", function(event) {

    event.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "/atendimento/set_mensagens.php?token=" + localStorage.getItem('token') + "&id_usuario=" + localStorage.getItem('id_usuario') + "&id_atendimento=" + localStorage.getItem('id_atendimento'),
        data: form_data
    }).done(function(response) {
        //console.log(response);
        $("#txt-msg").val("")
    });

});

$(document).ready(function() {

    // procurando por novas mensagens
    setInterval(function() {
        getMensagens()
    }, 1000);

    // desabilitar botão quando a mensagem estiver em branco
    $("#txt-msg").keyup(function() {
        $("#btn-enviar").prop("disabled", ($(this).val() == ""));
    });

    // exibindo botão de finalizar atendimento somente para atendentes
    if (localStorage.getItem('atendente') == "1") {
        $("#finalizar").html("<button id='btn-finalizar' class='btn btn-danger float-end'>Finalizar atendimento</button>");
    }

    // finalizando atendimento
    $("#btn-finalizar").click(function() {
        //console.log(localStorage.getItem('id_atendimento'))
        $.ajax({
            url: "/atendimento/encerrar_atendimento.php?token=" + localStorage.getItem('token') + "&id_usuario=" + localStorage.getItem('id_usuario') + "&id_atendimento=" + localStorage.getItem('id_atendimento')
        }).done(function(response) {
            //console.log(response)
        });
    });

});