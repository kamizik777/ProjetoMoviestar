$(function () {
  const MAX_DISPLAYED_MESSAGES = 30;
  const socket = io();
  socket.nickname = "";

  $("#form").keypress(function (e) {
    if (e.keyCode === 13) {
      $("#form").appendTo(".areamsg");
      $("#msg").focus();
    }
  });

  $("#form").submit(function (evt) {
    if (socket.nickname === "") {
      $("#msg").attr("placeholder", "Envie sua mensagem");
      socket.nickname = $("#msg").val();
      socket.emit("login", socket.nickname);
      $("#msg").keypress(function (evt) {
        socket.emit("status", `${socket.nickname}`);
      });

      $("#msg").keypress(function (evt) {
        socket.emit("status", `${socket.nickname} está digitando...`);
      });
    } else {
      socket.emit("chat msg", $("#msg").val());
    }

    $("#msg").val("");
    return false;
  });

  function scrollToBottom() {
    const $container = $(".card-body");
    $container.scrollTop($container[0].scrollHeight);
  }

  socket.on("chat msg", function (obj) {
    console.log(socket.nickname, obj);
    if ((socket.nickname == obj.nome)) {
      $("#mensagens").append($('<p class="enviado">').text(obj.msg));
    } else {
      $("#mensagens").append($('<p class="recebido">').text(obj.msg));
    }

    scrollToBottom();
  });

  const $messages = $("#mensagens p");
  if ($messages.length > MAX_DISPLAYED_MESSAGES) {
    $messages.last().remove();
  }

  socket.on("status", function (msg) {
    $("#status").html(msg);
  });
});
