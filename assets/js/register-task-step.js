$(document).ready(function(){

    $('#multiple-select-field' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
        closeOnSelect: false,
    });

    $('#form_cadastro').on("submit", function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "src/insert_task_sequenciada.php",
                data: new FormData(this),
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Aguarde...',
                        text: 'Cadastrando...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (result) {
                console.log(result);
                var json = JSON.parse(result);
                Swal.close();
                if(json.erro == false){
                    let timerInterval;
                    Swal.fire({
                        icon: 'success',
                        title: "Sucesso!",
                        html: "Fechando em <b></b> milisegundos...",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                    }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = "http://192.168.0.166/jkanban/";
                    }
                    });
                }else if(json.erro == true){
                    Swal.fire({
                        title: json.msg,
                        icon: 'error',
                        allowOutsideClick: () => {
                            const popup = Swal.getPopup()
                            popup.classList.remove('swal2-show')
                            setTimeout(() => {
                            popup.classList.add('animate__animated', 'animate__headShake')
                            })
                            setTimeout(() => {
                            popup.classList.remove('animate__animated', 'animate__headShake')
                            }, 500)
                            return false
                        }
                    })
                }
            }
            })
    });

});