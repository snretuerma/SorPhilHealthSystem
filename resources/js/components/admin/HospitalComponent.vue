<template>
    <div>
        <!-- Header -->
        <div class="row">
            <div class="col-sm-12">
                <h2>Hospitals</h2>
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
                <el-table v-loading="loading" :data="data">
                    <el-table-column width="350" label="Name" prop="name"></el-table-column>
                    <el-table-column width="310" label="Address" prop="address"></el-table-column>
                    <el-table-column width="150" label="Abbr" prop="hospital_code"></el-table-column>
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
                        <el-tooltip class="item" effect="light" content="Delete" placement="top">
                            <el-button size="mini" type="danger" icon="el-icon-delete" circle @click="handleDelete(scope.$index, scope.row)"></el-button>
                        </el-tooltip>
                    </template>
                    </el-table-column>
                </el-table>
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
        title="Hospital Details"
        :visible.sync="dialogFormVisible"
        top="5vh"
        center
        :close-on-press-escape="false"
        :close-on-click-modal="false"
        >
        <el-form :model="form" ref="form">
            <el-form-item label="Name" :label-width="formLabelWidth" prop="name">
                <el-input v-model="form.name" autocomplete="off"></el-input>
                <span class="font-italic text-danger" v-if="errors.name"><small>{{ errors.name[0] }}</small></span>
            </el-form-item>
            <el-form-item label="Address" :label-width="formLabelWidth" prop="address">
                <el-input v-model="form.address" autocomplete="off"></el-input>
                <span class="font-italic text-danger" v-if="errors.address"><small>{{ errors.address[0] }}</small></span>
            </el-form-item>
            <el-form-item label="Abbr" :label-width="formLabelWidth" prop="hospital_code">
                <el-input v-model="form.hospital_code" autocomplete="off"></el-input>
                <span class="font-italic text-danger" v-if="errors.hospital_code"><small>{{ errors.hospital_code[0] }}</small></span>
            </el-form-item>
        </el-form>

        <span slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button v-if="form.formmode == 'add'" type="primary" @click="hospitalFunctions('add');formLoading();">Save</el-button>
        <el-button v-if="form.formmode == 'edit'" type="primary" @click="hospitalFunctions('edit');formLoading();">Save Changes</el-button>
        </span>
        </el-dialog>
        <!-- Add Personnel form ends here-->

        <!-- Show Personnel Details -->
		<el-dialog title="Hospital Info" :visible.sync="dialogTableVisible">
			<el-table :data="gridData">
				<el-table-column property="name" label="Name" width="350"></el-table-column>
                <el-table-column property="address" label="Address" width="310"></el-table-column>
				<el-table-column property="hospital_code" label="Hospital" width="150"></el-table-column>
			</el-table>
		</el-dialog>
		<!-- Show Personnel Details -->

    </div>
</template>
<script>
export default {
    data (){
        return{
        data: [],
        errors: [],
        loading: true,
        search: "",
        dialogTableVisible: false,
        dialogFormVisible: false,
        formLabelWidth: "120px",
        // Add Personnel form
        form: {
            id: "",
            name: "",
            address: "",
            hospital_code: "",
            formmode: "",
            edit_object_index: "",
        },
        // Edit Personnel form check
        form_check: {
            name: "",
            address: "",
            hospital_code: "",
        },
        // View info data
        gridData: [
            {
            name: "",
            address: "",
			hospital_code: "",
			},
		],
        };
    },
    methods: {
        formLoading: function () {
            const loading = this.$loading({
                lock: true,
                spinner: "el-icon-loading",
                target: "div.el-dialog",
            });
            loading.close();
        },
        getHospitals: function () {
            axios
                .get("hospital_get")
                .then((response) => {
                this.data = response.data;
                this.loading = false;
                })
                .catch(function (error) {});
        },
        handleView(index, row) {
			this.dialogTableVisible = true;
            this.gridData[0].name = row.name;
            this.gridData[0].address = row.address;
			this.gridData[0].hospital_code = row.hospital_code;
        },
        handleEdit(index, row) {
			this.clearFields();
			this.form.id = row.id;
			this.form.formmode = "edit";

			this.form.name = row.name;
			this.form.address = row.address;
			this.form.hospital_code = row.hospital_code;

			this.form.edit_object_index = this.data.indexOf(row);

			this.form_check.name = row.name;
            this.form_check.address = row.address;
            this.form_check.hospital_code = row.hospital_code;
            
			this.dialogFormVisible = true;
        },
         handleDelete(index, row) {
			var data = this.data;

			this.deleteHospital(row.id, (res_value) => {
				if (res_value) {
				data.splice(data.indexOf(row), 1);
				}
			});
		},
        hospitalFunctions: function (mode) {
            switch (mode) {
                case "add":
                if (
                    this.form.name == "" ||
                    this.form.address == "" ||
                    this.form.hospital_code == ""
                ) {
                    this.open_notif(
                    "info",
                    "Message",
                    "Required fields were missing values."
                    );
                } else {
                    axios
                    .post("add_hospital", this.form)
                    .then((response) => {
                        if (response.status > 199 && response.status < 203) {
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
                    this.form.name == this.form_check.name &&
                    this.form.address == this.form_check.address &&
                    this.form.hospital_code == this.form_check.hospital_code
                ) {
                    this.open_notif("info", "Message", "No Changes");
                } else {
                    axios
                    .post("edit_hospital/" + this.form.id, this.form)
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
                        ].name = this.form.name;
                        this.data[
                            parseInt(this.form.edit_object_index)
                        ].address = this.form.address;
                        this.data[
                            parseInt(this.form.edit_object_index)
                        ].hospital_code = this.form.hospital_code;
                        }
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    });
                }
                break;
            }
        },
        deleteHospital: function (id, res) {
            this.$confirm("Are you sure you want to delete?", "Confirm Delete", {
                distinguishCancelAndClose: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                type: "warning",
            })
            .then(() => {
                var _this = this;
                axios.post("delete_hospital/" + id).then(function (response) {
                    if (response.status > 199 && response.status < 203) {
                    _this.open_notif("success", "Success", "Deleted Successfully");
                    res(id);
                    }
                });
            })
            .catch((action) => {
                this.open_notif("info", "Cancelled", "No Changes");
            });
        },
       
        clearFields: function () {
            this.form.name = "";
            this.form.address = "";
			this.form.hospital_code = "";
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
    },
    mounted() {
        this.getHospitals();
    },
};
</script>