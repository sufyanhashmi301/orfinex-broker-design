<script data-cfasync="false">

    $(document).ready(function () {
        'use strict';

        let pusherAppKey = "{{ config('broadcasting.connections.pusher.key') }}";
        let pusherAppCluster = "{{ config('broadcasting.connections.pusher.options.cluster') }}";
        let soundUrl = "{{ route('notification-tune') }}";

        var notification = new Pusher(pusherAppKey, {
            encrypted: true,
            cluster: pusherAppCluster,
        });
        var channel = notification.subscribe('{{ $for }}-notification{{$userId}}');
        channel.bind('notification-event', function (result) {
            console.log(result);
            const type = result.data.sound_type || 'default';
            playSound(type);
            latestNotification();
            notifyToast(result);
        });

        function latestNotification() {
            $.get('{{ route($for.'.latest-notification')}}', function (data) {
                $('.{{ $for }}-notifications{{$userId}}').html(data);
            })
        }

        function notifyToast(data) {
            new Notify ({
                status: 'info',
                title: data.data.title,
                text: data.data.notice,
                effect: 'fade',
                speed: 300,
                customClass: '',
                customIcon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone"><path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>',
                showIcon: true,
                showCloseButton: true,
                autoclose: true,
                autotimeout: 9000,
                notificationsGap: null,
                notificationsPadding: null,
                type: '1',
                position: 'right top',
                customWrapper: '',
            })

        }

        function playSound(type = 'default') {
            $.get(`${soundUrl}?type=${type}`, function (data) {
                const audio = new Audio(data);
                audio.play();
                audio.muted = false;
            });
        }

    });
</script>
