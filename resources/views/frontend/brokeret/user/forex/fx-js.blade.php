<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        Alpine.store('modals', {
            current: null,
            data: {},
            html: '',
            loading: false,

            open(modalName, payload = {}) {
                this.current = modalName;
                this.data = payload;
                
                Alpine.nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
                // this.loadLucide();
            },

            close() {
                this.current = null;
                this.data = {};
                this.data.main_password = '';
            },

            isOpen(modalName) {
                return this.current === modalName;
            },

            async loadLeverage(data) {
                this.open('changeLeverage', data)
                this.loading = true
                this.html = ''

                const formData = new FormData()
                formData.append('id', data.id)
                formData.append('login', data.login)

                try {
                    const res = await fetch(data.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })

                    if (!res.ok) throw new Error('Server error')
                    const html = await res.text()
                    this.html = html

                } catch (e) {
                    alert('Failed to load form.')
                    this.close()
                } finally {
                    this.loading = false
                }
            },

            async updateUserInfo(route, formData, btn) {
                btn.disabled = true;

                try {
                    const res = await fetch(route, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const result = await res.json();

                    if (result.success) {
                        alert(result.success); // Replace with tNotify if available
                        if (result.reload) {
                            setTimeout(() => location.reload(), 800);
                        } else {
                            this.close();
                        }
                    } else if (result.error) {
                        alert(result.error);
                        btn.disabled = false;
                    } else if (result.errors) {
                        alert(result.message);
                        btn.disabled = false;
                    }
                } catch (error) {
                    alert("Something went wrong!");
                    console.error(error);
                    btn.disabled = false;
                }
            },

            depositDemo($refs) {
                const amount = $refs.amount.value;
                const login = $refs.login.value;
                const btn = $refs.submitBtn;
                const url = $refs.form.getAttribute('action');

                if (!amount || isNaN(amount) || parseFloat(amount) <= 0) {
                    alert('Please enter a valid amount.'); // Replace with your validation message system
                    return;
                }

                const formData = new FormData();
                formData.append('target_id', login);
                formData.append('amount', amount);

                btn.disabled = true;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(async res => {
                    const result = await res.json();
                    if (res.ok && result.success) {
                        tNotify('success', result.success);
                        if (result.reload) {
                            setTimeout(() => location.reload(), 900);
                        }
                    } else {
                        const message = result.error || result.message || 'Unknown error.';
                        tNotify('warning', message);
                        btn.disabled = false;
                    }
                })
                .catch(err => {
                    console.error(err);
                    tNotify('warning', 'Sorry, something went wrong! Please try again.');
                    btn.disabled = false;
                });
            },

            updateLeverage($refs) {
                const leverage = $refs.leverage.value;

                if (!leverage || leverage === 'default_option') {
                    alert("Please select a valid leverage option.");
                    return;
                }

                const formData = new FormData();
                formData.append('login', $refs.login.value);
                formData.append('leverage', leverage);

                this.updateUserInfo("{{ route('user.forex.update.account') }}", formData, $refs.submitBtn);
            },
            
            renameAccount($refs) {
                const formData = new FormData();
                formData.append('login', $refs.login.value);
                formData.append('name', $refs.nickname.value);

                this.updateUserInfo("{{ route('user.forex.update.account') }}", formData, $refs.submitBtn);
            },

            updateMainPassword($refs) {
                const formData = new FormData();
                formData.append('login', $refs.login.value);
                formData.append('main_password', $refs.main_password.value);

                this.updateUserInfo("{{ route('user.forex.update.account') }}", formData, $refs.submitBtn);
            },

            updateInvestorPassword($refs) {
                const formData = new FormData();
                formData.append('login', $refs.login.value);
                formData.append('invest_password', $refs.invest_password.value);

                this.updateUserInfo("{{ route('user.forex.update.account') }}", formData, $refs.submitBtn);
            },

            archiveAccount($refs) {
                const formData = new FormData();
                formData.append('login', $refs.login.value);
                formData.append('archive', true);

                this.updateUserInfo("{{ route('user.forex.update.account') }}", formData, $refs.submitBtn);
            },

            unarchiveAccount($refs) {
                const formData = new FormData();
                formData.append('login', $refs.login.value);
                formData.append('reactive', true);

                this.updateUserInfo("{{ route('user.forex.update.account') }}", formData, $refs.submitBtn);
            },

        });
    });

</script>
