<template>
    <div class="form">
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
            <div class="form-input" :disabled="working" :class="{'has-error': errors.has('username') }">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username"
                    v-model="data.username"
                    v-validate
                    data-vv-rules="required">
            </div>
            <div class="form-input" :disabled="working" :class="{'has-error': errors.has('password') }">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" 
                    v-model="data.password"
                    v-validate
                    data-vv-rules="required">
            </div>
            <div class="form-input">
                <button type="submit" class="btn full-width" :disabled="working">
                    Login
                </button>
            </div>
        </form>
    </div>
</template>
<script>
    export default{
        data: function(){
            return {
                data: {
                    username: '',
                    password: ''
                },
                working: false,
                success: false,
                hasError: false,
                errorList: []
            }
        },
        methods: {
            validateBeforeSubmit: function(e){
                this.$validator.validateAll();
                this.working = true;

                if(!this.errors.any()){
                    this.login();

                    return false;
                }

                this.working = false;
            },
            login: function(){
                var _this = this;

                _this.working = true;

                axios.post('/login', _this.data)
                    .then(function(payload){
                        window.location = '/';
                    })
                    .catch(function(e){
                        var error = e.response.data,
                            errors = Object.keys(error).map(function (key) { return error[key][0]; });

                        _this.working = false;
                        _this.hasError = true;
                        _this.errorList = errors;
                        console.log(error);
                    })
            }
        }
    }
</script>