<template>
    <div>
        <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                <i class="fa fa-user-md"></i>&nbsp;&nbsp;Doctors
                </span>
            </div>
        </div>
        <!-- End Header -->
        <div id="search-box" class="row">
            <div class="col-xl-9 col-lg-8 col-md-6 col-sm-12">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    placeholder="Type to search"
                />
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <el-button
                            class="btn-action block-button"
                            @click="handleAddButtonClick"
                        >
                            Add
                        </el-button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <el-dropdown @command="formDialog" class="block-button btn-action">
                            <el-button>
                                Excel
                                <i class="el-icon-arrow-down el-icon--right"/>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item
                                    icon="el-icon-upload2"
                                    command="import_data"
                                >
                                    Import Data
                                </el-dropdown-item>
                                <el-dropdown-item
                                    icon="el-icon-download"
                                    command="export_data"
                                >
                                    Export Data
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <el-table v-loading="loading" :data="tableData">
                            <el-table-column label="Name" prop="name"></el-table-column>
                            <el-table-column
                                label="Employment Type"
                                prop="is_parttime"
                                align="center"
                                :formatter="employmentType"
                            ></el-table-column>
                            <el-table-column
                                label="Employment Status"
                                prop="is_active"
                                align="center"
                                :formatter="employmentStatus"
                            ></el-table-column>
                            <el-table-column width="135" align="center" fixed="right">
                                <template slot="header"> Action </template>
                                <template slot-scope="scope">
                                    <el-tooltip
                                        class="item"
                                        effect="light"
                                        content="Edit"
                                        placement="top"
                                        :enterable="false"
                                    >
                                        <el-button
                                            size="mini"
                                            type="primary"
                                            icon="el-icon-edit"
                                            circle
                                            @click="handleEdit(scope.row)"
                                        ></el-button>
                                    </el-tooltip>
                                    <el-tooltip
                                        class="item"
                                        effect="light"
                                        content="Delete"
                                        placement="top"
                                        :enterable="false"
                                    >
                                        <el-button
                                            size="mini"
                                            type="danger"
                                            icon="el-icon-delete"
                                            circle
                                            @click="handleDelete(scope.row)"
                                        ></el-button>
                                    </el-tooltip>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <el-pagination
                            class="align-middle"
                            background
                            layout="prev, pager, next"
                            @current-change="handleCurrentChange"
                            :page-size="page_size"
                            :total="total"
                        />
                    </div>
                </div>
            </div>
        </div>
        <el-dialog
            title="Doctor Details"
            :visible.sync="show_dialog"
            center
            :close-on-press-escape="false"
            :close-on-click-modal="false"
        >
            <el-form :model="form" :rules="rules" ref="doctors_form">
                <el-row>
                    <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item label="Last Name" prop="last_name" ref="form_last_name">
                            <el-input
                                v-model="form.last_name"
                                autocomplete="off"
                                @input="buildFullName"
                            />
                            <span class="font-italic text-danger" v-if="errors.last_name">
                                <small>{{ errors.last_name[0] }}</small>
                            </span>
                        </el-form-item>
                    </el-col>
                    <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item label="First Name" prop="first_name" ref="form_first_name">
                            <el-input
                                v-model="form.first_name"
                                autocomplete="off"
                                @input="buildFullName"
                            />
                            <span class="font-italic text-danger" v-if="errors.first_name">
                                <small>{{ errors.first_name[0] }}</small>
                            </span>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item label="Middle Name" prop="middle_name" ref="form_middle_name">
                            <el-input
                                v-model="form.middle_name"
                                autocomplete="off"
                                @input="buildFullName"
                            />
                            <span class="font-italic text-danger" v-if="errors.middle_name">
                                <small>{{ errors.middle_name[0] }}</small>
                            </span>
                        </el-form-item>
                    </el-col>
                    <el-col class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item label="Suffix" prop="suffix" ref="form_suffix">
                            <el-input
                                v-model="form.suffix"
                                autocomplete="off"
                                @input="buildFullName"
                            />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col>
                        <el-form-item label="Full Name" prop="name">
                            <el-input
                                v-model="form.name"
                                autocomplete="off"
                                :readonly="true"
                            />
                        </el-form-item>
                    </el-col>
                </el-row>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item prop="is_parttime" label="Employment Type">
                            <br>
                            <el-radio-group v-model="form.is_parttime" ref="form_is_parttime">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                        <el-radio label="0">Full-time</el-radio>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                        <el-radio label="1">Part-time</el-radio>
                                    </div>
                                </div>
                            </el-radio-group>
                            <span class="font-italic text-danger" v-if="errors.is_parttime">
                                <small>{{ errors.is_parttime[0] }}</small>
                            </span>
                        </el-form-item>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <el-form-item prop="is_active" label="Employment Status">
                            <br>
                            <el-radio-group v-model="form.is_active" ref="form_is_active">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                        <el-radio label="1">Active</el-radio>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                        <el-radio label="0">Inactive</el-radio>
                                    </div>
                                </div>
                            </el-radio-group>
                            <span class="font-italic text-danger" v-if="errors.is_active">
                                <small>{{ errors.is_active[0] }}</small>
                            </span>
                        </el-form-item>
                    </div>
                </div>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="show_dialog = false">Cancel</el-button>
                <el-button
                    v-if="form.form_type == 'add'"
                    type="primary"
                    @click="doctorsFormAction()"
                >
                    Save
                </el-button>
                <el-button
                    v-else
                    type="primary"
                    @click="doctorsFormAction()"
                >
                    Save Changes
                </el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
/**
 * TODO:
 *  1. Modify the edit function to a better one
**/
'use strict'
export default {
    data() {
        return {
            doctors: [],
            edit_object: '',
            errors: [],
            form: {
                id: '',
                name: '',
                first_name: '',
                middle_name: '',
                last_name: '',
                suffix: '',
                is_parttime: '',
                is_active: true,
                form_type: '',
            },
            loading: true,
            page: 1,
            page_size: 10,
            rules: {
                first_name: [
                    {
                        required: true,
                        message: "First name is required",
                        trigger: "blur"
                    }
                ],
                middle_name: [
                    {
                        required: true,
                        message: "Middle name is required",
                        trigger: "blur"
                    }
                ],
                last_name: [
                    {
                        required: true,
                        message: "Last name is required",
                        trigger: "blur"
                    }
                ],
                is_parttime: [
                    {
                        required: true,
                        message: "Please select employment type",
                        trigger: "change"
                    }
                ],
                is_active: [
                    {
                        required: true,
                        message: "Please select employment status",
                        trigger: "change"
                    }
                ],
            },
            search: '',
            show_dialog: false,
            total: '',
        }
    },
    computed: {
        searching() {
            if (!this.search) {
                this.total = this.doctors.length;
                return this.doctors;
            }
            this.page = 1;
            return this.doctors.filter(data => data.name.toLowerCase().includes(this.search.toLowerCase()));
        },
        tableData() {
            this.total = this.searching.length;
            return this.searching.slice(
                this.page_size * this.page - this.page_size,
                this.page_size * this.page
            );
        }
    },
    methods: {
        getDoctors() {
            axios.get("get_doctors")
            .then((response) => {
                this.doctors = response.data;
                this.loading = false;
            });
        },
        doctorsFormAction() {
            const loading = this.$loading({
                lock: true,
                spinner: "el-icon-loading",
                target: "div.el-dialog"
            });
            switch(this.form.form_type) {
                case 'add':
                    if(this.form.name == '' || this.form.is_parttime == '' || this.is_active == '') {
                        this.$notify({
                            type: 'info',
                            title: 'Adding Doctor Failed',
                            message: 'All fields are required',
                            offset: 0,
                        });
                        this.show_dialog = false;
                        this.formResetFields();
                        loading.close();
                    }else {
                        this.form.name = this.form.name.trim();
                        if(this.testName(this.form.name)) {
                            axios.post('add_doctor', this.form)
                            .then(response => {
                                this.doctors.push(response.data)
                                this.show_dialog = false;
                                this.formResetFields();
                                loading.close();
                                this.$notify({
                                    type: 'success',
                                    title: 'Adding Doctor Successful',
                                    message: `Successfully added ${response.data.name}`,
                                    offset: 0,
                                });
                            })
                            .catch(error => {
                                this.show_dialog = false;
                                this.formResetFields();
                                loading.close();
                                this.$notify({
                                    type: 'error',
                                    title: 'Adding Doctor Failed',
                                    message: `Error Code: ${error.response.status} : ${error.response.data.message}`,
                                    offset: 0,
                                });
                            });
                        } else {
                            this.show_dialog = false;
                            loading.close();
                            this.$notify({
                                type: 'error',
                                title: 'Adding Doctor Failed',
                                message: `${this.form.name} does not follow the correct naming convention\n (Ex: Dela Cruz, Jose Juan Jr.)`,
                                offset: 0,
                            })
                            this.formResetFields();
                        }

                    }
                    break;
                case 'edit':
                    if(
                        this.edit_object.name == this.form.name &&
                        this.edit_object.is_active == this.form.is_active &&
                        this.edit_object.is_parttime == this.form.is_parttime
                    ) {
                        this.$notify({
                            type: 'info',
                            title: 'Editing Failed',
                            message: 'No changes are made to the doctor\'s record',
                            offset: 0,
                        });
                        this.show_dialog = false;
                        this.formResetFields();
                        loading.close();
                    }else {
                        this.form.name = this.form.name.trim();
                        this.form.id = this.edit_object.id;
                        axios.put('edit_doctor', this.form)
                        .then(response => {
                            if (response.status >= 200 && response.status <=299) {
                                var index = this.doctors.findIndex(object => object.id == response.data.id);
                                if (index !== undefined) {
                                    this.doctors[index].name = response.data.name;
                                    this.doctors[index].is_parttime = response.data.is_parttime;
                                    this.doctors[index].is_active = response.data.is_active;
                                }
                                this.show_dialog = false;
                                this.formResetFields();
                                this.edit_object = '';
                                loading.close();
                                this.$notify({
                                    type: 'success',
                                    title: 'Editing Doctor Successful',
                                    message: `Successfully edited ${response.data.name}`,
                                    offset: 0,
                                });
                            }else {
                                this.show_dialog = false;
                                this.formResetFields();
                                this.edit_object = '';
                                loading.close();
                                this.$notify({
                                    type: 'error',
                                    title: 'Editing Doctor Failed',
                                    message: `Error Code: ${error.response.status} : ${error.response.data.message}`,
                                    offset: 0,
                                });
                            }
                        }).catch(error => {
                            this.show_dialog = false;
                            this.formResetFields();
                            this.edit_object = '';
                            loading.close();
                            this.$notify({
                                type: 'error',
                                title: 'Editing Doctor Failed',
                                message: `Error Code: ${error.response.status} : ${error.response.data.message}`,
                                offset: 0,
                            });
                        });
                    }
                    break;
            }
        },
        deleteDoctor(data) {
            this.$confirm(
                `Are you sure you want to delete record for doctor ${data.name}?`,
                "Confirm Delete",
                {
                    distinguishCancelAndClose: true,
                    confirmButtonText: "Delete",
                    cancelButtonText: "Cancel",
                    type: "warning"
                }
            ).then(() => {
                axios.delete(`delete_doctor/${data.id}`)
                .then(response => {
                    console.log(response.data);
                    if (response.status > 199 && response.status < 203) {
                        if(response.data.status == 'success') {
                            var index = this.doctors.findIndex(object => object.id == data.id);
                            if (index !== undefined) {
                                this.doctors.splice(index, 1);
                            }
                        }
                        this.$notify({
                            type: response.data.status,
                            title: response.data.title,
                            message: response.data.message,
                            offset: 0,
                        });
                    }
                });
            })
            .catch(action => {
                this.$notify({
                    type: 'info',
                    title: 'Deleting Doctor Cancelled',
                    message: `Delete method cancelled. No changes were made`,
                    offset: 0,
                });
            });
        },
        formDialog(id) {
            /**
             * TODO: Don't use if else statement here, you can use the parameter as the #id because it is also string
            **/
            if (id == "import_data") {
                $("#importModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
            } else {
                $("#exportModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
            }
        },
        handleAddButtonClick() {
            this.show_dialog = true;
            this.form.form_type = "add";
            this.formResetFields();
        },
        handleEdit(row_data) {
            this.edit_object = row_data;
            this.show_dialog = true;
            this.form.form_type = "edit";
            this.formResetFields();
            this.form.name = row_data.name;
        },
        handleDelete(row_data) {
            this.deleteDoctor(row_data);
        },
        employmentType(data) {
            if(data.is_parttime) {
                return 'Part-time'
            }
            return 'Full-time'
        },
        employmentStatus(data) {
            if(data.is_active) {
                return 'Active'
            }
            return 'Inactive'
        },
        handleCurrentChange(value) {
            this.page = value;
        },
        formResetFields() {
            if(this.$refs.doctors_form !== undefined) {
                this.$refs.doctors_form.resetFields();
            }
        },
        testName(name) {
            var regex = new RegExp(/^([a-zA-ZñÑ.]+)((?:\s|-)([a-zA-ZñÑ.]+))*,\s([a-zA-ZñÑ.]+)((?:\s|-)([a-zA-ZñÑ.]+))*$/);
            if(regex.test(name)) {
              return true;
            }
            return false;
        },
        buildFullName() {
            if (this.form.suffix.trim()) {
                this.form.name = `${this.form.last_name.trim()}, ${this.form.first_name.trim()} ${this.form.suffix.trim()} ${this.form.middle_name.trim()}`;
            } else {
                this.form.name = `${this.form.last_name.trim()}, ${this.form.first_name.trim()} ${this.form.middle_name.trim()}`;
            }
        }
    },
    mounted() {
        this.getDoctors();
    }
}
</script>

<style lang="css">
    #search-box {
        margin-bottom: 10px;
    }
    .block-button, .block-button button {
        width: 100%;
    }
    .doctors-form-radio-button {
        width: 100%;
    }
</style>