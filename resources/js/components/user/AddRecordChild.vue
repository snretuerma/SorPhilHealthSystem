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
            <div class="col-xl-9 col-lg-8 col-md-6 col-sm-12">
               
            </div>
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
            <el-form :model="form"  ref="form">
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Last Name" prop="lname">
                                <el-input
                                    v-model="form.lname"
                                    autocomplete="off"
                                    @input="buildFullName"
                                />
                                <!-- <span class="font-italic text-danger" v-if="errors.last_name">
                                    <small>{{ errors.last_name[0] }}</small>
                                </span> -->
                            </el-form-item>
                        </el-col>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="First Name" prop="fname">
                                <el-input
                                    v-model="form.fname"
                                    autocomplete="off"
                                    @input="buildFullName"
                                />
                                <!-- <span class="font-italic text-danger" v-if="errors.first_name">
                                    <small>{{ errors.first_name[0] }}</small>
                                </span> -->
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
                                    v-model="form.admission"
                                    type="date"
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
                                    v-model="form.discharge"
                                    type="date"
                                    placeholder="Pick a day"
                                    :picker-options="pickerOptions">
                                    </el-date-picker>
                                </div>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Name will appear like this on the database" prop="name">
                                <el-input
                                    v-model="form.name"
                                    autocomplete="off"
                                    :readonly="true"
                                />
                            </el-form-item>
                        </el-col>
                        <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <el-form-item label="Professional Fee" prop="pf">
                                <el-input
                                    v-model="form.pf"
                                    autocomplete="off"
                                />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Attending" prop="attending">
                                <el-select 
                                    style="width:100%"
                                    size="large"
                                    v-model="form.attending"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.name">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Requesting" prop="requesting">
                                <el-select 
                                    style="width:100%"
                                    size="large"
                                    v-model="form.requesting"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.name">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Surgeon" prop="surgeon">
                                <el-select 
                                    style="width:100%"
                                    size="large"
                                    v-model="form.surgeon"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.name">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="ER" prop="er">
                                <el-select 
                                    style="width:100%"
                                    size="large"
                                    v-model="form.er"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.name">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Anesthesiologist" prop="anesthesiologist">
                                <el-select 
                                    style="width:100%"
                                    size="large"
                                    v-model="form.anesthesiologist"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.name">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Co-management" prop="comanagement">
                                <el-select 
                                    style="width:100%"
                                    size="large"
                                    v-model="form.comanagement"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.name">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <el-form-item label="Admitting" prop="admitting">
                                <el-select 
                                    style="width:100%"
                                    size="large"
                                    v-model="form.admitting"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose physician">
                                    <el-option
                                    v-for="item in doctors"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.name">
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
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="Choose tags for your article">
                                    <el-option
                                    v-for="item in batches"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col class="col-sm-4 col-md-4 col-lg-4 col-xl-4 block-button">
                            <el-form-item>
                                  
                            </el-form-item>
                        </el-col>
                         <el-col class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <el-form-item>
                                    <el-button
                                    class="btn-action block-button"
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
            doctors:[],
             pickerOptions: {
                disabledDate(time) {
                    return time.getTime() > Date.now();
                },
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
            batches:[{
                value:'22112020-28112020',
                label:'22112020-28112020'
            }],
             options: [{
                value: 'HTML',
                label: 'HTML'
                }, {
                value: '1',
                label: 'asd'
                }, {
                value: '2',
                label: 'ads'
                }, {
                value: '3',
                label: 'qwe'
                }, {
                value: 'CSS',
                label: 'fgasdas'
                }, {
                value: 'JavaScript',
                label: 'JavaScript'
            }],
            form: {
                fname: '',
                mname: '',
                lname: '',
                suffix: '',
                admission:'',
                discharge:'',
                batch:'',
                name:'',
                pf:'',
                attending: [],
                requesting: [],
                surgeon: [],
                er: [],
                anesthesiologist: [],
                comanagement: [],
                admitting: [],
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
             axios
                .post("add_records",this.form)
                .then(response => {
                    this.data = response.data;
                })
                .catch(function(error) {});
        },
        getDoctors(){
             axios
                .get("get_doctors")
                .then(response => {
                    this.doctors = response.data;
                })
                .catch(function(error) {});
        },
    },
    mounted(){
        this.getDoctors();
    }
}
</script>
<style scoped>
</style>
