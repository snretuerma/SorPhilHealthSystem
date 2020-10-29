<template>
    <div>
        <!-- Header -->
        <div class="row">
        <div class="col-sm-12">
            <h2 class="font-weight-bold"><i class="fa fa-coins"></i>&nbsp;&nbsp;Budget</h2>
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
                        <el-table-column width="300" label="Start Date" prop="start_date"></el-table-column>
                        <el-table-column width="300" label="Amount" prop="total"></el-table-column>
                        <el-table-column width="150" label="End date" prop="end_date"></el-table-column>
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
        <!-- Card Ends Here -->

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

        <!-- Add/Edit Budget Dialog -->
        <el-dialog
            title="Budget Details"
            :visible.sync="dialogFormVisible"
            top="5vh"
            :close-on-press-escape="false"
            :close-on-click-modal="false"
            >
            <el-form :model="form" :rules="rules" ref="form">
                <el-form-item label="Start date" :label-width="formLabelWidth" prop="start_date">
                    <el-date-picker type="date" placeholder="Pick a date" v-model="form.start_date" style="width: 100%" value-format="yyyy-MM-dd"></el-date-picker>
                    <span class="font-italic text-danger" v-if="errors.start_date"><small>{{ errors.start_date[0] }}</small></span>
                </el-form-item>
                <el-form-item label="Amount" :label-width="formLabelWidth" prop="total">
                    <el-input v-model="form.total" autocomplete="off"></el-input>
                    <span class="font-italic text-danger" v-if="errors.total"><small>{{ errors.total[0] }}</small></span>
                </el-form-item>
                <el-form-item label="End date" :label-width="formLabelWidth" prop="end_date">
                    <el-date-picker type="date" placeholder="Pick a date" v-model="form.end_date" style="width: 100%" value-format="yyyy-MM-dd"></el-date-picker>
                    <span class="font-italic text-danger" v-if="errors.end_date"><small>{{ errors.end_date[0] }}</small></span>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
            <el-button @click="dialogFormVisible = false">Cancel</el-button>
            <el-button v-if="form.formmode == 'add'" type="primary" @click="budgetFunctions('add');formLoading();">Save</el-button>
            <el-button v-if="form.formmode == 'edit'" type="primary" @click="budgetFunctions('edit');formLoading();">Save Changes</el-button>
            </span>
        </el-dialog>
        <!-- Add/Edit Dialog -->

        <!-- Show Personnel Details -->
        <el-dialog title="Staff Info" :visible.sync="dialogTableVisible">
            <el-table :data="gridData">
                <el-table-column property="start_date" label="Start Date" width="250"></el-table-column>
                <el-table-column property="total" label="Amount" width="250"></el-table-column>
                <el-table-column property="end_date" label="End Date" width="150"></el-table-column>
            </el-table>
        </el-dialog>
        <!-- Show Personnel Details -->
    </div>
</template>

<script>
"use strict";
export default {
    data() {
        return {
        page: 1,
		pageSize: 10,
        loading: true,
        search: "",
        data: [],
        errors: [],
        layout: "pagination, table",
        dialogTableVisible: false,
        dialogFormVisible: false,
        formLabelWidth: "120px",
        // Validation
        rules: {
            start_date: [ { required: true, message: "Start date is required.", trigger: "blur"} ],
            total: [ { required: true, message: "Amount is required.", trigger: "blur" } ],
            end_date: [ { required: true, message: "End date is required.", trigger: "blur" } ]
        },

        // Add form
        form: {
            id: "",
            start_date: "",
            total: "",
            end_date: "",
            formmode: "",
            edit_object_index: "",
        },

        // Edit form check
        form_check: {
            start_date: "",
            total: "",
            end_date: "",
        },

        // View info data
        gridData: [
            {
            start_date: "",
            total: "",
            end_date: "",
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
        masknumber: function (num) {
            num = parseFloat(num)
                .toFixed(2)
                .replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
            return num;
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
        getBudget: function () {
            axios
                .get("budget_get")
                .then((response) => {
                response.data.forEach((entry) => {
                    entry.total = this.masknumber(entry.total);
                });
                this.data = response.data;
                this.loading = false;
                })
                .catch(function (error) {

                });
        },
        handleView(index, row) {
			this.dialogTableVisible = true;
            this.gridData[0].start_date = row.start_date;
			this.gridData[0].total = row.total;
			this.gridData[0].end_date = row.end_date;
        },
        handleEdit(index, row) {
			this.clearFields();
			this.form.id = row.id;
			this.form.formmode = "edit";

			this.form.start_date = row.start_date;
			this.form.total = row.total;
			this.form.end_date = row.end_date;

			this.form.edit_object_index = this.data.indexOf(row);

			this.form_check.start_date = row.start_date;
            this.form_check.total = row.total;
            this.form_check.end_date = row.end_date;

			this.dialogFormVisible = true;
        },
        handleDelete(index, row) {
			var data = this.data;

			this.deleteBudget(row.id, (res_value) => {
				if (res_value) {
				data.splice(data.indexOf(row), 1);
				}
			});
		},
        budgetFunctions: function (mode) {
            switch (mode) {
                case "add":
                if (
                    this.form.start_date == "" ||
                    this.form.total == "" ||
                    this.form.end_date == ""
                ) {
                    this.open_notif(
                    "info",
                    "Message",
                    "Required fields were missing values."
                    );
                } else {
                    axios
                    .post("add_budget", this.form)
                    .then((response) => {
                        if (response.status > 199 && response.status < 203) {
                        var total = response.data.total;
                        response.data.total = this.masknumber(this.form.total);
                        this.data.push(response.data);
                        this.dialogFormVisible = false;
                        this.open_notif(
                            "success",
                            "Success",
                            "Budget added successfully"
                        );
                        } else {
                        this.open_notif("error", "System", "Failed to add budget");
                        }
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    });
                }
                break;
                case "edit":
                if (
                    this.form.start_date == this.form_check.start_date &&
                    this.form.total == this.form_check.total &&
                    this.form.end_date == this.form_check.end_date
                ) {
                    this.open_notif("info", "Message", "No Changes");
                } else {
                    this.form.total = parseFloat(this.form.total.replace(/,/g, ""));
                    axios
                    .post("edit_budget/" + this.form.id, this.form)
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
                        ].start_date = this.form.start_date;
                        this.data[
                            parseInt(this.form.edit_object_index)
                        ].total = this.masknumber(this.form.total);;
                        this.data[
                            parseInt(this.form.edit_object_index)
                        ].end_date = this.form.end_date;
                        }
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    });
                }
                break;
            }
        },
        deleteBudget: function (id, res) {
            this.$confirm("Are you sure you want to delete?", "Confirm Delete", {
                distinguishCancelAndClose: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                type: "warning",
            })
            .then(() => {
                var _this = this;
                axios.post("budget_delete/" + id).then(function (response) {
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
            this.form.start_date = "";
            this.form.total = "";
            this.form.end_date = "";
        },
  },
  mounted() {
    this.getBudget();
  },
};
</script>
