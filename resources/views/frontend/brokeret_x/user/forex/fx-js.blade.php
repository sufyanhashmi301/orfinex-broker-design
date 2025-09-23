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
                    this.close()
                    notify().warning('Failed to load form.')
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
                        this.close(); // Hide modal first
                        notify().success(result.success); // Show notification
                        if (result.reload) {
                            setTimeout(() => location.reload(), 1200); // Slightly longer delay to see notification
                        }
                    } else if (result.error) {
                        this.close(); // Hide modal first
                        notify().warning(result.error); // Show notification
                    } else if (result.errors) {
                        this.close(); // Hide modal first
                        notify().warning(result.message); // Show notification
                    }
                } catch (error) {
                    this.close(); // Hide modal first
                    notify().warning("Something went wrong!"); // Show notification
                }
            },

            depositDemo($refs) {
                const amount = $refs.amount.value;
                const login = $refs.login.value;
                const btn = $refs.submitBtn;
                const url = $refs.form.getAttribute('action');

                if (!amount || isNaN(amount) || parseFloat(amount) <= 0) {
                    notify().warning('Please enter a valid amount.');
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
                        this.close();
                        notify().success(result.success);
                        if (result.reload) {
                            setTimeout(() => location.reload(), 900);
                        }
                    } else {
                        const message = result.error || result.message || 'Unknown error.';
                        this.close();
                        notify().warning(message);
                        btn.disabled = false;
                    }
                })
                .catch(err => {
                    this.close();
                    notify().warning('Sorry, something went wrong! Please try again.');
                    btn.disabled = false;
                });
            },

            updateLeverage($refs) {
                const leverage = $refs.leverage.value;

                if (!leverage || leverage === 'default_option') {
                    this.close();
                    notify().warning("Please select a valid leverage option.");
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
