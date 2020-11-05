<template>
    <div>
        <!-- Header -->
        <div class="row">
            <div class="col-sm-12">
                <h2>Reset Password</h2>
            </div>
        </div>
        <hr />
        <!-- End Header -->

        <el-form
            :model="form"
            status-icon
            :rules="rules"
            ref="form"
            label-width="150px"
        >
            <el-form-item label="New Password" prop="password">
                <el-input
                    type="password"
                    v-model="form.password"
                    autocomplete="off"
                ></el-input>
                <span class="font-italic text-danger" v-if="errors.password"
                    ><small>{{ errors.password[0] }}</small></span
                >
            </el-form-item>
            <el-form-item label="Confirm Password " prop="checkPass">
                <el-input
                    type="password"
                    v-model="form.checkPass"
                    autocomplete="off"
                ></el-input>
            </el-form-item>
            <el-form-item>
                <el-button
                    type="primary"
                    @click="
                        submitForm('form');
                        formLoading();
                    "
                    >Submit</el-button
                >
                <el-button @click="resetForm('form')">Reset</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
export default {
    data() {
        var validatePass = (rule, value, callback) => {
            if (value === "") {
                callback(new Error("Please input the password"));
            } else {
                if (this.form.checkPass !== "") {
                    this.$refs.form.validateField("checkPass");
                }
                callback();
            }
        };
        var validatePass2 = (rule, value, callback) => {
            if (value === "") {
                callback(new Error("Please input the password again"));
            } else if (value !== this.form.password) {
                callback(new Error("Two inputs don't match!"));
            } else {
                callback();
            }
        };
        return {
            errors: [],
            form: {
                password: "",
                checkPass: ""
            },
            rules: {
                password: [{ validator: validatePass, trigger: "blur" }],
                checkPass: [{ validator: validatePass2, trigger: "blur" }]
            }
        };
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate(valid => {
                if (valid) {
                    axios
                        .post("resetpassadmin", this.form)
                        .then(response => {
                            if (
                                response.status > 199 &&
                                response.status < 203
                            ) {
                                this.loading = false;
                                this.open_notif(
                                    "success",
                                    "Success",
                                    "Password has been saved"
                                );
                                this.clearFields();
                            }
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                        });
                }
            });
        },
        formLoading: function() {
            const loading = this.$loading({
                lock: true,
                spinner: "el-icon-loading",
                target: "div.el-form"
            });
            loading.close();
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        clearFields: function() {
            this.form.password = "";
            this.form.checkPass = "";
        },
        open_notif: function(status, title, message) {
            if (status == "success") {
                this.$notify.success({
                    title: title,
                    message: message,
                    offset: 0
                });
            } else if (status == "error") {
                this.$notify.error({
                    title: title,
                    message: message,
                    offset: 0
                });
            } else if (status == "info") {
                this.$notify.info({
                    title: title,
                    message: message,
                    offset: 0
                });
            } else if (status == "warning") {
                this.$notify.warning({
                    title: title,
                    message: message,
                    offset: 0
                });
            }
        }
    },
    mounted() {}
};
</script>
