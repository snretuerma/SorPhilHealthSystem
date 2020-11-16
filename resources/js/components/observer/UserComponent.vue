<template>
    <div>
        <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-users"></i>&nbsp;&nbsp;Hospitals & Users
                </span>
            </div>
        </div>
        <!-- End Header -->

        <!-- Search Box -->
        <div class="row" style="margin-bottom: 10px">
            <div class="col-sm-6">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    size="medium"
                    placeholder="Type to search"
                />
            </div>
        </div>
        <!-- Search End -->

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <el-table v-loading="loading" :data="listData">
                    <el-table-column
                        width="400"
                        label="Hospital"
                        prop="hospital_name"
                    ></el-table-column>
                    <el-table-column
                        width="460"
                        label="Address / Location"
                        prop="address"
                    ></el-table-column>
                    <el-table-column
                        min-width="150"
                        label="Email Address"
                        prop="email_address"
                    ></el-table-column>
                    <el-table-column width="60" align="center" fixed="right">
                        <template slot="header">
                            Action
                        </template>
                        <template slot-scope="scope">
                            <el-tooltip
                                class="item"
                                effect="light"
                                content="View Users"
                                placement="top"
                                :enterable=false
                            >
                                <el-button
                                    size="mini"
                                    type="success"
                                    icon="el-icon-user-solid"
                                    circle
                                    @click="
                                        handleViewUsers(scope.$index, scope.row)
                                    "
                                ></el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>
                <!-- Table End -->
                <div style="text-align: center">
                    <el-pagination
                        background
                        layout="prev, pager, next"
                        @current-change="handleCurrentChange"
                        :page-size="pageSize"
                        :total="total"
                    >
                    </el-pagination>
                </div>
            </div>
        </div>
        <!-- Card Ends Here -->

        <!-- Footer -->
        <hr />
        <div class="footer">
            <div class="containter-fluid">
                <div class="row text-center">
                    <span class="text-muted"
                        >&nbsp;&nbsp;&nbsp;&nbsp;©PF Management System
                        2020</span
                    >
                </div>
            </div>
        </div>
        <!-- Footer ends -->

        <!--User List-->
        <el-dialog
            :title="userdialog_title"
            :visible.sync="dialogFormVisible_user"
            top="2vh"
            width="80%"
            :close-on-click-modal="false"
            :close-on-press-escape="false"
        >
            <!-- Search Box -->
            <div class="row" style="margin-bottom: 10px">
                <div class="col-sm-6">
                    <el-input
                        prefix-icon="el-icon-search"
                        v-model="search"
                        size="small"
                        placeholder="Type to search"
                    />
                </div>
            </div>
            <!-- Search End -->

            <el-table
                :data="
                    userdata.filter(
                        userdata =>
                            !search ||
                            userdata.username
                                .toLowerCase()
                                .includes(search.toLowerCase())
                    )
                "
                style="width: 100%"
            >
                <el-table-column label="Username" prop="username">
                </el-table-column>
                <el-table-column label="Created at" prop="created_at">
                </el-table-column>
                <el-table-column label="Updated at" prop="updated_at">
                </el-table-column>
            </el-table>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible_user = false"
                    >Close</el-button
                >
            </span>
        </el-dialog>
        <!--End User List-->
    </div>
</template>
<script>
export default {
    data() {
        return {
            page: 1,
            pageSize: 10,
            loading: true,
            search: "",
            userdialog_title: "",
            data: [],
            userdata: [],
            dialogTableVisible: false,
            dialogFormVisible_user: false,
            notify: false
        };
    },
    computed: {
        searching() {
            if (!this.search) {
                this.total = this.data.length;
                return this.data;
            }
            this.page = 1;
            return this.data.filter(
                data =>
                    data.hospital_name
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.address
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.email_address
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
            );
        },
        listData() {
            this.total = this.searching.length;

            return this.searching.slice(
                this.pageSize * this.page - this.pageSize,
                this.pageSize * this.page
            );
        }
    },
    methods: {
        handleCurrentChange(val) {
            this.page = val;
        },
        handleViewUsers(index, row) {
            this.userdialog_title = row.hospital_code + " - User Account";
            var data = this.data[parseInt(this.data.indexOf(row))].users;
            this.data_hospital_index = parseInt(this.data.indexOf(row));

            data.forEach(entry => {
                var date = new Date(entry.created_at);
                entry.created_at =
                    (date.getMonth() > 8
                        ? date.getMonth() + 1
                        : "0" + (date.getMonth() + 1)) +
                    "/" +
                    (date.getDate() > 9
                        ? date.getDate()
                        : "0" + date.getDate()) +
                    "/" +
                    date.getFullYear();
                var date = new Date(entry.updated_at);
                entry.updated_at =
                    (date.getMonth() > 8
                        ? date.getMonth() + 1
                        : "0" + (date.getMonth() + 1)) +
                    "/" +
                    (date.getDate() > 9
                        ? date.getDate()
                        : "0" + date.getDate()) +
                    "/" +
                    date.getFullYear();
            });
            this.userdata = data;
            this.dialogFormVisible_user = true;
        },
        getHospitalsList: function() {
            axios
                .get("observerHospitalList")
                .then(response => {
                    response.data.forEach(entry => {
                        entry.hospital_name =
                            entry.name + " (" + entry.hospital_code + ")";
                    });
                    this.data = response.data;
                    this.loading = false;
                })
                .catch(function(error) {});
        }
    },
    mounted() {
        this.getHospitalsList();
    }
};
</script>
