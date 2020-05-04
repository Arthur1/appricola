export default {
    methods: {
        formErrorsToast: function(err) {
            let errors = err.response.data.errors
            for (let key of Object.keys(errors)) {
                for (let message of errors[key]) {
                    this.$toast.open({
                        message: message,
                        type: 'error'
                    })
                }
            }
        },
        csrfErrorToast: function() {
            this.$toast.open({
                message: 'お手数ですが再度送信してください',
                type: 'error'
            })
        }
    }
}
