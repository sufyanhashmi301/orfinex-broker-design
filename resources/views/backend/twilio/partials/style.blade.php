<style>
@import url(https://fonts.googleapis.com/css?family=Lato:400,400italic,700);

$lightGray: #2D2D2D;
$blue: #285EFA;
$green: #3DE066;
$red: #FA4A5D;

$pulseDuration: 0.5s;

body {
    background-color: #2196F2;
    font-family: Lato;
    font-weight: 400;
    letter-spacing: 1px;

    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

#dataTable td,
#dataTable th {
    border-color: #e7e7e7 !important;
    padding: 4px 0 4px 0;

}


.modal-note {
    position: fixed;
    /* Use fixed to keep it in place even during scrolling */
    margin: 0;
    /* Remove any default margin */
    height: 100vh;
    /* Set the height to full viewport height */
    right: 0;
    /* Align to the right side of the viewport */
    top: 0;
    /* Align to the top of the viewport */
    width: 400px;
    /* Set the specific width as required */
}


.add_new_contact{
    border-top: 1px solid;
    padding: 15px;
    color: #ffffff;
    background: #3c4259;
}

.add_new_contact .form-group {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.add_new_contact .form-group label {
    flex: 0 0 90px; /* Fixed width for labels */
    margin-right: 10px;
    font-weight: bold;
}

.stage_list .new_stage_select{
    width: 247px;
    padding: 0px 6px;
}

.add_new_contact .form-group input,
.add_new_contact .form-group select {
    flex: 1;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.add_new_contact .form-select{
    width: 247px;
    flex: 1;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 0px 6px;
}

.add_new_contact .form-group input:focus,
.add_new_contact .form-group select:focus {
    outline: none;
    border-color: #007bff;
}


.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 0 !important;
}

.modal-header {
    padding: 5px 13px 3px 13px !important;
}
#status_buttons{
    display: none;
    margin: 0px 0 8px 0;
}


.footer-nav {
  background-color: #f8f9fa; /* Light background */
  border-top: 1px solid #ddd;
}

.footer-section {
  cursor: pointer;
}

.call-section {
  padding: 20px;
  display: none; /* Hidden by default */
}
.call_history{
    background: #cdcdcd;
    display:none;
    overflow-y: scroll; /* Enable vertical scrolling */
    overflow-x: hidden; /* Hide horizontal scrolling */
}

.call_history::-webkit-scrollbar {
  width: 0; /* Remove vertical scrollbar */
  height: 0; /* Remove horizontal scrollbar */
}

.call_history {
  scrollbar-width: none; /* Firefox: Hide scrollbar */
  -ms-overflow-style: none; /* IE/Edge: Hide scrollbar */
}
#loader {
  margin: 20px 0;
}
.activity_section{
    display: none;
}
.pad {
    width: 100%;
    background-color: #0f172ae6;
    left: 0;
    right: 0;
    bottom: 0;
    animation: showPad 1s ease forwards 1;
    border-radius: 7px;
    padding: 35px 0 35px 0;
    
}

.dial-pad {
    .contact {
        width: 70%;
        position: relative;
        margin-left: 15%;
        margin-top: 0px;
        opacity: 0;
        transition: opacity 0.5s ease;

        &.showContact {
            opacity: 1;
        }

        .avatar {
            background-repeat: no-repeat;
            background-size: auto 100%;
            background-position: center center;
            width: 60px;
            height: 60px;
            border-radius: 100%;
            box-shadow: 0 15px 30px -10px black;
            position: absolute;
            left: 0px;
            top: 8px;
        }

        .contact-info {
            border-radius: 8px;
            width: 85%;
            margin-left: 15%;
            background-color: $lightGray;
            height: 50px;
            overflow: hidden;

            &>div {
                width: 80%;
                margin-left: 20%;
                font-size: 12px;
                margin-top: 3px;
            }

            .contact-name {
                color: #FDFDFD;
                margin-top: 12px;
            }

            .contact-position {
                font-style: italic;
                color: #AEAEAE;
            }

            .contact-number {
                color: white;

                span {
                    color: $green;
                    display: inline;
                }
            }
        }

        .contact-buttons {
            position: absolute;
            right: -5px;
            top: 0px;
            width: 40px;
            height: 76px;

            button {
                border: none;
                width: 25px;
                height: 25px;
                border-radius: 100%;
                box-shadow: 0 12px 25px -5px black;
                display: block;
                position: absolute;
                right: 0px;
                background-size: 75% auto;
                background-position: center center;
                background-repeat: no-repeat;

                &:focus {
                    outline: none;
                }

                &.icon-message {
                    background-color: #FFC44E;
                    top: 5px;
                }

                &.icon-video {
                    background-color: #A529F9;
                    bottom: 5px;
                }
            }
        }
    }

    .phoneString {
        width: 100%;
        height: 65px;
        text-align: center;
        background-color: #75757557;
        /* margin-top: 40px; */

        input {
            background-color: transparent;
            width: 75%;
            margin-left: 17%;
            height: 65px;
            border: none;
            font-size: 23px;
            color: white;
            font-weight: 700;
            margin-top: -13px;
            letter-spacing: 2px;

            &:focus {
                outline: none;
            }
        }
    }

    .digits {
        overflow: hidden;
        width: 70%;
        margin-left: 15%;
        margin-top: 20px;

        .dig-spacer {
            width: 60px;
            margin: 10px calc(50% - 90px);
            float: left;
        }

        .dig {
            color: white;
            font-size: 23px;
            float: left;
            background-color: $lightGray;
            text-align: center;
            width: 58px;
            height: 50px;
            border-radius: 100%;
            margin: 10px 0px;
            padding-top: 4px;
            font-weight: 700;
            cursor: pointer;

            &.clicked {
                animation: pulse-gray linear $pulseDuration 1;
            }

            &:nth-child(3n-1) {
                margin: 10px calc(50% - 90px);
            }

            &.astrisk {
                padding-top: 17px;
                height: 43px;
            }

            &.pound {
                padding-top: 10px;
                height: 50px;
            }

            .sub-dig {
                font-size: 8px;
                font-weight: 300;
                position: relative;
                top: -2px;
            }

            &.addPerson,
            &.goBack {
                background-repeat: no-repeat;
                background-position: center center;
                background-size: 55% auto;
                margin-bottom: 25px;
                box-shadow: 0px 25px 30px -15px black;
            }

            &.addPerson {
                background-color: #285EFA;
                background-image: url(https://s16.postimg.org/4u2rbu85t/add_Person.png);

                &.clicked {
                    animation: pulse-blue linear $pulseDuration 1;
                }
            }

            &.goBack {
                background-color: #FA4A5D;
                background-image: url(https://s4.postimg.org/x6g6auu7d/back_Arrow.png);

                &.clicked {
                    animation: pulse-red linear $pulseDuration 1;
                }
            }
        }
    }
}

.call-pad {
    height: 0px;
    text-align: center;
    pointer-events: none;
    background-image: url(https://s21.postimg.org/x4te7wpo7/call_Background.png);
    background-repeat: no-repeat;
    background-size: 100% 100%;
    background-position: center center;
    transition: opacity 0.3s ease;
    position: absolute;
    width: 100%;

    left: 0px;
    top: 0px;
    transition: opacity 0.3s ease;

    &.in-call {
        height: 100%;
        opacity: 1;
        pointer-events: all;
    }

    .pulsate {
        opacity: 0;
        width: 150px;
        height: 0px;
        overflow: visible;
        position: relative;
        display: block;
        margin: 0 auto 0;
        top: 120px;
        transition: opacity 0.5s ease;

        &.active-call {
            animation: pulsator 2s ease infinite;
            opacity: 1;
        }

        div {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.06);
            border-radius: 100%;
            margin: auto;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;

            &:nth-child(1) {
                width: 110px;
                height: 110px;
            }

            &:nth-child(2) {
                width: 122px;
                height: 122px;
            }

            &:nth-child(3) {
                width: 134px;
                height: 134px;
            }
        }
    }

    .ca-avatar {
        width: 100px;
        height: 100px;
        margin: 9px 129px;
        margin-bottom: 39px;
        display: block;
        border-radius: 100%;
        box-shadow: 0px 20px 25px -10px rgba(0, 0, 0, 0.8);
        background-position: center center;
        background-size: 100% auto;
        background-repeat: no-repeat;
        transition: opacity 1s ease,
            transform 1s ease;
        opacity: 0.5;
        transform: scale(0.5, 0.5);

        &.in-call {
            transform: scale(1, 1);
            opacity: 1;
        }
    }



    .ca-name,
    .ca-number,
    .ca-message .ca-status {
        width: 60%;
        margin-left: 20%;
        color: white;
        text-align: center;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .ca-name {
        font-size: 18px;
    }

    .ca-number {
        font-size: 28px;
        letter-spacing: 2px;
    }

    .new_text {
        font-size: 18px;
        width: 60%;
        color: white;
        text-align: center;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .ca-status,
    .ca-message {
        text-align: center;
        font-size: 30px;
        margin-top: 40px;
        color: white;
        margin-bottom: 40px;
    }

    .ca-status {
        font-size: 30px;
        margin-top: 40px;
        margin-bottom: 40px;

        &:after {
            content: attr(data-dots);
            position: absolute;
        }
    }

    .ca-buttons {
        width: 70%;
        margin-left: 15%;

        .ca-b-single {
            float: left;
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 100%;
            position: relative;
            margin-bottom: 40px;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: 55% auto;

            &:nth-child(3n-1) {
                margin-left: calc(100% - 230px);
                margin-right: calc(100% - 230px);
            }

            &:after {
                content: attr(data-label);
                color: white;
                position: absolute;
                text-align: center;
                font-size: 10px;
                width: 100px;
                bottom: -20px;
                left: -18px;
                letter-spacing: 2px;

            }
        }
    }
}

}

.digit-div {
    height: 300px;
}

.toast-error {
    background-color: #fb2b2b !important;
}

.typing-icon {
    cursor: pointer;
    position: absolute;
    top: 5px;
    right: 5px;
}

.typing-icon i {
    color: #666;
}

.code-label {
    margin: 0 0 19px 0;
    color: #a2afaf;
    font-size: 11px;
}

.footer-section {
    text-align: center;
    cursor: pointer;
}

.footer-text-lebel {
    font-size: 10px;

}

.call {
    color: white;
    font-size: 24px;
    text-align: center;
    width: 47px;
    height: 47px;
    border-radius: 100%;
    margin: 0px 0px;
    font-weight: 700;
    cursor: pointer;
    position: absolute;
    left: calc(51% - 30px);
    bottom: 30px;
    box-shadow: 0px 25px 30px -15px black;
    background-color: #249b42;

    .call-icon {
        position: absolute;
        left: 01px;
        top: 7px;
        width: 100%;
        height: 100%;
        background-size: 60% auto;
        background-repeat: no-repeat;
        background-position: center center;
        background-image: url(https://s13.postimg.org/sqno4q8sj/call.png);
        transition: transform 0.3s ease;

        &.in-call {
            -ms-transform: rotate(134deg);
            -webkit-transform: rotate(134deg);
            transform: rotate(134deg);
        }
    }

    .call-change {
        width: 60px;
        height: 60px;
        border-radius: 100%;
        overflow: hidden;

        /* span {
            width: 70px;
            height: 67px;
            display: block;
            background-color: $red;
            position: relative;
            top: 70px;
            left: 70px;
            border-radius: 100%;
            transition: left 0.3s ease, top 0.3s ease;
        } */

        /* &.in-call span {
            top: -5px;
            left: -5px;
        } */
    }

    &.clicked {
        animation: pulse-green linear $pulseDuration 1 forwards;
    }
}

@keyframes pulse-gray {
    0% {
        box-shadow: inset 0 0 0px 30px $lightGray, inset 0 0 0px 30px white;
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    10% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
    }

    30% {
        box-shadow: inset 0 0 0px 10px $lightGray, inset 0 0 0px 30px white;
    }

    60% {
        box-shadow: inset 0 0 0px 0px $lightGray, inset 0 0 0px 0px white;
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
    }

    100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }
}

@keyframes pulse-blue {
    0% {
        box-shadow: inset 0 0 0px 30px $blue, inset 0 0 0px 30px white;
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    10% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
    }

    30% {
        box-shadow: inset 0 0 0px 10px $blue, inset 0 0 0px 30px white;
    }

    60% {
        box-shadow: inset 0 0 0px 0px $blue, inset 0 0 0px 0px white;
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
    }

    100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }
}

@keyframes pulse-green {
    0% {
        box-shadow: inset 0 0 0px 30px $green, inset 0 0 0px 30px white;
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    10% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
    }

    30% {
        box-shadow: inset 0 0 0px 10px $green, inset 0 0 0px 30px white;
    }

    60% {
        box-shadow: inset 0 0 0px 0px $green, inset 0 0 0px 0px white;
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
    }

    100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);

    }
}

@keyframes pulse-red {
    0% {
        box-shadow: inset 0 0 0px 30px $red, inset 0 0 0px 30px white;
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    10% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
    }

    30% {
        box-shadow: inset 0 0 0px 10px $red, inset 0 0 0px 30px white;
    }

    60% {
        box-shadow: inset 0 0 0px 0px $red, inset 0 0 0px 0px white;
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
    }

    100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }
}

@keyframes pulsator {
    0% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    40% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
    }

    100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }
}

@keyframes showPad {
    0% {
        top: 50px;
        opacity: 0;
    }

    100% {
        top: 0px;
        opacity: 1;
    }
}

p {
    /* position: fixed;
    bottom: 0px;
    left: 15px;
    color: white;
    font-family: Lato;
    font-weight: 300;
    overflow: hidden; */

    a:link,
    a:visited {
        color: white;
    }

    a:hover {
        opacity: 0.5;
    }

    img {
        width: 20px;
        height: 20px;
        position: relative;
        top: 6px;
    }
}





/* Customize the modal styles for a full-page appearance */
.modal-full {
    min-width: 100%;
    margin: 0;
}

.modal-full .modal-content {
    min-height: 100vh;
}

#video-call-container {
    display: flex;
    height: 100vh;
    background-color: #737373;
    /* Change background color as needed */
    position: relative;
}

#guest-video {
    flex: 1;
    object-fit: cover;
}

#host-video {
    width: 250px;
    /* Adjust width as needed */
    height: 150px;
    /* Adjust height as needed */
    position: absolute;
    bottom: 20px;
    right: 20px;
    border: 2px solid white;
    /* Add border as needed */
    border-radius: 5px;
    /* Add border-radius as needed */
}

#guest-controls {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    text-align: center;

    @media (max-width: 1020px) {
        width: 100%;
        display: inline;
        text-align: center;
    }
}

#timer {
    color: white;
}

#guest-controls button i {
    margin-right: 5px;
}

.video-modal .modal-header {
    padding: 5px 15px 5px 15px;
}



video {
    width: 100%;
    height: 100%;
}

.remote-video {
    width: 100%;
    height: 100%;
    margin-right: 10px;
    margin-bottom: 10px;
}


#remote-media-container {
    height: 100%;
    width: 100%;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
}

#local-media-container {
    width: 200px;
    /* Adjust width as needed */
    height: 150px;
    /* Adjust height as needed */
    position: absolute;
    bottom: 20px;
    right: 20px;
    border: 2px solid white;
    /* Add border as needed */
    border-radius: 5px;

    /* Add border-radius as needed */
    @media (max-width: 1020px) {
        width: 150px;
        height: 150px;
        top: 10px;
        right: 10px;
    }
}

#local-media-container video {
    height: 100%;
    width: 100%;
    border-radus: 5px;
}

.profile-card {
    width: 100%;
    border: 1px solid #fbfbfb;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.profile-top {
    display: flex;
    align-items: center;
    width: 100%;
}

/* .profile-img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    margin-right: 10px;
    object-fit: cover;
} */

.name-info h2,
.name-info h3 {
    margin: 0;
    padding: 0;
}

.profile-data {
    margin-top: 20px;
}

.data-item {
    margin-bottom: 10px;
}

.data-item label {
    font-weight: bold;
    display: block;
}

.profile-bottom {
    width: 100%;
    display: flex;
    justify-content: space-around;
    margin-top: 10px;
}

.icon-button {
    border: none;
    color: #111d45;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.icon-button i {
    font-size: 16px;
}


.nav-tabs {
    border-bottom: 2px solid #dee2e6;
}

.tab-content {
    padding: 20px;
    border: 1px solid #dee2e6;
    border-top: none;
}

/* Custom styles to match your design needs */
.tab-section {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #dee2e6;
    border-radius: 5px;
}

/* .list-group-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
} */

.call-type {
    padding: 2px 8px;
    border-radius: 5px;
    color: #fff;
    font-weight: bold;
}
.call-profile-img{
    border-radius: 50%;
    height: 130px;
    width: 130px;
    object-fit: cover;
}
.custom-twilio-button{
    padding: 0;
    height: 32px;
    border-radius: 6px;
    background-color: #ffc107;
    margin: 5px 0 0 0;
    display: none;
}
#toast-container .toast-success {
    background-color: #51a351;
    /* Change this to any color you prefer */
}

@media (max-width: 768px) {
    .modal-dialog-twilio {
        width: 100%;
        text-align: center;
        justify-content: center;
        padding: 0px 40px 0px 40px;
    }

    .open_call_modal{
        display: inline;
    }
}

@media (min-width: 768px) {
    .modal-dialog-twilio {
        width: 480px;
        text-align: center;
        justify-content: center;
        padding: 0px 50px 0px 50px;
    }
}
</style>