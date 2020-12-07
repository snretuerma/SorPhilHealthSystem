<template>
    <div>
         <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                <i class="fa fa-file-medical-alt"></i>&nbsp;&nbsp;Add Record
                </span>
            </div>
        </div>
        <!-- End Header -->
        <!-- Search Box -->
        <div class="row" id="search_box" style="margin-bottom: 10px">
            <div class="col-xl-9 col-lg-8 col-md-6 col-sm-12"></div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                       <el-button
                            class="btn-action block-button"
                            @click="triggerClose"
                        >
                            Back
                        </el-button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Search End -->
        <!-- table -->
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Patient</span>
            </div>
            <el-form class="form" id="form" :model="form" :rules="rules" ref="form">
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Attending" prop="attending">
                                <el-radio v-model="form.is_private" :label="false" @change="clearField()" >Public</el-radio>
                                <el-radio v-model="form.is_private" :label="true" @change="clearField()">Private</el-radio>
                                <el-select v-if="form.is_private"
                                    ref="attending2"
                                    style="width:100%"
                                    size="large"
                                    v-model="form.attending"
                                    multiple
                                    :multiple-limit="1"
                                    value-key="id"
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="{id:item.id,name:item.name,role:'attending'}"
                                    >
                                    </el-option>
                                </el-select>
                                <el-select v-else
                                    ref="attending1"
                                    style="width:100%"
                                    size="large"
                                    v-model="form.attending"
                                    value-key="id"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="{id:item.id,name:item.name,role:'attending'}"
                                    >
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="form.is_private==false">
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Requesting" prop="requesting">
                                <el-select
                                    style="width:100%"
                                    size="large"
                                    v-model="form.requesting"
                                    value-key="id"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value-key="item.id"
                                    :value="{id:item.id,name:item.name,role:'requesting'}"
                                    >
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="form.is_private==false">
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Surgeon" prop="surgeon">
                                <el-select
                                    style="width:100%"
                                    size="large"
                                    v-model="form.surgeon"
                                    value-key="id"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value-key="item.id"
                                    :value="{id:item.id,name:item.name,role:'surgeon'}"
                                    >
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="form.is_private==false">
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Healthcare" prop="healthcare">
                                <el-select
                                    style="width:100%"
                                    size="large"
                                    v-model="form.healthcare"
                                    value-key="id"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value-key="item.id"
                                    :value="{id:item.id,name:item.name,role:'healthcare'}"
                                    >
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="form.is_private==false">
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="ER" prop="er">
                                <el-select
                                    style="width:100%"
                                    size="large"
                                    v-model="form.er"
                                    value-key="id"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value-key="item.id"
                                    :value="{id:item.id,name:item.name,role:'er'}"
                                    >
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="form.is_private==false">
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Anesthesiologist" prop="anesthesiologist">
                                <el-select
                                    style="width:100%"
                                    size="large"
                                    v-model="form.anesthesiologist"
                                    value-key="id"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value-key="item.id"
                                    :value="{id:item.id,name:item.name,role:'anesthesiologist'}"
                                    >
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="form.is_private==false">
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Co-management" prop="comanagement">
                                <el-select
                                    style="width:100%"
                                    size="large"
                                    v-model="form.comanagement"
                                    value-key="id"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value-key="item.id"
                                    :value="{id:item.id,name:item.name,role:'comanagement'}"
                                    >
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="form.is_private==false">
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Admitting" prop="admitting">
                                <el-select
                                    style="width:100%"
                                    size="large"
                                    v-model="form.admitting"
                                    value-key="id"
                                    @input="asd()"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value-key="item.id"
                                    :value="{id:item.id,name:item.name,role:'admitting'}"
                                    >
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Period Covered" prop="batch">
                                <el-select
                                    class="block-button"
                                    size="large"
                                    v-model="form.batch"
                                    value-key="id"
                                    @input="asd()"
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose batch">
                                    <el-option
                                    v-for="item in batch"
                                    :key="item.batch"
                                    :label="item.label"
                                    :value="item.batch">
                                    </el-option>
                                </el-select>
                                <span class="font-italic text-danger" v-if="errors.batch">
                                    <small>{{ errors.batch[0] }}</small>
                                </span>
                            </el-form-item>
                        </el-col>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Professional Fee" prop="pf">
                                <el-input
                                    v-model="form.pf"
                                    autocomplete="off"
                                />
                                <span class="font-italic text-danger" v-if="errors.pf">
                                    <small>{{ errors.pf[0] }}</small>
                                </span>
                            </el-form-item>
                        </el-col>
                    </el-row>
                <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Last Name" prop="lname">
                                <el-input
                                    v-model="form.lname"
                                    autocomplete="off"
                                    @input="buildFullName"
                                />
                                <span class="font-italic text-danger" v-if="errors.lname">
                                    <small>{{ errors.lname[0] }}</small>
                                </span>
                            </el-form-item>
                        </el-col>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="First Name" prop="fname">
                                <el-input
                                    v-model="form.fname"
                                    autocomplete="off"
                                    @input="buildFullName"
                                />
                                <span class="font-italic text-danger" v-if="errors.fname">
                                    <small>{{ errors.fname[0] }}</small>
                                </span>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Middle Name" prop="mname">
                                <el-input
                                    v-model="form.mname"
                                    autocomplete="off"
                                    @input="buildFullName"
                                />
                                <!-- <span class="font-italic text-danger" v-if="errors.middle_name">
                                    <small>{{ errors.middle_name[0] }}</small>
                                </span> -->
                            </el-form-item>
                        </el-col>
                            <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Suffix" prop="suffix">
                                <el-input
                                    v-model="form.suffix"
                                    autocomplete="off"
                                    @input="buildFullName"
                                />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Admission" prop="admission">
                                <div class="block">
                                    <el-date-picker
                                    style="width:100%"
                                    size="large"
                                    v-model="form.admission"
                                    type="datetime"
                                    format="yyyy-MM-dd hh:mm:ss a"
                                    placeholder="Pick a day"
                                    :picker-options="pickerOptions">
                                    </el-date-picker>
                                </div>
                            </el-form-item>
                        </el-col>
                            <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Discharge" prop="discharge">
                                 <div class="block">
                                    <el-date-picker
                                    style="width:100%"
                                    size="large"
                                    v-model="form.discharge"
                                    type="datetime"
                                    format="yyyy-MM-dd hh:mm:ss a"
                                    placeholder="Pick a day"
                                    :picker-options="pickerOptions">
                                    </el-date-picker>
                                </div>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Name will appear like this on the database" prop="name">
                                <el-input
                                    v-model="form.name"
                                    autocomplete="off"
                                    :readonly="true"
                                />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-4 col-md-4 col-lg-4 col-xl-4 block-button">
                            <el-form-item></el-form-item>
                        </el-col>
                         <el-col class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <el-form-item>
                                    <el-button
                                    class="btn-action block-button"
                                    :loading="btnLoading"
                                    @click="addCreditRecord"
                                >
                                    Save
                                </el-button>
                            </el-form-item>
                        </el-col>
                        <el-col class="col-sm-4 col-md-4 col-lg-4 col-xl-4 block-button">
                            <el-form-item>

                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-form>
        </el-card>
        <!-- end table -->
    </div>
</template>
<script>
export default {
    props:['data'],
    data() {
        return {
            is_private:false,
            btnLoading:false,
            errors:[],
            doctors:[],
            batch:[],
             pickerOptions: {
                shortcuts: [{
                    text: 'Today',
                    onClick(picker) {
                    picker.$emit('pick', new Date());
                    }
                }, {
                    text: 'Yesterday',
                    onClick(picker) {
                    const date = new Date();
                    date.setTime(date.getTime() - 3600 * 1000 * 24);
                    picker.$emit('pick', date);
                    }
                }, {
                    text: 'A week ago',
                    onClick(picker) {
                    const date = new Date();
                    date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                    picker.$emit('pick', date);
                    }
                }]
            },
            form: {
                fname: '',
                mname: '',
                lname: '',
                suffix: '',
                admission:'',
                discharge:'',
                batch:'',
                is_private:false,
                doctortype:[],
                name:'',
                pf:'',
                doctors_id:[],
                attending: [],
                requesting: [],
                surgeon: [],
                healthcare:[],
                er: [],
                anesthesiologist: [],
                comanagement: [],
                admitting: [],
            },
            rules: {
                lname: [
                    { required: true, message: 'Please input Lastname', trigger: 'blur' },
                ],
                fname: [
                    { required: true, message: 'Please input Firstname', trigger: 'blur' },
                ],
                admission: [
                    { required: true, message: 'Please input Admission', trigger: 'blur' },
                ],
                discharge: [
                    { required: true, message: 'Please input Discharge', trigger: 'blur' },
                ],
                batch: [
                    { required: true, message: 'Please input Batch', trigger: 'blur' },
                ],
                pf: [
                    { required: true, message: 'Please input Professional fee', trigger: 'blur' },
                ],
            }
        };
    },
    methods: {
        //function to hide addrecordchild.vue
        triggerClose() {
            //add-close was invoke in recordparent.vue so the emit will
            //same just call 'add-close'
            this.$emit("add-close");
        },
        clearField() {
            if(this.$refs.form !== undefined) {
                this.$refs.form.resetFields();
            }
        },
        onSubmit() {
        console.log('submit!');
        },
        testName(name) {
            // ^([a-zA-ZñÑ.]+)((?:\s|-)([a-zA-ZñÑ.]+))*,\s([a-zA-ZñÑ.]+)((?:\s|-)([a-zA-ZñÑ.]+))*$
            var regex = new RegExp(/^([a-zA-ZñÑ.]+)((?:\s|-)([a-zA-ZñÑ.]+))*,\s([a-zA-ZñÑ.]+)((?:\s|-)([a-zA-ZñÑ.]+))*$/);
            if(regex.test(name)) {
              return true;
            }
            return false;
        },
        buildFullName() {
            if (this.form.suffix.trim()) {
                this.form.name = `${this.form.lname.trim()}, ${this.form.fname.trim()} ${this.form.suffix.trim()} ${this.form.mname.trim()}`;
            } else {
                this.form.name = `${this.form.lname.trim()}, ${this.form.fname.trim()} ${this.form.mname.trim()}`;
            }
        },
        addCreditRecord(){
            var _this=this;
            var temp=[];
            var attending = this.form.attending.map(function (value, index, array) {
                _this.form.doctortype.push(value);
                temp.push(value.id);
            });
            var requesting = this.form.requesting.map(function (value, index, array) {
                _this.form.doctortype.push(value);
                temp.push(value.id);
            });
            var surgeon = this.form.surgeon.map(function (value, index, array) {
                _this.form.doctortype.push(value);
                temp.push(value.id);
            });
            var er = this.form.er.map(function (value, index, array) {
                _this.form.doctortype.push(value);
                temp.push(value.id);
            });
            var anesthesiologist = this.form.anesthesiologist.map(function (value, index, array) {
                _this.form.doctortype.push(value);
                temp.push(value.id);
            });
            var comanagement = this.form.comanagement.map(function (value, index, array) {
                _this.form.doctortype.push(value);
                temp.push(value.id);
            });
            var admitting = this.form.admitting.map(function (value, index, array) {
                _this.form.doctortype.push(value);
                temp.push(value.id);
            });
            console.log(this.form.doctortype);
            this.form.doctors_id=temp;
            const loading = this.$loading({
                lock: true,
                spinner: "el-icon-loading",
                target: "#form",
                fullscreen:false
            });
            if (this.form.lname =="" || this.form.fname =="" ||
            this.form.admitting =="" || this.form.discharge =="" ||
            this.form.batch =="" || this.form.pf =="")
            {
                this.$notify({
                    type: 'info',
                    title: 'Adding Record Failed',
                    message: 'All fields with * are required',
                    offset: 0,
                });
                loading.close();
                this.btnLoading=false;
            }
            else
            {
                this.form.pf=Number(this.form.pf);
                axios
                .post("add_records",this.form)
                .then(response => {
                    console.log(response.data);
                })
                .catch(error=> {
                    this.errors=error.response.data.errors;
                });
            }
        },
        getDoctors(){
             axios
                .get("get_doctors")
                .then(response => {
                    this.doctors = response.data;
                })
                .catch(function(error) {});
        },
        getBatch(){
             axios
                .get("get_batch")
                .then(response => {
                    this.batch = response.data;
                    console.log(this.batch);
                })
                .catch(function(error) {});
        },
        asd(){
            // this.form.batch.forEach(el=>{
                console.log(this.form.admitting);
            // })
        }
    },
    mounted(){
        this.getDoctors();
        this.getBatch();
    }
}
</script>
<style scoped>
</style>
