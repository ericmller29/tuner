<template>
    <div class="form">
        <div class="message success slam" v-if="success">
            <i class="fa fa-check-circle"></i>
            <p><strong>Success!</strong> We're logging you in now!</p>
        </div>
        <div class="message error slam" v-if="hasError">
            <header class="message-header">
                <i class="fa fa-exclamation-circle"></i>
               <strong>Error!</strong>
            </header>
            <ul>
                <li v-for="err in errorList">
                    {{ err }}
                </li>
            </ul>
        </div>
        <form v-on:submit.prevent="validateBeforeSubmit($event)">
            <div class="form-input" :disabled="success || working" :class="{'has-error': errors.has('name') }">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" 
                    v-model="data.name"
                    v-validate
                    data-vv-rules="required|alpha_spaces">
            </div>
            <div class="form-input" :disabled="success || working" :class="{'has-error': errors.has('email') }">
                <label for="email">email:</label>
                <input type="text" name="email" id="email" 
                    v-model="data.email"
                    v-validate
                    data-vv-rules="required|email">
            </div>
            <div class="form-input" :disabled="success || working" :class="{'has-error': errors.has('username') }">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" 
                    v-model="data.username"
                    v-validate
                    data-vv-rules="required|alpha_dash">
            </div>
            <div class="form-input" :disabled="success || working" :class="{'has-error': errors.has('password') }">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" 
                    v-model="data.password"
                    v-validate
                    data-vv-rules="required">
            </div>
            <div class="form-input" :disabled="success || working" :class="{'has-error': errors.has('password_confirmation') }">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    v-model="data.password_confirmation"
                    v-validate
                    data-vv-rules="required|confirm_pass">
            </div>
            <div class="form-input" :disabled="success || working">
                <button type="submit" class="btn full-width">
                    Register
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                data: {
                    name: '',
                    email: '',
                    username: '',
                    password: '',
                    password_confirmation: ''
                },
                success: false,
                working: false,
                hasError: false,
                errorList: []
            }
        },
        mounted(){
            var _this = this;

            _this.$validator.remove('confirm_pass');
            _this.$validator.extend('confirm_pass', {
                getMessage(field){
                    return 'Your passwords do not match.';
                },
                validate(value){
                    return new Promise(resolve => {
                        resolve({
                            valid: value === _this.data.password
                        })
                    })
                }
            });
        },
        methods: {
            validateBeforeSubmit: function(e){
                this.$validator.validateAll();
                this.working = true;

                if(!this.errors.any()){
                    this.register();

                    return false;
                }

                this.working = false;
            },
            register: function(){
                var _this = this;

                axios.post('/app/register', _this.data)
                    .then(function(data){
                        _this.success = true;

                        setTimeout(function(){
                            window.location = '/';
                        }, 1000);
                    })
                    .catch(function(err){
                        var error = err.response.data,
                            errors = Object.keys(error).map(function (key) { return error[key][0]; });

                        _this.working = false;
                        _this.hasError = true;
                        _this.errorList = errors;

                        console.log(error);
                    });
            }
        }
    }
</script>