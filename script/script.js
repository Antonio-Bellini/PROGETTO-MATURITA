$(document).ready(function () {
    // timer per far scomparire l'esito dell'operazione
    $(".success, .error, .warning").fadeIn();
    $(".success, .error, .warning").delay(2500).fadeOut();

    // funzione per mostrare o nascondere la password
    $('#togglePassword').click(function(){
        let passwordField = $('#password, #new_psw');
        let passwordField2 = $('#password, #old_psw');
        let passwordField3 = $('#password, #confirm_password');
        let passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
          passwordField.attr('type', 'text');
          passwordField2.attr('type', 'text');
          passwordField3.attr('type', 'text');
          $('#togglePassword').html('👁️').addClass('active');
        } else {
          passwordField.attr('type', 'password');
          passwordField2.attr('type', 'password');
          passwordField3.attr('type', 'password');
          $('#togglePassword').html('👁️').removeClass('active');
        }
    });

    // bottoni per eseguire modifica ed eliminazione di un record
    $('#table').on('click', '.table_btn, .btn_delete', function(e) {
        // chiedo la doppia conferma di eliminazione
        if ($(this).hasClass('btn_delete')) {        
            let confirmed = confirm("Sei sicuro di voler procedere con l'eliminazione?\n\nATTENZIONE!\nQuesta azione è irreversibile e tutti i dati saranno persi in modo irrecuperabile.");
            
            if (confirmed) {
                let operation = $(this).data('operation');
                let user = $(this).data('user');
                let profile = $(this).data('profile');
                modify_delete_Profile(operation, user, profile);
            }
        } else {
            let operation = $(this).data('operation');
            let user = $(this).data('user');
            let profile = $(this).data('profile');
            modify_delete_Profile(operation, user, profile);
        }
    });

    // eliminazione di un contenuto news nella home
    $(".del_content_button, .delete_content_button").on("click", function() {
        // controllo se si é cliccato il bottone nella gallery.php (delete_content_button)
        let buttonClicked = $(this).hasClass('delete_content_button');
        let pathPrefix = buttonClicked ? "../" : "";

        // assegno i nuovi path in base al bottone cliccato
        let path1 = pathPrefix + "util/ajax/send_data.php";
        let path2 = pathPrefix + "private/crud.php";
    
        if (window.location.href.indexOf("offer.php") > -1) {
            path1 = "util/ajax/send_data.php";
            path2 = "private/crud.php";
        }
        
        let operation = $(this).data('operation');
        let user = $(this).data('user');
        let profile = $(this).data('profile');
    
        let confirmed = confirm("Sei sicuro di voler procedere con l'eliminazione?\n\nATTENZIONE!\nQuesta azione è irreversibile e tutti i dati saranno persi in modo irrecuperabile.");
        
        if (confirmed) {
            $.ajax({
                type: "POST",
                url: path1,
                data: { operation: operation, user: user, profile: profile },
                success: function (response) {
                    window.location.href = path2;
                }
            });
        }
    });  

    // controlli vari in fase di registrazione
    if (window.location.href.includes("register") > -1) {
        //controllo sulla disponibilitá dell'username
        $('#username').on("input", function() {
            let username = $(this).val();
            checkInputUsername(username);
        });

        // controllo di conferma password
        $('#confirm_password').on("input", function() {
            let password = $("#password").val();
            let other_password = $(this).val();
            confirm_password(password, other_password);
        });

        // vieto l'invio del form nel caso in cui l'username sia occupato
        $('#form_register__user').submit(function (event) {
            if ($("#usernameError").text().includes("Username non disponibile")) {
                event.preventDefault();
                alert("Non puoi inviare il modulo perché l'username non è disponibile.");
            }

            if ($("#confirm_passwordError").text().includes("Le due password non sono uguali")) {
                event.preventDefault();
                alert("Non puoi inviare il modulo perché le due password non sono uguali.");
            }
        });
    }
    
    // vieto l'invio del form se la password non rispetta i requisiti
    if (window.location.href.indexOf("crud.php") > -1) {
        // controllo per la modifica della password
        $('#new_psw').on("input", function() {
            let old_psw = $('#old_psw').val();
            let new_psw = $(this).val();
            checkNewPassword(old_psw, new_psw);
        });

        // controllo che la password nuova sia stata riscritta uguale
        $('#confirm_password').on("input", function() {
            let password = $("#password, #new_psw").val();
            let other_password = $(this).val();
            confirm_password(password, other_password);
        });

        // vieto l'invio del form se la password non soddisfa i requisiti 
        $('#form_update__user').submit(function (event) {
            if ($("#passwordError").text().includes("Le due password non possono essere uguali") || 
                $("#passwordError").text().includes("La vecchia password non corrisponde")) {
                event.preventDefault();
                alert("La password non rispetta i requisiti");
            }

            if ($("#confirm_passwordError").text().includes("Le due password non sono uguali")) {
                event.preventDefault();
                alert("Non puoi inviare il modulo perché le due password non sono uguali.");
            }
        });
    }

    // alterno i due form in base all'opzione selezionata
    if (window.location.href.indexOf("page_upload.php") > -1) {
        // mostro uno dei due form di default
        $('#form_assisted').show();
        $('#form_volunteer').hide();

        // mostro il nuovo form in base alla nuova opzione selezionata
        $('#upload_choice').change(function() {
            let selectedOption = $(this).val();
            $('#form_assisted').toggle(selectedOption === '1');
            $('#form_volunteer').toggle(selectedOption === '2');
        });
    }    

    // scorimento immagini nella pagina index
    const gallery = document.querySelector('.body__main_gallery');
    if (gallery) {
        const images = document.querySelectorAll('.photo');
        let currentIndex = 0;
        let isTransitioning = false;

        // tempo dopo cui viene eseguita la funzione (5 secondi)
        setInterval(slideImages, 5000);

        // funzione per il movimento
        function slideImages() {
            if (!isTransitioning) {
                isTransitioning = true;
                currentIndex++;
                gallery.style.transition = 'transform 0.5s ease-in-out'; 
                gallery.style.transform = `translateX(-${currentIndex * 100}vw)`;

                // quando arriva all'ultima foto avanza invece di scorrere indietro
                if (currentIndex === images.length) {
                    setTimeout(() => {
                        gallery.style.transition = 'none';
                        currentIndex = 0;
                        gallery.style.transform = `translateX(0)`;
                    }, 500);
                }
                setTimeout(() => {
                    isTransitioning = false;
                }, 500);
            }
        }
    }

    // mostro la modale per aggiornare i dati dell'associazione o aggiungere news
    $("#updateButton, #add_content_button").click(function() {
        $("#newData_modal").css("display", "block");
    });
    $("#add_content_button").click(function() {
        $("#newNews_modal").css("display", "block");
    });
    $("#add_title_button").click(function() {
        $("#newTitle_modal").css("display", "block");
    });
    // chiudo la modale quando si clicca la x
    $(".close").click(function() {
        $(".modal").css("display", "none");
    });
    // eliminazione di una sezione della galleria
    $("#del_content_button").on("click", function() {
        $("#delTitle_modal").css("display", "block");
    });
    // salvo i dati inseriti
    $("#saveButton").click(function() {
        $("#newData_modal, #newNews_modal").css("display", "none");
    });

    // visualizzazione di diversi tipi di utenti 
    if (window.location.href.indexOf("admin_operation.php") > -1) {
        // bottone per mostrare un determinato tipo di utente
        user = $('#user_selected').val();
        getUserSelected(user);
        $('#user_selected').change(function(){
            let selected = $(this).val();
            getUserSelected(selected);
        });

        // bottone per scegliere cosa fare con le liberatorie
        choice = $('#rls_choice').val();
        doRlsEvent(choice);
        $('#rls_choice').change(function () {
            let choice = $(this).val();
            doRlsEvent(choice);
        });

        // visualizzazione form degli eventi
        let selectedOption = $('#mng_event__selected').val();
        executeEventOperation(selectedOption);
        $('#crud__volu_event').show();
        $('#crud__assi_event, #crud__event, #crud__eventType, #view__all').hide();
        $('#mng_event__selected').change(function() {
            let selectedOption = $(this).val();
            executeEventOperation(selectedOption);

            // nascondo o mostro il menu in base all'opzione selezionata
            const mng_event__option = {
                '1': { crud__volu_event: true, crud__assi_event: false, crud__event: false, crud__eventType: false, view__all: false },
                '2': { crud__volu_event: false, crud__assi_event: true, crud__event: false, crud__eventType: false, view__all: false },
                '3': { crud__volu_event: false, crud__assi_event: false, crud__event: true, crud__eventType: false, view__all: false },
                '4': { crud__volu_event: false, crud__assi_event: false, crud__event: false, crud__eventType: true, view__all: false },
                '5': { crud__volu_event: false, crud__assi_event: false, crud__event: false, crud__eventType: false, view__all: true }
            };
            const mng_event__option_MAP = mng_event__option[selectedOption];
            
            $('#crud__volu_event').toggle(mng_event__option_MAP.crud__volu_event);
            $('#crud__assi_event').toggle(mng_event__option_MAP.crud__assi_event);
            $('#crud__event').toggle(mng_event__option_MAP.crud__event);
            $('#crud__eventType').toggle(mng_event__option_MAP.crud__eventType);
            $('#view__all').toggle(mng_event__option_MAP.view__all);
        });

        // funzione per mostrare o nascondere i sotto menu della pagina gestione eventi
        function togglechoices(prefix, maxChoices) {
            // mostro la prima opzione e nascondo tutte le altre opzioni del sotto menu
            $("#crud_" + prefix + "__choice1").show();
            for (let i = 1; i <= maxChoices; i++) {
                $("#crud_" + prefix + "__choice" + i).hide();
            }

            // applico la funzione change al menu desiderato per mostrare i sotto menu
            $("#crud_" + prefix + "__choice").change(function() {
                let selectedOption = $(this).val();
                
                const crud__option = {
                    '1': { crud__choice1: true, crud__choice2: false, crud__choice3: false, crud__choice4: false },
                    '2': { crud__choice1: false, crud__choice2: true, crud__choice3: false, crud__choice4: false },
                    '3': { crud__choice1: false, crud__choice2: false, crud__choice3: true, crud__choice4: false },
                    '4': { crud__choice1: false, crud__choice2: false, crud__choice3: false, crud__choice4: true }
                }
                const crud__option_MAP = crud__option[selectedOption];
                
                $("#crud_" + prefix + "__choice1").toggle(crud__option_MAP.crud__choice1);
                $("#crud_" + prefix + "__choice2").toggle(crud__option_MAP.crud__choice2);
                $("#crud_" + prefix + "__choice3").toggle(crud__option_MAP.crud__choice3);
                $("#crud_" + prefix + "__choice4").toggle(crud__option_MAP.crud__choice4);
            });
        }

        togglechoices("volu", 4);
        togglechoices("assi", 4);
        togglechoices("eventType", 2);
        $("#crud_volu__choice1, #crud_assi__choice1, #crud_eventType__choice1").show();
    }

    // bottoni dell'area personale dell'admin
    if (window.location.href.indexOf("area_personale.php") > -1) {    
        $("#personal").addClass("btn_sel");
        $('#admin_btn').on('click', '.btn', handlePersonalAreaBtnClick);
        function handlePersonalAreaBtnClick() {
            let operation = $(this).data('operation');
            personalAreaAction(operation);
        }
    }
    if (window.location.href.includes("admin_operation.php")) {
        $("#personal").addClass("btn_sel");
    }

    // bottoni per aggiungere o eliminare contenuti da bacheca o newsletter
    if (window.location.href.includes("bacheca.php") || window.location.href.includes("newsletter.php")) {

        let is_deletable = $("#bacheca_newsletter__title").length > 0;
        $('#delBachecaBtn, #delNewsletterBtn').toggleClass("btn_dis", is_deletable);

        // listener sui due bottoni per far aggiungere o eliminare contenuti
        $('#addBachecaBtn, #addNewsletterBtn').click(function() {
            let operation = $(this).data('operation');
            let table = $(this).data('table');
            crudBachecaNewsletterAdd(operation, table);
        });
        $('#delBachecaBtn, #delNewsletterBtn').click(function() {
            if (!is_deletable) {
                let operation = $(this).data('operation');
                let table = $(this).data('table');
                crudBachecaNewsletterDel(operation, table);
            }
        });
    }    

    // attivazione dello sfondo del bottone per mostrare la pagina in cui si é
    if (window.location.href.includes("bacheca.php")) {
        $("#bacheca").addClass("btn_sel");
    }
    if (window.location.href.includes("newsletter.php")) {
        $("#newsletter").addClass("btn_sel");
    }
    if (window.location.href.includes("crud_bacheca_newsletter.php")) {
        $("#newsletter").removeClass("btn_sel");
        $("#bacheca").removeClass("btn_sel");
    }
    if (window.location.href.includes("about.php")) {
        $("#about").addClass("btn_sel");
    }
    if (window.location.href.includes("offer.php")) {
        $("#offer").addClass("btn_sel");
    }
    if (window.location.href.includes("gallery.php")) {
        $("#gallery").addClass("btn_sel");
    }

    // controllo per limitare a max 255 caratteri l'input del numero di telefono
    $('#new_tf, #new_tm, #phone_f, #phone_m').on("input", function() {
        let input = $(this).val();
        if (input > 15)
            $(this).val(input.slice(0, 255));
    });    
});

// -------------------------- FUNZIONI AJAX ----------------------------- \\

// ajax per il controllo live dell'username inserito
function checkInputUsername(username) {
    $.ajax({
        type: "POST",
        url: "../util/ajax/check_username.php",
        data: { username: username },
        success: function (response) {
            if (response === "exists")
                $("#usernameError").text("Username non disponibile");
            else
                $("#usernameError").text("");
        }
    });
}

// ajax per il controllo live della password inserita
function checkNewPassword(old_psw, new_psw) {
    $.ajax({
        type: "POST",
        url: "../util/ajax/check_password.php",
        data: { old_psw: old_psw, new_psw: new_psw },
        success: function (response) {
            if (response === "same_password")
                $("#passwordError").text("Le due password non possono essere uguali");
            else if (response === "not_correct")
                $("#passwordError").text("La vecchia password non corrisponde");
            else 
                $("#passwordError").text("");
        }
    });
}

// ajax per ottenere i dati del tipo di utente selezionato
function getUserSelected(user) {
    $.ajax({
        type: "POST",
        url: "../util/ajax/get_user.php",
        data: { user_selected: user },
        success: function (response) {
            switch (parseInt(user)) {
                case 1:
                    $('#table').html(response);
                    $('#user_title').text("PRESIDENTI REGISTRATI");
                    $('#create_title').show().text("Crea un nuovo account presidente");
                    $('#button_parent').show();
                    $('#button_title').show().attr('href', '../register/register_president.php');
                    break;

                case 2:
                    $('#table').html(response);
                    $('#user_title').text("ADMIN REGISTRATI");
                    $('#create_title').hide();
                    $('#button_parent').hide();
                    $('#button_title').hide();
                    break;

                case 3:
                    $('#table').html(response);
                    $('#user_title').text("TERAPISTI REGISTRATI");
                    $('#create_title').show().text("Crea un nuovo account terapista");
                    $('#button_parent').show();
                    $('#button_title').show().attr('href', '../register/register_terapist.php');
                    break;

                case 4:
                    $('#table').html(response);
                    $('#user_title').text("GENITORI/REFERENTI REGISTRATI");
                    $('#create_title').show().text("Crea un nuovo account genitore/referente");
                    $('#button_parent').show();
                    $('#button_title').show().attr('href', '../register/register_user.php');
                    break;
            }
        }
    });
}

// gestione della pagina "gestione liberatorie"
function doRlsEvent(choice) {
    switch (parseInt(choice)) {
        case 1:
            $('#up_rls').show();
            $('#table').hide();
            $('#up_rls').click(function() {
                window.location.href = "../upload/page_upload.php";
            });
            break;

        case 2:
            $('#up_rls').hide();
            $('#table').show();
            $.ajax({
                type: "POST",
                url: "../util/ajax/get_release.php",
                success: function (response) {
                    $('#table').html(response);
                }
            });
            break;
    }
}

// ajax per fare il CRUD su bacheca e newsletter
function crudBachecaNewsletterAdd(operation, table) {
    $.ajax({
        type: "POST",
        url: "../util/ajax/send_data.php",
        data: { operation: operation, table: table },
        success: function (response) {
            window.location.href = "../private/crud_bacheca_newsletter.php";
            window.history.pushState({}, '', '../' + table + '/' + table + '.php');
        }
    });
}
function crudBachecaNewsletterDel(operation, table) {
    $.ajax({
        type: "POST",
        url: "../util/ajax/send_data.php",
        data: { operation: operation, table: table },
        success: function (response) {
            window.location.href = "../private/crud_bacheca_newsletter.php";
            window.history.pushState({}, '', '../' + table + '/' + table + '.php');
        }
    });
}

// ajax per click dei bottoni nell'area personale dell'admin
function personalAreaAction(operation) {
    $.ajax({
        type: "POST",
        url: "../util/ajax/send_data.php",
        data: { operation: operation },
        success: function (response) {
            window.location.href = "../private/admin_operation.php";
            window.history.pushState({}, '', '../private/area_personale.php');
        }
    });
}

// ajax per mandare alla pagina di modifica o cancellazione i dati con $_POST
function modify_delete_Profile(operation, user, profile) {
    $.ajax({
        type: "POST",
        url: "../util/ajax/send_data.php",
        data: { operation: operation, user: user, profile: profile },
        success: function (response) {
            window.location.href = "../private/crud.php";
            window.history.pushState({}, '', '../private/area_personale.php');
        }
    });
}

// funzione per eseguire una delle opzioni possibili nella pagina degli eventi
function executeEventOperation(selected) {
    switch (parseInt(selected)) {
        case 1:
            $.ajax({
                type: "POST",
                url: "../util/ajax/send_data.php",
                data: { function: "crud_volunteer_event" },
                success: function (response) {
                    window.history.pushState({}, '', '../private/area_personale.php');
                }
            });
            break;

        case 2:
            $.ajax({
                type: "POST",
                url: "../util/ajax/send_data.php",
                data: { function: "crud_assisted_event" },
                success: function (response) {
                    window.history.pushState({}, '', '../private/area_personale.php');
                }
            });
            break;
        
        case 3:
            $.ajax({
                type: "POST",
                url: "../util/ajax/send_data.php",
                data: { function: "crud_event" },
                success: function (response) {
                    window.history.pushState({}, '', '../private/area_personale.php');
                }
            });
            break;

        case 4:
            $.ajax({
                type: "POST",
                url: "../util/ajax/send_data.php",
                data: { function: "crud_eventType" },
                success: function (response) {
                    window.history.pushState({}, '', '../private/area_personale.php');
                }
            });
            break;

        case 5:
            $.ajax({
                type: "POST",
                url: "../util/ajax/send_data.php",
                data: { function: "view_all_event" },
                success: function (response) {
                    window.history.pushState({}, '', '../private/area_personale.php');
                }
            });
            break;
    }
}

// funzione per confermare se la password reinserita é uguale a quella scritta prima
function confirm_password(password, other_password) {
    $.ajax({
        type: "POST",
        url: "../util/ajax/confirm_password.php",
        data: { password: password, other_password: other_password },
        success: function (response) {
            console.log(response);
            if (response === "not_correct")
                $("#confirm_passwordError").text("Le due password non sono uguali");
            else
                $("#confirm_passwordError").text("");
        }
    });
}