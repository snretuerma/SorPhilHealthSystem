<template>
    <div>
        <!-- Header -->
        <div class="row">
            <div class="col-sm-12">
                <h2>Admin Staffs List</h2>
            </div>
        </div>
        <hr />
        <!-- End Header -->

        <div class="row">
            <!-- Add Button -->
            <div class="col-sm-12" align="right" style="margin-bottom: 10px">
                <el-button type="primary" @click="dialogFormVisible = true; form.formmode = 'add';clearFields();">Add</el-button>
            </div>
            <!-- End Button -->
        </div>

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <el-table v-loading="loading" :data="ListData">
                    <el-table-column width="250" label="Name" prop="name"></el-table-column>
                    <el-table-column width="150" label="Type" prop="is_private"></el-table-column>
                    <el-table-column width="150" label="Sex" prop="sex"></el-table-column>
                    <el-table-column width="150" label="Birthdate" prop="birthdate"></el-table-column>
					<el-table-column width="150" label="Hospital" prop="hospital_code"></el-table-column>
                    <el-table-column width="250" align="right" fixed="right">
                    <template slot="header" slot-scope="scope">
                        <el-input v-model="search" size="mini" placeholder="Type to search"/>
                    </template>
                    <template slot-scope="scope">
                        <el-tooltip class="item" effect="light" content="View" placement="top">
                            <el-button size="mini" type="info" icon="el-icon-info" circle @click="handleView(scope.$index, scope.row)"></el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="light" content="Edit" placement="top">
                            <el-button size="mini" type="primary" icon="el-icon-edit" circle @click="handleEdit(scope.$index, scope.row)"></el-button>
                        </el-tooltip>
                    </template>
                </el-table-column>
                </el-table>

                <div style="text-align: center">
                    <el-pagination
                        background
                        layout="prev, pager, next"
                        @current-change="handleCurrentChange"
                        :page-size="pageSize"
                        :total="data.length">
                    </el-pagination>
                </div>
                <!-- End table -->
            </div>
        </div>
        <!-- Card ends here -->

        <!-- Footer -->
        <hr />
        <div class="footer">
            <div class="containter-fluid">
                <div class="row text-center">
                    <span class="text-muted">&nbsp;&nbsp;&nbsp;&nbsp;©PF Management System 2020</span>
                </div>
            </div>
        </div>
        <!-- Footer ends -->

        <!-- Add/Edit Personnel form -->
        <el-dialog
        title="Staff Details"
        :visible.sync="dialogFormVisible"
        top="5vh"
        center
        :close-on-press-escape="false"
        :close-on-click-modal="false"
        >
        <el-form :model="form" :rules="rules" ref="form">
            <el-form-item label="Lastname" :label-width="formLabelWidth" prop="last_name">
                <el-input v-model="form.last_name" autocomplete="off"></el-input>
                <span class="font-italic text-danger" v-if="errors.last_name"><small>{{ errors.last_name[0] }}</small></span>
            </el-form-item>
            <el-form-item label="Firstname" :label-width="formLabelWidth" prop="first_name">
                <el-input v-model="form.first_name" autocomplete="off"></el-input>
                <span class="font-italic text-danger" v-if="errors.first_name"><small>{{ errors.first_name[0] }}</small></span>
            </el-form-item>
            <el-form-item label="Middlename" :label-width="formLabelWidth" prop="middle_name">
                <el-input v-model="form.middle_name" autocomplete="off"></el-input>
                <span class="font-italic text-danger" v-if="errors.middle_name"><small>{{ errors.middle_name[0] }}</small></span>
            </el-form-item>
            <el-form-item label="Suffix" :label-width="formLabelWidth">
                <el-input v-model="form.name_suffix" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Sex" :label-width="formLabelWidth" prop="sex">
                <el-select v-model="form.sex" placeholder="Please select">
                    <el-option label="Male" value="0"></el-option>
                    <el-option label="Female" value="1"></el-option>
                </el-select>
                <br />
                <span class="font-italic text-danger" v-if="errors.sex"><small>{{ errors.sex[0] }}</small></span>
            </el-form-item>
            <el-form-item label="Type" :label-width="formLabelWidth" prop="is_private">
                <el-radio-group v-model="form.is_private">
                    <el-radio label="0">Private</el-radio>
                    <el-radio label="1">Non-private</el-radio>
                </el-radio-group>
                <br />
                <span class="font-italic text-danger" v-if="errors.is_private"><small>{{ errors.is_private[0] }}</small></span>
            </el-form-item>
            <el-form-item label="Birthdate" :label-width="formLabelWidth" prop="birthdate">
                <el-date-picker type="date" placeholder="Pick a date" v-model="form.birthdate" style="width: 100%" value-format="yyyy-MM-dd"></el-date-picker>
                <span class="font-italic text-danger" v-if="errors.birthdate"><small>{{ errors.birthdate[0] }}</small></span>
            </el-form-item>
			<el-form-item label="Hospital code" :label-width="formLabelWidth" prop="hospital_code">
              	<el-select v-model="form.hospital_code" @change="onChange(form.hospital_code)" placeholder="Please select">
					<el-option label="DFBDSMH" value="1"></el-option>
					<el-option label="DDH" value="2"></el-option>
					<el-option label="IDH" value="3"></el-option>
					<el-option label="SREDH" value="4"></el-option>
					<el-option label="VLPMDH" value="5"></el-option>
					<el-option label="MagMCH" value="6"></el-option>
					<el-option label="MatMCH" value="7"></el-option>
					<el-option label="PGGMH" value="8"></el-option>
					<el-option label="PDMH" value="9"></el-option>
              	</el-select>
				<br />
				<span class="font-italic text-danger" v-if="errors.hospital_code"><small>{{ errors.hospital_code[0] }}</small></span>
            </el-form-item>
        </el-form>

        <span slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button v-if="form.formmode == 'add'" type="primary" @click="personnelFunctions('add');formLoading();">Save</el-button>
        <el-button v-if="form.formmode == 'edit'" type="primary" @click="personnelFunctions('edit');formLoading();">Save Changes</el-button>
        </span>
        </el-dialog>
        <!-- Add Personnel form ends here-->

        <!-- Show Personnel Details -->
		<el-dialog title="Staff Info" :visible.sync="dialogTableVisible">
			<el-table :data="gridData">
				<el-table-column property="name" label="Name" width="250"></el-table-column>
                <el-table-column property="is_private" label="Type" width="150"></el-table-column>
				<el-table-column property="sex" label="Sex" width="150"></el-table-column>
				<el-table-column property="birthdate" label="Birthdate" width="150"></el-table-column>
				<el-table-column property="hospital_code" label="Hospital" width="150"></el-table-column>
			</el-table>
		</el-dialog>
		<!-- Show Personnel Details -->
    </div>
</template>

<script>
"use strict";
import constants from "../../constants.js";
export default {
    data() {
        return {
        page: 1,
		pageSize: 10,
        loading: true,
        search: "",
        data: [],
        errors: [],
        dialogFormVisible_import_excel: false,
		progressbar_import: false,
		enableUpload: false,
        dialogTableVisible: false,
        dialogFormVisible: false,
        formLabelWidth: "120px",
        // Validation
        rules: {
            last_name: [
            { required: true, message: "Lastname is required.", trigger: "blur" },
            ],
            first_name: [
			{ required: true, message: "Firstname is required.", trigger: "blur" }
			],
			middle_name: [
			{ required: true, message: "Middlename is required.", trigger: "blur" }
			],
            is_private: [
            { required: true, message: "Please select staff type.", trigger: "change"}
            ],
            sex: [
			{ required: true, message: "Sex is required.", trigger: "change" }
			],
			birthdate: [
			{ required: true, message: "Please pick a date.", trigger: "change" }
			],
			hospital_code: [
			{ required: true, message: "Please select a hospital.", trigger: "change" }
			]
        },

        titles: [
            { prop: "name", label: "Name", width:"250px" },
            { prop: "is_private", label: "Type", width:"150px" },
			{ prop: "sex", label: "Sex", width:"150px" },
			{ prop: "birthdate", label: "Birthdate", width:"150px" },
			{ prop: "hospital_code", label: "Hospital", width:"150px" }
        ],
        // Add Personnel form
        form: {
            id: "",
            last_name: "",
            first_name: "",
            middle_name: "",
            name_suffix: "",
            sex: "",
            birthdate: "",
			is_private: "",
			hospital_code: "",
        	codeholder: "",
            name: "",
            formmode: "",
            edit_object_index: "",
        },
        // Edit Personnel form check
        form_check: {
            last_name: "",
            first_name: "",
            middle_name: "",
            name_suffix: "",
            sex: "",
            birthdate: "",
			is_private: "",
			codeholder: "",
            name: "",
        },
        // View info data
        gridData: [
            {
            name: "",
            sex: "",
            birthdate: "",
			is_private: "",
			hospital_code: "",
			},
		],
        };
    },
    computed: {
        ListData() {
            if(this.search == null) return this.data;
            this.filtered = this.data.filter(data => !this.search || data.first_name.toLowerCase().includes(this.search.toLowerCase()) || data.last_name.toLowerCase().includes(this.search.toLowerCase()));
            this.total = this.filtered.length;
            return this.filtered.slice(this.pageSize * this.page - this.pageSize, this.pageSize * this.page);
        }
    },
    methods: {
		 onChange: function (event) {
			this.form.codeholder = event;
		},
        handleCurrentChange(val) {
			this.page = val;
		},
        formLoading: function () {
            const loading = this.$loading({
                lock: true,
                spinner: "el-icon-loading",
                target: "div.el-dialog",
            });
            loading.close();
        },
        getPersonnels: function () {
            axios
                .get("personnel_get")
                .then((response) => {
                response.data.forEach((element) => {
                    this.buildPersonnelData(element);
                });
                this.data = response.data;
                this.loading = false;
                })
                .catch(function (error) {});
        },
        handleView(index, row) {
			this.dialogTableVisible = true;
			this.gridData[0].name = this.buildName(
				row.first_name,
				row.middle_name,
				row.last_name,
				row.name_suffix
            );
            this.gridData[0].is_private = row.is_private;
			this.gridData[0].sex = row.sex;
			this.gridData[0].birthdate = row.birthdate;
			this.gridData[0].hospital_code = row.hospital_code;
        },
        handleEdit(index, row) {
			this.clearFields();
			this.form.id = row.id;
			this.form.formmode = "edit";

			this.form.last_name = row.last_name;
			this.form.first_name = row.first_name;
			this.form.middle_name = row.middle_name;
            this.form.name_suffix = row.name_suffix;
            this.form.is_private = row.is_private;
			this.form.sex = row.sex;
			this.form.birthdate = row.birthdate;
			this.form.codeholder =
                constants.hospital_code.indexOf(row.hospital_code) - 1;
            this.form.hospital_code = row.hospital_code;

			this.form.edit_object_index = this.data.indexOf(row);

			this.form_check.last_name = row.last_name;
            this.form_check.first_name = row.first_name;
            this.form_check.middle_name = row.middle_name;
            this.form_check.name_suffix = row.name_suffix;
            this.form_check.is_private = row.is_private;
            this.form_check.sex = row.sex;
			this.form_check.birthdate = row.birthdate;
			this.form_check.codeholder = this.form.codeholder;
            
            this.form_check.name =
            this.form_check.last_name +
            ", " +
            this.form_check.name_suffix +
            " " +
            this.form_check.first_name +
            " " +
            this.form_check.middle_name.slice(0, 1) +
            ". ";
			this.dialogFormVisible = true;
        },
        personnelFunctions: function (mode) {
            switch (mode) {
                case "add":
                if (
                    this.form.last_name == "" ||
                    this.form.first_name == "" ||
                    this.form.middle_name == "" ||
                    this.form.is_private == "" ||
                    this.form.sex == "" ||
                    this.form.birthdate == "" ||
            		this.form.codeholder == ""
                ) {
                    this.open_notif(
                    "info",
                    "Message",
                    "Required fields were missing values."
                    );
                } else {
                    axios
                    .post("add_personnel", this.form)
                    .then((response) => {
                        if (response.status > 199 && response.status < 203) {
                        response.data.name =
                            this.form.last_name +
                            ", " +
                            this.form.name_suffix +
                            " " +
                            this.form.first_name +
                            " " +
                            this.form.middle_name.slice(0, 1) +
                            ". ";
                        response.data.is_private =
                            constants.is_private[Number(this.form.is_private)];
						response.data.sex = constants.sex[Number(this.form.sex)];
						response.data.hospital_code =
							constants.hospital_code[
							Number(this.form.hospital_code - 1)
							];
                        this.data.push(response.data);
                        this.dialogFormVisible = false;
                        this.open_notif(
                            "success",
                            "Success",
                            "Staff added successfully"
                        );
                        } else {
                        this.open_notif("error", "System", "Failed to add personnel");
                        }
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    });
                }
                break;
                case "edit":
                if (
                    this.form.last_name == this.form_check.last_name &&
                    this.form.first_name == this.form_check.first_name &&
                    this.form.middle_name == this.form_check.middle_name &&
                    this.form.name_suffix == this.form_check.name_suffix &&
                    this.form.sex == this.form_check.sex &&
                    this.form.birthdate == this.form_check.birthdate &&
            		this.form.codeholder == this.form_check.codeholder
                ) {
                    this.open_notif("info", "Message", "No Changes");
                } else {
                    if (this.form.sex == "Male") {
                    this.form.sex = 0;
                    } else if (this.form.sex == "Female") {
                    this.form.sex = 1;
                    }
                    if (this.form.is_private == "Private") {
                    this.form.is_private = 0;
                    } else if (this.form.is_private == "Non-private") {
                    this.form.is_private = 1;
					}
					if (this.form.hospital_code == "DFBDSMH") {
					this.form.codeholder = 1;
					} else if (this.form.hospital_code == "DDH") {
					this.form.codeholder = 2;
					} else if (this.form.hospital_code == "IDH") {
					this.form.codeholder = 3;
					} else if (this.form.hospital_code == "SREDH") {
					this.form.codeholder = 4;
					} else if (this.form.hospital_code == "VLPMDH") {
					this.form.codeholder = 5;
					} else if (this.form.hospital_code == "MagMCH") {
					this.form.codeholder = 6;
					} else if (this.form.hospital_code == "MatMCH") {
					this.form.codeholder = 7;
					} else if (this.form.hospital_code == "PGGMH") {
					this.form.codeholder = 8;
					} else if (this.form.hospital_code == "PDMH") {
					this.form.codeholder = 9;
					}
                    this.form.name =
                    this.form.last_name +
                    ", " +
                    this.form.name_suffix +
                    " " +
                    this.form.first_name +
                    " " +
                    this.form.middle_name.slice(0, 1) +
                    ". ";
                    axios
                    .post("edit_personnel/" + this.form.id, this.form)
                    .then((response) => {
                        if (response.status > 199 && response.status < 203) {
                        this.open_notif(
                            "success",
                            "Success",
                            "Changes has been saved"
                        );
                        this.dialogFormVisible = false;
                        this.data[
                            parseInt(this.form.edit_object_index)
                        ].last_name = this.form.last_name;
                        this.data[
                            parseInt(this.form.edit_object_index)
                        ].first_name = this.form.first_name;
                        this.data[
                            parseInt(this.form.edit_object_index)
                        ].middle_name = this.form.middle_name;
                        this.data[
                            parseInt(this.form.edit_object_index)
                        ].name_suffix = this.form.name_suffix;
                        this.data[parseInt(this.form.edit_object_index)].sex =
                            constants.sex[Number(this.form.sex)];
                        this.data[parseInt(this.form.edit_object_index)].is_private =
                            constants.is_private[Number(this.form.is_private)];
                        this.data[
                            parseInt(this.form.edit_object_index)
						].birthdate = this.form.birthdate;
						this.data[
							parseInt(this.form.edit_object_index)
						].hospital_code =
							constants.hospital_code[Number(this.form.codeholder) - 1];
                        this.data[
                            parseInt(this.form.edit_object_index)
                        ].name = this.form.name;
                        }
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    });
                }
                break;
            }
        },
        open_notif: function (status, title, message) {
			if (status == "success") {
				this.$notify.success({ title: title, message: message, offset: 0, });
			} else if (status == "error") {
				this.$notify.error({ title: title, message: message, offset: 0, });
			} else if (status == "info") {
				this.$notify.info({ title: title, message: message, offset: 0, });
			} else if (status == "warning") {
				this.$notify.warning({ title: title, message: message, offset: 0, });
			}
		},
        clearFields: function () {
            this.form.last_name = "";
            this.form.first_name = "";
            this.form.middle_name = "";
            this.form.name_suffix = "";
            this.form.is_private = "";
            this.form.sex = "";
			this.form.birthdate = "";
			this.form.hospital_code = "";
			this.form.codeholder = "";
        },
        assignType: function (type_value) {
            var type;
            switch (type_value) {
                case 0:
                type = "Private";
                break;
                case 1:
                type = "Non-private";
                break;
                default:
                type = "Not Known";
            }
            return type;
        },
        assignSex: function (sex_value) {
            var sex;
            switch (sex_value) {
                case 0:
                sex = "Male";
                break;
                case 1:
                sex = "Female";
                break;
                case 9:
                sex = "Not Applicable";
                break;
                default:
                sex = "Not Known";
        }
        return sex;
        },
        buildName: function (first_name, middle_name, last_name, suffix) {
            return (
                last_name +
                " " +
                suffix +
                ", " +
                first_name +
                " " +
                middle_name.slice(0, 1) +
                "."
            ).trim();
        },
        buildPersonnelData: function (element) {
            if (element.name_suffix == undefined) {
                element.name_suffix = "";
            }
            element.name = this.buildName(
                element.first_name,
                element.middle_name,
                element.last_name,
                element.name_suffix
            );
            element.sex = this.assignSex(element.sex);
            element.is_private = this.assignType(element.is_private);
        },
    },
    mounted() {
        this.getPersonnels();
    },
};
</script>